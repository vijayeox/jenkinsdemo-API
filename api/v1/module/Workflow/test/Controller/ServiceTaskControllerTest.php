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

    private function getMockRestClientForScheduleService()
    {
        $taskService = $this->getApplicationServiceLocator()->get(Service\ServiceTaskService::class);
        $mockRestClient = Mockery::mock('Oxzion\Utils\RestClient');
        $taskService->setRestClient($mockRestClient);
        return $mockRestClient;
    }


    public function testServiceTaskMailExecution()
    {
        $this->initAuthToken($this->adminUser);
        $data['variables']  = ['command'=>'mail' , 'to' => 'bharatgtest@myvamla.com', 'body' => 'create a new body','subject'=>'NewSubject'];
        $this->setJsonContent(json_encode($data));
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $payload = json_encode(array('to'=>$data['variables']['to'],'subject'=>$data['variables']['subject'],'body'=>$data['variables']['body'],'attachments'=>null));
            $mockMessageProducer->expects('sendQueue')->with($payload, 'mail')->once()->andReturn(123);
        }
        $this->dispatch('/callback/workflow/servicetask', 'POST', $data);
        $this->assertResponseStatusCode(200);
    }

    public function testServiceTaskWithoutSubjectExecution()
    {
        $this->initAuthToken($this->adminUser);
        $data['variables']  = ['command'=>'mail' , 'to' => 'bharatgtest@myvamla.com', 'body' => 'create a new body'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/callback/workflow/servicetask', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(406);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
        $this->assertEquals($content['data']['errors']['subject'], 'required');
    }
    public function testServiceTaskWithoutRecepientMailExecution()
    {
        $this->initAuthToken($this->adminUser);
        $data['variables']  = ['command'=>'mail' , 'body' => 'create a new body','subject'=>'NewSubject'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/callback/workflow/servicetask', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(406);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
        $this->assertEquals($content['data']['errors']['to'], 'required');
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
        $params['variables']  = ['command'=>'pdf' , 'template' => 'GenericTemplate','orgid'=>1,'options'=>array('initial_title' => 'Vantage agora Pdf Template','second_title' => 'Title 2','pdf_header_logo'=> '/logo_example.jpg','pdf_header_logo_width'=>20,'header_text_color'=>array(139, 58, 58),'header_line_color'=>array(255, 48, 48),'footer_text_color'=>array(123, 121, 34),'footer_line_color'=>array(56, 142, 142)),'destination'=>$config['TEMPLATE_FOLDER']."GenericTemplate.pdf"];
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
        $params['variables']  = ['command'=>'pdf' , 'template' => 'GenericTemplate','orgid'=>1,'options'=>array('initial_title' => 'Vantage agora Pdf Template','second_title' => 'Title 2','pdf_header_logo'=> '/logo_example.jpg','pdf_header_logo_width'=>20,'header_text_color'=>array(139, 58, 58),'header_line_color'=>array(255, 48, 48),'footer_text_color'=>array(123, 121, 34),'footer_line_color'=>array(56, 142, 142)),'destination'=>$config['TEMPLATE_FOLDER']."GenericTemplate.pdf"];
        $this->setJsonContent(json_encode($params));
        $this->dispatch('/callback/workflow/servicetask', 'POST', $params);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(500);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Template not found!');
    }
    public function testServiceTaskPDFInvalidDestinationExecution()
    {
        $config = $this->getApplicationConfig();
        $params['variables'] = ['command'=>'pdf' , 'template' => 'GenericTemplate','orgid'=>1,'options'=>array('initial_title' => 'Vantage agora Pdf Template','second_title' => 'Title 2','pdf_header_logo'=> '/logo_example.jpg','pdf_header_logo_width'=>20,'header_text_color'=>array(139, 58, 58),'header_line_color'=>array(255, 48, 48),'footer_text_color'=>array(123, 121, 34),'footer_line_color'=>array(56, 142, 142)),'destination'=>$config['TEMPLATE_FOLDER']."GenericTemplate.pdf"];
        $this->setJsonContent(json_encode($params));
        $this->dispatch('/callback/workflow/servicetask', 'POST', $params);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(500);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Template not found!');
    }

    public function testServiceTaskSchedule()
    {
        $this->initAuthToken($this->adminUser);
        $data = ["activityInstanceId" => "Task_1bw1uyk:651f1320-ef09-11e9-a364-62be4f9e1bfd","processInstanceId" => "651eebfb-ef09-11e9-a364-62be4f9e1bfd","variables" => array("firstname" => "Neha","policy_period" => "1year","card_expiry_date" => "10/24","city" => "Bangalore","orgUuid" => "53012471-2863-4949-afb1-e69b0891c98a","isequipmentliability" => "1","card_no" => "1234","jobUrl" => "/app/ec8942b7-aa93-4bc6-9e8c-e1371988a5d4/delegate/DispatchAutoRenewalNotification","state" => "karnataka","app_id" => "ec8942b7-aa93-4bc6-9e8c-e1371988a5d4","cron" => "0 0/1 * * * ? *","zip" => "560030","coverage" => "100000","product" => "Individual Professional Liability","address2" => "dhgdhdh","address1" => "hjfjhfjfjfhfg","expiry_date" => "2020-06-30","form_id" =>"0","entity_id" => "1","created_by"=> "1","url" => "setupjob","command" =>"schedule","expiry_year" => "2019","orgid" => "53012471-2863-4949-afb1-e69b0891c98a","lastname" => "Rai","isexcessliability" => "1","workflow_instance_id" => "142","credit_card_type" => "credit","workflowId" => "a01a6776-431a-401e-9288-6acf3b2f3925","fileId"=> "134","email" => 'bharat@gmail.com'),"parentInstanceId" => "651eebfb-ef09-11e9-a364-62be4f9e1bfd","parentActivity" => "651eebfb-ef09-11e9-a364-62be4f9e1bfd"];

        $this->setJsonContent(json_encode($data));
        if (enableCamel == 0) {
            $mockRestClient = $this->getMockRestClientForScheduleService();
            $mockRestClient->expects('postWithHeader')->with("setupjob", Mockery::any())->once()->andReturn();
        }
        $this->dispatch('/callback/workflow/servicetask', 'POST', $data);
        $this->assertResponseStatusCode(200);
    }

  
    public function testServiceTaskCancelJob()
    {
        $this->initAuthToken($this->adminUser);
        $data = ["activityInstanceId" => "Task_1bw1uyk:651f1320-ef09-11e9-a364-62be4f9e1bfd","processInstanceId" => "651eebfb-ef09-11e9-a364-62be4f9e1bfd","variables" => array("firstname" => "Neha","policy_period" => "1year","card_expiry_date" => "10/24","city" => "Bangalore","orgUuid" => "53012471-2863-4949-afb1-e69b0891c98a","isequipmentliability" => "1","card_no" => "1234","state" => "karnataka","app_id" => "ec8942b7-aa93-4bc6-9e8c-e1371988a5d4","cron" => "0 0/1 * * * ? *","zip" => "560030","coverage" => "100000","product" => "Individual Professional Liability","address2" => "dhgdhdh","address1" => "hjfjhfjfjfhfg","expiry_date" => "2020-06-30","form_id" =>"0","entity_id" => "1","created_by"=> "1","url" => "canceljob","command" =>"cancelJob","jobId" => "651eebfb-ef09-11e9-a364-62be", "jobGroup" => "Cancel Job" ,"expiry_year" => "2019","orgid" => "53012471-2863-4949-afb1-e69b0891c98a","lastname" => "Rai","isexcessliability" => "1","workflow_instance_id" => "142","credit_card_type" => "credit","workflowId" => "a01a6776-431a-401e-9288-6acf3b2f3925","fileId"=> "134","email" => 'bharat@gmail.com'),"parentInstanceId" => "651eebfb-ef09-11e9-a364-62be4f9e1bfd","parentActivity" => "651eebfb-ef09-11e9-a364-62be4f9e1bfd"];

        $this->setJsonContent(json_encode($data));
        if (enableCamel == 0) {
            $mockRestClient = $this->getMockRestClientForScheduleService();
            $mockRestClient->expects('postWithHeader')->with("canceljob", Mockery::any())->once()->andReturn();
        }
        $this->dispatch('/callback/workflow/servicetask', 'POST', $data);
        $this->assertResponseStatusCode(200);
    }
}
