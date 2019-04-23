<?php
namespace Group\Service;

use Bos\Service\AbstractService;
use Group\Model\GroupTable;
use Group\Model\Group;
use Bos\Auth\AuthContext;
use Bos\Auth\AuthConstants;
use Bos\ValidationException;
use Zend\Db\Sql\Expression;
use Exception;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Service\OrganizationService;
use Zend\Log\Logger;

class GroupService extends AbstractService {

    private $table;
    private $organizationService;
    protected $logger;


    public function __construct($config, $dbAdapter, GroupTable $table,$organizationService) {
        parent::__construct($config, $dbAdapter);
        parent::initLogger(__DIR__ . '/../../../../logs/group.log');
        $this->table = $table;
        $this->messageProducer = MessageProducer::getInstance();
        $this->organizationService = $organizationService;
    }

    public function setMessageProducer($messageProducer)
    {
		$this->messageProducer = $messageProducer;
    }

    public function getGroupsforUser($userId) {
    $queryString = "select usr_grp.id, usr_grp.avatar_id, usr_grp.group_id, grp.name, grp.manager_id, grp.parent_id from ox_user_group as usr_grp 
        left join ox_group as grp on usr_grp.group_id = grp.id";
    $where = "where avatar_id = " . $userId;
    $order = "order by grp.name";
    $resultSet = $this->executeQuerywithParams($queryString, $where, null, $order);
    return $resultSet->toArray();
    }

    public function createGroup(&$data) {
        $form = new Group();   
        $org = $this->organizationService->getOrganization($data['org_id']);
        $data['name'] = $data['name'] ? $data['name'] : uniqid();
        $data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
        $data['created_id'] = AuthContext::get(AuthConstants::USER_ID);
        $data['modified_id'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_created'] = date('Y-m-d H:i:s');
        $data['date_modified'] = date('Y-m-d H:i:s');
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
        $this->messageProducer->sendTopic(json_encode(array('groupname' => $data['name'], 'orgname'=> $org['name'])),'GROUP_ADDED');
        return $count;
    }

    public function updateGroup ($id, &$data) {
        $obj = $this->table->get($id,array());
        $org = $this->organizationService->getOrganization($obj->org_id);
        if (is_null($obj)) {
            return 0;
        }
        $form = new Group();
        $data = array_merge($obj->toArray(), $data);
        $data['id'] = $id;
        $data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
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
        $this->messageProducer->sendTopic(json_encode(array('old_groupname' => $obj->name, 'orgname'=> $org['name'], 'new_groupname'=>$data['name'])),'GROUP_UPDATED');
        return $count;
    }

    public function deleteGroup($id) {
        $obj = $this->table->get($id,array());
        if (is_null($obj)) {
            return 0;
        }

        $org = $this->organizationService->getOrganization($obj->org_id);
        $count = 0;
        try {
            $count = $this->table->delete($id);
            if($count == 0) {
                return 0;
            }
        } catch(Exception $e) {
            $this->rollback();
        }
        $this->messageProducer->sendTopic(json_encode(array('groupname' => $obj->name , 'orgname'=> $org['name'] )),'GROUP_DELETED');
        return $count;
    }

    public function getUserList($id,$q,$f,$pg,$psz,$sort) {
    $query = "SELECT ox_user.id,ox_user.name";
    $from = " FROM ox_user left join ox_user_group on ox_user.id = ox_user_group.avatar_id left join ox_group on ox_group.id = ox_user_group.group_id";
    
     $cntQuery ="SELECT count(ox_user.id)".$from;
            if(empty($q)){
                $where = " WHERE ox_group.id = ".$id;
            }
            else{
                $where = " WHERE ox_group.id = ".$id." AND ox_user.".$f." like '".$q."%'";   
            }
            $offset = ($pg - 1) * $psz;
            $sort = " ORDER BY ".$sort;
            $limit = " LIMIT ".$psz." offset ".$offset;
            $resultSet = $this->executeQuerywithParams($cntQuery.$where);
            $count=$resultSet->toArray()[0]['count(ox_user.id)'];
            $query =$query." ".$from." ".$where." ".$sort." ".$limit;
            $resultSet = $this->executeQuerywithParams($query);
            return array('data' => $resultSet->toArray(), 
                     'pagination' => array('page' => $pg,
                                            'noOfPages' => ceil($count/$psz),
                                            'pageSize' => $psz));
    
    }

    public function saveUser($id,$data) {
        $obj = $this->table->get($id,array());
        if (is_null($obj)) {
            $this->logger->log(Logger::INFO, "Invalid group id - $id");
            return 0;
        }
        
        $org = $this->organizationService->getOrganization($obj->org_id);
        if ($org['id'] != AuthContext::get(AuthConstants::ORG_ID)) {
            $this->logger->log(Logger::WARN, "Group $id does not belong to logged in Organization");
            return 0;
        }
        
        if(!isset($data['userid']) || empty($data['userid'])) {
            return 2;
        }

        $userArray=json_decode($data['userid'],true);

        if($userArray){
            $userSingleArray= array_unique(array_map('current', $userArray));
            $queryString = "SELECT ox_user.id, ox_user.username FROM ox_user_group " . 
                           "inner join ox_user on ox_user.id = ox_user_group.avatar_id ".
                           "where ox_user_group.group_id = ".$id.
                           " and ox_user_group.avatar_id not in (".implode(',', $userSingleArray).")";
            $deletedUser = $this->executeQuerywithParams($queryString)->toArray();
            $query = "SELECT u.id, u.username, ug.avatar_id FROM ox_user_group ug ".
                     "right join ox_user u on u.id = ug.avatar_id and ug.group_id = ".$id.
                     " where u.id in (".implode(',', $userSingleArray).") and ug.avatar_id is null";
            $insertedUser = $this->executeQuerywithParams($query)->toArray();
            $this->beginTransaction();
            try{
                $delete="Delete from ox_user_group where ox_user_group.group_id =".$id. " OR (ox_user_group.group_id !=" .$id. " AND ox_user_group.avatar_id in (".implode(',', $userSingleArray)."))"; 
                $result = $this->runGenericQuery($delete);

                $query ="Insert into ox_user_group(avatar_id,group_id) (Select ox_user.id, ".$id." AS group_id from ox_user_group right join  ox_user on ox_user_group.avatar_id = ox_user.id where ox_user.id in (".implode(',', $userSingleArray)."))";
                $resultInsert = $this->runGenericQuery($query);
                if(count($resultInsert) != count($userSingleArray)){
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
                $this->messageProducer->sendTopic(json_encode(array('groupname' => $obj->name , 'orgname'=> $org['name'], 'username' => $value['username'] )),'USERTOGROUP_DELETED');
            }
            foreach($insertedUser as $key => $value){
                $this->messageProducer->sendTopic(json_encode(array('groupname' => $obj->name , 'orgname'=> $org['name'], 'username' => $value['username'] )),'USERTOGROUP_ADDED');
            }
            return 1;
        }
        return 0;
    }
}