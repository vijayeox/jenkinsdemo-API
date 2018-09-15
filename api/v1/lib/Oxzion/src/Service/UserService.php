<?php
namespace Oxzion\Service;
use Zend\Db\Sql\Sql;
use Oxzion\Service\CacheService;
use Oxzion\Service\AbstractService;

class UserService extends AbstractService{
	
	private $cacheService;
	const GROUPS = '_groups';

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
	
	
	private function getGroupsFromDb($username){
		$sql = $this->getSqlObject();
		$select = $sql->select()
		->from('groups_avatars')
        ->columns(array())
        ->join('groups', 'groups.id = groups_avatars.groupid')
        ->where(array('groups_avatars.avatarid' => $this->id));
		return $this->executeQuery($select)->toArray();
	}
	public function getGroups($username){
		if($groupData = $this->cacheService->get($userName.GROUPS)){
			$data = $groupData;
		} else {
			$data = $this->getGroupsFromDb($username);
			$this->cacheService->set($this->userName.GROUPS, $data);
		}
		return $data;
	}
	
}
?>