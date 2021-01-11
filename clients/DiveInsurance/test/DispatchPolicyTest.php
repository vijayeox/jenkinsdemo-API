<?php

use Mockery as Mockery;
use Oxzion\AppDelegate\AppDelegateService;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\DelegateException;
use Oxzion\Test\DelegateTest;
use Oxzion\Utils\FileUtils;
use PHPUnit\DbUnit\DataSet\DefaultDataSet;
use PHPUnit\DbUnit\DataSet\YamlDataSet;

class DispatchPolicyTest extends DelegateTest
{
    private $policyService;
    private $templateService;

    public function setUp(): void
    {
        $this->loadConfig();
        $this->config = $this->getApplicationConfig();
        $this->data = array(
            "appName" => 'ox_client_app',
            'UUID' => 8765765,
            'fileUuid' => '53012471-2863-4949-afb1-e69b0891cabt',
            'description' => 'FirstAppOfTheClient',
            'orgUuid' => '53012471-2863-4949-afb1-e69b0891c98a',
        );
        $this->persistence = new Persistence($this->config, $this->data['UUID'], $this->data['appName']);
        $path = __DIR__ . '/../../../api/v1/data/delegate/' . $this->data['UUID'];
        if (!is_link($path)) {
            symlink(__DIR__ . '/../data/delegate/', $path);
        }
        $this->tempFile =  $this->config['TEMPLATE_FOLDER'] . $this->data['orgUuid'];
        $templateLocation = __DIR__ . "/../data/template";

        if (FileUtils::fileExists($this->tempFile)) {
            FileUtils::rmDir($this->tempFile);
        }
        FileUtils::symlink($templateLocation, $this->tempFile);

        $this->appFolder =  $this->config['APP_DOCUMENT_FOLDER'] . $this->data['orgUuid'];
        if (!file_exists($this->appFolder)) {
            FileUtils::createDirectory($this->appFolder);
        }
        $this->appFile = $this->appFolder . '/' . $this->data['fileUuid'];
        $appLocation = __DIR__ . "/../test/Files";
        if (FileUtils::fileExists($this->appFile)) {
            FileUtils::rmDir($this->appFile);
        }
        FileUtils::symlink($appLocation, $this->appFile);
        $this->chmod_r(dirname($this->config['APP_DOCUMENT_FOLDER'] . $this->data['orgUuid'] . "/" . $this->data['fileUuid'] . "/"), 777, 777);
        parent::setUp();
    }


