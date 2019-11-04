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
                 'product' => 'Individual Professional Liability',
                 'sameasmailingaddress' => 1,
                 'endrosement_status' => 'Instructor',
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
        $this->assertEquals(isset($content['policy_document']), true);
        $doc = $config['APP_DOCUMENT_FOLDER'].$content['coi_document'];
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
                 'sameasmailingaddress' => 1,
                 'endrosement_status' => 'Instructor',
                 'additionalInsured' => '{"name" : ["LITITZ COMM CENTER","BAINBRIDGE SPORTSMENS CLUB INC.","BURLINGTON COUNTY COLLEGE","GOLDEN MEADOWS SWIM CENTER","WILLOW SPRINGS PARK","HOLIDAY INN EXPRESS (LITITZ, PA)"]}',
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
                 'sameasmailingaddress' => 1,
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
                 'additionalInsured' => '{"name" : ["LITITZ COMM CENTER","BAINBRIDGE SPORTSMENS CLUB INC.","BURLINGTON COUNTY COLLEGE","GOLDEN MEADOWS SWIM CENTER","WILLOW SPRINGS PARK","HOLIDAY INN EXPRESS (LITITZ, PA)"]}',
                 'manager_name' => 'Julie Joseph',
                 'manager_email' => 'abcd@gmail.com',
             ];
        $config = $this->getApplicationConfig();
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'QuoteDocument', $data);
        $this->assertEquals(isset($content['uuid']), true);
        $this->assertEquals(isset($content['policy_id']), true);
        $this->assertEquals(isset($content['carrier']), true);
        $this->assertEquals(isset($content['license_number']), true);
        $this->assertEquals(isset($content['certificate_no']), false);
        $doc = $config['APP_DOCUMENT_FOLDER'].$content['coi_document'];
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
                 'additionalInsured' => '{"name" : ["LITITZ COMM CENTER","BAINBRIDGE SPORTSMENS CLUB INC.","BURLINGTON COUNTY COLLEGE","GOLDEN MEADOWS SWIM CENTER","WILLOW SPRINGS PARK","HOLIDAY INN EXPRESS (LITITZ, PA)"]}',
                 'manager_name' => 'Julie Joseph',
                 'manager_email' => 'abcd@gmail.com',
             ];
        $config = $this->getApplicationConfig();
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'QuoteDocument', $data);
        $this->assertEquals(isset($content['uuid']), true);
        $this->assertEquals(isset($content['policy_id']), true);
        $this->assertEquals(isset($content['carrier']), true);
        $this->assertEquals(isset($content['license_number']), true);
        $this->assertEquals(isset($content['certificate_no']), true);
        $doc = $config['APP_DOCUMENT_FOLDER'].$content['coi_document'];
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
                 'additionalNamedInsured' => '{"name" : ["LITITZ COMM CENTER","BAINBRIDGE SPORTSMENS CLUB INC.","BURLINGTON COUNTY COLLEGE","GOLDEN MEADOWS SWIM CENTER","WILLOW SPRINGS PARK","HOLIDAY INN EXPRESS (LITITZ, PA)"]}'            ];
        $config = $this->getApplicationConfig();
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'QuoteDocument', $data);
        $this->assertEquals(isset($content['uuid']), true);
        $this->assertEquals(isset($content['policy_id']), true);
        $this->assertEquals(isset($content['carrier']), true);
        $this->assertEquals(isset($content['license_number']), true);
        $this->assertEquals(isset($content['certificate_no']), true);
        $doc = $config['APP_DOCUMENT_FOLDER'].$content['coi_document'];
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
        $this->assertEquals(isset($content['policy_document']), true);
        $doc = $config['APP_DOCUMENT_FOLDER'].$content['coi_document'];
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
                 'additionalInsured' => array("LITITZ COMM CENTER","BAINBRIDGE SPORTSMENS CLUB INC.","BURLINGTON COUNTY COLLEGE","GOLDEN MEADOWS SWIM CENTER","WILLOW SPRINGS PARK","HOLIDAY INN EXPRESS (LITITZ, PA)"),
                 'manager_name' => 'Julie Joseph',
                 'manager_email' => 'abcd@gmail.com',
                 'lossPayees' => array("LITITZ COMM CENTER","BAINBRIDGE SPORTSMENS CLUB INC.","BURLINGTON COUNTY COLLEGE","GOLDEN MEADOWS SWIM CENTER","WILLOW SPRINGS PARK","HOLIDAY INN EXPRESS (LITITZ, PA)")];
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
        $this->assertEquals(isset($content['policy_document']), true);
        $doc = $config['APP_DOCUMENT_FOLDER'].$content['coi_document'];
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
                 'manager_name' => 'Julie Joseph',
                 'manager_email' => 'abcd@gmail.com',
                 'additionalInsured' => array("LITITZ COMM CENTER","BAINBRIDGE SPORTSMENS CLUB INC.","BURLINGTON COUNTY COLLEGE","GOLDEN MEADOWS SWIM CENTER","WILLOW SPRINGS PARK","HOLIDAY INN EXPRESS (LITITZ, PA)"),
                 'lossPayees' => array("COMMUNITY BANK OF ELMHURST-300 W.BUTTERFIELD RD. ELMHURST, IL 60126- (LOAN #1000477-1 AND LOAN#133183-1)","COMMUNITY BANK OF ELMHURST-300 W.BUTTERFIELD RD. ELMHURST, IL 60126- (LOAN #1000477-1 AND LOAN#133183-1)")];

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
        $doc = $config['APP_DOCUMENT_FOLDER'].$content['coi_document'];
        $this->assertTrue(is_file($doc));
        $this->assertTrue(filesize($doc)>0);
        $doc = substr($doc, 0, strripos($doc, '/'));
        $files = glob($config['APP_DOCUMENT_FOLDER'].$this->data['orgUuid'].'/'.$content['uuid'].'/'."*");
        $filecount = count($files);
        $this->assertEquals($filecount,2);
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
                 'product' => 'Emergency First Response',
                 'additionalInsured' => '{"name" : ["LITITZ COMM CENTER","BAINBRIDGE SPORTSMENS CLUB INC.","BURLINGTON COUNTY COLLEGE","GOLDEN MEADOWS SWIM CENTER","WILLOW SPRINGS PARK","HOLIDAY INN EXPRESS (LITITZ, PA)"]}'];
        $config = $this->getApplicationConfig();
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'PolicyDocument', $data);
        $this->assertEquals(isset($content['uuid']), true);
        $this->assertEquals(isset($content['policy_id']), true);
        $this->assertEquals(isset($content['carrier']), true);
        $this->assertEquals(isset($content['license_number']), true);
        $this->assertEquals(isset($content['certificate_no']), true);
        $doc = $config['APP_DOCUMENT_FOLDER'].$content['coi_document'];
        $this->assertTrue(is_file($doc));
        $this->assertTrue(filesize($doc)>0);
        $doc = substr($doc, 0, strripos($doc, '/'));
        $files = glob($config['APP_DOCUMENT_FOLDER'].$this->data['orgUuid'].'/'.$content['uuid'].'/'."*");
        $filecount = count($files);
        $this->assertEquals($filecount,3);
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
                 'groupAdditionalInsured' => array("LITITZ COMM CENTER","BAINBRIDGE SPORTSMENS CLUB INC.","BURLINGTON COUNTY COLLEGE","GOLDEN MEADOWS SWIM CENTER","WILLOW SPRINGS PARK","HOLIDAY INN EXPRESS (LITITZ, PA)")];
        $config = $this->getApplicationConfig();
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'PolicyDocument', $data);
        $this->assertEquals(isset($content['uuid']), true);
        $this->assertEquals(isset($content['policy_id']), true);
        $this->assertEquals(isset($content['carrier']), true);
        $this->assertEquals(isset($content['license_number']), true);
        $this->assertEquals(isset($content['certificate_no']), true);
        $doc = $config['APP_DOCUMENT_FOLDER'].$content['coi_document'];
        $this->assertTrue(is_file($doc));
        $this->assertTrue(filesize($doc)>0);
        $doc = substr($doc, 0, strripos($doc, '/'));
        $files = glob($config['APP_DOCUMENT_FOLDER'].$this->data['orgUuid'].'/'.$content['uuid'].'/'."*");
        $filecount = count($files);
        $this->assertEquals($filecount,1);
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
                 'groupAdditionalInsured' => array("LITITZ COMM CENTER","BAINBRIDGE SPORTSMENS CLUB INC.","BURLINGTON COUNTY COLLEGE","GOLDEN MEADOWS SWIM CENTER","WILLOW SPRINGS PARK","HOLIDAY INN EXPRESS (LITITZ, PA)"),
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
        $doc = $config['APP_DOCUMENT_FOLDER'].$content['coi_document'];
        $this->assertTrue(is_file($doc));
        $this->assertTrue(filesize($doc)>0);
        $doc = substr($doc, 0, strripos($doc, '/'));
        $files = glob($config['APP_DOCUMENT_FOLDER'].$this->data['orgUuid'].'/'.$content['uuid'].'/'."*");
        $filecount = count($files);
        $this->assertEquals($filecount,2);
        FileUtils::rmDir($config['APP_DOCUMENT_FOLDER'].$this->data['orgUuid']);
    }
}