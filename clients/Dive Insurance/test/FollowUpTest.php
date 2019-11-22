<?php

use Oxzion\AppDelegate\AppDelegateService;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Test\DelegateTest;
use Oxzion\Transaction\TransactionManager;
use Oxzion\Utils\FileUtils;
use PHPUnit\DbUnit\DataSet\YamlDataSet;

// use Zend\Db\Adapter\Adapter;
// use Mockery as Mockery;

class FollowUpTest extends DelegateTest
{

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
        $this->tempFile = $this->config['TEMPLATE_FOLDER'] . $this->data['orgUuid'];
        $templateLocation = __DIR__ . "/../data/template";

        if (FileUtils::fileExists($this->tempFile)) {
            FileUtils::rmDir($this->tempFile);
        }
        FileUtils::symlink($templateLocation, $this->tempFile);
        parent::setUp();
    }

    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__) . "/Dataset/FileData.yml");
        return $dataset;
    }

    public function tearDown(): void
    {
        parent::tearDown();
        $path = __DIR__ . '/../../../api/v1/data/delegate/' . $this->data['UUID'];
        if (is_link($path)) {
           unlink($path);
        }
        FileUtils::unlink($this->tempFile);
    }

    public function testSendEmailToPolicyHolders()
    {
        $orgId = AuthContext::put(AuthConstants::ORG_ID, 1);
        $data = array("0" => array("firstname" => "Neha",
            "policy_period" => "1year",
            "card_expiry_date" => "10/24",
            "city" => "Bangalore",
            "orgUuid" => "53012471-2863-4949-afb1-e69b0891c98a",
            "isequipmentliability" => "1",
            "card_no" => "1234",
            "state" => "karnataka",
            "app_id" => "ec8942b7-aa93-4bc6-9e8c-e1371988a5d4",
            "zip" => "560030",
            "coverage" => "100000",
            "product" => "Individual Professional Liability",
            "address2" => "dhgdhdh",
            "address1" => "hjfjhfjfjfhfg",
            "expiry_date" => "2020-06-30",
            "form_id" => "0",
            "entity_id" => "1",
            "created_by" => "1",
            "expiry_year" => "2019",
            "lastname" => "Rai",
            "isexcessliability" => "1",
            "workflow_instance_id" => "1",
            "credit_card_type" => "credit",
            "workflowId" => "a01a6776-431a-401e-9288-6acf3b2f3925",
            "email" => "bharat@gmail.com",
            "subject" => "Policy Renewal Remainder",
            "product" => "Individual Professional Liability"),
        );

        $appId = $this->data['UUID'];
        $delegateService = $this->getApplicationServiceLocator()->get(AppDelegateService::class);
        $delegateService->setPersistence($appId, $this->persistence);
        $content = $delegateService->execute($appId, 'FollowUp', $data);
        // print_r($content);exit;
        $this->assertEquals($content, Array(0));
    }
}
