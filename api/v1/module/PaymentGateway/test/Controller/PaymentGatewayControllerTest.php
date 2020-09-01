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
        $data = ['payment_client' => 'convergeTest', 'api_url' => "https://api.demo.com/hosted‐payments/transaction_demo", 'server_instance_name' => "Demo", 'org_id' => $this->testOrgId, 'payment_config' => "{\"merchant_id\": \"927092398\",\"user_id\": \"u9910idjki109\",\"pincode\": \"8989\" }"];
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
        $this->assertEquals($content['data'][0]['payment_client'], 'ConvergePay');
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
        $this->dispatch('/app/aff3ddd4-e411-11e9-a359-2a2ae2dbcce4/paymentgateway/initiate', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('initiatepaymentprocess');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }
    public function testinitiatePaymentWithIncorrectGateWay()
    {
        $this->initAuthToken($this->adminUser);
        $data = array('firstname' => 'First Name', 'lastname' => 'Last Name', 'amount' => 1);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/app/aff3e23e-e411-11e9-a359-2a2ae2dbcce4/paymentgateway/initiate', 'POST', $data);
        $this->assertResponseStatusCode(400);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('initiatepaymentprocess');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }
    public function testUpdatePayment()
    {
        $this->initAuthToken($this->adminUser);
        $data = '{"ssl_merchant_initiated_unscheduled":"N","ssl_issuer_response":"00","ssl_partner_app_id":"VM","ssl_card_number":"41**********9990","ssl_oar_data":"010012082410191314590000047554200000000000356142929213120824","ssl_transaction_type":"SALE","ssl_result":"0","ssl_txn_id":"191019A42-A7FD4C66-99BB-4B1B-96AA-4E29A7677EAD","ssl_avs_response":" ","ssl_approval_code":"356142","ssl_amount":"404.00","ssl_txn_time":"10/19/2019 09:14:59 AM","ssl_account_balance":"0.00","ssl_ps2000_data":"A0191019091459485522VE","ssl_exp_date":"1119","ssl_result_message":"APPROVAL","ssl_card_short_description":"VISA","ssl_get_token":"Y","ssl_token_response":"SUCCESS","ssl_card_type":"CREDITCARD","ssl_cvv2_response":"M","ssl_token":"4421912014039990","ssl_add_token_response":"Card Updated"}';
        $this->setJsonContent($data);
        $this->dispatch('/app/aff3ddd4-e411-11e9-a359-2a2ae2dbcce4/transaction/1/status', 'POST', json_decode($data, true));
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('updatetransactionstatus');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }
    public function testUpdatePaymentNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $data = '{"ssl_merchant_initiated_unscheduled":"N","ssl_issuer_response":"00","ssl_partner_app_id":"VM","ssl_card_number":"41**********9990","ssl_oar_data":"010012082410191314590000047554200000000000356142929213120824","ssl_transaction_type":"SALE","ssl_result":"0","ssl_txn_id":"191019A42-A7FD4C66-99BB-4B1B-96AA-4E29A7677EAD","ssl_avs_response":" ","ssl_approval_code":"356142","ssl_amount":"404.00","ssl_txn_time":"10/19/2019 09:14:59 AM","ssl_account_balance":"0.00","ssl_ps2000_data":"A0191019091459485522VE","ssl_exp_date":"1119","ssl_result_message":"APPROVAL","ssl_card_short_description":"VISA","ssl_get_token":"Y","ssl_token_response":"SUCCESS","ssl_card_type":"CREDITCARD","ssl_cvv2_response":"M","ssl_token":"4421912014039990","ssl_add_token_response":"Card Updated"}';
        $this->setJsonContent($data);
        $this->dispatch('/app/aff3ddd4-e411-11e9-a359-2a2ae2dbcce4/transaction/2/status', 'POST', json_decode($data, true));
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('updatetransactionstatus');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }
}
