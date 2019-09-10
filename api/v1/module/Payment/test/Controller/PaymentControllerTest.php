<?php
namespace Payment;

use Oxzion\Test\ControllerTest;
use Payment\Controller\PaymentController;
use PHPUnit\DbUnit\DataSet\YamlDataSet;

class PaymentControllerTest extends ControllerTest
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
        $this->assertModuleName('Payment');
        $this->assertControllerName(PaymentController::class); // as specified in router's controller name alias
        $this->assertControllerClass('PaymentController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }

    public function testCreate()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['payment_client' => 'convergeTest', 'api_url' => "https://api.demo.com/hosted‐payments/transaction_demo", 'server_instance_name' => "Demo", 'payment_config' => "{\"merchant_id\": \"927092398\",\"user_id\": \"u9910idjki109\",\"pincode\": \"8989\" }"];
        
        $this->assertEquals(2, $this->getConnection()->getRowCount('ox_payment'));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/payment/app/1', 'POST', null);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        // $this->assertMatchedRouteName('payment');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['payment_client'], $data['payment_client']);
        // $this->assertEquals(3, $this->getConnection()->getRowCount('ox_payment'));
    }

    public function testCreateWithOutClientFailure()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['api_url' => "https://api.demo.com/hosted‐payments/transaction_demo", 'server_instance_name' => "Demo", 'payment_config' => "{\"merchant_id\": \"927092398\",\"user_id\": \"u9910idjki109\",\"pincode\": \"8989\" }"];
        $this->assertEquals(2, $this->getConnection()->getRowCount('ox_payment'));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/payment/app/1', 'POST', null);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('payment');
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
        $this->dispatch('/payment/1/app/1', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('payment');
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
        $this->dispatch('/payment/122/app/1', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('payment');
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

}
