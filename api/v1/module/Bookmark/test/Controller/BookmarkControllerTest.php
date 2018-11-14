<?php
namespace Bookmark;

use Bookmark\Controller\BookmarkController;
use Bookmark\Model;
use Oxzion\Test\ControllerTest;
use Oxzion\Db\ModelTable;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;


class BookmarkControllerTest extends ControllerTest{

    public function setUp() : void{
        $this->loadConfig();
        parent::setUp();
    }
    public function getDataSet() {
        $dataset = new YamlDataSet(dirname(__FILE__)."/../Dataset/Bookmark.yml");
        return $dataset;
    }
    public function getBookmarkData() {
        // echo "<pre>";print_r(yaml_parse_file(dirname(__FILE__)."/../Dataset/Bookmark.yml"));
        return array(
            'links' => array(
                0 => array(
                    "id" => 1,
                    "name" => "Bookmark 1",
                    "org_id" => 1,
                    "avatar_id" => 2,
                    "url" => "https://oxzion.com"
                ),
                1 => array(
                    "id" => 2,
                    "name" => "Bookmark 2",
                    "org_id" => 1,
                    "avatar_id" => 2,
                    "url" => "https://google.com"
                )
            )
        );
    }
    protected function setDefaultAsserts(){
        $this->assertModuleName('Bookmark');
        $this->assertControllerName(BookmarkController::class); // as specified in router's controller name alias
        $this->assertControllerClass('BookmarkController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }
    public function testGetList(){
        $data = $this->getBookmarkData()['links'];
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/bookmark', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('bookmark');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), count($data));
        $count = 0;
        $assert_fields = array(
            'id',
            'name',
            'url'
        );
        while ($count < count($data)) {
            foreach ($assert_fields as $assert_field) {
                $this->assertEquals($content['data'][$count][$assert_field], $data[$count][$assert_field]);
            }
            $count++;
        }
    }
    public function testGet(){
        $data = $this->getBookmarkData()['links'][0];
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/bookmark/1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('bookmark');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $assert_fields = array(
            'id',
            'name',
            'url'
        );
        foreach ($assert_fields as $assert_field) {
            $this->assertEquals($content['data'][$assert_field], $data[$assert_field]);
        }
    }
    public function testGetNotFound(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/bookmark/64', 'GET');
        $this->assertResponseStatusCode(404);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }
    public function testCreate(){
        $data = $this->getBookmarkData()['links'][0];
        unset($data['id']);
        $this->initAuthToken($this->adminUser);
        $this->assertEquals(2, $this->getConnection()->getRowCount('links'));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/bookmark', 'POST', null);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('bookmark');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $assert_fields = array(
            'name',
            'url'
        );
        foreach ($assert_fields as $assert_field) {
            $this->assertEquals($content['data'][$assert_field], $data[$assert_field]);
        }
        $this->assertEquals(3, $this->getConnection()->getRowCount('links'));
    }
    public function testCreateWithOutNameFailure(){
        $data = $this->getBookmarkData()['links'][0];
        unset($data['id']);
        unset($data['name']);
        $this->initAuthToken($this->adminUser);
        $this->assertEquals(2, $this->getConnection()->getRowCount('links'));
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/bookmark', 'POST', null);
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('bookmark');
        $this->assertResponseStatusCode(404);
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
        $this->assertEquals($content['data']['errors']['name'], 'required');
    }
    public function testCreateAccess(){
        $data = $this->getBookmarkData()['links'][0];
        unset($data['id']);
        $this->initAuthToken($this->employeeUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/bookmark', 'POST', null);
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertModuleName('Bookmark');
        $this->assertControllerName(BookmarkController::class); // as specified in router's controller name alias
        $this->assertControllerClass('BookmarkController');
        $this->assertMatchedRouteName('bookmark');
        $this->assertResponseStatusCode(401);
        $this->assertResponseHeaderContains('content-type', 'application/json');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }
    public function testUpdate(){
        $data = $this->getBookmarkData()['links'][0];
        unset($data['id']);
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/bookmark/1', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('bookmark');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $data['id'] = 1;
        $assert_fields = array(
            'id',
            'name',
            'url'
        );
        foreach ($assert_fields as $assert_field) {
            $this->assertEquals($content['data'][$assert_field], $data[$assert_field]);
        }
    }

    public function testUpdateNotFound(){
        $data = $this->getBookmarkData()['links'][0];
        unset($data['id']);
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/bookmark/122', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('bookmark');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testDelete(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/bookmark/1', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('bookmark');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testDeleteNotFound(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/bookmark/122', 'DELETE');
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $this->assertMatchedRouteName('bookmark');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

}