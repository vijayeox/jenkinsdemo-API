<?php
namespace Oxzion\AppDelegate;

use Oxzion\Db\Persistence\Persistence;

trait UserContextTrait
{
	private $userId;
	private $username;
	private $orgId;
    private $privilege;

    public function setUserContext($userId, $username, $orgId,$privilege){
    	$this->username = $username;
    	$this->userId = $userId;
    	$this->orgId = $orgId;
        $this->privilege = $privilege;
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
    public function getPrivilege(){
        return $this->privilege;
    }

}
