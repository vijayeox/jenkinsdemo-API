<?php
namespace Oxzion\Service;
use Zend\Db\Sql\Sql;
use Oxzion\Service\CacheService;
use Oxzion\Service\AbstractService;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;

class UserService extends AbstractService{
	
	private $cacheService;
	const GROUPS = '_groups';
	const ROLES = '_roles';
	private $id;

	public function __construct($config, $dbAdapter){
		parent::__construct($config, $dbAdapter);
		$this->cacheService = CacheService::getInstance();
	}
	
	public function getUserContextDetails($userName){
		if($results = $this->cacheService->get($userName)){
			return $results;
		}
		$sql = $this->getSqlObject();
		$select = $sql->select()
		->from('avatars')
        ->columns(array('id','name','orgid'))
		->where(array('username = "'.(string)$userName.'"'))->limit(1);
		$results = $this->executeQuery($select);
		$results = $results->toArray();
		if(count($results) > 0){
			$results = $results[0];
		}
		$this->cacheService->set($userName,$results);
		return $results;
	}	
	
	private function getGroupsFromDb($userName){
		$sql = $this->getSqlObject();
		$select = $sql->select()
		->from('groups_avatars')
        ->columns(array())
        ->join('groups', 'groups.id = groups_avatars.groupid')
        ->where(array('groups_avatars.avatarid' => $this->id));
		return $this->executeQuery($select)->toArray();
	}
	public function getGroups($userName){
		if($groupData = $this->cacheService->get($userName.GROUPS)){
			$data = $groupData;
		} else {
			$data = $this->getGroupsFromDb($userName);
			$this->cacheService->set($userName.GROUPS, $data);
		}
		return $data;
	}
	private function getRolesFromDb($userId){
		$sql = $this->getSqlObject();
		$select = $sql->select()
		->from('ox_role_user')
        ->columns(array('role_id'))
        ->where(array('ox_role_user.user_id' => $userId));
		return $this->executeQuery($select)->toArray();
	}
	private function getPrivelegesFromDb($userId){
		$sql = $this->getSqlObject();
		$select = $sql->select()
		->from('ox_role_privilege')
        ->columns(array('privilege_name','permissions'))
        ->join('ox_roles', 'ox_roles.id = ox_role_privilege.id',array())
        ->join('ox_role_user', 'ox_roles.id = ox_role_user.role_id',array())
        ->where(array('ox_role_user.user_id' => $userId));
        $results = $this->executeQuery($select)->toArray();
        $permissions = array();
        foreach ($results as $key => $value) {
        	$permissions = array_merge($permissions,$this->addPermissions($value['privilege_name'],$value['permissions']));
        }
		return array_unique($permissions);
	}
	public function addPermissions($privilegeName,$permission){
		$permissionArray = array();
		if (($permission & 1) != 0){
			$permissionArray[] = $privilegeName."_".'READ';
		}
		if(($permission & 2) != 0 ){
			$permissionArray[] = $privilegeName."_".'WRITE';
		}
		if(($permission & 4) != 0  ){
			$permissionArray[] = $privilegeName."_".'CREATE';
		}
		if(($permission & 8) != 0) {
			$permissionArray[] = $privilegeName."_".'DELETE';
		}
		return $permissionArray;
	}
	public function getPriveleges($userId){
		// if($roleData = $this->cacheService->get($userId.PRIVELEGES)){
			// $data = $roleData;
		// } else {
			$data = $this->getPrivelegesFromDb($userId);
			// $this->cacheService->set($userId.PERMISSIONS, $data);
		// }
		return $data;
	}
}
?>