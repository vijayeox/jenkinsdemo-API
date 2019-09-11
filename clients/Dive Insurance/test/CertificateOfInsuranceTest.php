<?php

use Oxzion\Document\DocumentGeneratorImpl;
use Oxzion\Document\DocumentBuilder;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Service\TemplateService;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Zend\Db\Adapter\Adapter;
use Oxzion\Utils\FileUtils;
use Oxzion\Test\ServiceTest;
use Oxzion\Transaction\TransactionManager;

class CertificateOfInsuranceTest extends ServiceTest
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

    public function testGenerateDocumentHubWithSameAddress()
    {
        $data = [
            'firstname' => 'Mohan', 
            'middlename' => 'Raj' ,
            'lastname' => 'D',
            'address1' => 'ABC 200',
            'address2' => 'XYZ 300',
            'city' => 'APO',
            'state' => 'District of Columbia',
            'country' => 'US',
            'zipcode' => '09522-9998',
            'certificate_no' => '200200178',
            'member_no' => '34567',
            'insured_status'=> 'Divester',
            'physical_address' => 'APO,AE',
            'policy_id' => 'PPK1992899',
            'single_limit' => '1,000,000',
            'annual_aggregate' => '2,000,000',
            'equipment_liability' => 'Not Included',
            'cylinder_coverage' => 'Not Covered',
            'update' => 1,
            'update_date' => '08/06/2019',
            'orgUuid' => '53012471-2863-4949-afb1-e69b0891c98a',
            'license_number' => '56342',
            'carrier' => 'Tokio Marine Specialty Insurance Company',
            'ismailingaddress' => 1,
            'address3' => 'Bangalore',
            'address4' => 'Karanataka',
            'padi' => '12345',
            'start_date' => '06/30/2019',
            'end_date' => '6/30/2020',
            'endrosement_status' => 'Instructor'
        ];
        AuthContext::put(AuthConstants::ORG_UUID, $data['orgUuid']);
        $config = $this->getApplicationConfig();
        $templateService = new TemplateService($config, $this->adapter);
        $documentGenerator = new DocumentGeneratorImpl();
        $documentBuilder = new DocumentBuilder($config, $templateService, $documentGenerator);
        $template = 'certificateOfInsurance';
        $tempFile = $config['TEMPLATE_FOLDER'].$data['orgUuid'];
        $templateLocation = __DIR__."/../data/template";
        if(FileUtils::fileExists($tempFile)){
            FileUtils::rmDir($tempFile);
        }
        FileUtils::symlink($templateLocation, $tempFile);
        $destination = $config['TEMPLATE_FOLDER'].$data['orgUuid']."/certificateOfInsurance.pdf";
        $options = array();
        $options['header'] = "COIheader.html";
        $options['footer'] = "COIfooter.html";
        try{
            $output = $documentBuilder->generateDocument($template, $data, $destination, $options);
        }catch(Exception $e){
            print($e->getMessage()); 
        }
        $this->assertTrue(is_file($output));
        $this->assertTrue(filesize($output)>0);
        FileUtils::deleteFile("certificateOfInsurance.pdf", $config['TEMPLATE_FOLDER'].$data['orgUuid']."/");
        FileUtils::unlink($tempFile);
    }

    public function testGenerateDocumentHub()
    {
        $data = [
            'firstname' => 'Mohan', 
            'middlename' => 'Raj' ,
            'lastname' => 'D',
            'address1' => 'ABC 200',
            'address2' => 'XYZ 300',
            'city' => 'APO',
            'state' => 'District of Columbia',
            'country' => 'US',
            'zipcode' => '09522-9998',
            'certificate_no' => '200200178',
            'member_no' => '34567',
            'insured_status'=> 'Divester',
            'physical_address' => 'APO,AE',
            'policy_id' => 'PPK1992899',
            'single_limit' => '1,000,000',
            'annual_aggregate' => '2,000,000',
            'equipment_liability' => 'Not Included',
            'cylinder_coverage' => 'Not Covered',
            'update' => 1,
            'update_date' => '08/06/2019',
            'orgUuid' => '53012471-2863-4949-afb1-e69b0891c98a',
            'license_number' => '56342',
            'carrier' => 'Tokio Marine Specialty Insurance Company',
            'ismailingaddress' => 0,
            'address3' => 'Bangalore',
            'address4' => 'Karanataka',
            'padi' => '12345',
            'start_date' => '06/30/2019',
            'end_date' => '6/30/2020',
            'endrosement_status' => 'Instructor'
        ];
        AuthContext::put(AuthConstants::ORG_UUID, $data['orgUuid']);
        $config = $this->getApplicationConfig();
        $templateService = new TemplateService($config, $this->adapter);
        $documentGenerator = new DocumentGeneratorImpl();
        $documentBuilder = new DocumentBuilder($config, $templateService, $documentGenerator);
        $template = 'certificateOfInsurance';
        $tempFile = $config['TEMPLATE_FOLDER'].$data['orgUuid'];
        $templateLocation = __DIR__."/../data/template";
        if(FileUtils::fileExists($tempFile)){
            FileUtils::rmDir($tempFile);
        }
        FileUtils::symlink($templateLocation, $tempFile);
        $destination = $config['TEMPLATE_FOLDER'].$data['orgUuid']."/certificateOfInsurance.pdf";
        $options = array();
        $options['header'] = "COIheader.html";
        $options['footer'] = "COIfooter.html";
        try{
            $output = $documentBuilder->generateDocument($template, $data, $destination, $options);
        }catch(Exception $e){
            print($e->getMessage()); 
        }
        $this->assertTrue(is_file($output));
        $this->assertTrue(filesize($output)>0);
        FileUtils::deleteFile("certificateOfInsurance.pdf", $config['TEMPLATE_FOLDER'].$data['orgUuid']."/");
        FileUtils::unlink($tempFile);
    }
}