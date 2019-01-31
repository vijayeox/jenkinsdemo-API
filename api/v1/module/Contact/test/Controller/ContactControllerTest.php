<?php
namespace Contact;

use Contact\Controller\ContactController;
use Contact\Model;
use Oxzion\Test\ControllerTest;
use Bos\Db\ModelTable;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use PHPUnit\Framework\TestResult;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;


class ContactControllerTest extends ControllerTest
{

    public function setUp() : void
    {
        $this->loadConfig();
        parent::setUp();
    }

    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__) . "/../Dataset/Contact.yml");
        return $dataset;
    }


    protected function setDefaultAsserts()
    {
        $this->assertModuleName('Contact');
        $this->assertControllerName(ContactController::class); // as specified in router's controller name alias
        $this->assertControllerClass('ContactController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }

//Testing to see if the Create Contact function is working as intended if all the value passed are correct.
    public function testCreate()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['user_id' => '3', 'first_name' => "Raks", 'last_name' => 'Iddya', 'phone_1' => '9810029938', 'phone_2' => '9092992992', 'email' => 'raks@va.com', 'company_name' => 'VA', 'address_1' => 'Malleshwaram', 'address_2' => 'Bangalore', 'country' => 'India', 'owner_id' => 3, 'org_id' => '1'];
        $this->assertEquals(2, $this->getConnection()->getRowCount('ox_contact'));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/contact', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('contacts');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['user_id'], $data['user_id']);
        $this->assertEquals($content['data']['first_name'], $data['first_name']);
        $this->assertEquals($content['data']['last_name'], $data['last_name']);
        $this->assertEquals($content['data']['phone_1'], $data['phone_1']);
        $this->assertEquals($content['data']['phone_2'], $data['phone_2']);
        $this->assertEquals($content['data']['email'], $data['email']);
        $this->assertEquals($content['data']['company_name'], $data['company_name']);
        $this->assertEquals($content['data']['address_1'], $data['address_1']);
        $this->assertEquals($content['data']['address_2'], $data['address_2']);
        $this->assertEquals($content['data']['country'], $data['country']);
        $this->assertEquals($content['data']['owner_id'], $data['owner_id']);
        $this->assertEquals(3, $this->getConnection()->getRowCount('ox_contact'));
    }

//Test Case to check the errors when the required field is not selected. Here I removed the parent_id field from the list.

    public function testCreateWithoutRequiredField()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['last_name' => 'Iddya', 'phone_1' => '9810029938', 'phone_2' => '9092992992', 'email' => 'raks@va.com', 'company_name' => 'VA', 'address_1' => 'Malleshwaram', 'address_2' => 'Bangalore', 'country' => 'India', 'owner_id' => 3];
        $this->assertEquals(2, $this->getConnection()->getRowCount('ox_contact'));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/contact', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('contacts');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
        $this->assertEquals($content['data']['errors']['first_name'], 'required');
    }

    public function testUpdate()
    {
        $data = ['user_id' => '3', 'first_name' => "Rakshith", 'last_name' => 'Iddya', 'phone_1' => '9810029938', 'phone_2' => '9092992992', 'email' => 'raks@va.com', 'company_name' => 'VA', 'address_1' => 'Malleshwaram', 'address_2' => 'Bangalore', 'country' => 'US', 'owner_id' => 3];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/contact/1', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('contacts');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['user_id'], $data['user_id']);
        $this->assertEquals($content['data']['first_name'], $data['first_name']);
        $this->assertEquals($content['data']['last_name'], $data['last_name']);
        $this->assertEquals($content['data']['phone_1'], $data['phone_1']);
        $this->assertEquals($content['data']['phone_2'], $data['phone_2']);
        $this->assertEquals($content['data']['email'], $data['email']);
        $this->assertEquals($content['data']['company_name'], $data['company_name']);
        $this->assertEquals($content['data']['address_1'], $data['address_1']);
        $this->assertEquals($content['data']['address_2'], $data['address_2']);
        $this->assertEquals($content['data']['country'], $data['country']);
        $this->assertEquals($content['data']['owner_id'], $data['owner_id']);
    }

    public function testUpdateNotFound()
    {
        $data = ['first_name' => "Rakshith", 'last_name' => 'Iddya', 'phone_1' => '9810029938', 'phone_2' => '9092992992', 'email' => 'raks@va.com', 'company_name' => 'VA', 'address_1' => 'Malleshwaram', 'address_2' => 'Bangalore', 'country' => 'US', 'owner_id' => 3];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/contact/10000', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('contacts');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testDelete()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/contact/1', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('contacts');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testDeleteNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/contact/10000', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('contacts');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testgetcontactByOwner()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/contact/1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testgetcontactByOwnerNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/contact/100000', 'GET');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testgetcontactByOrg()
    {
        $this->initAuthToken($this->managerUser);
        $this->dispatch('/contact/org', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testGetContactListWithLimit()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['offset' => '0', 'limit' => '100', 'orderFieldName' => 'first_name', 'order' => 'asc', 'searchVal' => 'kar',
        ];
        $this->assertEquals(2, $this->getConnection()->getRowCount('ox_contact'));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/contact/list', 'POST', $data);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('contactlist');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }


    /*Code to test when the offset value is greater than the limit.*/
    public function testGetContactListWithLimitAndOffsetGreater()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['offset' => '10', 'limit' => '2', 'orderFieldName' => 'first_name', 'order' => 'asc', 'searchVal' => 'kar',
        ];
        $this->assertEquals(2, $this->getConnection()->getRowCount('ox_contact'));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/contact/list', 'POST', $data);
        $this->assertResponseStatusCode(400);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('contactlist');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Offset cannot be greater then the Limit!');
    }

}