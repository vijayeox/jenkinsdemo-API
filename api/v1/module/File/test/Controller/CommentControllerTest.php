<?php
namespace File;

use File\Controller\CommentController;
use Oxzion\Test\ControllerTest;
use Oxzion\Db\ModelTable;
use PHPUnit\DbUnit\TestCaseTrait;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;
use Oxzion\Utils\FileUtils;

class CommentControllerTest extends ControllerTest {
    public function setUp() : void{
        $this->loadConfig();
        parent::setUp();
    }   
    public function getDataSet() {
        $dataset = new YamlDataSet(dirname(__FILE__)."/../Dataset/Comment.yml");
        return $dataset;
    }
    protected function setDefaultAsserts() {
        $this->assertModuleName('File');
        $this->assertControllerName(CommentController::class); // as specified in router's controller name alias
        $this->assertControllerClass('CommentController');
        $this->assertResponseHeaderContains('content-type', 'application/json; charset=utf-8');
    }
   public function testGetList(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/file/1/comment', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals(count($content['data']), 4);
        $this->assertEquals($content['data'][0]['id'], 1);
        $this->assertEquals($content['data'][0]['text'], 'Comment 1');
        $this->assertEquals($content['data'][1]['id'], 2);
        $this->assertEquals($content['data'][1]['text'], 'Comment 2');
    }
    public function testGet(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/file/1/comment/1', 'GET');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['id'], 1);
        $this->assertEquals($content['data']['text'], 'Comment 1');
    }
    public function testGetNotFound(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/file/1/comment/23', 'GET');
        $this->assertResponseStatusCode(404);
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }
    public function testCreate(){
        $this->initAuthToken($this->adminUser);
        $data = ['text' => 'Comment 5','parent' => 4];
        $this->assertEquals(4, $this->getConnection()->getRowCount('ox_comment'));
        $this->dispatch('/file/1/comment', 'POST', $data);
        $this->assertResponseStatusCode(201);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['text'], $data['text']);
        $this->assertEquals(5, $this->getConnection()->getRowCount('ox_comment'));
    }
    public function testCreateWithOutTextFailure(){
        $this->initAuthToken($this->adminUser);
        $data = ['parent' => 4];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/file/1/comment', 'POST', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'Validation Errors');
        $this->assertEquals($content['data']['errors']['text'], 'required');
    }

    public function testCreateAccess() {
        $this->initAuthToken($this->employeeUser);
        $data = ['text' => 'Comment 1','parent' => 4];
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/file/1/comment', 'POST', null);
        $this->assertResponseStatusCode(401);
        $this->assertModuleName('File');
        $this->assertControllerName(CommentController::class); // as specified in router's controller name alias
        $this->assertControllerClass('CommentController');
        $this->assertMatchedRouteName('Comment');
        $this->assertResponseHeaderContains('content-type', 'application/json');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }
        
    public function testUpdate() {
        $data = ['text' => 'Updated Comment','parent' => 4];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/file/1/comment/1', 'PUT', null);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
        $this->assertEquals($content['data']['text'], $data['text']);
        $this->assertEquals($content['data']['parent'], $data['parent']);
    }
    public function testUpdateRestricted() {
        $data = ['text' => 'Updated Comment','parent' => 4];
        $this->initAuthToken($this->employeeUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/file/1/comment/1', 'PUT', null);
        $this->assertResponseStatusCode(401);
        $this->assertModuleName('File');
        $this->assertControllerName(CommentController::class); // as specified in router's controller name alias
        $this->assertControllerClass('CommentController');
        $this->assertMatchedRouteName('Comment');
        $this->assertResponseHeaderContains('content-type', 'application/json');
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
        $this->assertEquals($content['message'], 'You have no Access to this API');
    }
    
    public function testUpdateNotFound(){
        $data = ['text' => 'Updated Comment','parent' => 4];
        $this->initAuthToken($this->adminUser);
        $this->setJsonContent(json_encode($data));
        $this->dispatch('/file/1/comment/122', 'PUT', null);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = (array)json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }

    public function testDelete(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/file/2/comment/2', 'DELETE');
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testDeleteNotFound(){
        $this->initAuthToken($this->adminUser);
        $this->dispatch('/file/2/comment/1222', 'DELETE');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');        
    }

    public function testGetChildList(){
    	$this->initAuthToken($this->adminUser);
        $this->dispatch('/file/1/comment/1/getchildlist', 'POST');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(200);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'success');
    }

    public function testGetChildListNoChild() {
    	$this->initAuthToken($this->adminUser);
        $this->dispatch('/file/1/comment/4/getchildlist', 'POST');
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertResponseStatusCode(404);
        $this->setDefaultAsserts();
        $content = json_decode($this->getResponse()->getContent(), true);
        $this->assertEquals($content['status'], 'error');
    }
}
?>