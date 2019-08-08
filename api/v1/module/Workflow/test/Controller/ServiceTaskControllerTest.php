<?php
namespace Workflow;

use Workflow\Controller\ActivityInstanceController;
use Zend\Stdlib\ArrayUtils;
use Oxzion\Test\ControllerTest;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Adapter\Platform\Mysql;
use Zend\Db\Adapter\Adapter;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Zend\Db\Adapter\AdapterInterface;
use Workflow\Service\ServiceTaskService;
use Oxzion\Utils\FileUtils;
use Mockery;

class ServiceTaskControllerTest extends ControllerTest
{
    public function setUp() : void
    {
        $this->loadConfig();
        parent::setUp();
    }

    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__)."/../Dataset/ActivityInstance.yml");
        return $dataset;
    }

    public function getMockMessageProducer()
    {
        $serviceTaskService = $this->getApplicationServiceLocator()->get(ServiceTaskService::class);
        $mockMessageProducer = Mockery::mock('Oxzion\Messaging\MessageProducer');
        $serviceTaskService->setMessageProducer($mockMessageProducer);
        return $mockMessageProducer;
    }

    public function testServiceTaskMailExecution()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['command'=>'mail' , 'to' => 'bharatgtest@myvamla.com', 'body' => 'create a new body','subject'=>'NewSubject'];
        $this->setJsonContent(json_encode($data));
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(json_encode(array('To'=>$data['to'],'Subject'=>$data['subject'],'body'=>$data['body'],'attachments'=>null)), 'mail')->once()->andReturn(123);
        }
        $this->dispatch('/callback/workflow/servicetask', 'POST', $data);
        $this->assertResponseStatusCode(200);
    }

    public function testServiceTaskWithoutSubjectExecution()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['command'=>'mail' , 'to' => 'bharatgtest@myvamla.com', 'body' => 'create a new body'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/callback/workflow/servicetask', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->assertEquals($content['status'], 'error');
    }
    public function testServiceTaskWithoutRecepientMailExecution()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['command'=>'mail' , 'body' => 'create a new body','subject'=>'NewSubject'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/callback/workflow/servicetask', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->assertEquals($content['status'], 'error');
    }
    public function testServiceTaskPDFExecution()
    {
        $data = ['uuid' => '53012471-2863-4949-afb1-e69b0891c98a'];
        $config = $this->getApplicationConfig();
        $tempFolder = $config['TEMPLATE_FOLDER']."53012471-2863-4949-afb1-e69b0891c98a/";
        FileUtils::createDirectory($tempFolder);
        $tempFile = $config['TEMPLATE_FOLDER']."/";
        FileUtils::createDirectory($tempFile);
        copy(__DIR__."/../Dataset/GenericTemplate.tpl", $tempFile."GenericTemplate.tpl");
        $params = ['command'=>'pdf' , 'template' => 'GenericTemplate','orgid'=>1,'options'=>array('initial_title' => 'Vantage agora Pdf Template','second_title' => 'Title 2','pdf_header_logo'=> '/logo_example.jpg','pdf_header_logo_width'=>20,'header_text_color'=>array(139, 58, 58),'header_line_color'=>array(255, 48, 48),'footer_text_color'=>array(123, 121, 34),'footer_line_color'=>array(56, 142, 142)),'destination'=>$config['TEMPLATE_FOLDER']."GenericTemplate.pdf"];
        $this->setJsonContent(json_encode($params));
        $this->dispatch('/callback/workflow/servicetask', 'POST', $params);
        $this->assertResponseStatusCode(200);
        $templateName="GenericTemplate.tpl";
        FileUtils::deleteFile($templateName, $tempFile);
        FileUtils::deleteFile("GenericTemplate.pdf", $config['TEMPLATE_FOLDER']);
    }
    public function testServiceTaskPDFInvalidTemplateExecution()
    {
        $config = $this->getApplicationConfig();
        $params = ['command'=>'pdf' , 'template' => 'GenericTemplate','orgid'=>1,'options'=>array('initial_title' => 'Vantage agora Pdf Template','second_title' => 'Title 2','pdf_header_logo'=> '/logo_example.jpg','pdf_header_logo_width'=>20,'header_text_color'=>array(139, 58, 58),'header_line_color'=>array(255, 48, 48),'footer_text_color'=>array(123, 121, 34),'footer_line_color'=>array(56, 142, 142)),'destination'=>$config['TEMPLATE_FOLDER']."GenericTemplate.pdf"];
        $this->setJsonContent(json_encode($params));
        $this->dispatch('/callback/workflow/servicetask', 'POST', $params);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->assertEquals($content['status'], 'error');
    }
    public function testServiceTaskPDFInvalidDestinationExecution()
    {
        $config = $this->getApplicationConfig();
        $params = ['command'=>'pdf' , 'template' => 'GenericTemplate','orgid'=>1,'options'=>array('initial_title' => 'Vantage agora Pdf Template','second_title' => 'Title 2','pdf_header_logo'=> '/logo_example.jpg','pdf_header_logo_width'=>20,'header_text_color'=>array(139, 58, 58),'header_line_color'=>array(255, 48, 48),'footer_text_color'=>array(123, 121, 34),'footer_line_color'=>array(56, 142, 142)),'destination'=>$config['TEMPLATE_FOLDER']."GenericTemplate.pdf"];
        $this->setJsonContent(json_encode($params));
        $this->dispatch('/callback/workflow/servicetask', 'POST', $params);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->assertEquals($content['status'], 'error');
    }
}
