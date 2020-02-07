<?php
namespace Oxzion\AppDelegate;

use Oxzion\Db\Persistence\Persistence;

trait UserContextTrait
{
	private $userId;
	private $uysername;
	private $orgId;

    public function setUserContext($userId, $username, $orgId){
    	$this->username = $username;
    	$this->userId = $userId;
    	$this->orgId = $orgId;
    }
    public function getUserId(){
    	return $this->userId;
    }
    public function getusername(){
    	return $this->username;
    }
    public function getOrgId(){
    	return $this->orgId;
    }
}
