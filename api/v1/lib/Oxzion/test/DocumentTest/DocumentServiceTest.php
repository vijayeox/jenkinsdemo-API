<?php
namespace DocumentTest;

use Oxzion\Document\DocumentGeneratorImpl;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Service\TemplateService;
use Zend\Db\Adapter\Adapter;
use Oxzion\Utils\FileUtils;
use Oxzion\Test\ServiceTest;
use Oxzion\Transaction\TransactionManager;

class DocumentServiceTest extends ServiceTest
{
    public function setUp() : void
    {
        $this->loadConfig();
        // parent::setUp();
        $config = $this->getApplicationConfig();
        $this->adapter = new Adapter($config['db']);
        $tm = TransactionManager::getInstance($this->adapter);
        $tm->setRollbackOnly(true);
        $tm->beginTransaction();
    }

    public function tearDown() : void
    {
        $tm = TransactionManager::getInstance($this->adapter);
        $tm->rollback();
        $_REQUEST = [];
    }


    public function testGenerateDocument()
    {
        $data = ['username' => 'John','orgid' => '53012471-2863-4949-afb1-e69b0891c98a'];
        AuthContext::put(AuthConstants::ORG_UUID, $data['orgid']);
        $config = $this->getApplicationConfig();
        $tempFolder = $config['TEMPLATE_FOLDER'].$data['orgid'];
        if(!is_link($tempFolder)){
             FileUtils::createDirectory($tempFolder."/");
        }
        $tempFile = $config['TEMPLATE_FOLDER']."/";
        FileUtils::createDirectory($tempFile);
        copy(__DIR__."/../Service/template/GenericTemplate.tpl", $tempFile."GenericTemplate.tpl");
        $TemplateService = new TemplateService($config, $this->adapter);
        $content = $TemplateService->getContent('GenericTemplate', $data);
        $destination = $config['TEMPLATE_FOLDER']."GenericTemplate.pdf";
        $options = array('initial_title' => 'Vantage agora Pdf Template','second_title' => 'Title 2','pdf_header_logo'=> '/logo_example.jpg',
        'pdf_header_logo_width'=>20,'header_text_color'=>array(139, 58, 58),'header_line_color'=>array(255, 48, 48),'footer_text_color'=>array(123, 121, 34),'footer_line_color'=>array(56, 142, 142));
        $generatePdf = new DocumentGeneratorImpl();
        $output = $generatePdf->generateDocument($content, $destination, $options);
        $this->assertTrue(file_exists($output));
        $this->assertTrue(filesize($output)>0);
        $templateName="GenericTemplate.tpl";
        FileUtils::deleteFile($templateName, $tempFile);
        FileUtils::deleteFile("GenericTemplate.pdf", $config['TEMPLATE_FOLDER']);
        // print_r($tempFolder);exit;
        FileUtils::rmDir($tempFolder);
        // TO DO DIGITAL SIGNATURE
    }



}
