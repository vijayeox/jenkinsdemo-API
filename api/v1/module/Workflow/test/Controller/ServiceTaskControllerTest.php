<?php
namespace Workflow;

use Exception;
use Mockery;
use Oxzion\Test\ControllerTest;
use Oxzion\Utils\FileUtils;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Workflow\Service\ServiceTaskService;

class ServiceTaskControllerTest extends ControllerTest
{
    public function setUp(): void
    {
        $this->loadConfig();
        parent::setUp();
    }

    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__) . "/../Dataset/ActivityInstance.yml");
        $dataset->addYamlFile(dirname(__FILE__) . "/../Dataset/Workflow.yml");
        return $dataset;
    }

    public function getMockMessageProducer()
    {
        $serviceTaskService = $this->getApplicationServiceLocator()->get(ServiceTaskService::class);
        $mockMessageProducer = Mockery::mock('Oxzion\Messaging\MessageProducer');
        $serviceTaskService->setMessageProducer($mockMessageProducer);
        return $mockMessageProducer;
    }

    private function getMockRestClientForScheduleService()
    {
        $taskService = $this->getApplicationServiceLocator()->get(Service\ServiceTaskService::class);
        $mockRestClient = Mockery::mock('Oxzion\Utils\RestClient');
        $taskService->setRestClient($mockRestClient);
        return $mockRestClient;
    }

    public function testServiceTaskMailExecution()
    {
        $this->initAuthToken($this->adminUser);
        $data['variables'] = ['command' => 'mail', 'to' => 'bharatgtest@myvamla.com', 'body' => 'create a new body', 'subject' => 'NewSubject'];
        $this->setJsonContent(json_encode($data));
        if (enableActiveMQ == 0) {
            $mockMessageProducer = $this->getMockMessageProducer();
            $payload = json_encode(array('to' => $data['variables']['to'], 'subject' => $data['variables']['subject'], 'body' => $data['variables']['body'], 'attachments' => null));
            $mockMessageProducer->expects('sendQueue')->with($payload, 'mail')->once()->andReturn(123);
        }
        $this->dispatch('/callback/workflow/servicetask', 'POST', $data);
        $this->assertResponseStatusCode(200);
    }

    public function testServiceTaskWithoutSubjectExecution()
    {
        $this->initAuthToken($this->adminUser);
        $data['variables'] = ['command' => 'mail', 'to' => 'bharatgtest@myvamla.com', 'body' => 'create a new body'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/callback/workflow/servicetask', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(406);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
        $this->assertEquals($content['data']['errors']['subject'], 'required');
    }
    
    public function testServiceTaskWithoutRecepientMailExecution()
    {
        $this->initAuthToken($this->adminUser);
        $data['variables'] = ['command' => 'mail', 'body' => 'create a new body', 'subject' => 'NewSubject'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/callback/workflow/servicetask', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(406);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
        $this->assertEquals($content['data']['errors']['to'], 'required');
    }

    public function testServiceTaskPDFExecution()
    {
        $data = ['uuid' => '53012471-2863-4949-afb1-e69b0891c98a'];
        $config = $this->getApplicationConfig();
        $tempFolder = $config['TEMPLATE_FOLDER'] . "53012471-2863-4949-afb1-e69b0891c98a/";
        FileUtils::createDirectory($tempFolder);
        $tempFile = $config['TEMPLATE_FOLDER'] . "/";
        FileUtils::createDirectory($tempFile);
        copy(__DIR__ . "/../Dataset/GenericTemplate.tpl", $tempFile . "GenericTemplate.tpl");
        $params['variables'] = ['command' => 'pdf', 'template' => 'GenericTemplate', 'orgid' => 1, 'options' => array('initial_title' => 'Vantage agora Pdf Template', 'second_title' => 'Title 2', 'pdf_header_logo' => '/logo_example.jpg', 'pdf_header_logo_width' => 20, 'header_text_color' => array(139, 58, 58), 'header_line_color' => array(255, 48, 48), 'footer_text_color' => array(123, 121, 34), 'footer_line_color' => array(56, 142, 142)), 'destination' => $config['TEMPLATE_FOLDER'] . "GenericTemplate.pdf"];
        $this->setJsonContent(json_encode($params));
        $this->dispatch('/callback/workflow/servicetask', 'POST', $params);
        $this->assertResponseStatusCode(200);
        $templateName = "GenericTemplate.tpl";
        FileUtils::deleteFile($templateName, $tempFile);
        FileUtils::deleteFile("GenericTemplate.pdf", $config['TEMPLATE_FOLDER']);
    }

    public function testServiceTaskPDFInvalidTemplateExecution()
    {
        $config = $this->getApplicationConfig();
        $params['variables'] = ['command' => 'pdf', 'template' => 'GenericTemplate', 'orgid' => 1, 'options' => array('initial_title' => 'Vantage agora Pdf Template', 'second_title' => 'Title 2', 'pdf_header_logo' => '/logo_example.jpg', 'pdf_header_logo_width' => 20, 'header_text_color' => array(139, 58, 58), 'header_line_color' => array(255, 48, 48), 'footer_text_color' => array(123, 121, 34), 'footer_line_color' => array(56, 142, 142)), 'destination' => $config['TEMPLATE_FOLDER'] . "GenericTemplate.pdf"];
        $this->setJsonContent(json_encode($params));
        $this->dispatch('/callback/workflow/servicetask', 'POST', $params);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(500);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Template not found!');
    }

    public function testServiceTaskPDFInvalidDestinationExecution()
    {
        $config = $this->getApplicationConfig();
        $params['variables'] = ['command' => 'pdf', 'template' => 'GenericTemplate', 'orgid' => 1, 'options' => array('initial_title' => 'Vantage agora Pdf Template', 'second_title' => 'Title 2', 'pdf_header_logo' => '/logo_example.jpg', 'pdf_header_logo_width' => 20, 'header_text_color' => array(139, 58, 58), 'header_line_color' => array(255, 48, 48), 'footer_text_color' => array(123, 121, 34), 'footer_line_color' => array(56, 142, 142)), 'destination' => $config['TEMPLATE_FOLDER'] . "GenericTemplate.pdf"];
        $this->setJsonContent(json_encode($params));
        $this->dispatch('/callback/workflow/servicetask', 'POST', $params);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(500);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Template not found!');
    }

    public function testServiceTaskSchedule()
    {
        $this->initAuthToken($this->adminUser);
        $data = ["activityInstanceId" => "Task_1bw1uyk:651f1320-ef09-11e9-a364-62be4f9e1bfd", "processInstanceId" => "651eebfb-ef09-11e9-a364-62be4f9e1bfd", "variables" => array("firstname" => "Neha", "policy_period" => "1year", "card_expiry_date" => "10/24", "city" => "Bangalore", "orgUuid" => "53012471-2863-4949-afb1-e69b0891c98a", "isequipmentliability" => "1", "card_no" => "1234", "jobUrl" => "/app/ec8942b7-aa93-4bc6-9e8c-e1371988a5d4/delegate/DispatchAutoRenewalNotification", "state" => "karnataka", "app_id" => "ec8942b7-aa93-4bc6-9e8c-e1371988a5d4", "cron" => "0 0/1 * * * ? *", "zip" => "560030", "coverage" => "100000", "product" => "Individual Professional Liability", "address2" => "dhgdhdh", "address1" => "hjfjhfjfjfhfg", "expiry_date" => "2020-06-30", "form_id" => "0", "entity_id" => "1", "created_by" => "1", "url" => "setupjob", "command" => "schedule", "jobName" => "autoRenewalJob", "expiry_year" => "2019", "orgid" => "53012471-2863-4949-afb1-e69b0891c98a", "lastname" => "Rai", "isexcessliability" => "1", "workflow_instance_id" => "142", "credit_card_type" => "credit", "workflowId" => "a01a6776-431a-401e-9288-6acf3b2f3925", "fileId" => "134", "email" => 'bharat@gmail.com'), "parentInstanceId" => "651eebfb-ef09-11e9-a364-62be4f9e1bfd", "parentActivity" => "651eebfb-ef09-11e9-a364-62be4f9e1bfd"];

        $this->setJsonContent(json_encode($data));
        if (enableCamel == 0) {
            $mockRestClient = $this->getMockRestClientForScheduleService();
            $mockRestClient->expects('postWithHeader')->with("setupjob", Mockery::any())->once()->andReturn();
        }
        $this->dispatch('/callback/workflow/servicetask', 'POST', $data);
        $this->assertResponseStatusCode(200);
    }

    public function testServiceTaskScheduleWithoutRequiredFields()
    {
        $this->initAuthToken($this->adminUser);
        $data = ["activityInstanceId" => "Task_1bw1uyk:651f1320-ef09-11e9-a364-62be4f9e1bfd", "processInstanceId" => "651eebfb-ef09-11e9-a364-62be4f9e1bfd", "variables" => array("firstname" => "Neha", "policy_period" => "1year", "card_expiry_date" => "10/24", "city" => "Bangalore", "orgUuid" => "53012471-2863-4949-afb1-e69b0891c98a", "isequipmentliability" => "1", "card_no" => "1234", "jobUrl" => "/app/ec8942b7-aa93-4bc6-9e8c-e1371988a5d4/delegate/DispatchAutoRenewalNotification", "state" => "karnataka", "app_id" => "ec8942b7-aa93-4bc6-9e8c-e1371988a5d4", "cron" => "0 0/1 * * * ? *", "zip" => "560030", "coverage" => "100000", "product" => "Individual Professional Liability", "address2" => "dhgdhdh", "address1" => "hjfjhfjfjfhfg", "expiry_date" => "2020-06-30", "form_id" => "0", "entity_id" => "1", "created_by" => "1", "url" => "setupjob", "command" => "schedule", "expiry_year" => "2019", "orgid" => "53012471-2863-4949-afb1-e69b0891c98a", "lastname" => "Rai", "isexcessliability" => "1", "workflow_instance_id" => "142", "credit_card_type" => "credit", "workflowId" => "a01a6776-431a-401e-9288-6acf3b2f3925", "fileId" => "134", "email" => 'bharat@gmail.com'), "parentInstanceId" => "651eebfb-ef09-11e9-a364-62be4f9e1bfd", "parentActivity" => "651eebfb-ef09-11e9-a364-62be4f9e1bfd"];

        $this->setJsonContent(json_encode($data));
        if (enableCamel == 0) {
            $mockRestClient = $this->getMockRestClientForScheduleService();
            $mockRestClient->expects('postWithHeader')->with("setupjob", Mockery::any())->once()->andReturn();
        }
        $this->dispatch('/callback/workflow/servicetask', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(404);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'JobUrl or Cron Expression or URL or JobName Not Specified');
    }

    public function testServiceTaskCancelJob()
    {
        $this->initAuthToken($this->adminUser);
        $data = ["activityInstanceId" => "Task_1bw1uyk:651f1320-ef09-11e9-a364-62be4f9e1bfd", "processInstanceId" => "651eebfb-ef09-11e9-a364-62be4f9e1bfd", "variables" => array("firstname" => "Neha", "policy_period" => "1year", "card_expiry_date" => "10/24", "city" => "Bangalore", "orgUuid" => "53012471-2863-4949-afb1-e69b0891c98a", "isequipmentliability" => "1", "card_no" => "1234", "state" => "karnataka", "app_id" => "ec8942b7-aa93-4bc6-9e8c-e1371988a5d4", "zip" => "560030", "coverage" => "100000", "product" => "Individual Professional Liability", "address2" => "dhgdhdh", "address1" => "hjfjhfjfjfhfg", "expiry_date" => "2020-06-30", "form_id" => "0", "entity_id" => "1", "created_by" => "1", "url" => "canceljob", "command" => "cancelJob", "jobName" => "autoRenewalJob", "autoRenewalJob" => '{"jobId":"14b5370e-a580-4b80-a17a-a13be8b47ee0","jobGroup":"Job"}', "expiry_year" => "2019", "orgid" => "53012471-2863-4949-afb1-e69b0891c98a", "lastname" => "Rai", "isexcessliability" => "1", "workflow_instance_id" => "142", "credit_card_type" => "credit", "workflowId" => "a01a6776-431a-401e-9288-6acf3b2f3925", "fileId" => "134", "email" => 'bharat@gmail.com'), "parentInstanceId" => "651eebfb-ef09-11e9-a364-62be4f9e1bfd", "parentActivity" => "651eebfb-ef09-11e9-a364-62be4f9e1bfd"];

        $this->setJsonContent(json_encode($data));
        if (enableCamel == 0) {
            $mockRestClient = $this->getMockRestClientForScheduleService();
            $mockRestClient->expects('postWithHeader')->with("canceljob", Mockery::any())->once()->andReturn();
        }
        $this->dispatch('/callback/workflow/servicetask', 'POST', $data);
        $this->assertResponseStatusCode(200);
    }

    public function testServiceTaskCancelJobWithoutJobName()
    {
        $this->initAuthToken($this->adminUser);
        $data = ["activityInstanceId" => "Task_1bw1uyk:651f1320-ef09-11e9-a364-62be4f9e1bfd", "processInstanceId" => "651eebfb-ef09-11e9-a364-62be4f9e1bfd", "variables" => array("firstname" => "Neha", "policy_period" => "1year", "card_expiry_date" => "10/24", "city" => "Bangalore", "orgUuid" => "53012471-2863-4949-afb1-e69b0891c98a", "isequipmentliability" => "1", "card_no" => "1234", "state" => "karnataka", "app_id" => "ec8942b7-aa93-4bc6-9e8c-e1371988a5d4", "zip" => "560030", "coverage" => "100000", "product" => "Individual Professional Liability", "address2" => "dhgdhdh", "address1" => "hjfjhfjfjfhfg", "expiry_date" => "2020-06-30", "form_id" => "0", "entity_id" => "1", "created_by" => "1", "url" => "canceljob", "command" => "cancelJob", "autoRenewalJob" => '{"jobId":"14b5370e-a580-4b80-a17a-a13be8b47ee0","jobGroup":"Job"}', "expiry_year" => "2019", "orgid" => "53012471-2863-4949-afb1-e69b0891c98a", "lastname" => "Rai", "isexcessliability" => "1", "workflow_instance_id" => "142", "credit_card_type" => "credit", "workflowId" => "a01a6776-431a-401e-9288-6acf3b2f3925", "fileId" => "134", "email" => 'bharat@gmail.com'), "parentInstanceId" => "651eebfb-ef09-11e9-a364-62be4f9e1bfd", "parentActivity" => "651eebfb-ef09-11e9-a364-62be4f9e1bfd"];

        $this->setJsonContent(json_encode($data));
        $this->dispatch('/callback/workflow/servicetask', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(404);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Job Name Not Specified');
    }

    public function testServiceTaskCancelJobWithoutJobID()
    {
        $this->initAuthToken($this->adminUser);
        $data = ["activityInstanceId" => "Task_1bw1uyk:651f1320-ef09-11e9-a364-62be4f9e1bfd", "processInstanceId" => "651eebfb-ef09-11e9-a364-62be4f9e1bfd", "variables" => array("firstname" => "Neha", "policy_period" => "1year", "card_expiry_date" => "10/24", "city" => "Bangalore", "orgUuid" => "53012471-2863-4949-afb1-e69b0891c98a", "isequipmentliability" => "1", "card_no" => "1234", "state" => "karnataka", "app_id" => "ec8942b7-aa93-4bc6-9e8c-e1371988a5d4", "zip" => "560030", "coverage" => "100000", "product" => "Individual Professional Liability", "address2" => "dhgdhdh", "address1" => "hjfjhfjfjfhfg", "expiry_date" => "2020-06-30", "form_id" => "0", "entity_id" => "1", "created_by" => "1", "url" => "canceljob", "command" => "cancelJob", "jobName" => "autoRenewalJob", "autoRenewalJob" => '{"jobID":"14b5370e-a580-4b80-a17a-a13be8b47ee0","jobgroup":"Job"}', "expiry_year" => "2019", "orgid" => "53012471-2863-4949-afb1-e69b0891c98a", "lastname" => "Rai", "isexcessliability" => "1", "workflow_instance_id" => "142", "credit_card_type" => "credit", "workflowId" => "a01a6776-431a-401e-9288-6acf3b2f3925", "fileId" => "134", "email" => 'bharat@gmail.com'), "parentInstanceId" => "651eebfb-ef09-11e9-a364-62be4f9e1bfd", "parentActivity" => "651eebfb-ef09-11e9-a364-62be4f9e1bfd"];

        $this->setJsonContent(json_encode($data));
        $this->dispatch('/callback/workflow/servicetask', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Job Id or Job Group Not Specified');
    }

    public function testServiceTaskCancelJobEmpty()
    {
        $this->initAuthToken($this->adminUser);
        $data = ["activityInstanceId" => "Task_1bw1uyk:651f1320-ef09-11e9-a364-62be4f9e1bfd", "processInstanceId" => "651eebfb-ef09-11e9-a364-62be4f9e1bfd", "variables" => array("firstname" => "Neha", "policy_period" => "1year", "card_expiry_date" => "10/24", "city" => "Bangalore", "orgUuid" => "53012471-2863-4949-afb1-e69b0891c98a", "isequipmentliability" => "1", "card_no" => "1234", "state" => "karnataka", "app_id" => "ec8942b7-aa93-4bc6-9e8c-e1371988a5d4", "zip" => "560030", "coverage" => "100000", "product" => "Individual Professional Liability", "address2" => "dhgdhdh", "address1" => "hjfjhfjfjfhfg", "expiry_date" => "2020-06-30", "form_id" => "0", "entity_id" => "1", "created_by" => "1", "url" => "canceljob", "command" => "cancelJob", "jobName" => "autoRenewalJob", "expiry_year" => "2019", "orgid" => "53012471-2863-4949-afb1-e69b0891c98a", "lastname" => "Rai", "isexcessliability" => "1", "workflow_instance_id" => "142", "credit_card_type" => "credit", "workflowId" => "a01a6776-431a-401e-9288-6acf3b2f3925", "fileId" => "134", "email" => 'bharat@gmail.com'), "parentInstanceId" => "651eebfb-ef09-11e9-a364-62be4f9e1bfd", "parentActivity" => "651eebfb-ef09-11e9-a364-62be4f9e1bfd"];

        $this->setJsonContent(json_encode($data));
        $this->dispatch('/callback/workflow/servicetask', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Job autoRenewalJob Not Specified');
    }

    public function testServiceTaskCancelInvalidJob()
    {
        $this->initAuthToken($this->adminUser);
        $data = ["activityInstanceId" => "Task_1bw1uyk:651f1320-ef09-11e9-a364-62be4f9e1bfd", "processInstanceId" => "651eebfb-ef09-11e9-a364-62be4f9e1bfd", "variables" => array("firstname" => "Neha", "policy_period" => "1year", "card_expiry_date" => "10/24", "city" => "Bangalore", "orgUuid" => "53012471-2863-4949-afb1-e69b0891c98a", "isequipmentliability" => "1", "card_no" => "1234", "state" => "karnataka", "app_id" => "ec8942b7-aa93-4bc6-9e8c-e1371988a5d4", "zip" => "560030", "coverage" => "100000", "product" => "Individual Professional Liability", "address2" => "dhgdhdh", "address1" => "hjfjhfjfjfhfg", "expiry_date" => "2020-06-30", "form_id" => "0", "entity_id" => "1", "created_by" => "1", "url" => "canceljob", "command" => "cancelJob", "jobName" => "autoRenewalJob", "autoRenewalJob" => '{"jobId":"f4f7833e-7e34-4b00-bcab-ef6048e7fbcb","jobGroup":"Job"}', "expiry_year" => "2019", "orgid" => "53012471-2863-4949-afb1-e69b0891c98a", "lastname" => "Rai", "isexcessliability" => "1", "workflow_instance_id" => "142", "credit_card_type" => "credit", "workflowId" => "a01a6776-431a-401e-9288-6acf3b2f3925", "fileId" => "134", "email" => 'bharat@gmail.com'), "parentInstanceId" => "651eebfb-ef09-11e9-a364-62be4f9e1bfd", "parentActivity" => "651eebfb-ef09-11e9-a364-62be4f9e1bfd"];

        $this->setJsonContent(json_encode($data));
        if (enableCamel == 0) {
            $mockRestClient = $this->getMockRestClientForScheduleService();
            $exception = Mockery::Mock('\GuzzleHttp\Exception\ClientException');
            $request = Mockery::Mock('\Psr\Http\Message\RequestInterface');
            $exception = new \GuzzleHttp\Exception\ClientException('Client error: `POST http://172.16.1.95:8085/canceljob` resulted in a `404 Not Found` response:{"timestamp":"2019-11-05T13:06:56.308+0000","status":404,"error":"Not Found","message":"Job Does not Exists","path":"/ca (truncated...)', $request);
            $mockRestClient->expects('postWithHeader')->with("canceljob", array('jobid' => 'f4f7833e-7e34-4b00-bcab-ef6048e7fbcb', 'jobgroup' => 'Job'))->once()->andThrow($exception);
        }
        $this->dispatch('/callback/workflow/servicetask', 'POST', $data);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(404);
    }

    public function testFileSave()
    {
        $this->initAuthToken($this->adminUser);
        $data = ["activityInstanceId" => "Task_1bw1uyk:651f1320-ef09-11e9-a364-62be4f9e1bfd", "processInstanceId" => "651eebfb-ef09-11e9-a364-62be4f9e1bfd", "variables" => array("firstname" => "Neha", "policy_period" => "1year", "card_expiry_date" => "10/24", "city" => "Bangalore", "orgUuid" => "53012471-2863-4949-afb1-e69b0891c98a", "isequipmentliability" => "1", "card_no" => "1234", "state" => "karnataka", "app_id" => "ec8942b7-aa93-4bc6-9e8c-e1371988a5d4", "zip" => "560030", "coverage" => "100000", "product" => "Individual Professional Liability", "address2" => "dhgdhdh", "address1" => "hjfjhfjfjfhfg", "expiry_date" => "2020-06-30", "form_id" => "0", "entity_id" => "1", "created_by" => "1", "command" => "fileSave", "expiry_year" => "2019", "orgid" => "53012471-2863-4949-afb1-e69b0891c98a", "lastname" => "Rai", "isexcessliability" => "1", "workflow_instance_id" => "1", "credit_card_type" => "credit", "workflowId" => "a01a6776-431a-401e-9288-6acf3b2f3925", "fileId" => "1", "email" => 'bharat@gmail.com'), "parentInstanceId" => "651eebfb-ef09-11e9-a364-62be4f9e1bfd", "parentActivity" => "651eebfb-ef09-11e9-a364-62be4f9e1bfd"];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/callback/workflow/servicetask', 'POST', $data);
        $query = "Select data from ox_file where uuid = 'd13d0c68-98c9-11e9-adc5-308d99c9145b'";
        $result = $this->executeQueryTest($query);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->assertEquals($content['status'], 'success');
        unset($data['variables']['command'], $data['variables']['orgid'], $data['variables']['fileId'], $data['variables']['app_id'], $data['variables']['form_id'], $data['variables']['created_by'], $data['variables']['entity_id'], $data['variables']['workflow_instance_id'], $data['variables']['workflowId']);
        $this->assertEquals($data['variables'], json_decode($result[0]['data'], true));
    }

    public function testFileSaveInvalidWokflowInstanceID()
    {
        $this->initAuthToken($this->adminUser);
        $data = ["activityInstanceId" => "Task_1bw1uyk:651f1320-ef09-11e9-a364-62be4f9e1bfd", "processInstanceId" => "651eebfb-ef09-11e9-a364-62be4f9e1bfd", "variables" => array("firstname" => "Neha", "policy_period" => "1year", "card_expiry_date" => "10/24", "city" => "Bangalore", "orgUuid" => "53012471-2863-4949-afb1-e69b0891c98a", "isequipmentliability" => "1", "card_no" => "1234", "state" => "karnataka", "app_id" => "ec8942b7-aa93-4bc6-9e8c-e1371988a5d4", "zip" => "560030", "coverage" => "100000", "product" => "Individual Professional Liability", "address2" => "dhgdhdh", "address1" => "hjfjhfjfjfhfg", "expiry_date" => "2020-06-30", "form_id" => "0", "entity_id" => "1", "created_by" => "1", "command" => "fileSave", "expiry_year" => "2019", "orgid" => "53012471-2863-4949-afb1-e69b0891c98a", "lastname" => "Rai", "isexcessliability" => "1", "workflow_instance_id" => "5", "credit_card_type" => "credit", "workflowId" => "a01a6776-431a-401e-9288-6acf3b2f3925", "fileId" => "1", "email" => 'bharat@gmail.com'), "parentInstanceId" => "651eebfb-ef09-11e9-a364-62be4f9e1bfd", "parentActivity" => "651eebfb-ef09-11e9-a364-62be4f9e1bfd"];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/callback/workflow/servicetask', 'POST', $data);
        $query = "Select data from ox_file where uuid = 'd13d0c68-98c9-11e9-adc5-308d99c9145b'";
        $result = $this->executeQueryTest($query);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(404);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Workflow Instance Id Not Found');
    }
}
