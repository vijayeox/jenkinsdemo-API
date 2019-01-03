<?php
namespace Project\Service;

use Bos\Service\AbstractService;
use Project\Model\ProjectTable;
use Project\Model\Project;
use Bos\Auth\AuthContext;
use Bos\Auth\AuthConstants;
use Bos\ValidationException;
use Zend\Db\Sql\Expression;
use Exception;

class ProjectService extends AbstractService {

	private $table;

	public function __construct($config, $dbAdapter, ProjectTable $table) {
		parent::__construct($config, $dbAdapter);
		$this->table = $table;
	}

	public function createProject(&$data) {
		$form = new Project();
    //Additional fields that are needed for the create      
		$data['name'] = $data['name'];
		$data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
		$data['created_by'] = AuthContext::get(AuthConstants::USER_ID);
		$data['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
		$data['date_created'] = date('Y-m-d H:i:s');
		$data['date_modified'] = date('Y-m-d H:i:s');
        $data['isdeleted'] = false;
        $form->exchangeArray($data);
		$form->validate();
		$this->beginTransaction();
		$count = 0;
		try {
        	$count = $this->table->save($form);
			if($count == 0) {
				$this->rollback();
				return 0;
			}
			$id = $this->table->getLastInsertValue();
			$data['id'] = $id;
			$this->commit();
		} catch(Exception $e) {
			$this->rollback();
			return 0;
		}
		return $count;
	}

	public function updateProject ($id, &$data) {
		$obj = $this->table->get($id,array());
		if (is_null($obj)) {
			return 0;
		}
		$form = new Project();
        $data = array_merge($obj->toArray(), $data); //Merging the data from the db for the ID
        $data['id'] = $id;
        $data['modified_id'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_modified'] = date('Y-m-d H:i:s');
        $form->exchangeArray($data);
        $form->validate();
        $count = 0;
        try {
        	$count = $this->table->save($form);
        	if($count == 0) {
        		$this->rollback();
        		return 0;
        	}
        } catch(Exception $e) {
        	$this->rollback();
        	return 0;
        }
        return $count;
    }

    public function deleteProject($id) {
    	$obj = $this->table->get($id,array());
        if (is_null($obj)) {
            return 0;
        }
        $form = new Project();
        $data = $obj->toArray();
        $data['id'] = $id;
        $data['modified_id'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_modified'] = date('Y-m-d H:i:s');
        $data['isdeleted'] = 1;
        $form->exchangeArray($data);
        $form->validate();
        $count = 0;
        try {
            $count = $this->table->save($form);
            if($count == 0) {
                $this->rollback();
                return 0;
            }
        } catch(Exception $e) {
            $this->rollback();
            return 0;
        }
        return $count;
    }
    public function getProjectsByUserId() { 
    	$userId = AuthContext::get(AuthConstants::USER_ID);
    	$queryString = "select * from ox_project 
    	left join ox_user_project on ox_user_project.project_id = ox_project.id";
    	$where = "where ox_user_project.user_id = " . $userId." AND ox_project.org_id=".AuthContext::get(AuthConstants::ORG_ID)." AND ox_project.isdeleted!=1"; 
    	$order = "order by ox_project.id";
    	$resultSet = $this->executeQuerywithParams($queryString, $where, null, $order);
    	return $resultSet->toArray();
    }

    public function getListOfUsers($project_id) {
        $queryString = "select id from ox_project";
        $order = "order by ox_project.id";
        $where = "where ox_project.isdeleted!=1";
        $resultSet_temp = $this->executeQuerywithParams($queryString, $where, null, $order)->toArray();
        $resultSet=array_map('current', $resultSet_temp);
        if(in_array($project_id, $resultSet)) {
            $query = "select user_id from ox_user_project";
            $where = "where project_id =".$project_id;
            $order = "order by ox_user_project.user_id";
            $resultSet_User = $this->executeQuerywithParams($query, $where, null, $order)->toArray();
            return $resultSet_User;
        }
        else {
            return 0;
        }
    }
    //Writing this incase we need to get all projects later. Please do not delete - Brian
    /*public function getProject($id) { 
    	$userId = AuthContext::get(AuthConstants::USER_ID);
    	$queryString = "select * from ox_project 
    	left join ox_user_project on ox_user_project.project_id = ox_project.id";
    	$where = "where ox_user_project.user_id = " . $userId." AND ox_project.org_id=".AuthContext::get(AuthConstants::ORG_ID)." AND ox_project.id=".$id; 
    	$order = "order by ox_project.id";
    	$resultSet = $this->executeQuerywithParams($queryString, $where, null, $order);
    	return $resultSet->toArray();
    }*/

    public function saveUser($project_id,$data) {
    	$userArray=json_decode($data['userid'],true);
        if($userArray){
            $userSingleArray= array_map('current', $userArray);
            //Check if project id exists
            $queryString = "select id from ox_project";
            $order = "order by ox_project.id";
            $where = "where ox_project.isdeleted!=1";
            $resultSet_temp = $this->executeQuerywithParams($queryString, $where, null, $order)->toArray();
            $resultSet=array_map('current', $resultSet_temp);
            //Check if user id exists
            $query = "select id from avatars";
            $order = "order by avatars.id";
            $resultSet_User_temp = $this->executeQuerywithParams($query, null, null, $order)->toArray();
            $resultSet_User=array_map('current', $resultSet_User_temp);

            if((in_array($project_id, $resultSet))&&(count(array_intersect($userSingleArray, $resultSet_User))==count($userSingleArray))) {
                $sql = $this->getSqlObject();
                $delete = $sql->delete('ox_user_project');
                $result = $this->executeUpdate($delete);
            	$storeData = array();
                if($userArray){
                    foreach ($userArray as $key => $value) {
                        $storeData[] = array('project_id'=>$project_id,'user_id'=>$value['id']);
                    }
                    $userId = AuthContext::get(AuthConstants::USER_ID);
                    $queryString =$this->multiInsertOrUpdate('ox_user_project',$storeData,array());
                }
            }
            else {
                return 0;
            }
        }
        else {
                return 0;
        }
        return 1;
    }

    /*public function deleteUser($project_id, $data) {
        $queryString = "select id from ox_project";
        $order = "order by ox_project.id";
        $where = "where ox_project.isdeleted!=1";
        $resultSet_temp = $this->executeQuerywithParams($queryString, $where, null, $order)->toArray();
        $resultSet=array_map('current', $resultSet_temp);

        $query = "select id from user";
        $order = "order by user.id";
        $resultSet_User_temp = $this->executeQuerywithParams($query, null, null, $order)->toArray();
        $resultSet_User=array_map('current', $resultSet_User_temp);
        $userArray=json_decode($data['userid'],true);

        if($userArray){
            $userSingleArray= array_map('current', $userArray);
            if((in_array($project_id, $resultSet))&&(count(array_intersect($userSingleArray, $resultSet_User))==count($userSingleArray))) {
                $sql = $this->getSqlObject();
                $delete = $sql->delete('ox_user_project');
                $delete->where(['user_id' => array_column($userArray,'id'),'project_id'=>$project_id]);
                $result = $this->executeUpdate($delete);
            }
            else {
                return 0;
            }
        }
        else{
            return 0;
        }
        return 1;
    }*/
}