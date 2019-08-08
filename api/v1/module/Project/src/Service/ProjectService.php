<?php
namespace Project\Service;

use Oxzion\Service\AbstractService;
use Project\Model\ProjectTable;
use Project\Model\Project;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\ValidationException;
use Zend\Db\Sql\Expression;
use Exception;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Service\OrganizationService;
use Doctrine\Migrations\AbstractMigration;
use Ramsey\Uuid\Uuid;
use Oxzion\Utils\FilterUtils;
use Oxzion\AccessDeniedException;
use Oxzion\Security\SecurityManager;
use Oxzion\ServiceException;



class ProjectService extends AbstractService {

    private $table;
    private $organizationService;
    static $fieldName = array('name' => 'ox_user.name','id' => 'ox_user.id');
    static $projectFields = array("name" => "p.name", "description" => "p.description");

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



    public function getProjectList($filterParams = null){

        if(isset($filterParams['org_id'])){
            if(!SecurityManager::isGranted('MANAGE_ORGANIZATION_WRITE') &&
                ($filterParams['org_id'] != AuthContext::get(AuthConstants::ORG_UUID))) {
                throw new AccessDeniedException("You do not have permissions to get the project list");
            }
        }

        $pageSize = 20;
        $offset = 0;
        $where = "";
        $sort = "name";

        $cntQuery ="SELECT count(p.id) as total FROM `ox_project` as p ";

        if(count($filterParams) > 0 || sizeof($filterParams) > 0){
            $filterArray = json_decode($filterParams['filter'],true);
            if(isset($filterArray[0]['filter'])){
               $filterlogic = isset($filterArray[0]['filter']['logic']) ? $filterArray[0]['filter']['logic'] : "AND" ;
               $filterList = $filterArray[0]['filter']['filters'];
               $where = " WHERE ".FilterUtils::filterArray($filterList,$filterlogic, self::$projectFields);
            }
            if(isset($filterArray[0]['sort']) && count($filterArray[0]['sort']) > 0){
                $sort = $filterArray[0]['sort'];
                $sort = FilterUtils::sortArray($sort);
            }
            $pageSize = $filterArray[0]['take'];
            $offset = $filterArray[0]['skip'];
        }


        $where .= strlen($where) > 0 ? " AND p.isdeleted!=1" : "WHERE p.isdeleted!=1";

        $sort = " ORDER BY p.".$sort;
        $limit = " LIMIT ".$pageSize." offset ".$offset;
        $resultSet = $this->executeQuerywithParams($cntQuery.$where);

        $count=$resultSet->toArray()[0]['total'];
        $query ="SELECT p.uuid, p.name, u.uuid as manager_id, p.description FROM `ox_project` as p inner join ox_user as u on u.id = p.manager_id ".$where." ".$sort." ".$limit;
        $resultSet = $this->executeQuerywithParams($query);
        $resultSet=$resultSet->toArray();

        return array('data' => $resultSet,
                 'total' => $count);
    }


     /**
     * GET Project Service
     * @method getProject
     * @param $id UUID of Project to GET
     * @return array $data
     * <code> {
     *               id : integer,
     *               name : string,
     *   } </code>
     * @return array Returns a JSON Response with Status Code and Created Project.
     */
    public function getProjectByUuid($id)
    {
        if(isset($data['org_id'])){
            if(!SecurityManager::isGranted('MANAGE_ORGANIZATION_WRITE') &&
                ($data['org_id'] != AuthContext::get(AuthConstants::ORG_UUID))) {
                throw new AccessDeniedException("You do not have permissions to get the project");
            }
        }
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_project')
            ->columns(array("*"))
            ->where(array('ox_project.uuid' => $id, 'isdeleted' => 0));
        $response = $this->executeQuery($select)->toArray();

        if (count($response) == 0) {
            return 0;
        }

        return $response[0];
    }

