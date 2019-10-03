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
        $this->assertModuleName('paymentgateway');
        $this->assertControllerName(PaymentGatewayController::class); // as specified in router's controller name alias
        $this->assertControllerClass('PaymentGatewayController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }

    public function testCreate()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['payment_client' => 'convergeTest', 'api_url' => "https://api.demo.com/hosted‐payments/transaction_demo", 'server_instance_name' => "Demo", 'payment_config' => "{\"merchant_id\": \"927092398\",\"user_id\": \"u9910idjki109\",\"pincode\": \"8989\" }"];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/aff3e23e-e411-11e9-a359-2a2ae2dbcce4/paymentgateway', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('paymentgateway');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['payment_client'], $data['payment_client']);
    }

    public function testCreateWithOutClientFailure()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['api_url' => "https://api.demo.com/hosted‐payments/transaction_demo", 'server_instance_name' => "Demo", 'payment_config' => "{\"merchant_id\": \"927092398\",\"user_id\": \"u9910idjki109\",\"pincode\": \"8989\" }"];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/aff3e23e-e411-11e9-a359-2a2ae2dbcce4/paymentgateway', 'POST', null);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('paymentgateway');
        $this->assertResponseStatusCode(404);
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
        $this->assertEquals($content['data']['errors']['payment_client'], 'required');
    }

    public function testUpdate()
    {
        $data = ['payment_client' => 'convergeTest', 'api_url' => "https://api.demo.com/hosted‐payments/transaction_demo", 'server_instance_name' => "Demo23", 'payment_config' => "{\"merchant_id\": \"927092398\",\"user_id\": \"u9910idjki109\",\"pincode\": \"8989\" }"];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/aff3e23e-e411-11e9-a359-2a2ae2dbcce4/paymentgateway/1', 'PUT', null);
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
        $this->dispatch('/app/aff3e23e-e411-11e9-a359-2a2ae2dbcce4/paymentgateway/122', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('paymentgateway');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testDelete()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/aff3ddd4-e411-11e9-a359-2a2ae2dbcce4/paymentgateway/1', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('paymentgateway');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testDeleteNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/aff3ddd4-e411-11e9-a359-2a2ae2dbcce4/paymentgateway/122', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('paymentgateway');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testgetPaymentDetails()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/aff3ddd4-e411-11e9-a359-2a2ae2dbcce4/paymentgateway', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('paymentgateway');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['app_id'], '99');
        $this->assertEquals($content['data'][0]['payment_client'], 'convergepay');
    }

    public function testgetPaymentDetailsNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/app/aff3ddd4-e411-11e9-a359-2a2ye2dbcik9/paymentgateway', 'GET');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('paymentgateway');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testinitiatePayment()
    {
        $this->initAuthToken($this->adminUser);
        $data = array('firstname' => 'First Name', 'lastname' => 'Last Name', 'amount' => 1);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/aff3ddd4-e411-11e9-a359-2a2ae2dbcce4/paymentgateway/initiate', 'POST',$data);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('initiatepaymentprocess');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

}
