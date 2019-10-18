<?php
use Mockery as Mockery;
use Oxzion\AppDelegate\AppDelegateService;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\DelegateException;
use Oxzion\Test\DelegateTest;
use Oxzion\Utils\FileUtils;
use PHPUnit\DbUnit\DataSet\DefaultDataSet;

class DispatchPolicyTest extends DelegateTest
{
    private $policyService;
    private $templateService;

    public function setUp(): void
    {
        $this->loadConfig();
        $config = $this->getApplicationConfig();
        $this->data = array(
            "appName" => 'ox_client_app',
            'UUID' => 8765765,
            'fileUuid' => '53012471-2863-4949-afb1-e69b0891cabt',
            'description' => 'FirstAppOfTheClient',
            'orgUuid' => '53012471-2863-4949-afb1-e69b0891c98a',
        );
        $this->persistence = new Persistence($config, $this->data['UUID'], $this->data['appName']);
        $path = __DIR__ . '/../../../api/v1/data/delegate/' . $this->data['UUID'];
        if (!is_link($path)) {
            symlink(__DIR__ . '/../data/delegate/', $path);
        }
        $this->tempFile = $config['TEMPLATE_FOLDER'] . $this->data['orgUuid'];
        $templateLocation = __DIR__ . "/../data/template";

        if (FileUtils::fileExists($this->tempFile)) {
            FileUtils::rmDir($this->tempFile);
        }
        FileUtils::symlink($templateLocation, $this->tempFile);

        $this->appFolder = $config['APP_DOCUMENT_FOLDER'] . $this->data['orgUuid'];
        if (!file_exists($this->appFolder)) {
            FileUtils::createDirectory($this->appFolder);
        }
        $this->appFile = $this->appFolder . '/' . $this->data['fileUuid'];
        $appLocation = __DIR__ . "/../test/Files";
        if (FileUtils::fileExists($this->appFile)) {
            FileUtils::rmDir($this->appFile);
        }
        FileUtils::symlink($appLocation, $this->appFile);

        parent::setUp();
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
        return new DefaultDataSet();
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
        $data['policy_document'] = '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/certificate.pdf';
        $data['coi_document'] = '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/dummy.pdf';
        $data['card'] = '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/dummy.pdf';
        $data['product'] = 'Individual Professional Liability';
        $data['orgUuid'] = '53012471-2863-4949-afb1-e69b0891c98a';
        if (enableCamel == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(Mockery::any(), 'mail')->once()->andReturn();
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
        $data['policy_document'] = '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/cate.pdf';
        $data['coi_document'] = '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/dummy.pdf';
        $data['product'] = 'Individual Professional Liability';
        $data['orgUuid'] = '53012471-2863-4949-afb1-e69b0891c98a';
        if (enableCamel == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(Mockery::any(), 'mail')->once()->andReturn();
        }
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $exception = $this->expectException(DelegateException::class);
        $content = $delegateService->execute($appId, 'DispatchNewPolicy', $data);
        $this->assertEquals($content, array());
    }

    public function testDispatchIplPolicyWithoutRequiredDocument()
    {
        $data = array();
        $config = $this->getApplicationConfig();
        $appId = $this->data['UUID'];
        $data['email'] = 'neha@myvamla.com';
        $data['firstname'] = 'Neha';
        $data['lastname'] = 'Rai';
        $data['policy_document'] = '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/certificate.pdf';
        $data['coi_document'] = '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/dummy.pdf';
        $data['product'] = 'Individual Professional Liability';
        $data['orgUuid'] = '53012471-2863-4949-afb1-e69b0891c98a';
        if (enableCamel == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(Mockery::any(), 'mail')->once()->andReturn();
        }
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $exception = $this->expectException(DelegateException::class);
        $content = $delegateService->execute($appId, 'DispatchNewPolicy', $data);
        $this->assertEquals($content, array());
    }

    public function testDispatchDbPolicy()
    {
        $data = array();
        $config = $this->getApplicationConfig();
        $appId = $this->data['UUID'];
        $data['email'] = 'neha@myvamla.com';
        $data['firstname'] = 'Neha';
        $data['lastname'] = 'Rai';
        $data['boat_name'] = 'Orion';
        $data['policy_document'] = '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/certificate.pdf';
        $data['coi_document'] = '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/dummy.pdf';
        $data['cover_letter'] = '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/dummy.pdf';
        $data['product'] = 'Dive Boat';
        $data['orgUuid'] = '53012471-2863-4949-afb1-e69b0891c98a';
        if (enableCamel == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(Mockery::any(), 'mail')->once()->andReturn();
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
        $data['boat_name'] = 'Orion';
        $data['policy_document'] = '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/ceicate.pdf';
        $data['coi_document'] = '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/dummy.pdf';
        $data['cover_letter'] = '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/dummy.pdf';
        $data['product'] = 'Dive Boat';
        $data['orgUuid'] = '53012471-2863-4949-afb1-e69b0891c98a';
        if (enableCamel == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(Mockery::any(), 'mail')->once()->andReturn();
        }
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $exception = $this->expectException(DelegateException::class);
        $content = $delegateService->execute($appId, 'DispatchNewPolicy', $data);
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
        $data['policy_document'] = '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/certificate.pdf';
        $data['coi_document'] = '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/dummy.pdf';
        $data['product'] = 'Dive Boat';
        $data['orgUuid'] = '53012471-2863-4949-afb1-e69b0891c98a';
        if (enableCamel == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(Mockery::any(), 'mail')->once()->andReturn();
        }
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $exception = $this->expectException(DelegateException::class);
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
        $data['policy_document'] = '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/certificate.pdf';
        $data['coi_document'] = '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/dummy.pdf';
        $data['cover_letter'] = '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/dummy.pdf';
        $data['product'] = 'Dive Store';
        $data['orgUuid'] = '53012471-2863-4949-afb1-e69b0891c98a';
        if (enableCamel == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(Mockery::any(), 'mail')->once()->andReturn();
        }
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'DispatchNewPolicy', $data);
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
        $data['policy_document'] = '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/cericate.pdf';
        $data['coi_document'] = '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/dummy.pdf';
        $data['product'] = 'Dive Store';
        $data['orgUuid'] = '53012471-2863-4949-afb1-e69b0891c98a';
        if (enableCamel == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(Mockery::any(), 'mail')->once()->andReturn();
        }
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $exception = $this->expectException(DelegateException::class);
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
        $data['policy_document'] = '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/certificate.pdf';
        $data['coi_document'] = '53012471-2863-4949-afb1-e69b0891c98a/53012471-2863-4949-afb1-e69b0891cabt/dummy.pdf';
        $data['product'] = 'Dive Store';
        $data['orgUuid'] = '53012471-2863-4949-afb1-e69b0891c98a';
        if (enableCamel == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(Mockery::any(), 'mail')->once()->andReturn();
        }
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $exception = $this->expectException(DelegateException::class);
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
        if (enableCamel == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(Mockery::any(), 'mail')->once()->andReturn();
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
            $mockMessageProducer->expects('sendTopic')->with(Mockery::any(), 'mail')->once()->andReturn();
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
        $data['isexcessliability'] = 0;
        $data['credit_card_type'] = 'credit';
        $data['card_no'] = '0000';
        $data['card_expiry_date'] = '01/10';
        $data['policy_period'] = 3;
        $data['expiry_year'] = '2019';
        $data['product'] = 'Individual Professional Liability';
        $data['orgUuid'] = '53012471-2863-4949-afb1-e69b0891c98a';
        if (enableCamel == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(Mockery::any(), 'mail')->once()->andReturn();
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
        if (enableCamel == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(Mockery::any(), 'mail')->once()->andReturn();
        }
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'DispatchExpiredNotification', $data);
        $this->assertEquals($content, array());
    }

}
