<?php
namespace Email;

use Email\Controller\EmailController;
use Oxzion\Test\ControllerTest;
use PHPUnit\DbUnit\DataSet\YamlDataSet;

class EmailControllerTest extends ControllerTest
{
    public function setUp(): void
    {
        $this->loadConfig();
        parent::setUp();
    }
    public function getDataSet()
    {
        $dataset = new YamlDataSet(dirname(__FILE__) . "/../Dataset/Email.yml");
        return $dataset;
    }
    protected function setDefaultAsserts()
    {
        $this->assertModuleName('Email');
        $this->assertControllerName(EmailController::class); // as specified in router's controller name alias
        $this->assertControllerClass('EmailController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }
    public function testGetList()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/email', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        $this->assertEquals($content['data'][0]['id'], 1);
        $this->assertEquals($content['data'][0]['userid'], 1);
        $this->assertEquals($content['data'][0]['email'], 'bharatg@myvamla.com');
    }
    public function testGet()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/email/1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['id'], 1);
        $this->assertEquals($content['data'][0]['email'], 'bharatg@myvamla.com');
    }
    public function testGetNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/email/64', 'GET');
        $this->assertResponseStatusCode(404);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }
    public function testCreate()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['email' => 'brianmp@myvamla.com', 'username' => 'brianmp@myvamla.com', 'password' => 'password', 'host' => 'box3053.bluehost.com'];
        $this->assertEquals(2, $this->getConnection()->getRowCount('email_setting_user'));
        $this->dispatch('/email', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['email'], $data['email']);
        $this->assertEquals(3, $this->getConnection()->getRowCount('email_setting_user'));
    }

    public function testCreateWithToken()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['email' => 'brianmp@myvamla.com', 'token' => 'token'];
        $this->assertEquals(2, $this->getConnection()->getRowCount('email_setting_user'));
        $this->dispatch('/email', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['email'], $data['email']);
        $this->assertEquals(3, $this->getConnection()->getRowCount('email_setting_user'));
    }

    public function testCreateWithOutDataFailure()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['username' => 'brianmp@myvamla.com'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/email', 'POST', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
        $this->assertEquals($content['data']['errors']['password'], 'required');
    }

    public function testUpdate()
    {
        $data = ['email' => 'bharatg@myvamla.com', 'password' => 'password1'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/email', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['email'], $data['email']);
    }

    public function testUpdateNotFound()
    {
        $data = ['email' => 'brianmp@myvamla.com'];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/email', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testDelete()
    {
        $this->initAuthToken($this->adminUser);
        $this->assertEquals(2, $this->getConnection()->getRowCount('email_setting_user'));
        $this->dispatch('/email/delete/bharatg@myvamla.com', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(1, $this->getConnection()->getRowCount('email_setting_user'));
    }

    public function testDeleteNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/email/delete/brianmp@myvamla.com', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Entity not found');
    }

    public function testEmailDefault()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/email/1/default', 'GET');
        $content = json_decode($this->getResponse()->getContent(), true);
        print_r($content);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data'][0]['id'], 1);
        $this->assertEquals($content['data'][0]['email'], 'admin1@eoxvantage.in');
    }

    public function testEmailDefaultNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/email/64', 'GET');
        $this->assertResponseStatusCode(404);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }
}
