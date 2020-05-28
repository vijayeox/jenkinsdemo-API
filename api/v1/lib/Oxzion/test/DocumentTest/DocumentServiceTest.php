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

    public function testGenerateDocumentDiveInsurance()
    {
        $data = ['firstname' => 'Mohan', 'initial' => 'Raj' ,'lastname' => 'D','address1' => 'ABC 200','address2' => 'XYZ 300','city' => 'APO','state' => 'District of Columbia','state_in_short' => 'DC','country' => 'US','zip' => '09522-9998','certificate_no' => '200200178','member_no' => '34567','careerCoverage'=> 'Divester','physical_address' => 'APO,AE','policy_id' => 'PPK1992899','single_limit' => 1000000,'annual_aggregate' => 2000000,'equipment_liability' => 'Not Included','update' => 1,'update_date' => '08/06/2019','pageno' => 1,'total_page' => 1,'orgUuid' => '53012471-2863-4949-afb1-e69b0891c98a','license_number' => '56342','carrier' => 'Tokio Marine Specialty Insurance Company','sameasmailingaddress' => 1,'address3' => 'Bangalore','address4' => 'Karanataka','padi' => '12345','start_date' => '06/30/2019','end_date' => '6/30/2020','endrosement_status' => 'Instructor','endorsement_options'=>'{"modify_personalInformation"=>true,"modify_coverage"=> false,"modify_additionalInsured"=> false}','careerCoverageVal' => 'instructor',
            'scubaFit' => 'scubaFitInstructorDeclined',
            'cylinder'=> 'cylinderInspectorOrInstructorDeclined',
            'equipment'=> 'equipmentLiabilityCoverageDeclined',
            'endorsement_status' => 'In Force'];
        AuthContext::put(AuthConstants::ORG_UUID, $data['orgUuid']);
        $config = $this->getApplicationConfig();
        $tempFile = $config['TEMPLATE_FOLDER'].$data['orgUuid'];
        $templateLocation = __DIR__."/../../../../../../clients/DiveInsurance/data/template";
        if(FileUtils::fileExists($tempFile)){
            FileUtils::rmDir($tempFile);
        }
        FileUtils::symlink($templateLocation, $tempFile);
        $TemplateService = new TemplateService($config, $this->adapter);
        $content = $TemplateService->getContent('ProfessionalLiabilityCOI', $data);
        $destination = $config['TEMPLATE_FOLDER'].$data['orgUuid']."/ProfessionalLiabilityCOI.pdf";
        $header = $config['TEMPLATE_FOLDER'].$data['orgUuid']."/COIheader.html";
        $footer = $config['TEMPLATE_FOLDER'].$data['orgUuid']."/COIfooter.html";
        $generatePdf = new DocumentGeneratorImpl();
        $output = $generatePdf->generatePdfDocumentFromHtml($content, $destination, $header, $footer);
        $this->assertTrue(is_file($output));
        $this->assertTrue(filesize($output)>0);
        FileUtils::deleteFile("ProfessionalLiabilityCOI.pdf", $config['TEMPLATE_FOLDER'].$data['orgUuid']."/");
        FileUtils::unlink($tempFile);
    }


}
