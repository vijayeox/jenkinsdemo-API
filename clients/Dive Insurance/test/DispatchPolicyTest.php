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

    public function testDispatchPolicy()
    {
        $data = array();
        $config = $this->getApplicationConfig();
        $appId = $this->data['UUID'];
        $data['email'] = 'neha@myvamla.com';
        $data['firstname'] = 'Neha';
        $data['policy_document'] = __DIR__."/files/certificate.pdf";
        $data['product'] = 'Individual Professional Liability';
        $data['orgUuid'] = '53012471-2863-4949-afb1-e69b0891c98a';
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'DispatchPolicy', $data);
        $this->assertEquals($content,array());
    }

}