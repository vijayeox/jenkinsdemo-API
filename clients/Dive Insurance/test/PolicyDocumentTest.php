<?php
use Oxzion\Test\DelegateTest;
use Oxzion\AppDelegate\AppDelegateService;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Oxzion\Utils\FileUtils;
use PHPUnit\DbUnit\DataSet\DefaultDataSet;
use Oxzion\Utils\ArtifactUtils;
use Oxzion\Encryption\Crypto;

class PolicyDocumentTest extends DelegateTest
{
    
    public function setUp() : void
    {
        $this->loadConfig();
        $config = $this->getApplicationConfig();
        $this->data = array(
            "appName" => 'ox_client_app',
            'UUID' => 8765765,
            'description' => 'FirstAppOfTheClient',
            'orgUuid' => '53012471-2863-4949-afb1-e69b0891c98a'
        );
        $migrationFolder = __DIR__  . "/../data/migrations/";
        $this->doMigration($this->data,$migrationFolder);
        $path = __DIR__.'/../../../api/v1/data/delegate/'.$this->data['UUID'];
        if (!is_link($path)) {
            symlink(__DIR__.'/../data/delegate/',$path);
        }

        $this->tempFile = $config['TEMPLATE_FOLDER'].$this->data['orgUuid'];
        $templateLocation = __DIR__."/../data/template";

        if(FileUtils::fileExists($this->tempFile)){
                FileUtils::rmDir($this->tempFile);
        }
        FileUtils::symlink($templateLocation, $this->tempFile);

        parent::setUp();               
    }

    public function getDataSet()
    {
        return new DefaultDataSet();
    }

    public function tearDown() : void
    {
        parent::tearDown();
        $path = __DIR__.'/../../../api/v1/data/delegate/'.$this->data['UUID'];
        if (is_link($path)) {
            unlink($path);
        }
        FileUtils::unlink($this->tempFile);
        $query = "DROP DATABASE " . $this->database;
        $statement = $this->getDbAdapter()->query($query);
        $result = $statement->execute();
        
    }

