<?php
namespace PaymentGateway;

use Oxzion\Test\ControllerTest;
use PaymentGateway\Controller\PaymentGatewayController;
use PHPUnit\DbUnit\DataSet\YamlDataSet;

class PaymentGatewayControllerTest extends ControllerTest
{
    public function setUp(): void
    {
        $this->loadConfig();
        parent::setUp();
    }

    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__) . "/../Dataset/Payment.yml");
        return $dataset;
    }

    protected function setDefaultAsserts()
    {
        $this->assertModuleName('PaymentGateway');
        $this->assertControllerName(PaymentGatewayController::class); // as specified in router's controller name alias
        $this->assertControllerClass('PaymentGatewayController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }

    public function testCreate()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['payment_client' => 'convergeTest', 'api_url' => "https://api.demo.com/hosted‐payments/transaction_demo", 'server_instance_name' => "Demo", 'payment_config' => "{\"merchant_id\": \"927092398\",\"user_id\": \"u9910idjki109\",\"pincode\": \"8989\" }"];

        $this->setJsonContent(json_encode($data));
        $this->dispatch('/paymentgateway/app/1221-1212-1212', 'POST', null);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        // $this->assertMatchedRouteName('payment');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['payment_client'], $data['payment_client']);
    }

    public function testCreateWithOutClientFailure()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['api_url' => "https://api.demo.com/hosted‐payments/transaction_demo", 'server_instance_name' => "Demo", 'payment_config' => "{\"merchant_id\": \"927092398\",\"user_id\": \"u9910idjki109\",\"pincode\": \"8989\" }"];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/paymentgateway/app/1221-1212-1212', 'POST', null);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('paymentgateway');
        $this->assertResponseStatusCode(404);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
        $this->assertEquals($content['data']['errors']['payment_client'], 'required');
    }

    // public function testCreateAccess()
    // {
    //     $this->initAuthToken($this->employeeUser);
    //     $data = ['payment_client' => 'convergeTest', 'api_url' => "https://api.demo.com/hosted‐payments/transaction_demo", 'server_instance_name' => "Demo", 'payment_config' => "{\"merchant_id\": \"927092398\",\"user_id\": \"u9910idjki109\",\"pincode\": \"8989\" }"];
    //     $this->setJsonContent(json_encode($data));
    //     $this->dispatch('/payment', 'POST', null);
    //     $content = (array) json_decode($this->getResponse()->getContent(), true);
    //     $this->assertModuleName('Payment');
    //     $this->assertControllerName(PaymentController::class); // as specified in router's controller name alias
    //     $this->assertControllerClass('PaymentController');
    //     $this->assertMatchedRouteName('payment');
    //     $this->assertResponseStatusCode(401);
    //     $this->assertResponseHeaderContains('content-type', 'application/json');
    //     $content = (array) json_decode($this->getResponse()->getContent(), true);
    //     $this->assertEquals($content['status'], 'error');
    //     $this->assertEquals($content['message'], 'You have no Access to this API');
    // }

    public function testUpdate()
    {
        $data = ['payment_client' => 'convergeTest', 'api_url' => "https://api.demo.com/hosted‐payments/transaction_demo", 'server_instance_name' => "Demo23", 'payment_config' => "{\"merchant_id\": \"927092398\",\"user_id\": \"u9910idjki109\",\"pincode\": \"8989\" }"];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/paymentgateway/1/app/1221-1212-1212', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('paymentgateway');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], 1);
        $this->assertEquals($content['data']['payment_client'], $data['payment_client']);
    }

    public function testUpdateNotFound()
    {
        $data = ['name' => 'Test Payment', 'status' => 1, 'description' => 'testing'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/paymentgateway/122/app/1212-5657-2323', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('paymentgateway');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    // public function testDelete()
    // {
    //     $this->initAuthToken($this->adminUser);
    //     $this->dispatch('/payment/1', 'DELETE');
    //     $this->assertResponseStatusCode(200);
    //     $this->setDefaultAsserts();
    //     $this->assertMatchedRouteName('payment');
    //     $content = json_decode($this->getResponse()->getContent(), true);
    //     $this->assertEquals($content['status'], 'success');
    // }

    // public function testDeleteNotFound()
    // {
    //     $this->initAuthToken($this->adminUser);
    //     $this->dispatch('/payment/122', 'DELETE');
    //     $this->assertResponseStatusCode(404);
    //     $this->setDefaultAsserts();
    //     $this->assertMatchedRouteName('payment');
    //     $content = json_decode($this->getResponse()->getContent(), true);
    //     $this->assertEquals($content['status'], 'error');
    // }

    public function testinitiatePayment()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/paymentgateway/app/1322-1212-1212/initiate', 'GET');
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('initiatepayment');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['app_id'], '1322-1212-1212');
        $this->assertEquals($content['data'][0]['payment_client'], 'Test1');
    }

    public function testinitiateNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/paymentgateway/app/3453453453/initiate', 'GET');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('initiatepayment');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

}
