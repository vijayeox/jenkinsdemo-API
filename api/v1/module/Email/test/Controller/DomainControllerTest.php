<?php
namespace Domain;

use Email\Controller\DomainController;
use Oxzion\Test\ControllerTest;
use PHPUnit\DbUnit\DataSet\YamlDataSet;

class DomainControllerTest extends ControllerTest
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
        $this->assertControllerName(DomainController::class); // as specified in router's controller name alias
        $this->assertControllerClass('DomainController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }

    public function testGetList()
    {
        $data = ['id' => 1,
            "name" => 'myvamla.com',
            "imap_server" => 'box3053.bluehost.com',
            "imap_port" => '993',
            "imap_secure" => 'ssl',
            "imap_short_login" => '2',
            "smtp_server" => 'box3053.bluehost.com',
            "smtp_port" => '465',
            "smtp_secure" => 'ssl',
            "smtp_short_login" => 'short_name',
            "smtp_auth" => 'auth',
            "smtp_use_php_mail" => 'No',
        ];
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/domain', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 2);
        foreach ($data as $key => $val) {
            $this->assertEquals($content['data'][0][$key], $val);
        }
    }

    public function testGet()
    {
        $data = ['id' => 1,
            "name" => 'myvamla.com',
            "imap_server" => 'box3053.bluehost.com',
            "imap_port" => '993',
            "imap_secure" => 'ssl',
            "imap_short_login" => '2',
            "smtp_server" => 'box3053.bluehost.com',
            "smtp_port" => '465',
            "smtp_secure" => 'ssl',
            "smtp_short_login" => 'short_name',
            "smtp_auth" => 'auth',
            "smtp_use_php_mail" => 'No',
        ];
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/domain/1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        foreach ($data as $key => $val) {
            $this->assertEquals($content['data'][$key], $val);
        }
    }

    public function testGetNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/domain/64', 'GET');
        $this->assertResponseStatusCode(404);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testCreate()
    {
        $this->initAuthToken($this->adminUser);
        $data = [
            'name' => 'yahoo.com',
            'imap_server' => 'imap.yahoo.com',
            'imap_port' => '90',
            'imap_secure' => '90',
            'imap_short_login' => '2',
            'smtp_server' => 'testing',
            'smtp_port' => '99',
            'smtp_secure' => 'securing3',
            'smtp_short_login' => 'short_name',
            'smtp_auth' => 'auth',
            'smtp_use_php_mail' => 'No',
        ];
        $this->assertEquals(2, $this->getConnection()->getRowCount('ox_email_domain'));
        $this->dispatch('/domain', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        foreach ($data as $key => $val) {
            $this->assertEquals($content['data'][$key], $val);
        }
        $this->assertEquals(3, $this->getConnection()->getRowCount('ox_email_domain'));
    }

    public function testCreateWithOutDataFailure()
    {
        $this->initAuthToken($this->adminUser);
        $data = ['name' => 'Wrong Server'];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/domain', 'POST', $data);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
        $this->assertEquals($content['data']['errors']['imap_server'], 'required');
    }

    public function testUpdate()
    {
        $data = [
            'id' => 1,
            'name' => 'Test Server Changed Name',
        ];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/domain/1', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('domain');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['name'], $data['name']);
    }

    public function testUpdateNotFound()
    {
        $data = [
            'id' => 99,
            'name' => 'Test Server Changed Name',
        ];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/domain/99', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('domain');
        $content = (array) json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testDelete()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/domain/1', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('domain');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testDeleteNotFound()
    {
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/domain/9999', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('domain');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }
}