    public function createProject(&$data,$params = null) {
        if(isset($params['orgId'])){
            if(!SecurityManager::isGranted('MANAGE_ORGANIZATION_WRITE') && 
                ($params['orgId'] != AuthContext::get(AuthConstants::ORG_UUID))) {
                throw new AccessDeniedException("You do not have permissions create project");
            }else{
                $data['org_id'] = $this->getIdFromUuid('ox_organization',$params['orgId']);    
            }
        }
        else{
            $data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
        }
        try {
            $data['name'] = isset($data['name']) ? $data['name'] : NULL;
       
            $select = "SELECT count(id),name,uuid,isdeleted from ox_project where name = '".$data['name']."' AND org_id = ".$data['org_id'];
         
            $result = $this->executeQuerywithParams($select)->toArray();

            if($result[0]['count(id)'] > 0){
                if($data['name'] == $result[0]['name'] && $result[0]['isdeleted'] == 0){
                    throw new ServiceException("Project already exists","project.exists");
                }
                else if($result[0]['isdeleted'] == 1){
                    $data['reactivate'] = isset($data['reactivate']) ? $data['reactivate'] : NULL;
                    if($data['reactivate'] == 1){
                        $data['isdeleted'] = 0;
                        $count = $this->updateProject($result[0]['uuid'],$data,$params['orgId']);
                        return;
                    }else{
                        throw new ServiceException("Project already exists would you like to reactivate?","project.already.exists");
                    }
                }
            }
     

        $sql = $this->getSqlObject();
        $form = new Project();
    //Additional fields that are needed for the create
        $data['uuid'] = Uuid::uuid4()->toString();
        $data['created_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_created'] = date('Y-m-d H:i:s');
        $data['date_modified'] = date('Y-m-d H:i:s');
        $data['isdeleted'] = false;
        $select ="SELECT id from ox_user where uuid = '".$data['manager_id']."'";
        $result = $this->executeQueryWithParams($select)->toArray();
        $data['manager_id']=$result[0]["id"];
        $org = $this->organizationService->getOrganization($data['org_id']);
        $form->exchangeArray($data);
        $form->validate();
        $this->beginTransaction();
        $count = 0;
            $count = $this->table->save($form);
            if($count == 0) {
                $this->rollback();
                throw new ServiceException("Failed to create a new entity","failed.project.create");
            }
            $id = $this->table->getLastInsertValue();
            $data['id'] = $id;
            $this->commit();
        } catch(Exception $e) {
            $this->rollback();
            throw $e;
        }
        $insert = $sql->insert('ox_user_project');
        $insert_data = array('user_id' => $data['manager_id'], 'project_id' => $data['id']);
        $insert->values($insert_data);
        $result = $this->executeUpdate($insert);
        if(isset($data['name']))
            $this->messageProducer->sendTopic(json_encode(array('orgname'=>  $org['name'],'projectname' => $data['name'],'description' => $data['description'],'uuid' => $data['uuid'])),'PROJECT_ADDED');
        return $count;
    }

