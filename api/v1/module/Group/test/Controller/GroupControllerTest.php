<?php
namespace Group;

use Group\Controller\GroupController;
use Zend\Stdlib\ArrayUtils;
use Groups\Model;
use Oxzion\Test\ControllerTest;

class GroupControllerTest extends ControllerTest{
    public function setUp(){
        $this->loadConfig();
        parent::setUp();
        $this->initAuthToken('testUser');
    }
    public function testGetList(){
                    //TODO CREATE TEST CASE FOR testGetList
    }
    public function testGet(){
                    //TODO CREATE TEST CASE FOR testGet
    }
    public function testGetNotFound(){
                    //TODO CREATE TEST CASE FOR testGetNotFound
    }
    public function testCreate(){
                    //TODO CREATE TEST CASE FOR testCreate
    }
    public function testCreateFailure(){
                    //TODO CREATE TEST CASE FOR testCreateFailure
    }
    public function testUpdate(){
                    //TODO CREATE TEST CASE FOR testUpdate
    }
    public function testUpdateNotFound(){
                    //TODO CREATE TEST CASE FOR testUpdateNotFound
    }
    public function testUpdateFailure(){
                    //TODO CREATE TEST CASE FOR testUpdateFailure
    }
    public function testDelete(){
                    //TODO CREATE TEST CASE FOR testDelete
    }
    public function testDeleteNotFound(){
                    //TODO CREATE TEST CASE FOR testDeleteNotFound
    }
}