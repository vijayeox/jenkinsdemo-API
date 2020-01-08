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

class CancelPolicyTest extends DelegateTest
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


    public function testPolicyDocumentDiveBoatSold()
    {
        $config = $this->getApplicationConfig();
        $orgId = AuthContext::put(AuthConstants::ORG_ID, 1);
        AuthContext::put(AuthConstants::ORG_UUID, $this->data['orgUuid']);
        $appId = $this->data['UUID'];
        $data = [
                 'uuid' => '0c8ea15f-d5e4-4a2d-9290-d2005cadc487',
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
                 'orgUuid' => $this->data['orgUuid'],
                 'product' => 'Dive Boat',
                 'CancelDate' => '11/15/2019',
                 'amount' => '$2880',
                 'certificate_no' => '123456789',
                 'padi' => 'purvi0808',
                 'policy_id' => '2300',
                 'reasonforCsrCancellation' => 'boatSold',
                 'cancellationStatus' => 'approved'];
        $config = $this->getApplicationConfig();
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'CancelPolicy', $data);
        $this->assertEquals(isset($content['uuid']), true);
        $this->assertEquals(isset($content['policy_id']), true);
        $this->assertEquals(isset($content['carrier']), true);
        $this->assertEquals(isset($content['license_number']), true);
        $this->assertEquals(isset($content['certificate_no']), true);
        $files = glob($config['APP_DOCUMENT_FOLDER'].$this->data['orgUuid'].'/'.$content['uuid'].'/'."*");
        $filecount = count($files);
        $this->assertEquals($filecount,8);
        FileUtils::rmDir($config['APP_DOCUMENT_FOLDER'].$this->data['orgUuid']);
    }
    // public function testPolicyDocumentDiveStoreSold()
    // {
    //     $config = $this->getApplicationConfig();
    //     $orgId = AuthContext::put(AuthConstants::ORG_ID, 1);
    //     AuthContext::put(AuthConstants::ORG_UUID, $this->data['orgUuid']);
    //     $appId = $this->data['UUID'];
    //     $data = [
    //              'uuid' => '0c8ea15f-d5e4-4a2d-9290-d2005cadc487',
    //              'firstname' => 'Mohan',
    //              'initial' => 'Raj' ,
    //              'lastname' => 'D',
    //              'address1' => 'ABC 200',
    //              'address2' => 'XYZ 300',
    //              'city' => 'APO',
    //              'state' => 'California',
    //              'country' => 'US',
    //              'zip' => '09522-9998',                
    //              'padi' => '34567',
    //              'start_date' => '2019-06-01',
    //              'end_date' => '2020-06-30',
    //              'orgUuid' => $this->data['orgUuid'],
    //              'product' => 'Dive Store',
    //              'CancelDate' => '11/15/2019',
    //              'amount' => '$2880',
    //              'certificate_no' => '123456789',
    //              'padi' => 'purvi0808',
    //              'policy_id' => '2300',
    //              'reasonforCsrCancellation' => 'storeSold',
    //              'cancellationStatus' => 'approved'];
    //     $config = $this->getApplicationConfig();
    //     $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
    //     $delegateService->setPersistence($appId, $this->persistence);
    //     $content = $delegateService->execute($appId, 'CancelPolicy', $data);
    //     $this->assertEquals(isset($content['uuid']), true);
    //     $this->assertEquals(isset($content['policy_id']), true);
    //     $this->assertEquals(isset($content['carrier']), true);
    //     $this->assertEquals(isset($content['license_number']), true);
    //     $this->assertEquals(isset($content['certificate_no']), true);
    //     $files = glob($config['APP_DOCUMENT_FOLDER'].$this->data['orgUuid'].'/'.$content['uuid'].'/'."*");
    //     $filecount = count($files);
    //     $this->assertEquals($filecount,8);
    //     FileUtils::rmDir($config['APP_DOCUMENT_FOLDER'].$this->data['orgUuid']);
    // }
    // public function testPolicyDocumentDiveBoatPremium()
    // {
    //     $config = $this->getApplicationConfig();
    //     $orgId = AuthContext::put(AuthConstants::ORG_ID, 1);
    //     AuthContext::put(AuthConstants::ORG_UUID, $this->data['orgUuid']);
    //     $appId = $this->data['UUID'];
    //     $data = [
    //              'uuid' => '0c8ea15f-d5e4-4a2d-9290-d2005cadc487',
    //              'firstname' => 'Mohan',
    //              'initial' => 'Raj' ,
    //              'lastname' => 'D',
    //              'address1' => 'ABC 200',
    //              'address2' => 'XYZ 300',
    //              'city' => 'APO',
    //              'state' => 'California',
    //              'country' => 'US',
    //              'zip' => '09522-9998',                
    //              'padi' => '34567',
    //              'start_date' => '2019-06-01',
    //              'end_date' => '2020-06-30',
    //              'orgUuid' => $this->data['orgUuid'],
    //              'product' => 'Dive Boat',
    //              'CancelDate' => '11/15/2019',
    //              'amount' => '$2880',
    //              'certificate_no' => '123456789',
    //              'padi' => 'purvi0808',
    //              'policy_id' => '2300',
    //              'reasonforCsrCancellation' => 'nonPaymentOfPremium',
    //              'cancellationStatus' => 'approved'];
    //     $config = $this->getApplicationConfig();
    //     $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
    //     $delegateService->setPersistence($appId, $this->persistence);
    //     $content = $delegateService->execute($appId, 'CancelPolicy', $data);
    //     $this->assertEquals(isset($content['uuid']), true);
    //     $this->assertEquals(isset($content['policy_id']), true);
    //     $this->assertEquals(isset($content['carrier']), true);
    //     $this->assertEquals(isset($content['license_number']), true);
    //     $this->assertEquals(isset($content['certificate_no']), true);
    //     $files = glob($config['APP_DOCUMENT_FOLDER'].$this->data['orgUuid'].'/'.$content['uuid'].'/'."*");
    //     $filecount = count($files);
    //     $this->assertEquals($filecount,8);
    //     FileUtils::rmDir($config['APP_DOCUMENT_FOLDER'].$this->data['orgUuid']);
    // }
}