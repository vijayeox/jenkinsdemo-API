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
        $query = "DROP DATABASE " . $this->database;//comment
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
                 'initial' => 'Raj' ,
                 'lastname' => 'D',
                 'address1' => 'ABC 200',
                 'address2' => 'XYZ 300',
                 'city' => 'APO',
                 'state' => 'California',
                 'country' => 'US',
                 'zip' => '09522-9998',                
                 'padi' => '34567',
                 'start_date' => '2019-06-01',
                 'end_date' => '2020-06-30',
                 'physical_address' => 'APO,AE',
                 'single_limit' => '1,000,000',
                 'annual_aggregate' => '2,000,000',
                 'orgUuid' => $this->data['orgUuid'],
                 'product' => 'Individual Professional Liability',
                 'sameasmailingaddress' => 1,
                 'careerCoverage' => 'instructor',
                 'cylinder'=> 'cylinderInspector',
                 'equipment'=> 'equipmentLiabilityCoverage',
                 'scubaFit' => 'scubaFitInstructor',
                 'endrosement_status' => 'Instructor',
                 'endorsement_options'=>'{"modify_personalInformation"=>true,
                                          "modify_coverage"=> false,
                                          "modify_additionalInsured" => false}',
                 'update' => 1,
                 'update_date' => '08/06/2019'];
        $config = $this->getApplicationConfig();
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'PolicyDocument', $data);
        $this->assertEquals(isset($content['uuid']), true);
        $this->assertEquals(isset($content['policy_id']), true);
        $this->assertEquals(isset($content['carrier']), true);
        $this->assertEquals(isset($content['license_number']), true);
        $this->assertEquals(isset($content['certificate_no']), true);
        $this->assertEquals(isset($content['documents']['policy_document']), true);
        $doc = $config['APP_DOCUMENT_FOLDER'].$content['documents']['coi_document'];
        $this->assertTrue(is_file($doc));
        $this->assertTrue(filesize($doc)>0);
        $doc = substr($doc, 0, strripos($doc, '/'));
        $files = glob($config['APP_DOCUMENT_FOLDER'].$this->data['orgUuid'].'/'.$content['uuid'].'/'."*");
        $filecount = count($files);
        $this->assertEquals($filecount,8);
        FileUtils::rmDir($config['APP_DOCUMENT_FOLDER'].$this->data['orgUuid']);
    }

    public function testPolicyDocumentWithScuba()
    {
        $config = $this->getApplicationConfig();
        $orgId = AuthContext::put(AuthConstants::ORG_ID, 1);
        AuthContext::put(AuthConstants::ORG_UUID, $this->data['orgUuid']);
        $appId = $this->data['UUID'];
        $data = [
                'firstname' => 'Mohan',
                 'initial' => 'Raj' ,
                 'lastname' => 'D',
                 'address1' => 'ABC 200',
                 'address2' => 'XYZ 300',
                 'city' => 'APO',
                 'state' => 'California',
                 'country' => 'US',
                 'zip' => '09522-9998',                
                 'padi' => '34567',
                 'start_date' => '2019-06-01',
                 'end_date' => '2020-06-30',
                 'physical_address' => 'APO,AE',
                 'single_limit' => '1,000,000',
                 'annual_aggregate' => '2,000,000',
                 'orgUuid' => $this->data['orgUuid'],
                 'product' => 'Individual Professional Liability',
                 'sameasmailingaddress' => 1,
                 'careerCoverage' => 'instructor',
                 'scubaFit' => 'scubaFitInstructor',
                 'cylinder'=> 'cylinderInspectorOrInstructorDeclined',
                 'equipment'=> 'equipmentLiabilityCoverageDeclined',
                 'endrosement_status' => 'Instructor',
                 'endorsement_options'=>'{"modify_personalInformation"=>true,
                                          "modify_coverage"=> false,
                                          "modify_additionalInsured" => false}',
                 'update' => 1,
                 'update_date' => '08/06/2019'];
        $config = $this->getApplicationConfig();
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'PolicyDocument', $data);
        $this->assertEquals(isset($content['uuid']), true);
        $this->assertEquals(isset($content['policy_id']), true);
        $this->assertEquals(isset($content['carrier']), true);
        $this->assertEquals(isset($content['license_number']), true);
        $this->assertEquals(isset($content['certificate_no']), true);
        $this->assertEquals(isset($content['documents']['policy_document']), true);
        $doc = $config['APP_DOCUMENT_FOLDER'].$content['documents']['coi_document'];
        $this->assertTrue(is_file($doc));
        $this->assertTrue(filesize($doc)>0);
        $doc = substr($doc, 0, strripos($doc, '/'));
        $files = glob($config['APP_DOCUMENT_FOLDER'].$this->data['orgUuid'].'/'.$content['uuid'].'/'."*");
        $filecount = count($files);
        $this->assertEquals($filecount,6);
        FileUtils::rmDir($config['APP_DOCUMENT_FOLDER'].$this->data['orgUuid']);
    }


    public function testPolicyDocumentWithCylinder()
    {
        $config = $this->getApplicationConfig();
        $orgId = AuthContext::put(AuthConstants::ORG_ID, 1);
        AuthContext::put(AuthConstants::ORG_UUID, $this->data['orgUuid']);
        $appId = $this->data['UUID'];
        $data = [
                'firstname' => 'Mohan',
                 'initial' => 'Raj' ,
                 'lastname' => 'D',
                 'address1' => 'ABC 200',
                 'address2' => 'XYZ 300',
                 'city' => 'APO',
                 'state' => 'California',
                 'country' => 'US',
                 'zip' => '09522-9998',                
                 'padi' => '34567',
                 'start_date' => '2019-06-01',
                 'end_date' => '2020-06-30',
                 'physical_address' => 'APO,AE',
                 'single_limit' => '1,000,000',
                 'annual_aggregate' => '2,000,000',
                 'orgUuid' => $this->data['orgUuid'],
                 'product' => 'Individual Professional Liability',
                 'sameasmailingaddress' => 1,
                 'careerCoverage' => 'instructor',
                 'scubaFit' => 'scubaFitInstructorDeclined',
                 'cylinder'=> 'cylinderInspectorAndInstructor',
                 'equipment'=> 'equipmentLiabilityCoverageDeclined',
                 'endrosement_status' => 'Instructor',
                 'endorsement_options'=>'{"modify_personalInformation"=>true,
                                          "modify_coverage"=> false,
                                          "modify_additionalInsured" => false}',
                 'update' => 1,
                 'update_date' => '08/06/2019'];
        $config = $this->getApplicationConfig();
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'PolicyDocument', $data);
        $this->assertEquals(isset($content['uuid']), true);
        $this->assertEquals(isset($content['policy_id']), true);
        $this->assertEquals(isset($content['carrier']), true);
        $this->assertEquals(isset($content['license_number']), true);
        $this->assertEquals(isset($content['certificate_no']), true);
        $this->assertEquals(isset($content['documents']['policy_document']), true);
        $doc = $config['APP_DOCUMENT_FOLDER'].$content['documents']['coi_document'];
        $this->assertTrue(is_file($doc));
        $this->assertTrue(filesize($doc)>0);
        $doc = substr($doc, 0, strripos($doc, '/'));
        $files = glob($config['APP_DOCUMENT_FOLDER'].$this->data['orgUuid'].'/'.$content['uuid'].'/'."*");
        $filecount = count($files);
        $this->assertEquals($filecount,6);
        FileUtils::rmDir($config['APP_DOCUMENT_FOLDER'].$this->data['orgUuid']);
    }


    public function testPolicyDocumentWithInstructor()
    {
        $config = $this->getApplicationConfig();
        $orgId = AuthContext::put(AuthConstants::ORG_ID, 1);
        AuthContext::put(AuthConstants::ORG_UUID, $this->data['orgUuid']);
        $appId = $this->data['UUID'];
        $data = [
                'firstname' => 'Mohan',
                 'initial' => 'Raj' ,
                 'lastname' => 'D',
                 'address1' => 'ABC 200',
                 'address2' => 'XYZ 300',
                 'city' => 'APO',
                 'state' => 'California',
                 'country' => 'US',
                 'zip' => '09522-9998',                
                 'padi' => '34567',
                 'start_date' => '2019-06-01',
                 'end_date' => '2020-06-30',
                 'physical_address' => 'APO,AE',
                 'single_limit' => '1,000,000',
                 'annual_aggregate' => '2,000,000',
                 'orgUuid' => $this->data['orgUuid'],
                 'product' => 'Individual Professional Liability',
                 'sameasmailingaddress' => 1,
                 'careerCoverage' => 'instructor',
                 'scubaFit' => 'scubaFitInstructorDeclined',
                 'cylinder'=> 'cylinderInspectorOrInstructorDeclined',
                 'equipment'=> 'equipmentLiabilityCoverageDeclined',
                 'endrosement_status' => 'Instructor',
                 'endorsement_options'=>'{"modify_personalInformation"=>true,
                                          "modify_coverage"=> false,
                                          "modify_additionalInsured" => false}',
                 'update' => 1,
                 'update_date' => '08/06/2019'];
        $config = $this->getApplicationConfig();
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'PolicyDocument', $data);
        $this->assertEquals(isset($content['uuid']), true);
        $this->assertEquals(isset($content['policy_id']), true);
        $this->assertEquals(isset($content['carrier']), true);
        $this->assertEquals(isset($content['license_number']), true);
        $this->assertEquals(isset($content['certificate_no']), true);
        $this->assertEquals(isset($content['documents']['policy_document']), true);
        $doc = $config['APP_DOCUMENT_FOLDER'].$content['documents']['coi_document'];
        $this->assertTrue(is_file($doc));
        $this->assertTrue(filesize($doc)>0);
        $doc = substr($doc, 0, strripos($doc, '/'));
        $files = glob($config['APP_DOCUMENT_FOLDER'].$this->data['orgUuid'].'/'.$content['uuid'].'/'."*");
        $filecount = count($files);
        $this->assertEquals($filecount,5);
        FileUtils::rmDir($config['APP_DOCUMENT_FOLDER'].$this->data['orgUuid']);
    }


    public function testPolicyDocumentWithAIDocument()
    {
        $config = $this->getApplicationConfig();
        $orgId = AuthContext::put(AuthConstants::ORG_ID, 1);
        AuthContext::put(AuthConstants::ORG_UUID, $this->data['orgUuid']);
        $appId = $this->data['UUID'];
        $data = [
                'firstname' => 'Mohan',
                 'initial' => 'Raj' ,
                 'lastname' => 'D',
                 'address1' => 'ABC 200',
                 'address2' => 'XYZ 300',
                 'city' => 'APO',
                 'state' => 'California',
                 'country' => 'US',
                 'zip' => '09522-9998',
                 'padi' => '34567',
                 'start_date' => '2019-06-01',
                 'end_date' => '2020-06-30',
                 'physical_address' => 'APO,AE',
                 'single_limit' => '1,000,000',
                 'annual_aggregate' => '2,000,000',
                 'update' => 1,
                 'update_date' => '08/06/2019',
                 'orgUuid' => $this->data['orgUuid'],
                 'product' => 'Individual Professional Liability',
                 'sameasmailingaddress' => 1,
                 'careerCoverage' => 'instructor',
                 'scubaFit' => 'scubaFitInstructorDeclined',
                 'cylinder'=> 'cylinderInspectorOrInstructorDeclined',
                 'equipment'=> 'equipmentLiabilityCoverageDeclined',
                 'endrosement_status' => 'Instructor',
                 'endorsement_options'=>'{"modify_personalInformation"=>true,
                                          "modify_coverage"=> false,
                                          "modify_additionalInsured"=> false}',
                 'additionalInsured' => array(array("additionalInformation" => "SHe ditched TVS for Royal Enfield","address" => "Hell","businessRelation"=> "Enemy","city"=> "Bangalore","name"=> "Neha","state"=> "Karnataka","zip"=> "420420"),array("additionalInformation"=> "Wonderful person","address"=> "No.33, 8th cross, 24th main","businessRelation"=> "Friend","city"=> "Bangalore","name"=> "Prajwal K","state"=> "Karnataka","zip"=> "560102")),
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
        $this->assertEquals(isset($content['documents']['policy_document']), true);
        $doc = $config['APP_DOCUMENT_FOLDER'].$content['documents']['coi_document'];
        $this->assertTrue(is_file($doc));
        $this->assertTrue(filesize($doc)>0);
        $doc = substr($doc, 0, strripos($doc, '/'));
        $files = glob($config['APP_DOCUMENT_FOLDER'].$this->data['orgUuid'].'/'.$content['uuid'].'/'."*");
        $filecount = count($files);
        $this->assertEquals($filecount,6);
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
                 'initial' => 'Raj' ,
                 'lastname' => 'D',
                 'address1' => 'ABC 200',
                 'address2' => 'XYZ 300',
                 'city' => 'APO',
                 'state' => 'California',
                 'country' => 'US',
                 'zip' => '09522-9998',
                 'padi' => '34567',
                 'start_date' => '2019-06-01',
                 'end_date' => '2020-06-30',
                 'physical_address' => 'APO,AE',
                 'single_limit' => '1,000,000',
                 'annual_aggregate' => '2,000,000',
                 'update' => 1,
                 'update_date' => '08/06/2019',
                 'orgUuid' => $this->data['orgUuid'],
                 'product' => 'Individual Professional Liability',
                 'sameasmailingaddress' => 1,
                 'endrosement_status' => 'Instructor',
                 'careerCoverage' => 'instructor',
                 'scubaFit' => 'scubaFitInstructorDeclined',
                 'cylinder'=> 'cylinderInspectorOrInstructorDeclined',
                 'equipment'=> 'equipmentLiabilityCoverageDeclined',
                 'endorsement_options'=>'{"modify_personalInformation"=>true,"modify_coverage"=> false,"modify_additionalInsured"=> false}'];
        $config = $this->getApplicationConfig();
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'PolicyDocument', $data);
        $this->assertEquals(isset($content['uuid']), true);
        $this->assertEquals(isset($content['policy_id']), true);
        $this->assertEquals(isset($content['carrier']), true);
        $this->assertEquals(isset($content['license_number']), true);
        $this->assertEquals(isset($content['certificate_no']), true);
        $this->assertEquals(isset($content['documents']['policy_document']), true);
        $doc = $config['APP_DOCUMENT_FOLDER'].$content['documents']['coi_document'];
        $this->assertTrue(is_file($doc));
        $this->assertTrue(filesize($doc)>0);
        $doc = substr($doc, 0, strripos($doc, '/'));
        $files = glob($config['APP_DOCUMENT_FOLDER'].$this->data['orgUuid'].'/'.$content['uuid'].'/'."*");
        $filecount = count($files);
        $this->assertEquals($filecount,5);
        FileUtils::rmDir($config['APP_DOCUMENT_FOLDER'].$this->data['orgUuid']);
    }


    public function testDiveBoatPolicyQuoteDocument()
    {
        $config = $this->getApplicationConfig();
        $orgId = AuthContext::put(AuthConstants::ORG_ID, 1);
        AuthContext::put(AuthConstants::ORG_UUID, $this->data['orgUuid']);
        $appId = $this->data['UUID'];
        $data = [
                 'firstname' => 'Mohan',
                 'lastname' => 'Raj' ,
                 'orgname' => "ABOVE AND BELOW THE SEA LLC L'S DIVE INC. & ST. THOMAS DIVING CLUB",
                 'address1' => 'ABC 200',
                 'address2' => 'XYZ 300',
                 'city' => 'APO',
                 'state' => 'California',
                 'country' => 'US',
                 'zip' => '09522-9998',
                 'padi' => '34567',
                 'start_date' => '2019-06-01',
                 'end_date' => '2020-06-30',
                 'orgUuid' => $this->data['orgUuid'],
                 'product' => 'Dive Boat',
                 'vessel_name' => 'LETTS DIVE',
                 'vessel_year' => '1778',
                 'vessel_length' => '30',
                 'vessel_hp' => '300',
                 'hull_type' => 'FIBER',
                 'hull_mfg' => 'HOPPER',
                 'vessel_sno' => '18000F888',
                 'limit_ins' => '90,000.00',
                 'personnal_effects' => '500.00/$5,000.00',
                 'passengers' => '18',
                 'crew_on_boat' => '2',
                 'crew_in_water' => 1,
                 'protection_liability_amt' => '1,000,000.00',
                 'medical_pay' => '5,000',
                 'total_premium' => '7,754.00',
                 'padi_admin_fee' => '75.00',
                 'navigation_limit_note' => 'WATERS OF PUERTO RICO AND THE U.S. VIRGIN ISLANDS NOT MORE THAN THREE (3) MILES FROM A HARBOR OF SAFE REFUGE. THE VESSEL MAY NOT CARRY PASSENGERS BETWEEN PUERTO RICO AND THE U.S. VIRGIN ISLANDS.',
                 'personal_effect_deduct' => '500.00',
                 'liability_ins_deduct' => '1,000.00',
                 'medical_deduct' => '100.00',
                 'manager_name' => 'Julie Joseph',
                 'manager_email' => 'abcd@gmail.com',
                 'quote_due_date' => '2019-06-01'
             ];
        $config = $this->getApplicationConfig();
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'QuoteDocument', $data);
        $this->assertEquals(isset($content['uuid']), true);
        $this->assertEquals(isset($content['policy_id']), true);
        $this->assertEquals(isset($content['carrier']), true);
        $this->assertEquals(isset($content['license_number']), true);
        $doc = $config['APP_DOCUMENT_FOLDER'].$content['documents']['coi_document'];
        $this->assertTrue(is_file($doc));
        $this->assertTrue(filesize($doc)>0);
        $doc = substr($doc, 0, strripos($doc, '/'));
        FileUtils::rmDir($config['APP_DOCUMENT_FOLDER'].$this->data['orgUuid']);
    }


    public function testDiveBoatPolicyQuoteWithAIDocument()
    {
        $config = $this->getApplicationConfig();
        $orgId = AuthContext::put(AuthConstants::ORG_ID, 1);
        AuthContext::put(AuthConstants::ORG_UUID, $this->data['orgUuid']);
        $appId = $this->data['UUID'];
        $data = [
                 'firstname' => 'Mohan',
                 'lastname' => 'Raj' ,
                 'orgname' => "ABOVE AND BELOW THE SEA LLC L'S DIVE INC. & ST. THOMAS DIVING CLUB",
                 'address1' => 'ABC 200',
                 'address2' => 'XYZ 300',
                 'city' => 'APO',
                 'state' => 'California',
                 'country' => 'US',
                 'zip' => '09522-9998',
                 'padi' => '34567',
                 'start_date' => '2019-06-01',
                 'end_date' => '2020-06-30',
                 'orgUuid' => $this->data['orgUuid'],
                 'product' => 'Dive Boat',
                 'vessel_name' => 'LETTS DIVE',
                 'vessel_year' => '1778',
                 'vessel_length' => '30',
                 'vessel_hp' => '300',
                 'hull_type' => 'FIBER',
                 'hull_mfg' => 'HOPPER',
                 'vessel_sno' => '18000F888',
                 'limit_ins' => '90,000.00',
                 'personnal_effects' => '500.00/$5,000.00',
                 'passengers' => '18',
                 'crew_on_boat' => '2',
                 'crew_in_water' => 1,
                 'protection_liability_amt' => '1,000,000.00',
                 'medical_pay' => '5,000',
                 'total_premium' => '7,754.00',
                 'padi_admin_fee' => '75.00',
                 'navigation_limit_note' => 'WATERS OF PUERTO RICO AND THE U.S. VIRGIN ISLANDS NOT MORE THAN THREE (3) MILES FROM A HARBOR OF SAFE REFUGE. THE VESSEL MAY NOT CARRY PASSENGERS BETWEEN PUERTO RICO AND THE U.S. VIRGIN ISLANDS.',
                 'personal_effect_deduct' => '500.00',
                 'liability_ins_deduct' => '1,000.00',
                 'medical_deduct' => '100.00',
                 'additionalInsured' => array(array("additionalInformation" => "SHe ditched TVS for Royal Enfield","address" => "Hell","businessRelation"=> "Enemy","city"=> "Bangalore","name"=> "Neha","state"=> "Karnataka","zip"=> "420420"),array("additionalInformation"=> "Wonderful person","address"=> "No.33, 8th cross, 24th main","businessRelation"=> "Friend","city"=> "Bangalore","name"=> "Prajwal K","state"=> "Karnataka","zip"=> "560102")),
                 'manager_name' => 'Julie Joseph',
                 'manager_email' => 'abcd@gmail.com',
                 'quote_due_date' => '2019-06-01'
             ];
        $config = $this->getApplicationConfig();
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'QuoteDocument', $data);
        $this->assertEquals(isset($content['uuid']), true);
        $this->assertEquals(isset($content['policy_id']), true);
        $this->assertEquals(isset($content['carrier']), true);
        $this->assertEquals(isset($content['license_number']), true);
        $doc = $config['APP_DOCUMENT_FOLDER'].$content['documents']['coi_document'];
        $this->assertTrue(is_file($doc));
        $this->assertTrue(filesize($doc)>0);
        $doc = substr($doc, 0, strripos($doc, '/'));
        FileUtils::rmDir($config['APP_DOCUMENT_FOLDER'].$this->data['orgUuid']);
    }


    public function testDiveBoatPolicyQuoteWithANIDocument()
    {
        $config = $this->getApplicationConfig();
        $orgId = AuthContext::put(AuthConstants::ORG_ID, 1);
        AuthContext::put(AuthConstants::ORG_UUID, $this->data['orgUuid']);
        $appId = $this->data['UUID'];
        $data = [
                 'firstname' => 'Mohan',
                 'lastname' => 'Raj' ,
                 'orgname' => "ABOVE AND BELOW THE SEA LLC L'S DIVE INC. & ST. THOMAS DIVING CLUB",
                 'quote_due_date' => '2019-06-01',
                 'address1' => 'ABC 200',
                 'address2' => 'XYZ 300',
                 'city' => 'APO',
                 'state' => 'California',
                 'country' => 'US',
                 'zip' => '09522-9998',
                  'padi' => '34567',
                 'start_date' => '2019-06-01',
                 'end_date' => '2020-06-30',
                 'orgUuid' => $this->data['orgUuid'],
                 'product' => 'Dive Boat',
                 'vessel_name' => 'LETTS DIVE',
                 'vessel_year' => '1778',
                 'vessel_length' => '30',
                 'vessel_hp' => '300',
                 'hull_type' => 'FIBER',
                 'hull_mfg' => 'HOPPER',
                 'vessel_sno' => '18000F888',
                 'limit_ins' => '90,000.00',
                 'personnal_effects' => '500.00/$5,000.00',
                 'passengers' => '18',
                 'crew_on_boat' => '2',
                 'crew_in_water' => 1,
                 'protection_liability_amt' => '1,000,000.00',
                 'medical_pay' => '5,000',
                 'total_premium' => '7,754.00',
                 'padi_admin_fee' => '75.00',
                 'navigation_limit_note' => 'WATERS OF PUERTO RICO AND THE U.S. VIRGIN ISLANDS NOT MORE THAN THREE (3) MILES FROM A HARBOR OF SAFE REFUGE. THE VESSEL MAY NOT CARRY PASSENGERS BETWEEN PUERTO RICO AND THE U.S. VIRGIN ISLANDS.',
                 'personal_effect_deduct' => '500.00',
                 'liability_ins_deduct' => '1,000.00',
                 'medical_deduct' => '100.00',
                 'additionalNamedInsured' => array(array("additionalInformation" => "SHe ditched TVS for Royal Enfield","address" => "Hell","businessRelation"=> "Enemy","city"=> "Bangalore","name"=> "Neha","state"=> "Karnataka","zip"=> "420420"),array("additionalInformation"=> "Wonderful person","address"=> "No.33, 8th cross, 24th main","businessRelation"=> "Friend","city"=> "Bangalore","name"=> "Prajwal K","state"=> "Karnataka","zip"=> "560102")),
                 'manager_name' => 'Julie Joseph',
                 'manager_email' => 'abcd@gmail.com',
                 ''];
        $config = $this->getApplicationConfig();
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'QuoteDocument', $data);
        $this->assertEquals(isset($content['uuid']), true);
        $this->assertEquals(isset($content['policy_id']), true);
        $this->assertEquals(isset($content['carrier']), true);
        $this->assertEquals(isset($content['license_number']), true);
        $doc = $config['APP_DOCUMENT_FOLDER'].$content['documents']['coi_document'];
        $this->assertTrue(is_file($doc));
        $this->assertTrue(filesize($doc)>0);
        $doc = substr($doc, 0, strripos($doc, '/'));
        FileUtils::rmDir($config['APP_DOCUMENT_FOLDER'].$this->data['orgUuid']);
    }




    public function testDiveBoatPolicyWithoutAIDocument()
    {
        $config = $this->getApplicationConfig();
        $orgId = AuthContext::put(AuthConstants::ORG_ID, 1);
        AuthContext::put(AuthConstants::ORG_UUID, $this->data['orgUuid']);
        $appId = $this->data['UUID'];
        $data = [
                 'firstname' => 'Mohan',
                 'lastname' => 'Raj' ,
                 'orgname' => "ABOVE AND BELOW THE SEA LLC L'S DIVE INC. & ST. THOMAS DIVING CLUB",
                 'address1' => 'ABC 200',
                 'address2' => 'XYZ 300',
                 'city' => 'APO',
                 'state' => 'California',
                 'country' => 'US',
                 'zip' => '09522-9998',
                 'padi' => '34567',
                 'start_date' => '2019-06-01',
                 'end_date' => '2020-06-30',
                 'orgUuid' => $this->data['orgUuid'],
                 'product' => 'Dive Boat',
                 'sameasmailingaddress' => 1,
                 'endrosement_status' => 'Instructor',
                 'cover_letter' => 1,
                 'manager_name' => 'Julie Joseph',
                 'manager_email' => 'abcd@gmail.com',
                 'vessel_name' => 'LETTS DIVE',
                 'vessel_year' => '1778',
                 'vessel_length' => '30',
                 'vessel_hp' => '300',
                 'hull_type' => 'FIBER',
                 'hull_mfg' => 'HOPPER',
                 'vessel_sno' => '18000F888',
                 'limit_ins' => '90,000.00',
                 'personnal_effects' => '500.00/$5,000.00',
                 'passengers' => '18',
                 'crew_on_boat' => '2',
                 'crew_in_water' => 1,
                 'protection_liability_amt' => '1,000,000.00',
                 'medical_pay' => '5,000',
                 'total_premium' => '7,754.00',
                 'padi_admin_fee' => '75.00',
                 'navigation_limit_note' => 'WATERS OF PUERTO RICO AND THE U.S. VIRGIN ISLANDS NOT MORE THAN THREE (3) MILES FROM A HARBOR OF SAFE REFUGE. THE VESSEL MAY NOT CARRY PASSENGERS BETWEEN PUERTO RICO AND THE U.S. VIRGIN ISLANDS.',
                 'personal_effect_deduct' => '500.00',
                 'liability_ins_deduct' => '1,000.00',
                 'medical_deduct' => '100.00'
                 ];
        $config = $this->getApplicationConfig();
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'PolicyDocument', $data);
        $this->assertEquals(isset($content['uuid']), true);
        $this->assertEquals(isset($content['policy_id']), true);
        $this->assertEquals(isset($content['carrier']), true);
        $this->assertEquals(isset($content['license_number']), true);
        $this->assertEquals(isset($content['certificate_no']), true);
        $this->assertEquals(isset($content['documents']['policy_document']), true);
        $doc = $config['APP_DOCUMENT_FOLDER'].$content['documents']['coi_document'];
        $this->assertTrue(is_file($doc));
        $this->assertTrue(filesize($doc)>0);
        $doc = substr($doc, 0, strripos($doc, '/'));
        FileUtils::rmDir($config['APP_DOCUMENT_FOLDER'].$this->data['orgUuid']);
    }


    public function testDiveBoatPolicyWithAIDocument()
    {
        $config = $this->getApplicationConfig();
        $orgId = AuthContext::put(AuthConstants::ORG_ID, 1);
        AuthContext::put(AuthConstants::ORG_UUID, $this->data['orgUuid']);
        $appId = $this->data['UUID'];
        $data = [
                 'firstname' => 'Mohan',
                 'lastname' => 'Raj' ,
                 'orgname' => "ABOVE AND BELOW THE SEA LLC L'S DIVE INC. & ST. THOMAS DIVING CLUB",
                 'address1' => 'ABC 200',
                 'address2' => 'XYZ 300',
                 'city' => 'APO',
                 'state' => 'California',
                 'country' => 'US',
                 'zip' => '09522-9998',
                 'padi' => '34567',
                 'start_date' => '2019-06-01',
                 'end_date' => '2020-06-30',
                 'orgUuid' => $this->data['orgUuid'],
                 'product' => 'Dive Boat',
                 'cover_letter' => 1,
                 'manager_name' => 'Julie Joseph',
                 'manager_email' => 'abcd@gmail.com',
                 'vessel_name' => 'LETTS DIVE',
                 'vessel_year' => '1778',
                 'vessel_length' => '30',
                 'vessel_hp' => '300',
                 'hull_type' => 'FIBER',
                 'hull_mfg' => 'HOPPER',
                 'vessel_sno' => '18000F888',
                 'limit_ins' => '90,000.00',
                 'personnal_effects' => '500.00/$5,000.00',
                 'passengers' => '18',
                 'crew_on_boat' => '2',
                 'crew_in_water' => 1,
                 'protection_liability_amt' => '1,000,000.00',
                 'medical_pay' => '5,000',
                 'total_premium' => '7,754.00',
                 'padi_admin_fee' => '75.00',
                 'navigation_limit_note' => 'WATERS OF PUERTO RICO AND THE U.S. VIRGIN ISLANDS NOT MORE THAN THREE (3) MILES FROM A HARBOR OF SAFE REFUGE. THE VESSEL MAY NOT CARRY PASSENGERS BETWEEN PUERTO RICO AND THE U.S. VIRGIN ISLANDS.',
                 'personal_effect_deduct' => '500.00',
                 'liability_ins_deduct' => '1,000.00',
                 'medical_deduct' => '100.00',
                 'additionalInsured' => array(array("additionalInformation" => "SHe ditched TVS for Royal Enfield","address" => "Hell","businessRelation"=> "Enemy","city"=> "Bangalore","name"=> "Neha","state"=> "Karnataka","zip"=> "420420"),array("additionalInformation"=> "Wonderful person","address"=> "No.33, 8th cross, 24th main","businessRelation"=> "Friend","city"=> "Bangalore","name"=> "Prajwal K","state"=> "Karnataka","zip"=> "560102")),
                 'manager_name' => 'Julie Joseph',
                 'manager_email' => 'abcd@gmail.com',
                 'lossPayees' => array(array("additionalInformation" => "SHe ditched TVS for Royal Enfield","address" => "Hell","businessRelation"=> "Enemy","city"=> "Bangalore","name"=> "Neha","state"=> "Karnataka","zip"=> "420420"),array("additionalInformation"=> "Wonderful person","address"=> "No.33, 8th cross, 24th main","businessRelation"=> "Friend","city"=> "Bangalore","name"=> "Prajwal K","state"=> "Karnataka","zip"=> "560102"))];
        $config = $this->getApplicationConfig();
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'PolicyDocument', $data);
        $this->assertEquals(isset($content['uuid']), true);
        $this->assertEquals(isset($content['policy_id']), true);
        $this->assertEquals(isset($content['carrier']), true);
        $this->assertEquals(isset($content['license_number']), true);
        $this->assertEquals(isset($content['certificate_no']), true);
        $this->assertEquals(isset($content['documents']['policy_document']), true);
        $doc = $config['APP_DOCUMENT_FOLDER'].$content['documents']['coi_document'];
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
                 'initial' => 'Raj' ,
                 'lastname' => 'D',
                 'address1' => 'ABC 200',
                 'address2' => 'XYZ 300',
                 'city' => 'APO',
                 'state' => 'New Jersey',
                 'country' => 'US',
                 'zip' => '09522-9998',
                 'padi' => '34567',
                 'start_date' => '2019-06-01',
                 'end_date' => '2020-06-30',
                 'orgUuid' => $this->data['orgUuid'],
                 'product' => 'Dive Store',
                 'liability' => array('general_liaility' => '1,000,000',
                 'personal_injury' =>'1,000,000',
                 'general_liability_aggregate' => '2,000,000',
                 'product_aggregate' => '2,000,000',
                 'damage' => '1,000,000',
                 'medical_expense' => 1,
                 'owned_auto' => 0,
                 'diving_pool_use' => 1,
                 'travel_agent' => 0),
                 'cover_letter' => 1,
                 'storename' => 'HUB INTERNATIONAL',
                 'manager_name' => 'Julie Joseph',
                 'manager_email' => 'abcd@gmail.com'];
        $config = $this->getApplicationConfig();
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'PolicyDocument', $data);
        $this->assertEquals(isset($content['uuid']), true);
        $this->assertEquals(isset($content['policy_id']), true);
        $this->assertEquals(isset($content['carrier']), true);
        $this->assertEquals(isset($content['license_number']), true);
        $this->assertEquals(isset($content['certificate_no']), true);
        $this->assertEquals(isset($content['documents']['liability_policy_document']), true);
        $doc = $config['APP_DOCUMENT_FOLDER'].$content['documents']['liability_coi_document'];
        $this->assertTrue(is_file($doc));
        $this->assertTrue(filesize($doc)>0);
        $doc = substr($doc, 0, strripos($doc, '/'));
        FileUtils::rmDir($config['APP_DOCUMENT_FOLDER'].$this->data['orgUuid']);
    }

    public function testDiveStorePropertyPolicy()
    {
        $config = $this->getApplicationConfig();
        $orgId = AuthContext::put(AuthConstants::ORG_ID, 1);
        AuthContext::put(AuthConstants::ORG_UUID, $this->data['orgUuid']);
        $appId = $this->data['UUID'];
        $data = [
                'firstname' => 'Mohan',
                 'initial' => 'Raj' ,
                 'lastname' => 'D',
                 'address1' => 'ABC 200',
                 'address2' => 'XYZ 300',
                 'city' => 'APO',
                 'state' => 'New Jersey',
                 'country' => 'US',
                 'zip' => '09522-9998',
                 'padi' => '34567',
                 'start_date' => '2019-06-01',
                 'end_date' => '2020-06-30',
                 'orgUuid' => $this->data['orgUuid'],
                 'product' => 'Dive Store',
                 'property' => array('content_limit' => '80,000', 'business_income' => '40,000','building_coverage' => 1,'equipment_breakdown' => 1,'dependant_prop' => '5,000','robbery_inside' => '2,500','robbery_outside' => '2,500','transit_coverage' => '10,000','emp_theft' => '5,000','prop_others' => '25,000','off_premises' => '10,000','glass' => '5,000','property' => 1,'cover_letter' => 1,'storename' => 'HUB INTERNATIONAL'),
                 'additionalInsured' => array(array("additionalInformation" => "SHe ditched TVS for Royal Enfield","address" => "Hell","businessRelation"=> "Enemy","city"=> "Bangalore","name"=> "Neha","state"=> "Karnataka","zip"=> "420420"),array("additionalInformation"=> "Wonderful person","address"=> "No.33, 8th cross, 24th main","businessRelation"=> "Friend","city"=> "Bangalore","name"=> "Prajwal K","state"=> "Karnataka","zip"=> "560102")),
                 'manager_name' => 'Julie Joseph',
                 'manager_email' => 'abcd@gmail.com',
                 'lossPayees' => array(array("additionalInformation" => "SHe ditched TVS for Royal Enfield","address" => "Hell","businessRelation"=> "Enemy","city"=> "Bangalore","name"=> "Neha","state"=> "Karnataka","zip"=> "420420"),array("additionalInformation"=> "Wonderful person","address"=> "No.33, 8th cross, 24th main","businessRelation"=> "Friend","city"=> "Bangalore","name"=> "Prajwal K","state"=> "Karnataka","zip"=> "560102")),
                'storename' => 'HUB INTERNATIONAL'];

        $config = $this->getApplicationConfig();
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'PolicyDocument', $data);
        $this->assertEquals(isset($content['uuid']), true);
        $this->assertEquals(isset($content['policy_id']), true);
        $this->assertEquals(isset($content['carrier']), true);
        $this->assertEquals(isset($content['license_number']), true);
        $this->assertEquals(isset($content['documents']['property_policy_document']), true);
        $doc = $config['APP_DOCUMENT_FOLDER'].$content['documents']['property_coi_document'];
        $this->assertTrue(is_file($doc));
        $this->assertTrue(filesize($doc)>0);
        $doc = substr($doc, 0, strripos($doc, '/'));
        FileUtils::rmDir($config['APP_DOCUMENT_FOLDER'].$this->data['orgUuid']);
    }

    public function testEFRPolicyDocument()
    {
        $config = $this->getApplicationConfig();
        $orgId = AuthContext::put(AuthConstants::ORG_ID, 1);
        AuthContext::put(AuthConstants::ORG_UUID, $this->data['orgUuid']);
        $appId = $this->data['UUID'];
        $data = [
                'firstname' => 'Mohan',
                 'initial' => 'Raj' ,
                 'lastname' => 'D',
                 'address1' => 'ABC 200',
                 'address2' => 'XYZ 300',
                 'city' => 'APO',
                 'state' => 'California',
                 'country' => 'US',
                 'zip' => '09522-9998',
                 'padi' => '34567',
                 'start_date' => '2019-06-01',
                 'end_date' => '2020-06-30',
                 'insured_status'=> 'Divester',
                 'physical_address' => 'APO,AE',
                 'single_limit' => '1,000,000',
                 'annual_aggregate' => '2,000,000',
                 'equipment_liability' => 0,
                 'cylinder_coverage' => 0,
                 'orgUuid' => $this->data['orgUuid'],
                 'product' => 'Emergency First Response'];
        $config = $this->getApplicationConfig();
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'PolicyDocument', $data);
        $this->assertEquals(isset($content['uuid']), true);
        $this->assertEquals(isset($content['policy_id']), true);
        $this->assertEquals(isset($content['carrier']), true);
        $this->assertEquals(isset($content['license_number']), true);
        $this->assertEquals(isset($content['certificate_no']), true);
        $doc = $config['APP_DOCUMENT_FOLDER'].$content['documents']['coi_document'];
        $this->assertTrue(is_file($doc));
        $this->assertTrue(filesize($doc)>0);
        $doc = substr($doc, 0, strripos($doc, '/'));
        $files = glob($config['APP_DOCUMENT_FOLDER'].$this->data['orgUuid'].'/'.$content['uuid'].'/'."*");
        $filecount = count($files);
        $this->assertEquals($filecount,3);
        FileUtils::rmDir($config['APP_DOCUMENT_FOLDER'].$this->data['orgUuid']);
    }


    public function testEFRPolicyWithAIDocument()
    {
        $config = $this->getApplicationConfig();
        $orgId = AuthContext::put(AuthConstants::ORG_ID, 1);
        AuthContext::put(AuthConstants::ORG_UUID, $this->data['orgUuid']);
        $appId = $this->data['UUID'];
        $data = [
                'firstname' => 'Mohan',
                 'initial' => 'Raj' ,
                 'lastname' => 'D',
                 'address1' => 'ABC 200',
                 'address2' => 'XYZ 300',
                 'city' => 'APO',
                 'state' => 'California',
                 'country' => 'US',
                 'zip' => '09522-9998',
                 'padi' => '34567',
                 'start_date' => '2019-06-01',
                 'end_date' => '2020-06-30',
                 'insured_status'=> 'Divester',
                 'physical_address' => 'APO,AE',
                 'single_limit' => '1,000,000',
                 'annual_aggregate' => '2,000,000',
                 'equipment_liability' => 0,
                 'cylinder_coverage' => 0,
                 'orgUuid' => $this->data['orgUuid'],
                 'product' => 'Emergency First Response',
                 'additionalInsured' => array(array("additionalInformation" => "SHe ditched TVS for Royal Enfield","address" => "Hell","businessRelation"=> "Enemy","city"=> "Bangalore","name"=> "Neha","state"=> "Karnataka","zip"=> "420420"),array("additionalInformation"=> "Wonderful person","address"=> "No.33, 8th cross, 24th main","businessRelation"=> "Friend","city"=> "Bangalore","name"=> "Prajwal K","state"=> "Karnataka","zip"=> "560102"))];
        $config = $this->getApplicationConfig();
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'PolicyDocument', $data);
        $this->assertEquals(isset($content['uuid']), true);
        $this->assertEquals(isset($content['policy_id']), true);
        $this->assertEquals(isset($content['carrier']), true);
        $this->assertEquals(isset($content['license_number']), true);
        $this->assertEquals(isset($content['certificate_no']), true);
        $doc = $config['APP_DOCUMENT_FOLDER'].$content['documents']['coi_document'];
        $this->assertTrue(is_file($doc));
        $this->assertTrue(filesize($doc)>0);
        $doc = substr($doc, 0, strripos($doc, '/'));
        $files = glob($config['APP_DOCUMENT_FOLDER'].$this->data['orgUuid'].'/'.$content['uuid'].'/'."*");
        $filecount = count($files);
        $this->assertEquals($filecount,4);
        FileUtils::rmDir($config['APP_DOCUMENT_FOLDER'].$this->data['orgUuid']);
    }


    public function testGroupPolicyDocument()
    {
        $config = $this->getApplicationConfig();
        $orgId = AuthContext::put(AuthConstants::ORG_ID, 1);
        AuthContext::put(AuthConstants::ORG_UUID, $this->data['orgUuid']);
        $appId = $this->data['UUID'];
        $data = [
                'firstname' => 'Mohan',
                 'initial' => 'Raj' ,
                 'lastname' => 'D',
                 'address1' => 'ABC 200',
                 'address2' => 'XYZ 300',
                 'city' => 'APO',
                 'state' => 'New Jersey',
                 'country' => 'US',
                 'zip' => '09522-9998',
                 'padi' => '34567',
                 'start_date' => '2019-06-01',
                 'end_date' => '2020-06-30',
                 'insured_status'=> 'Divester',
                 'physical_address' => 'APO,AE',
                 'single_limit' => '1,000,000',
                 'annual_aggregate' => '2,000,000',
                 'equipment_liability' => 0,
                 'cylinder_coverage' => 0,
                 'orgUuid' => $this->data['orgUuid'],
                 'product' => 'Group Professional Liability',
                 'groupAdditionalInsured' => array(array("additionalInformation" => "SHe ditched TVS for Royal Enfield","address" => "Hell","businessRelation"=> "Enemy","city"=> "Bangalore","name"=> "Neha","state"=> "Karnataka","zip"=> "420420"),array("additionalInformation"=> "Wonderful person","address"=> "No.33, 8th cross, 24th main","businessRelation"=> "Friend","city"=> "Bangalore","name"=> "Prajwal K","state"=> "Karnataka","zip"=> "560102"))];
        $config = $this->getApplicationConfig();
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'PolicyDocument', $data);
        $this->assertEquals(isset($content['uuid']), true);
        $this->assertEquals(isset($content['policy_id']), true);
        $this->assertEquals(isset($content['carrier']), true);
        $this->assertEquals(isset($content['license_number']), true);
        $this->assertEquals(isset($content['certificate_no']), true);
        $doc = $config['APP_DOCUMENT_FOLDER'].$content['documents']['coi_document'];
        $this->assertTrue(is_file($doc));
        $this->assertTrue(filesize($doc)>0);
        $doc = substr($doc, 0, strripos($doc, '/'));
        $files = glob($config['APP_DOCUMENT_FOLDER'].$this->data['orgUuid'].'/'.$content['uuid'].'/'."*");
        $filecount = count($files);
        $this->assertEquals($filecount,2);
        FileUtils::rmDir($config['APP_DOCUMENT_FOLDER'].$this->data['orgUuid']);
    }

    public function testGroupPolicyNiDocument()
    {
        $config = $this->getApplicationConfig();
        $orgId = AuthContext::put(AuthConstants::ORG_ID, 1);
        AuthContext::put(AuthConstants::ORG_UUID, $this->data['orgUuid']);
        $appId = $this->data['UUID'];
        $data = [
                'firstname' => 'Mohan',
                 'initial' => 'Raj' ,
                 'lastname' => 'D',
                 'address1' => 'ABC 200',
                 'address2' => 'XYZ 300',
                 'city' => 'APO',
                 'state' => 'New Jersey',
                 'country' => 'US',
                 'zip' => '09522-9998',
                 'padi' => '34567',
                 'start_date' => '2019-06-01',
                 'end_date' => '2020-06-30',
                 'insured_status'=> 'Divester',
                 'physical_address' => 'APO,AE',
                 'single_limit' => '1,000,000',
                 'annual_aggregate' => '2,000,000',
                 'equipment_liability' => 0,
                 'cylinder_coverage' => 0,
                 'orgUuid' => $this->data['orgUuid'],
                 'product' => 'Group Professional Liability',
                 'groupAdditionalInsured' => array(array("additionalInformation" => "SHe ditched TVS for Royal Enfield","address" => "Hell","businessRelation"=> "Enemy","city"=> "Bangalore","name"=> "Neha","state"=> "Karnataka","zip"=> "420420"),array("additionalInformation"=> "Wonderful person","address"=> "No.33, 8th cross, 24th main","businessRelation"=> "Friend","city"=> "Bangalore","name"=> "Prajwal K","state"=> "Karnataka","zip"=> "560102")),
                 'namedInsured' => array(0 =>array('memberid' => '000048','name' => 'MU LI','status' => 'Swim Instructor','effective_date' => '2020-06-30','upgrade' => 0),1 => array('memberid' => '000048','name' => 'MU LI','status' => 'Swim Instructor','effective_date' => '2020-06-30','upgrade' => 0))];
        $config = $this->getApplicationConfig();
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'PolicyDocument', $data);
        $this->assertEquals(isset($content['uuid']), true);
        $this->assertEquals(isset($content['policy_id']), true);
        $this->assertEquals(isset($content['carrier']), true);
        $this->assertEquals(isset($content['license_number']), true);
        $this->assertEquals(isset($content['certificate_no']), true);
        $doc = $config['APP_DOCUMENT_FOLDER'].$content['documents']['coi_document'];
        $this->assertTrue(is_file($doc));
        $this->assertTrue(filesize($doc)>0);
        $doc = substr($doc, 0, strripos($doc, '/'));
        $files = glob($config['APP_DOCUMENT_FOLDER'].$this->data['orgUuid'].'/'.$content['uuid'].'/'."*");
        $filecount = count($files);
        $this->assertEquals($filecount,3);
        FileUtils::rmDir($config['APP_DOCUMENT_FOLDER'].$this->data['orgUuid']);
    }


    public function testDiveStorePremiumSummary()
    {
        $config = $this->getApplicationConfig();
        $orgId = AuthContext::put(AuthConstants::ORG_ID, 1);
        AuthContext::put(AuthConstants::ORG_UUID, $this->data['orgUuid']);
        $appId = $this->data['UUID'];
        $data = [
            'firstname' => 'Mohan',
            'initial' => 'Raj' ,
            'lastname' => 'D',
            'storename' => 'UNDERSEAS SCUBA CENTER',
            'address1' => 'ABC 200',
            'address2' => 'XYZ 300',
            'city' => 'APO',
            'state' => 'New Jersey',
            'country' => 'US',
            'zip' => '09522-9998',
            'padi' => '34567',
            'start_date' => '2019-06-01',
            'end_date' => '2020-06-30',
            'insured_status'=> 'Divester',
            'physical_address' => 'APO,AE',
            'single_limit' => '1,000,000',
            'annual_aggregate' => '2,000,000',
            'equipment_liability' => 0,
            'cylinder_coverage' => 0,
            'orgUuid' => $this->data['orgUuid'],
            'product' => 'Dive Store',
            'divecenterGLpremium' => '2,939.00',
            'divecenterPLpremium' => '1,166.00',
            'surpluslinetax' => '50.00',
            'padiadminfee' => '50.00',
            'storepremium' => '5500.00',
            'annualreceipt' => '234,565',
            'divecentergrouppremium' => '1,242.00',
            'groupsurpluslinetax' => '3,234.00',
            'grouppadiadminfee' => '1,555.00',
            'groupannualreceipts' => '2,345',
            'totalgrouppremium' => '1,234.00',
            'divecenterdepositpaid' => '2,056.84',
            'desopitpaidpercentage' => '30'];
        $config = $this->getApplicationConfig();
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'DiveStorePremiumSummary', $data);
        $this->assertEquals(isset($content['uuid']), true);
        $this->assertEquals(isset($content['policy_id']), true);
        $this->assertEquals(isset($content['carrier']), true);
        $this->assertEquals(isset($content['license_number']), true);
        $this->assertEquals(isset($content['certificate_no']), true);
        $doc = $config['APP_DOCUMENT_FOLDER'].$content['documents']['premium_summary'];
        $this->assertTrue(is_file($doc));
        $this->assertTrue(filesize($doc)>0);
        $doc = substr($doc, 0, strripos($doc, '/'));
        $files = glob($config['APP_DOCUMENT_FOLDER'].$this->data['orgUuid'].'/'.$content['uuid'].'/'."*");
        $filecount = count($files);
        $this->assertEquals($filecount,1);
        FileUtils::rmDir($config['APP_DOCUMENT_FOLDER'].$this->data['orgUuid']);
    }


    public function testDiveBoatPolicyEndorsement()
    {
        $config = $this->getApplicationConfig();
        $orgId = AuthContext::put(AuthConstants::ORG_ID, 1);
        AuthContext::put(AuthConstants::ORG_UUID, $this->data['orgUuid']);
        $appId = $this->data['UUID'];
        $data = [
                 'firstname' => 'Mohan',
                 'lastname' => 'Raj' ,
                 'orgname' => "ABOVE AND BELOW THE SEA LLC L'S DIVE INC. & ST. THOMAS DIVING CLUB",
                 'address1' => 'ABC 200',
                 'address2' => 'XYZ 300',
                 'city' => 'APO',
                 'state' => 'California',
                 'country' => 'US',
                 'zip' => '09522-9998',
                 'padi' => '34567',
                 'start_date' => '2019-06-01',
                 'end_date' => '2020-06-30',
                 'orgUuid' => $this->data['orgUuid'],
                 'product' => 'Dive Boat',
                 'vessel_name' => 'LETTS DIVE',
                 'vessel_year' => '1778',
                 'vessel_length' => '30',
                 'vessel_hp' => '300',
                 'hull_type' => 'FIBER',
                 'hull_mfg' => 'HOPPER',
                 'vessel_sno' => '18000F888',
                 'limit_ins' => '90,000.00',
                 'personnal_effects' => '500.00/$5,000.00',
                 'passengers' => '18',
                 'crew_on_boat' => '2',
                 'crew_in_water' => 1,
                 'protection_liability_amt' => '1,000,000.00',
                 'medical_pay' => '5,000',
                 'total_premium' => '7,754.00',
                 'padi_admin_fee' => '75.00',
                 'navigation_limit_note' => 'WATERS OF PUERTO RICO AND THE U.S. VIRGIN ISLANDS NOT MORE THAN THREE (3) MILES FROM A HARBOR OF SAFE REFUGE. THE VESSEL MAY NOT CARRY PASSENGERS BETWEEN PUERTO RICO AND THE U.S. VIRGIN ISLANDS.',
                 'personal_effect_deduct' => '500.00',
                 'liability_ins_deduct' => '1,000.00',
                 'medical_deduct' => '100.00',
                 'manager_name' => 'Julie Joseph',
                 'manager_email' => 'abcd@gmail.com',
                 'quote_due_date' => '2019-06-01',
                 'layup_period' => array('startdate' => '2019-06-01','enddate' => '2019-06-01')
             ];
        $config = $this->getApplicationConfig();
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'PolicyEndorsement', $data);
        $this->assertEquals(isset($content['uuid']), true);
        $this->assertEquals(isset($content['policy_id']), true);
        $this->assertEquals(isset($content['carrier']), true);
        $this->assertEquals(isset($content['license_number']), true);
        $doc = $config['APP_DOCUMENT_FOLDER'].$content['documents']['endorsement'];
        $this->assertTrue(is_file($doc));
        $this->assertTrue(filesize($doc)>0);
        $doc = substr($doc, 0, strripos($doc, '/'));
        FileUtils::rmDir($config['APP_DOCUMENT_FOLDER'].$this->data['orgUuid']);
    }

}