    function chmod_r($dir, $dirPermissions, $filePermissions)
    {
        $dp = opendir($dir);
        while ($file = readdir($dp)) {
            if (($file == ".") || ($file == ".."))
                continue;
            $fullPath = $dir . "/" . $file;

            if (is_dir($fullPath)) {
                chmod($fullPath, $dirPermissions);
                $this->chmod_r($fullPath, $dirPermissions, $filePermissions);
            } else {
                chmod($fullPath, $filePermissions);
            }
        }
        closedir($dp);
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $path = __DIR__ . '/../../../api/v1/data/delegate/' . $this->data['UUID'];
        if (is_link($path)) {
            unlink($path);
        }
        FileUtils::unlink($this->tempFile);
        FileUtils::unlink($this->appFile);
        FileUtils::rmDir($this->appFolder);
    }

    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__) . "/Dataset/File.yml");
        return $dataset;
    }

    public function getMockMessageProducer()
    {
        $dispatchService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $mockMessageProducer = Mockery::mock('Oxzion\Messaging\MessageProducer');
        $dispatchService->setMessageProducer($mockMessageProducer);
        return $mockMessageProducer;
    }

    public function testDispatchIplPolicy()
    {
        $data = array();
        $config = $this->getApplicationConfig();
        $appId = $this->data['UUID'];
        $data['email'] = 'neha@myvamla.com';
        $data['firstname'] = 'Neha';
        $data['lastname'] = 'Rai';
        $data['documents'] = array('policy_document' => '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/certificate.pdf', 'coi_document' => '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/dummy.pdf', 'card' => '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/dummy.pdf', 'blanket_document' => '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/dummy.pdf');
        $data['product'] = 'Individual Professional Liability';
        $data['orgUuid'] = '53012471-2863-4949-afb1-e69b0891c98a';
        $data['padi'] = '124';
        if (enableCamel == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendQueue')->with(Mockery::any(), 'mail')->once()->andReturn();
        }
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'DispatchNewPolicy', $data);
        $this->assertEquals($content, array());
    }

    public function testDispatchIplPolicyInvalidFilePath()
    {
        $data = array();
        $config = $this->getApplicationConfig();
        $appId = $this->data['UUID'];
        $data['email'] = 'neha@myvamla.com';
        $data['firstname'] = 'Neha';
        $data['lastname'] = 'Rai';
        $data['documents'] = array('policy_document' => '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/cert.pdf', 'coi_document' => '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/dummy.pdf', 'card' => '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/dummy.pdf', 'blanket_document' => '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/dummy.pdf');
        $data['product'] = 'Individual Professional Liability';
        $data['orgUuid'] = '53012471-2863-4949-afb1-e69b0891c98a';
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $this->expectException(DelegateException::class);
        $this->expectExceptionMessage("Documents Not Found");
        $content = $delegateService->execute($appId, 'DispatchNewPolicy', $data);
    }

    public function testDispatchIplPolicyWithoutRequiredDocument()
    {
        $data = array();
        $config = $this->getApplicationConfig();
        $appId = $this->data['UUID'];
        $data['email'] = 'neha@myvamla.com';
        $data['firstname'] = 'Neha';
        $data['lastname'] = 'Rai';
        $data['documents'] = array('policy_document' => '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/certificate.pdf', 'coi_document' => '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/dummy.pdf');
        $data['product'] = 'Individual Professional Liability';
        $data['orgUuid'] = '53012471-2863-4949-afb1-e69b0891c98a';
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $this->expectException(DelegateException::class);
        $this->expectExceptionMessage("Required Documents are not Found");
        $content = $delegateService->execute($appId, 'DispatchNewPolicy', $data);
    }

    public function testDispatchDbPolicy()
    {
        $data = array();
        $config = $this->getApplicationConfig();
        $appId = $this->data['UUID'];
        $data['email'] = 'neha@myvamla.com';
        $data['firstname'] = 'Neha';
        $data['lastname'] = 'Rai';
        $data['vessel_name'] = 'Orion';
        $data['documents'] = array('policy_document' => '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/certificate.pdf', 'coi_document' => '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/dummy.pdf', 'cover_letter' => '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/dummy.pdf');
        $data['product'] = 'Dive Boat';
        $data['orgUuid'] = '53012471-2863-4949-afb1-e69b0891c98a';
        $data['padi'] = '435435';
        $data['approverName'] = 'CSR1';
        $data['approverEmailId'] = 'csr@gmail.com';
        $data['approverDesignation'] = 'CSR';
        if (enableCamel == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendQueue')->with(Mockery::any(), 'mail')->once()->andReturn();
        }
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'DispatchNewPolicy', $data);
        $this->assertEquals($content, array());
    }

    public function testDispatchDbPolicyWithInvalidDocument()
    {
        $data = array();
        $config = $this->getApplicationConfig();
        $appId = $this->data['UUID'];
        $data['email'] = 'neha@myvamla.com';
        $data['firstname'] = 'Neha';
        $data['lastname'] = 'Rai';
        $data['vessel_name'] = 'Orion';
        $data['documents'] = array('policy_document' => '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/ceicate.pdf', 'coi_document' => '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/dummy.pdf', 'cover_letter' => '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/dummy.pdf');
        $data['product'] = 'Dive Boat';
        $data['orgUuid'] = '53012471-2863-4949-afb1-e69b0891c98a';
        $data['padi'] = 133;
        $data['approverName'] = 'CSR1';
        $data['approverEmailId'] = 'csr@gmail.com';
        $data['approverDesignation'] = 'CSR';
        $data['identifier_field'] = 'padi';
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $this->expectException(DelegateException::class);
        $this->expectExceptionMessage("Documents Not Found");
        $content = $delegateService->execute($appId, 'NewPolicyDocumentDispatch', $data);
        $this->assertEquals($content, array());
    }

    public function testDispatchDbPolicyWithoutRequiredDocument()
    {
        $data = array();
        $config = $this->getApplicationConfig();
        $appId = $this->data['UUID'];
        $data['email'] = 'neha@myvamla.com';
        $data['firstname'] = 'Neha';
        $data['lastname'] = 'Rai';
        $data['boat_name'] = 'Orion';
        $data['documents'] = array('policy_document' => '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/certificate.pdf', 'coi_document' => '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/dummy.pdf');
        $data['product'] = 'Dive Boat';
        $data['orgUuid'] = '53012471-2863-4949-afb1-e69b0891c98a';
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $this->expectException(DelegateException::class);
        $this->expectExceptionMessage("Required Documents are not Found");
        $content = $delegateService->execute($appId, 'DispatchNewPolicy', $data);
        $this->assertEquals($content, array());
    }

    public function testDispatchDsPolicy()
    {
        $data = array();
        $config = $this->getApplicationConfig();
        $appId = $this->data['UUID'];
        $data['email'] = 'neha@myvamla.com';
        $data['firstname'] = 'Neha';
        $data['lastname'] = 'Rai';
        $data['documents'] = array('property_coi_document' => '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/certificate.pdf', 'liability_coi_document' => '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/dummy.pdf', 'property_policy_document' => '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/dummy.pdf', 'liability_policy_document' => '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/dummy.pdf', 'cover_letter' => '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/dummy.pdf');
        $data['product'] = 'Dive Store';
        $data['orgUuid'] = '53012471-2863-4949-afb1-e69b0891c98a';
        $data['propertyCoverageSelect'] = 'no';
        $data['groupProfessionalLiabilitySelect'] = 'no';
        $data['business_padi'] = '234';
        $data['approverName'] = 'CSR1';
        $data['approverEmailId'] = 'csr@gmail.com';
        $data['approverDesignation'] = 'CSR';
        if (enableCamel == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendQueue')->with(Mockery::any(), 'mail')->once()->andReturn();
        }
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'NewPolicyDocumentDispatch', $data);
        $this->assertEquals($content, array());
    }

    public function testDispatchDsPolicyWithInvalidDocument()
    {
        $data = array();
        $config = $this->getApplicationConfig();
        $appId = $this->data['UUID'];
        $data['email'] = 'neha@myvamla.com';
        $data['firstname'] = 'Neha';
        $data['lastname'] = 'Rai';
        $data['documents'] =  array('policy_document' => '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/cericate.pdf', 'coi_document' => '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/dummy.pdf', 'cover_letter' => '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/dummy.pdf');
        $data['product'] = 'Dive Store';
        $data['orgUuid'] = '53012471-2863-4949-afb1-e69b0891c98a';
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $exception = $this->expectException(DelegateException::class);
        $this->expectExceptionMessage("Required Documents are not Found");
        $content = $delegateService->execute($appId, 'DispatchNewPolicy', $data);
        $this->assertEquals($content, array());
    }

    public function testDispatchDsPolicyWithoutRequiredDocument()
    {
        $data = array();
        $config = $this->getApplicationConfig();
        $appId = $this->data['UUID'];
        $data['email'] = 'neha@myvamla.com';
        $data['firstname'] = 'Neha';
        $data['lastname'] = 'Rai';
        $data['documents'] = array('policy_document' => '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/certificate.pdf', 'coi_document' => '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/dummy.pdf');
        $data['product'] = 'Dive Store';
        $data['orgUuid'] = '53012471-2863-4949-afb1-e69b0891c98a';
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $this->expectException(DelegateException::class);
        $this->expectExceptionMessage("Required Documents are not Found");
        $content = $delegateService->execute($appId, 'DispatchNewPolicy', $data);
        $this->assertEquals($content, array());
    }

    public function testDispatchIplRenewalPolicy()
    {
        $data = array();
        $config = $this->getApplicationConfig();
        $appId = $this->data['UUID'];
        $data['email'] = 'neha@myvamla.com';
        $data['firstname'] = 'Neha';
        $data['lastname'] = 'Rai';
        $data['store_name'] = 'ABCD';
        $data['store_id'] = '00000';
        $data['expiry_year'] = '2019';
        $data['policy_document'] = '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/certificate.pdf';
        $data['coi_document'] = '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/dummy.pdf';
        $data['product'] = 'Dive Store';
        $data['orgUuid'] = '53012471-2863-4949-afb1-e69b0891c98a';
        $data['business_name'] = "ABCD";
        $data['business_padi'] = 1234;
        if (enableCamel == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendQueue')->with(Mockery::any(), 'mail')->once()->andReturn();
        }
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'DispatchRenewalPolicy', $data);
        $this->assertEquals($content, array());
    }

    public function testDispatchIplRenewalReminder()
    {
        $data = array();
        $config = $this->getApplicationConfig();
        $appId = $this->data['UUID'];
        $data['email'] = 'neha@myvamla.com';
        $data['firstname'] = 'Neha';
        $data['lastname'] = 'Rai';
        $data['expiry_year'] = '2019';
        $data['product'] = 'Individual Professional Liability';
        $data['orgUuid'] = '53012471-2863-4949-afb1-e69b0891c98a';
        if (enableCamel == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendQueue')->with(Mockery::any(), 'mail')->once()->andReturn();
        }
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'DispatchRenewalNotification', $data);
        $this->assertEquals($content, array());
    }

    public function testDispatchIplAutoRenewal()
    {
        $data = array();
        $config = $this->getApplicationConfig();
        $appId = $this->data['UUID'];
        $data['email'] = 'neha@myvamla.com';
        $data['firstname'] = 'Neha';
        $data['lastname'] = 'Rai';
        $data['address1'] = 'Bangalore';
        $data['address2'] = 'Karnataka';
        $data['city'] = 'Bangalore';
        $data['state'] = 'Karnataka';
        $data['zip'] = '12345';
        $data['coverage'] = '437653';
        $data['isequipmentliability'] = 1;
        $data['equipment'] = 'Equipment';
        $data['careerCoverage'] = 'Instructor';
        $data['careerCoverageVal'] = 234;
        $data['isexcessliability'] = 0;
        $data['excessLiability'] = 'excessLiabilityCoverageDeclined';
        $data['credit_card_type'] = 'credit';
        $data['card_no'] = '0000';
        $data['amount'] = "500";
        $data['card_expiry_date'] = '01/10';
        $data['policy_period'] = 3;
        $data['expiry_year'] = '2019';
        $data['product'] = 'Individual Professional Liability';
        $data['orgUuid'] = '53012471-2863-4949-afb1-e69b0891c98a';
        $data['padi'] = 123;
        $data['state_in_short'] = 'CA';
        $data['country'] = 'US';
        if (enableCamel == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendQueue')->with(Mockery::any(), 'mail')->once()->andReturn();
        }
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'DispatchAutoRenewalNotification', $data);

        $this->assertEquals($content, array());
    }

    public function testDispatchIplExpiredPolicy()
    {
        $data = array();
        $config = $this->getApplicationConfig();
        $appId = $this->data['UUID'];
        $data['email'] = 'neha@myvamla.com';
        $data['firstname'] = 'Neha';
        $data['lastname'] = 'Rai';
        $data['expiry_year'] = '2019';
        $data['cost'] = '1000000';
        $data['product'] = 'Individual Professional Liability';
        $data['orgUuid'] = '53012471-2863-4949-afb1-e69b0891c98a';
        $data['padi'] = '124';
        $data['amount'] = 200;
        if (enableCamel == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendQueue')->with(Mockery::any(), 'mail')->once()->andReturn();
        }
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'DispatchExpiredNotification', $data);
        $this->assertEquals($content, array());
    }


    public function testDispatchQuoteDocument()
    {
        $data = array();
        $config = $this->getApplicationConfig();
        $appId = $this->data['UUID'];
        $data['email'] = 'neha@myvamla.com';
        $data['firstname'] = 'Neha';
        $data['lastname'] = 'Rai';
        $data['documents'] = array('cover_letter' => '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/certificate.pdf', 'coi_document' => '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/dummy.pdf');
        $data['product'] = 'Dive Store';
        $data['orgId'] = '53012471-2863-4949-afb1-e69b0891c98a';
        $data['activityInstanceId'] = '512311-2863-4949-afb1-e69b0891c98a';
        $data['workflowInstanceId'] = '212311-2863-4949-afb1-e69b0891c98a';
        $data['vessel_name'] = 'HUB';
        $data['fileId'] = 'd1968945-9191-4a66-a86d-ee73e703234';
        if (enableCamel == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendQueue')->with(Mockery::any(), 'mail')->once()->andReturn();
        }
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'DispatchProposalDocument', $data);
        $this->assertEquals($content, array());
    }

    public function testDispatchDiveStoreQuoteDocument()
    {
        $data = array();
        $config = $this->getApplicationConfig();
        $appId = $this->data['UUID'];
        $data['email'] = 'neha@myvamla.com';
        $data['firstname'] = 'Neha';
        $data['lastname'] = 'Rai';
        $data['activityInstanceId'] = '512311-2863-4949-afb1-e69b0891c98a';
        $data['workflowInstanceId'] = '212311-2863-4949-afb1-e69b0891c98a';
        $data['documents'] = array('cover_letter' => '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/certificate.pdf', 'coi_document' => '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/dummy.pdf');
        $data['product'] = 'Dive Store';
        $data['orgId'] = '53012471-2863-4949-afb1-e69b0891c98a';
        $data['fileId'] = 'd1968945-9191-4a66-a86d-ee73e703234';
        if (enableCamel == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendQueue')->with(Mockery::any(), 'mail')->once()->andReturn();
        }
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'DispatchProposalDocument', $data);
        $this->assertEquals($content, array());
    }

    public function testNewPolicyDocumentDispatch()
    {
        $data = array();
        $config = $this->getApplicationConfig();
        $appId = $this->data['UUID'];
        $data['email'] = 'neha@myvamla.com';
        $data['firstname'] = 'Neha';
        $data['lastname'] = 'Rai';
        $data['documents'] = array(
            'cover_letter' => '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/certificate.pdf',
            'coi_document' => '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/dummy.pdf',
            'property_coi_document' => '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/dummy.pdf',
            'group_coi_document' => '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/dummy.pdf'
        );
        $data['product'] = 'Dive Store';
        $data['orgUuid'] = '53012471-2863-4949-afb1-e69b0891c98a';
        $data['propertyCoverageSelect'] = "yes";
        $data['groupProfessionalLiabilitySelect'] = "yes";
        $data['business_padi'] = 1234;
        $data['approverName'] = 'CSR1';
        $data['approverEmailId'] = 'csr@gmail.com';
        $data['approverDesignation'] = 'CSR';
        if (enableCamel == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendQueue')->with(Mockery::any(), 'mail')->times(3)->andReturn();
        }
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'NewPolicyDocumentDispatch', $data);
        $this->assertEquals($content, array());
    }
    public function testNewPolicyDocumentDispatchWithoutProperty()
    {
        $data = array();
        $config = $this->getApplicationConfig();
        $appId = $this->data['UUID'];
        $data['email'] = 'neha@myvamla.com';
        $data['firstname'] = 'Neha';
        $data['lastname'] = 'Rai';
        $data['documents'] = array(
            'cover_letter' => '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/certificate.pdf',
            'coi_document' => '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/dummy.pdf',
            'group_coi_document' => '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/dummy.pdf'
        );
        $data['product'] = 'Dive Store';
        $data['orgUuid'] = '53012471-2863-4949-afb1-e69b0891c98a';
        $data['propertyCoverageSelect'] = "no";
        $data['groupProfessionalLiabilitySelect'] = "yes";
        $data['business_padi'] = 1234;
        $data['approverName'] = 'CSR1';
        $data['approverEmailId'] = 'csr@gmail.com';
        $data['approverDesignation'] = 'CSR';
        if (enableCamel == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendQueue')->with(Mockery::any(), 'mail')->twice(2)->andReturn();
        }
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'NewPolicyDocumentDispatch', $data);
        $this->assertEquals($content, array());
    }
}
