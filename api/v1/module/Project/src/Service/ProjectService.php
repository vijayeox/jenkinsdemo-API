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
use Oxzion\Messaging\MessageProducer;
use Oxzion\Service\OrganizationService;

class ProjectService extends AbstractService {

    private $table;
    private $organizationService;
    
    public function setMessageProducer($messageProducer)
    {
		$this->messageProducer = $messageProducer;
    }

	public function __construct($config, $dbAdapter, ProjectTable $table, $organizationService) {
		parent::__construct($config, $dbAdapter);
        $this->table = $table;
        $this->messageProducer = MessageProducer::getInstance();
        $this->organizationService = $organizationService;
	}

	public function createProject(&$data) {
		$form = new Project();
    //Additional fields that are needed for the create
		$data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
		$data['created_by'] = AuthContext::get(AuthConstants::USER_ID);
		$data['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
		$data['date_created'] = date('Y-m-d H:i:s');
		$data['date_modified'] = date('Y-m-d H:i:s');
        $data['isdeleted'] = false;
        $org = $this->organizationService->getOrganization($data['org_id']);
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
        $result = $this->messageProducer->sendTopic(json_encode(array('orgname'=>  $org['name'],'projectname' => $data['name'])),'PROJECT_ADDED');
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
        $org = $this->organizationService->getOrganization($obj->org_id);
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
        $result = $this->messageProducer->sendTopic(json_encode(array('orgname'=> $org['name'],'old_projectname' => $obj->toArray()['name'],'new_projectname' => $data['name'])),'PROJECT_UPDATED');
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
        $org = $this->organizationService->getOrganization($obj->org_id);
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
        $result = $this->messageProducer->sendTopic(json_encode(array('orgname' => $org['name'] ,'projectname' => $data['name'])),'PROJECT_DELETED');
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
    public function getUserList($id) {
        if(!isset($id)) {
            return 0;
        }
        $queryString = "SELECT ox_user.id,ox_user.name FROM ox_user left join ox_user_project on ox_user.id = ox_user_project.user_id left join ox_project on ox_project.id = ox_user_project.project_id where ox_project.id = ".$id." AND ox_project.isdeleted!=1";
        $order = "order by ox_user.id";
        $resultSet = $this->executeQuerywithParams($queryString, null , null, $order)->toArray();
        return $resultSet?$resultSet:0;
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
        if(!isset($data['userid']) || empty($data['userid'])) {
            return 2;
        }
    	$userArray=json_decode($data['userid'],true);
        if($userArray){
            $userSingleArray= array_map('current', $userArray);
            //Check if project id exists
            $queryString = "select id,name,org_id from ox_project";
            $order = "order by ox_project.id";
            $where = "where ox_project.isdeleted!=1";
            $resultSet_temp = $this->executeQuerywithParams($queryString, $where, null, $order)->toArray();
            $resultSet=array_map('current', $resultSet_temp);
            //Check if user id exists
            $query = "select id,username from ox_user";
            $order = "order by ox_user.id";
            $resultSet_User_temp = $this->executeQuerywithParams($query, null, null, $order)->toArray();
            $resultSet_User=array_map('current', $resultSet_User_temp);

            $org = $this->organizationService->getOrganization($resultSet_temp[0]['org_id']);

            if((in_array($project_id, $resultSet))&&(count(array_intersect($userSingleArray, $resultSet_User))==count($userSingleArray))) {
                $sql = $this->getSqlObject();
                $delete = $sql->delete('ox_user_project');
                $delete->where(['project_id'=>$project_id]);
                $result = $this->executeUpdate($delete);
            	$storeData = array();
                if($userArray){
                    foreach ($userArray as $key => $value) {
                        $storeData[] = array('project_id'=>$project_id,'user_id'=>$value['id']);
                    }
                    $userId = AuthContext::get(AuthConstants::USER_ID);
                    $queryString =$this->multiInsertOrUpdate('ox_user_project',$storeData,array());
                    $this->messageProducer->sendTopic(json_encode(array('username' => $resultSet_User_temp[0]['username'],'orgname' => $org['name'] ,'projectname' => $resultSet_temp[0]['name'] )),'USERTOPROJECT_ADDEED');
                }
            } else {
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