    public function updateProject ($id, $data,$orgId = null) {
        if(isset($orgId)){
            if(!SecurityManager::isGranted('MANAGE_ORGANIZATION_WRITE') && 
                ($orgId != AuthContext::get(AuthConstants::ORG_UUID))) {
                throw new AccessDeniedException("You do not have permissions to edit the project");
            }else{
                $data['org_id'] = $this->getIdFromUuid('ox_organization',$orgId);    
            }
        }
        else{
            $data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
        }
        $obj = $this->table->getByUuid($id,array());
        if (is_null($obj)) {
            throw new ServiceException("Updating non-existent Group","non.existent.group");
        }
        $form = new Project();
        if(isset($data['manager_id'])){
            $data['manager_id']=$this->getIdFromUuid('ox_user', $data['manager_id']);
        }
        $data = array_merge($obj->toArray(), $data); //Merging the data from the db for the ID
        $data['modified_id'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_modified'] = date('Y-m-d H:i:s');
        $form->exchangeArray($data);
        $form->validate();
        $count = 0;
        $org = $this->organizationService->getOrganization($obj->org_id);
        try {

            $count = $this->table->save($form);
            if($count === 1) {
                $select = "SELECT count(id) as users from ox_user_project where user_id =".$data['manager_id']." AND project_id = (SELECT id from ox_project where uuid = '".$id."')";
                $query=$this->executeQuerywithParams($select)->toArray();
                if($query[0]['users'] === '0'){
                    $insert = "INSERT INTO ox_user_project (`user_id`,`project_id`) VALUES (".$data['manager_id'].",(SELECT id from ox_project where uuid = '".$id."'))";
                    $query1 = $this->executeQuerywithParams($insert);
                }
            } else {
                throw new ServiceException("Failed to Update","failed.update.project");
            }

        } catch(Exception $e) {
            $this->rollback();
            throw $e;
        }
        $this->messageProducer->sendTopic(json_encode(array('orgname'=> $org['name'],'old_projectname' => $obj->name,'new_projectname' => $data['name'],'description' => $data['description'],'uuid' => $data['uuid'])),'PROJECT_UPDATED');
        return $count;
    }

    public function deleteProject($id,$data) {
        if(isset($id['orgId'])){
            if(!SecurityManager::isGranted('MANAGE_ORGANIZATION_WRITE') && 
                ($id['orgId'] != AuthContext::get(AuthConstants::ORG_UUID))) {
                throw new AccessDeniedException("You do not have permissions to delete the project");
            }
        }
        $obj = $this->table->getByUuid($id['projectUuid'],array());
        if (is_null($obj)) {
            throw new ServiceException("Entity not found","project.not.found");
        }
        $form = new Project();
        $data = $obj->toArray();
        $data['uuid'] = $id['projectUuid'];
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
                throw new ServiceException("Failed to Delete","failed.project.delete");
            }
        } catch(Exception $e) {
            $this->rollback();
            throw $e;
        }
        $this->messageProducer->sendTopic(json_encode(array('orgname' => $org['name'] ,'projectname' => $data['name'],'uuid' => $data['uuid'])),'PROJECT_DELETED');
        return $count;
    }

    public function getProjectsOfUser($data) {
        if(isset($data['org_id'])){
            if(!SecurityManager::isGranted('MANAGE_ORGANIZATION_WRITE') &&
                ($data['org_id'] != AuthContext::get(AuthConstants::ORG_UUID))) {
                throw new AccessDeniedException("You do not have permissions to get the users of project");
            }
        }
        $userId = AuthContext::get(AuthConstants::USER_ID);
        $queryString = "select * from ox_project
            left join ox_user_project on ox_user_project.project_id = ox_project.id";
        $where = "where ox_user_project.user_id = " . $userId." AND ox_project.org_id=".AuthContext::get(AuthConstants::ORG_ID)." AND ox_project.isdeleted!=1";
        $order = "order by ox_project.id";
        $resultSet = $this->executeQuerywithParams($queryString, $where, null, $order);
        return $resultSet->toArray();
    }

    public function getProjectsOfUserById($userId) {
            $queryString = "select ox_project.* , ox_user.username as manager_username, ox_user.uuid as manager_uuid from ox_project
                inner join ox_user_project on ox_user_project.project_id = ox_project.id inner join ox_user on ox_project.manager_id = ox_user.id";
            $where = "where ox_user_project.user_id = " . $userId." AND ox_project.org_id=".AuthContext::get(AuthConstants::ORG_ID)." AND ox_project.isdeleted!=1";
            $order = "order by ox_project.id";
            $resultSet = $this->executeQuerywithParams($queryString, $where, null, $order);
            return $resultSet->toArray();
    }

    public function getUserList($id,$filterParams = null) {

        if(isset($filterParams['org_id'])){
            if(!SecurityManager::isGranted('MANAGE_ORGANIZATION_WRITE') &&
                ($filterParams['org_id'] != AuthContext::get(AuthConstants::ORG_UUID))) {
                throw new AccessDeniedException("You do not have permissions to get the userlist of project");
            }
        }

        if(!isset($id)) {
            return 0;
        }

        $pageSize = 20;
        $offset = 0;
        $where = "";
        $sort = "ox_user.name";


         $query = "SELECT ox_user.uuid,ox_user.name,
                                case when (ox_project.manager_id = ox_user.id) 
                                    then 1
                                end as is_manager";
         $from = " FROM ox_user left join ox_user_project on ox_user.id = ox_user_project.user_id left join ox_project on ox_project.id = ox_user_project.project_id";

         $cntQuery ="SELECT count(ox_user.id)".$from;

         if(count($filterParams) > 0 || sizeof($filterParams) > 0){
                $filterArray = json_decode($filterParams['filter'],true);
                if(isset($filterArray[0]['filter'])){
                   $filterlogic = isset($filterArray[0]['filter']['logic']) ? $filterArray[0]['filter']['logic'] : " AND ";
                   $filterList = $filterArray[0]['filter']['filters'];
                   $where = " WHERE ".FilterUtils::filterArray($filterList,$filterlogic,self::$fieldName);
                }
                if(isset($filterArray[0]['sort']) && count($filterArray[0]['sort']) > 0){
                    $sort = $filterArray[0]['sort'];
                    $sort = FilterUtils::sortArray($sort,self::$fieldName);
                }
                $pageSize = $filterArray[0]['take'];
                $offset = $filterArray[0]['skip'];
            }



            $where .= strlen($where) > 0 ? " AND ox_project.uuid = '".$id."' AND ox_project.isdeleted!=1" : " WHERE ox_project.uuid = '".$id."' AND ox_project.isdeleted!=1";


            $sort = " ORDER BY ".$sort;
            $limit = " LIMIT ".$pageSize." offset ".$offset;
            $resultSet = $this->executeQuerywithParams($cntQuery.$where);
            $count=$resultSet->toArray()[0]['count(ox_user.id)'];
            $query =$query." ".$from." ".$where." ".$sort." ".$limit;
            $resultSet = $this->executeQuerywithParams($query);
            return array('data' => $resultSet->toArray(),
                     'total' => $count);

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

    public function saveUser($id,$data) {

        if(isset($data['org_id'])){
            if(!SecurityManager::isGranted('MANAGE_ORGANIZATION_WRITE') &&
                ($data['org_id'] != AuthContext::get(AuthConstants::ORG_UUID))) {
                throw new AccessDeniedException("You do not have permissions to add users to project");
            }
        }

        $obj = $this->table->getByUuid($id,array());
        $org = $this->organizationService->getOrganization($obj->org_id);
        if(!isset($data['userid']) || empty($data['userid'])) {
            return 2;
        }
    	
        $userArray = $this->organizationService->getUserIdList($data['userid']);

        $projectId = $obj->id;

        if($userArray){
            $userSingleArray= array_map('current', $userArray);
            $queryString = "SELECT ox_user.id,ox_user.uuid, ox_user.username FROM ox_user_project " .
                            "inner join ox_user on ox_user.id = ox_user_project.user_id ".
                            "where ox_user_project.project_id = ".$projectId.
                            " and ox_user_project.user_id not in (".implode(',', $userSingleArray).")";
            $deletedUser = $this->executeQuerywithParams($queryString)->toArray();
            $query = "SELECT u.id,u.uuid, u.username, up.user_id, u.firstname, u.lastname, u.email , u.timezone FROM ox_user_project up ".
                     "right join ox_user u on u.id = up.user_id and up.project_id = ".$projectId.
                     " where u.id in (".implode(',', $userSingleArray).") and up.user_id is null";
            $insertedUser = $this->executeQuerywithParams($query)->toArray();
            $this->beginTransaction();
            try{
                $delete = $this->getSqlObject()
                ->delete('ox_user_project')
                ->where(['project_id' => $projectId]);
                $result = $this->executeQueryString($delete);
                $query ="Insert into ox_user_project(user_id,project_id) (Select ox_user.id, ".$projectId." AS project_id from ox_user where ox_user.id in (".implode(',', $userSingleArray)."))";
                $resultInsert = $this->runGenericQuery($query);
                if(count($resultInsert) != count($userArray)){
                    $this->rollback();
                    return 0;
                }
                $this->commit();
            }
            catch(Exception $e){
                $this->rollback();
                throw $e;
            }
            foreach($deletedUser as $key => $value){
                $this->messageProducer->sendTopic(json_encode(array('orgname' => $org['name'] ,'projectname' => $obj->name,'username' => $value['username'])),'USERTOPROJECT_DELETED');
                $test = $this->messageProducer->sendTopic(json_encode(array('username' => $value['username'],'projectUuid' => $obj->uuid)),'DELETION_USERFROMPROJECT');

            }
            foreach($insertedUser as $key => $value){
                $this->messageProducer->sendTopic(json_encode(array('orgname' => $org['name'] ,'projectname' => $obj->name,'username' => $value['username'])),'USERTOPROJECT_ADDED');
                $test = $this->messageProducer->sendTopic(json_encode(array('username' => $value['username'],'firstname' => $value['firstname'],'lastname' => $value['lastname'],'email' => $value['email'], 'timezone' => $value['timezone'],'projectUuid' => $obj->uuid)),'ADDITION_USERTOPROJECT');
            }
            return 1;
        }
        return 0;
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