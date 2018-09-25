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
	public function hasPermission($e){
        $request = $e->getRequest();
		$controllerName = $e->getRouteMatch()->getParam('controller', null);
        $moduleName = explode("\\", $controllerName);
		$actionName = $e->getRouteMatch()->getParam('action', null);
		//TODO Rules for custom Actions
		if(isset($actionName)){

		}
        $api_permission = $this->getPermission(strtolower($request->getMethod()));
		$config = $e->getApplication()->getServiceManager()->get('Config');
		$roles = AuthContext::get(AuthConstants::ROLES);
		$requiredAccess = $config[$moduleName[0]."Privilege"]['privilege']."_".$api_permission;
		if (in_array($requiredAccess, $roles)) {
			return 1;
		}
		return 0;
	}
	private function getPermission($method){
		if(isset($method)){
			switch ($method) {
				case 'post':
					return "CREATE";
					break;
				case 'put':
					return "WRITE";
					break;
				case 'get':
					return "READ";
					break;
				case 'delete':
					return "DELETE";
					break;
				default:
					return "READ";
					break;
			}
		}
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
		->from('ox_role_privelege')
        ->columns(array('privelege_name','permissions'))
        ->join('ox_roles', 'ox_roles.id = ox_role_privelege.id',array())
        ->join('ox_role_user', 'ox_roles.id = ox_role_user.role_id',array())
        ->where(array('ox_role_user.user_id' => $userId));
        $results = $this->executeQuery($select)->toArray();
        $permissions = array();
        foreach ($results as $key => $value) {
        	$permissions[] = implode($this->addPermissions($value['privelege_name'],$value['permissions']),",");
        }
		return array_unique(explode(",",implode($permissions, ",")));
	}
	public function addPermissions($privelegeName,$permission){
		$permissionArray = array();
		switch (true) {
			case ($permission == 1):
				$permissionArray[] = $privelegeName."_".'READ';
				break;
			case $permission >= 2 && $permission <= 3 :
				$permissionArray[] = $privelegeName."_".'READ';
				$permissionArray[] = $privelegeName."_".'CREATE';
				break;
			case $permission >= 4 && $permission <= 7 :
				$permissionArray[] = $privelegeName."_".'READ';
				$permissionArray[] = $privelegeName."_".'CREATE';
				$permissionArray[] = $privelegeName."_".'WRITE';
				break;
			case $permission >= 8 && $permission <= 14 :
				$permissionArray[] = $privelegeName."_".'READ';
				$permissionArray[] = $privelegeName."_".'CREATE';
				$permissionArray[] = $privelegeName."_".'WRITE';
				break;
			case 15:
				$permissionArray[] = $privelegeName."_".'READ';
				$permissionArray[] = $privelegeName."_".'CREATE';
				$permissionArray[] = $privelegeName."_".'WRITE';
				$permissionArray[] = $privelegeName."_".'DELETE';
				break;
			default:
				$permissionArray[] = $privelegeName."_".'READ';
				break;
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