    public function testPolicyDocument()
    {
        $config = $this->getApplicationConfig();
        $orgId = AuthContext::put(AuthConstants::ORG_ID, 1);
        AuthContext::put(AuthConstants::ORG_UUID, $this->data['orgUuid']);
        $appId = $this->data['UUID'];
        $data = [
                'firstname' => 'Mohan',
                 'middlename' => 'Raj' ,
                 'lastname' => 'D',
                 'address1' => 'ABC 200',
                 'address2' => 'XYZ 300',
                 'city' => 'APO',
                 'state' => 'California',
                 'country' => 'US',
                 'zipcode' => '09522-9998',                
                 'padi' => '34567',
                 'start_date' => '2019-06-01',
                 'end_date' => '2020-06-30',
                 'insured_status'=> 'Divester',
                 'physical_address' => 'APO,AE',
                 'single_limit' => '1,000,000',
                 'annual_aggregate' => '2,000,000',
                 'equipment_liability' => 'Not Included',
                 'cylinder_coverage' => 'Not Covered',
                 'update' => 1,
                 'update_date' => '08/06/2019',
                 'orgUuid' => $this->data['orgUuid'],
                 'product' => 'Individual Professional Liability',
                 'ismailingaddress' => 0,
                 'endrosement_status' => 'Instructor',
                 'addInsured' => 1,
                 'aiList' => '{"name" : ["LITITZ COMM CENTER","BAINBRIDGE SPORTSMENS CLUB INC.","BURLINGTON COUNTY COLLEGE","GOLDEN MEADOWS SWIM CENTER","WILLOW SPRINGS PARK","HOLIDAY INN EXPRESS (LITITZ, PA)"]}',
                 'lapseletter' => 1];
        $config = $this->getApplicationConfig();
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'PolicyDocument', $data);
        $this->assertEquals(isset($content['uuid']), true);
        $this->assertEquals(isset($content['policy_id']), true);
        $this->assertEquals(isset($content['carrier']), true);
        $this->assertEquals(isset($content['license_number']), true);
        $this->assertEquals(isset($content['certificate_no']), true);
        $this->assertEquals(isset($content['policy_document']), true);
        $doc = $config['APP_DOCUMENT_FOLDER'].$content['coi_document'];
        $this->assertTrue(is_file($doc));
        $this->assertTrue(filesize($doc)>0);
        $doc = substr($doc, 0, strripos($doc, '/'));
        $files = glob($config['APP_DOCUMENT_FOLDER'].$this->data['orgUuid'].'/'.$content['uuid'].'/'."*");
        $filecount = count($files);
        $this->assertEquals($filecount,7);
        FileUtils::rmDir($config['APP_DOCUMENT_FOLDER'].$this->data['orgUuid']);
    }


    public function testPolicyWithoutAIDocument()
    {
        $config = $this->getApplicationConfig();
        $orgId = AuthContext::put(AuthConstants::ORG_ID, 1);
        AuthContext::put(AuthConstants::ORG_UUID, $this->data['orgUuid']);
        $appId = $this->data['UUID'];
        $data = [
                'firstname' => 'Mohan',
                 'middlename' => 'Raj' ,
                 'lastname' => 'D',
                 'address1' => 'ABC 200',
                 'address2' => 'XYZ 300',
                 'city' => 'APO',
                 'state' => 'California',
                 'country' => 'US',
                 'zipcode' => '09522-9998',                
                 'padi' => '34567',
                 'start_date' => '2019-06-01',
                 'end_date' => '2020-06-30',
                 'insured_status'=> 'Divester',
                 'physical_address' => 'APO,AE',
                 'single_limit' => '1,000,000',
                 'annual_aggregate' => '2,000,000',
                 'equipment_liability' => 'Not Included',
                 'cylinder_coverage' => 'Not Covered',
                 'update' => 1,
                 'update_date' => '08/06/2019',
                 'orgUuid' => $this->data['orgUuid'],
                 'product' => 'Individual Professional Liability',
                 'ismailingaddress' => 0,
                 'endrosement_status' => 'Instructor'];
        $config = $this->getApplicationConfig();
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'PolicyDocument', $data);
        $this->assertEquals(isset($content['uuid']), true);
        $this->assertEquals(isset($content['policy_id']), true);
        $this->assertEquals(isset($content['carrier']), true);
        $this->assertEquals(isset($content['license_number']), true);
        $this->assertEquals(isset($content['certificate_no']), true);
        $this->assertEquals(isset($content['policy_document']), true);
        $doc = $config['APP_DOCUMENT_FOLDER'].$content['coi_document'];
        $this->assertTrue(is_file($doc));
        $this->assertTrue(filesize($doc)>0);
        $doc = substr($doc, 0, strripos($doc, '/'));
        $files = glob($config['APP_DOCUMENT_FOLDER'].$this->data['orgUuid'].'/'.$content['uuid'].'/'."*");
        $filecount = count($files);
        $this->assertEquals($filecount,4);
        FileUtils::rmDir($config['APP_DOCUMENT_FOLDER'].$this->data['orgUuid']);
    }

    public function testDiveBoatPolicyDocument()
    {
        $config = $this->getApplicationConfig();
        $orgId = AuthContext::put(AuthConstants::ORG_ID, 1);
        AuthContext::put(AuthConstants::ORG_UUID, $this->data['orgUuid']);
        $appId = $this->data['UUID'];
        $data = [
                'firstname' => 'Mohan',
                 'middlename' => 'Raj' ,
                 'lastname' => 'D',
                 'address1' => 'ABC 200',
                 'address2' => 'XYZ 300',
                 'city' => 'APO',
                 'state' => 'California',
                 'country' => 'US',
                 'zipcode' => '09522-9998',                
                 'padi' => '34567',
                 'start_date' => '06/30/2019',
                 'end_date' => '6/30/2020 12:01:00 AM',
                 'orgUuid' => $this->data['orgUuid'],
                 'product' => 'Dive Boat',
                 'ismailingaddress' => 0,
                 'endrosement_status' => 'Instructor',
                 'losspayees' => 1,
                 'addInsurance' => 1];
        $config = $this->getApplicationConfig();
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'PolicyDocument', $data);
        $this->assertEquals(isset($content['uuid']), true);
        $this->assertEquals(isset($content['policy_id']), true);
        $this->assertEquals(isset($content['carrier']), true);
        $this->assertEquals(isset($content['license_number']), true);
        $this->assertEquals(isset($content['certificate_no']), true);
        $this->assertEquals(isset($content['policy_document']), true);
        $doc = $config['APP_DOCUMENT_FOLDER'].$content['coi_document'];
        $this->assertTrue(is_file($doc));
        $this->assertTrue(filesize($doc)>0);
        $doc = substr($doc, 0, strripos($doc, '/'));
        FileUtils::rmDir($config['APP_DOCUMENT_FOLDER'].$this->data['orgUuid']);
    }


    public function testDiveStorePolicyDocument()
    {
        $config = $this->getApplicationConfig();
        $orgId = AuthContext::put(AuthConstants::ORG_ID, 1);
        AuthContext::put(AuthConstants::ORG_UUID, $this->data['orgUuid']);
        $appId = $this->data['UUID'];
        $data = [
                'firstname' => 'Mohan',
                 'middlename' => 'Raj' ,
                 'lastname' => 'D',
                 'address1' => 'ABC 200',
                 'address2' => 'XYZ 300',
                 'city' => 'APO',
                 'state' => 'New Jersey',
                 'country' => 'US',
                 'zipcode' => '09522-9998',                
                 'padi' => '34567',
                 'start_date' => '06/30/2019',
                 'end_date' => '6/30/2020 12:01:00 AM',
                 'orgUuid' => $this->data['orgUuid'],
                 'product' => 'Dive Store',
                 'general_liaility' => '1,000,000',
                 'personal_injury' =>'1,000,000',
                 'general_liability_aggregate' => '2,000,000',
                 'product_aggregate' => '2,000,000',
                 'damage' => '1,000,000',
                 'medical_expense' => 1,
                 'owned_auto' => 0,
                 'diving_pool_use' => 1,
                 'travel_agent' => 0,
                 'addInsurance' => 1,
                 'liability_policy' => 1];
        $config = $this->getApplicationConfig();
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'PolicyDocument', $data);
        $this->assertEquals(isset($content['uuid']), true);
        $this->assertEquals(isset($content['policy_id']), true);
        $this->assertEquals(isset($content['carrier']), true);
        $this->assertEquals(isset($content['license_number']), true);
        $this->assertEquals(isset($content['certificate_no']), true);
        $this->assertEquals(isset($content['policy_document']), true);
        $doc = $config['APP_DOCUMENT_FOLDER'].$content['coi_document'];
        $this->assertTrue(is_file($doc));
        $this->assertTrue(filesize($doc)>0);
        $doc = substr($doc, 0, strripos($doc, '/'));
        FileUtils::rmDir($config['APP_DOCUMENT_FOLDER'].$this->data['orgUuid']);
    }
}