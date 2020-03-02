<?php

namespace Contact;

use Contact\Controller\ContactController;
use Contact\Model;
use Oxzion\Test\ControllerTest;
use Oxzion\Db\ModelTable;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use PHPUnit\Framework\TestResult;
use Zend\Db\Sql\Sql;
use Oxzion\Utils\FileUtils;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;


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
    
    public function testCreate()
    {
        $this->initAuthToken($this->adminUser);
        $data = [ 'first_name' => "Raks", 'last_name' => 'Iddya', 'phone_1' => '9810029938', 'email' => 'raks@va.com', 'company_name' => 'VA', 'address_1' => 'Malleshwaram', 'address_2' => 'Bangalore', 'country' => 'India'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/contact', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('contacts');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['first_name'], $data['first_name']);
        $this->assertEquals($content['data']['last_name'], $data['last_name']);
        $this->assertEquals($content['data']['phone_1'], $data['phone_1']);
        $this->assertEquals($content['data']['email'], $data['email']);
        $this->assertEquals($content['data']['company_name'], $data['company_name']);
        $this->assertEquals($content['data']['address_1'], $data['address_1']);
        $this->assertEquals($content['data']['address_2'], $data['address_2']);
        $this->assertEquals($content['data']['country'], $data['country']);
        $this->assertEquals($content['data']['owner_id'], 1);
    }

    /*
        TODO We need to create test cases for the create method to check if the ID is already passed. The create method has the update option also available
    */

    //Testing to see if the Create Contact function is working as intended if all the value passed are correct.

    protected function setDefaultAsserts()
    {
        $this->assertModuleName('Contact');
        $this->assertControllerName(ContactController::class); // as specified in router's controller name alias
        $this->assertControllerClass('ContactController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }

    //Test Case to check the errors when the required field is not selected. Here I removed the parent_id field from the list.

    public function testCreateWithoutRequiredField()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['last_name' => 'Iddya', 'phone_1' => '9810029938', 'email' => 'raks@va.com', 'company_name' => 'VA', 'address_1' => 'Malleshwaram', 'address_2' => 'Bangalore', 'country' => 'India'];
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
        $data = ['user_id' => '3', 'first_name' => "Rakshith", 'last_name' => 'Iddya', 'phone_1' => '9810029938', 'email' => 'raks@va.com', 'company_name' => 'VA', 'address_1' => 'Malleshwaram', 'address_2' => 'Bangalore', 'country' => 'US'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/contact/c384bdbf-48e1-4180-937a-08e5852718ea', 'POST', null);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('contacts');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['first_name'], $data['first_name']);
        $this->assertEquals($content['data']['last_name'], $data['last_name']);
        $this->assertEquals($content['data']['phone_1'], $data['phone_1']);
        $this->assertEquals($content['data']['email'], $data['email']);
        $this->assertEquals($content['data']['company_name'], $data['company_name']);
        $this->assertEquals($content['data']['address_1'], $data['address_1']);
        $this->assertEquals($content['data']['address_2'], $data['address_2']);
        $this->assertEquals($content['data']['country'], $data['country']);
    }

    public function testUpdateNotFound()
    {
        $data = ['last_name' => 'Iddya', 'phone_1' => '9810029938', 'email' => 'raks@va.com', 'company_name' => 'VA', 'address_1' => 'Malleshwaram', 'address_2' => 'Bangalore', 'country' => 'US', 'owner_id' => 3];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/contact/10000', 'PUT', null);
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(500);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('contacts');
        $this->assertEquals($content['status'], 'error');
    }

    public function testDelete()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/contact/c384bdbf-48e1-4180-937a-08e5852718ea', 'DELETE');
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

    public function testgetcontactsSuccess()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/contact/search?column=1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();

        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['myContacts'][0]['user_id'], 1);
        $this->assertEquals($content['data']['myContacts'][0]['first_name'], 'Karan S');
        $this->assertEquals($content['data']['myContacts'][0]['last_name'], 'Agarwal');

        $this->assertEquals($content['data']['orgContacts'][0]['user_id'], 1);
        $this->assertEquals($content['data']['orgContacts'][0]['first_name'], 'Bharat');
        $this->assertEquals($content['data']['orgContacts'][0]['last_name'], 'Gogineni');

        $this->assertEquals($content['data']['orgContacts'][1]['user_id'], 2);
        $this->assertEquals($content['data']['orgContacts'][1]['first_name'], 'Karan');
        $this->assertEquals($content['data']['orgContacts'][1]['last_name'], 'Agarwal');

        $this->assertEquals($content['data']['orgContacts'][2]['user_id'], 3);
        $this->assertEquals($content['data']['orgContacts'][2]['first_name'], 'rakshith');
        $this->assertEquals($content['data']['orgContacts'][2]['last_name'], 'amin');
    }

    public function testgetcontactsForAllColumnsSuccess()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/contact/search?column=-1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');

        $this->assertEquals($content['data']['myContacts'][0]['user_id'], 1);
        $this->assertEquals($content['data']['myContacts'][0]['first_name'], 'Karan S');
        $this->assertEquals($content['data']['myContacts'][0]['last_name'], 'Agarwal');
        $this->assertEquals($content['data']['myContacts'][0]['phone_1'], '14034');
        $this->assertEquals($content['data']['myContacts'][0]['phone_list'], array('data'=>["8399547885"," 7899290200"," 123123122445"]));
        $this->assertEquals($content['data']['myContacts'][0]['email'], 'karan@myvamla.com');
        $this->assertEquals($content['data']['myContacts'][0]['email_list'], array('data'=>["raks@va.com"," asas@ox.com"]));
        $this->assertEquals($content['data']['orgContacts'][0]['user_id'], '1');
        $this->assertEquals($content['data']['orgContacts'][0]['first_name'], 'Bharat');
        $this->assertEquals($content['data']['orgContacts'][0]['last_name'], 'Gogineni');
        $this->assertEquals($content['data']['orgContacts'][0]['phone_1'], '+93-1234567891');
        $this->assertEquals($content['data']['orgContacts'][0]['phone_list'], null);
        $this->assertEquals($content['data']['orgContacts'][0]['email'], 'bharatg@myvamla.com');
        $this->assertEquals($content['data']['orgContacts'][0]['email_list'], null);

        $this->assertEquals($content['data']['orgContacts'][1]['user_id'], '2');
        $this->assertEquals($content['data']['orgContacts'][1]['first_name'], 'Karan');
        $this->assertEquals($content['data']['orgContacts'][1]['last_name'], 'Agarwal');
        $this->assertEquals($content['data']['orgContacts'][1]['phone_1'], '+93-1234567891');
        $this->assertEquals($content['data']['orgContacts'][1]['phone_list'], null);
        $this->assertEquals($content['data']['orgContacts'][1]['email'], 'test1@va.com');
        $this->assertEquals($content['data']['orgContacts'][1]['email_list'], null);

        $this->assertEquals($content['data']['orgContacts'][2]['user_id'], '3');
        $this->assertEquals($content['data']['orgContacts'][2]['first_name'], 'rakshith');
        $this->assertEquals($content['data']['orgContacts'][2]['last_name'], 'amin');
        $this->assertEquals($content['data']['orgContacts'][2]['phone_1'], '+93-1234567891');
        $this->assertEquals($content['data']['orgContacts'][2]['phone_list'], null);
        $this->assertEquals($content['data']['orgContacts'][2]['email'], 'test@va.com');
        $this->assertEquals($content['data']['orgContacts'][2]['email_list'], null);
    }

    public function testgetcontactsWithFilter()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/contact/search?column=-1&filter=karan', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');

        $this->assertEquals($content['data']['myContacts'][0]['user_id'], 1);
        $this->assertEquals($content['data']['myContacts'][0]['first_name'], 'Karan S');
        $this->assertEquals($content['data']['myContacts'][0]['last_name'], 'Agarwal');
        $this->assertEquals($content['data']['myContacts'][0]['phone_1'], '14034');
    }


    public function testgeticonWhenIconTypeIsNotNull()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/contact/search?column=-1&filter=karan', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['myContacts'][0]['user_id'], 1);
        $this->assertEquals($content['data']['myContacts'][0]['first_name'], 'Karan S');
        $this->assertEquals($content['data']['myContacts'][0]['last_name'], 'Agarwal');
        $this->assertEquals($content['data']['myContacts'][0]['icon'], 'http://localhost:8080/user/profile/4fd99e8e-758f-11e9-b2d5-68ecc57cde45');
    }


    public function testContactImport()
    {
        $this->initAuthToken($this->adminUser);
        $_FILES['file'] = array();
        $_FILES['file']['name'] = 'contact1.csv';
        $_FILES['file']['type'] = 'text/csv';
        $_FILES['file']['tmp_name'] = __DIR__.'/../files/contact1.csv';
        $_FILES['file']['error'] = 0;
        $_FILES['file']['size'] = 1007;
        $this->dispatch('/contact/import', 'POST');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Contact');
        $this->assertControllerName(ContactController::class); // as specified in router's controller name alias
        $this->assertControllerClass('ContactController');
        $this->assertMatchedRouteName('contactImport');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testContactImportWithInvalidColumnHeaders()
    {
        $this->initAuthToken($this->adminUser);
        $_FILES['file'] = array();
        $_FILES['file']['name'] = 'invalidheaders.csv';
        $_FILES['file']['type'] = 'text/csv';
        $_FILES['file']['tmp_name'] = __DIR__.'/../files/invalidheaders.csv';
        $_FILES['file']['error'] = 0;
        $_FILES['file']['size'] = 1007;
        $this->dispatch('/contact/import', 'POST');
        $this->assertResponseStatusCode(404);
        $this->assertModuleName('Contact');
        $this->assertControllerName(ContactController::class); // as specified in router's controller name alias
        $this->assertControllerClass('ContactController');
        $this->assertMatchedRouteName('contactImport');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Column Headers donot match...');
    }


    /*
     ? Having a invalid test case with success response does not make sense, need to revisit this test case
     TODO We need to create negetive test cases for the import
    */
    // public function testContactImportWithInvalidData()
    // {
    //     $this->initAuthToken($this->adminUser);
    //     $_FILES['file'] = array();
    //     $_FILES['file']['name'] = 'invaliddata.csv';
    //     $_FILES['file']['type'] = 'text/csv';
    //     $_FILES['file']['tmp_name'] = __DIR__.'/../files/invaliddata.csv';
    //     $_FILES['file']['error'] = 0;
    //     $_FILES['file']['size'] = 1007;
    //     $this->dispatch('/contact/import', 'POST');
    //     $this->assertResponseStatusCode(200);
    //     $this->assertModuleName('Contact');
    //     $this->assertControllerName(ContactController::class); // as specified in router's controller name alias
    //     $this->assertControllerClass('ContactController');
    //     $this->assertMatchedRouteName('contactImport');
    //     $content = json_decode($this->getResponse()->getContent(), true);
    //     $this->assertEquals($content['status'], 'success');
    //     $this->assertEquals($content['message'], 'Validate and Import the downloaded file');
    //     $this->assertEquals(count($content['data']), 1);
    // }

    public function testContactExport()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/contact/export', 'POST', null);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Contact');
        $this->assertControllerName(ContactController::class); // as specified in router's controller name alias
        $this->assertControllerClass('ContactController');
        $this->assertMatchedRouteName('contactExport');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['message'], 'Exported CSV Data');
        $this->assertEquals(count($content['data']), 1);
    }

    public function testContactExportByUuid()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['143949cf-6696-42ad-877a-26e8119603c3'];
        $this->dispatch('/contact/export', 'POST');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Contact');
        $this->assertControllerName(ContactController::class); // as specified in router's controller name alias
        $this->assertControllerClass('ContactController');
        $this->assertMatchedRouteName('contactExport');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['message'], 'Exported CSV Data');
        $this->assertEquals(count($content['data']), 1);
    }

    public function testMultipleContactDelete(){
        $this->initAuthToken($this->adminUser);
        $data = ['c384bdbf-48e1-4180-937a-08e5852718ea'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/contacts/delete', 'POST',$data);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Contact');
        $this->assertControllerName(ContactController::class); // as specified in router's controller name alias
        $select ="SELECT * from ox_contact where owner_id = 1";
        $result = $this->executeQueryTest($select);
        $this->assertControllerClass('ContactController');
        $this->assertMatchedRouteName('contactsDelete');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($result),1);
    }

    public function testConactDeleteofDifferentOwner(){
        $this->initAuthToken($this->adminUser);
        $data = ['143949cf-6696-42ad-877a-26e8119603c3'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/contacts/delete', 'POST',$data);
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('Contact');
        $this->assertControllerName(ContactController::class); // as specified in router's controller name alias
        $select ="SELECT * from ox_contact where uuid = '143949cf-6696-42ad-877a-26e8119603c3'";
        $result = $this->executeQueryTest($select);
        $this->assertControllerClass('ContactController');
        $this->assertMatchedRouteName('contactsDelete');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($result),1);
    }
    
}
