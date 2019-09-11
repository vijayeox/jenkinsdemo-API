<?php
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Service\TemplateService;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Zend\Db\Adapter\Adapter;
use Oxzion\Utils\FileUtils;
use Oxzion\Transaction\TransactionManager;
use Oxzion\AppDelegate\MailDelegate;
use Oxzion\Test\DelegateTest;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use PHPUnit\DbUnit\DataSet\DefaultDataSet;
use Oxzion\AppDelegate\AppDelegateService;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Encryption\Crypto;
use Mockery as Mockery;
use Oxzion\Db\Persistence\Persistence;


class DispatchPolicyTest extends DelegateTest
{
    private $policyService;
    private $templateService;

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
        $this->persistence = new Persistence($config, $this->data['UUID'], $this->data['appName']);
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

    public function tearDown() : void
    {
        parent::tearDown();
        $path = __DIR__.'/../../../api/v1/data/delegate/'.$this->data['UUID'];
        if (is_link($path)) {
            unlink($path);
        }
        FileUtils::unlink($this->tempFile);
    }

    public function getDataSet()
    {
        return new DefaultDataSet();
    }

    public function getMockMessageProducer(){
        $dispatchService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $mockMessageProducer = Mockery::mock('Oxzion\Messaging\MessageProducer');
        $dispatchService->setMessageProducer($mockMessageProducer);
        return $mockMessageProducer;
    }

    public function testDispatchIplPolicy()
    {
        $data = array();
        $crypto = new Crypto();
        $config = $this->getApplicationConfig();
        $appId = $this->data['UUID'];
        $data['email'] = 'neha@myvamla.com';
        $data['firstname'] = 'Neha';
        $data['lastname'] = 'Rai';
        $data['policy_document'] = __DIR__."/files/certificate.pdf";
        $data['policy_document'] = $crypto->encryption($data['policy_document']);
        $data['product'] = 'Individual Professional Liability';
        $data['orgUuid'] = '53012471-2863-4949-afb1-e69b0891c98a';
        if(enableCamel == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(Mockery::any(),'mail')->once()->andReturn();
        }
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'DispatchNewPolicy', $data);
        $this->assertEquals($content,array());
    }


    public function testDispatchDbPolicy()
    {
        $data = array();
        $crypto = new Crypto();
        $config = $this->getApplicationConfig();
        $appId = $this->data['UUID'];
        $data['email'] = 'neha@myvamla.com';
        $data['firstname'] = 'Neha';
        $data['lastname'] = 'Rai';
        $data['boat_name'] = 'Orion';
        $data['policy_document'] = __DIR__."/files/certificate.pdf";
        $data['policy_document'] = $crypto->encryption($data['policy_document']);
        $data['product'] = 'Dive Boat';
        $data['orgUuid'] = '53012471-2863-4949-afb1-e69b0891c98a';
        if(enableCamel == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(Mockery::any(),'mail')->once()->andReturn();
        }
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'DispatchNewPolicy', $data);
        $this->assertEquals($content,array());
    }

     public function testDispatchDsPolicy()
    {
        $data = array();
         $crypto = new Crypto();
        $config = $this->getApplicationConfig();
        $appId = $this->data['UUID'];
        $data['email'] = 'neha@myvamla.com';
        $data['firstname'] = 'Neha';
        $data['lastname'] = 'Rai';
        $data['policy_document'] = __DIR__."/files/certificate.pdf";
        $data['policy_document'] = $crypto->encryption($data['policy_document']);
        $data['product'] = 'Dive Store';
        $data['orgUuid'] = '53012471-2863-4949-afb1-e69b0891c98a';
        if(enableCamel == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(Mockery::any(),'mail')->once()->andReturn();
        }
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'DispatchNewPolicy', $data);
        $this->assertEquals($content,array());
    }

    public function testDispatchIplRenewalPolicy()
    {
        $data = array();
        $crypto = new Crypto();
        $config = $this->getApplicationConfig();
        $appId = $this->data['UUID'];
        $data['email'] = 'neha@myvamla.com';
        $data['firstname'] = 'Neha';
        $data['lastname'] = 'Rai';
        $data['store_name'] = 'ABCD';
        $data['store_id'] = '00000';
        $data['expiry_year'] = '2019';
        $data['policy_document'] = __DIR__."/files/certificate.pdf";
        $data['policy_document'] = $crypto->encryption($data['policy_document']);
        $data['product'] = 'Dive Store';
        $data['orgUuid'] = '53012471-2863-4949-afb1-e69b0891c98a';
        if(enableCamel == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(Mockery::any(),'mail')->once()->andReturn();
        }
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'DispatchRenewalPolicy', $data);
        $this->assertEquals($content,array());
    }


    public function testDispatchIplRenewalReminder()
    {
        $data = array();
        $crypto = new Crypto();
        $config = $this->getApplicationConfig();
        $appId = $this->data['UUID'];
        $data['email'] = 'neha@myvamla.com';
        $data['firstname'] = 'Neha';
        $data['lastname'] = 'Rai';
        $data['expiry_year'] = '2019';
        $data['product'] = 'Individual Professional Liability';
        $data['orgUuid'] = '53012471-2863-4949-afb1-e69b0891c98a';
        if(enableCamel == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(Mockery::any(),'mail')->once()->andReturn();
        }
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'DispatchRenewalNotification', $data);
        $this->assertEquals($content,array());
    }


    public function testDispatchIplAutoRenewal()
    {
        $data = array();
        $crypto = new Crypto();
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
        if(enableCamel == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(Mockery::any(),'mail')->once()->andReturn();
        }
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'DispatchAutoRenewalNotification', $data);

        $this->assertEquals($content,array());
    }

    public function testDispatchIplExpiredPolicy()
    {
        $data = array();
        $crypto = new Crypto();
        $config = $this->getApplicationConfig();
        $appId = $this->data['UUID'];
        $data['email'] = 'neha@myvamla.com';
        $data['firstname'] = 'Neha';
        $data['lastname'] = 'Rai';
        $data['expiry_year'] = '2019';
        $data['cost'] = '1000000';
        $data['product'] = 'Individual Professional Liability';
        $data['orgUuid'] = '53012471-2863-4949-afb1-e69b0891c98a';
        if(enableCamel == 0){
            $mockMessageProducer = $this->getMockMessageProducer();
            $mockMessageProducer->expects('sendTopic')->with(Mockery::any(),'mail')->once()->andReturn();
        }
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'DispatchExpiredNotification', $data);
        $this->assertEquals($content,array());
    }

}