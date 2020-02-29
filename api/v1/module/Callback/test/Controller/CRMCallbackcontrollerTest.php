<?php
namespace Callback;

use Callback\Controller\CRMCallbackController;
use Oxzion\Test\ControllerTest;
use PHPUnit\DbUnit\DataSet\DefaultDataSet;

class CRMCallbackControllerTest extends ControllerTest
{
    public function setUp(): void
    {
        $this->loadConfig();
        parent::setUp();
    }

    public function getDataSet()
    {
        return new DefaultDataSet();
    }

    public function testCreate()
    {
        $data = ['user_id' => '1', 'firstName' => "Bharat", 'lastName' => 'Goki', 'phones' => array('9739591462'), 'email' => 'bharat@va.com', 'accounts' => array('name' => 'VA'), 'addresses' => array(array('name' => 'Indiranagar')), 'owner_id' => 1, 'organization' => array('id' => 1), 'assignedTo' => array('username' => $this->adminUser), 'owner' => array('username' => $this->adminUser)];
        $this->dispatch('/callback/crm/addcontact', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('crmaddcontactcallback');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['user_id'], $data['user_id']);
        $this->assertEquals($content['data']['first_name'], $data['firstName']);
        $this->assertEquals($content['data']['last_name'], $data['lastName']);
        $this->assertEquals($content['data']['phone_1'], $data['phones'][0]);
        $this->assertEquals($content['data']['email'], $data['email']);
        $this->assertEquals($content['data']['address1'], $data['addresses'][0]['name']);
        $this->assertEquals($content['data']['owner_id'], 1);
    }

    public function testCreateFailure()
    {
        $data = ['lastName' => 'Goki', 'phones' => array('9739591462'), 'email' => 'bharat@va.com', 'accounts' => array('name' => 'VA'), 'addresses' => array(array('name' => 'Indiranagar')), 'owner_id' => 1, 'organization' => array('id' => 1), 'assignedTo' => array('username' => $this->adminUser), 'owner' => array('username' => $this->adminUser)];
        $this->dispatch('/callback/crm/addcontact', 'POST', array(json_encode($data) => ''));
        $this->assertResponseStatusCode(500);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('crmaddcontactcallback');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Could not add contact to the CRM');
    }

    protected function setDefaultAsserts()
    {
        $this->assertModuleName('Callback');
        $this->assertControllerName(CRMCallbackController::class); // as specified in router's controller name alias
        $this->assertControllerClass('CRMCallbackController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }
}
