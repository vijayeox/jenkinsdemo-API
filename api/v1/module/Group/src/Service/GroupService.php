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
use Oxzion\Utils\FileUtils;
use Ramsey\Uuid\Uuid;


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


    /**
     * GET Group Service
     * @method getGroup
     * @param $id ID of Group to GET
     * @return array $data
     * <code> {
     *               id : integer,
     *               name : string,
     *               logo : string,
     *               status : String(Active|Inactive),
     *   } </code>
     * @return array Returns a JSON Response with Status Code and Created Group.
     */
    public function getGroupByUuid($id)
    {
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_group')
            ->columns(array("*"))
            ->where(array('ox_group.uuid' => $id, 'status' => "Active"));
        $response = $this->executeQuery($select)->toArray();
        
        if (count($response) == 0) {
            return 0;
        }

        return $response[0];
    }


    public function createGroup(&$data,$files) {
        $form = new Group();
        $data['uuid'] = Uuid::uuid4();   
        $data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
        $data['created_id'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_created'] = date('Y-m-d H:i:s');
        $org = $this->organizationService->getOrganization($data['org_id']); 
        $form->exchangeArray($data);
        $form->validate();
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->save($form);
            $this->uploadGroupLogo($org['uuid'],$data['uuid'],$files);
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



    public function getGroupLogoPath($orgId,$id,$ensureDir=false){

        $baseFolder = $this->config['DATA_FOLDER'];
        //TODO : Replace the User_ID with USER uuid
        $folder = $baseFolder."organization/".$orgId."/group/";
        if(isset($id)){
            $folder = $folder.$id."/";
        }

        if($ensureDir && !file_exists($folder)){
            FileUtils::createDirectory($folder);
        }
        return $folder;
    }


    

    /**
     * createUpload
     *
     * Upload files from Front End and store it in temp Folder
     *
     *  @param files Array of files to upload
     *  @return JSON array of filenames
    */
    public function uploadGroupLogo($orgId,$id,$file){
        
        if(isset($file)){

            $destFile = $this->getGroupLogoPath($orgId,$id,true);
            $file['name'] = 'logo.png';
            FileUtils::storeFile($file,$destFile); 
            
        }
    }


    public function updateGroup ($id, &$data,$files = null) {
        $obj = $this->table->getByUuid($id,array());
        if (is_null($obj)) {
            return 2;
        }
        $org = $this->organizationService->getOrganization($obj->org_id);
        $form = new Group();
        $data = array_merge($obj->toArray(), $data);
        $data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
        $data['modified_id'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_modified'] = date('Y-m-d H:i:s');
        $form->exchangeArray($data);
        $form->validate();
        $count = 0;
        try {
            $count = $this->table->save($form);
             if(isset($files)){
                $this->uploadGroupLogo($org['uuid'],$id,$files);
            }
            if($count == 0) {
                $this->rollback();
                return 1;
            }
        } catch(Exception $e) {
            $this->rollback();
            return 0;
        }
        $this->messageProducer->sendTopic(json_encode(array('old_groupname' => $obj->name, 'orgname'=> $org['name'], 'new_groupname'=>$data['name'])),'GROUP_UPDATED');
        return $count;
    }

    public function deleteGroup($id) {
        $obj = $this->table->getByUuid($id,array());
        if (is_null($obj)) {
            return 0;
        }

        $org = $this->organizationService->getOrganization($obj->org_id);
        $count = 0;
        try {
            $count = $this->table->deleteByUuid($id);
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
        $obj = $this->table->getByUuid($id,array());
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
        $group_id = $obj->id;

        if($userArray){
            $userSingleArray= array_unique(array_map('current', $userArray));
            $queryString = "SELECT ox_user.id, ox_user.username FROM ox_user_group " . 
                           "inner join ox_user on ox_user.id = ox_user_group.avatar_id ".
                           "where ox_user_group.id = ".$group_id.
                           " and ox_user_group.avatar_id not in (".implode(',', $userSingleArray).")";
            $deletedUser = $this->executeQuerywithParams($queryString)->toArray();
            $query = "select avatar_id, group_id from ox_user_group";
            $userGroup = $this->executeQuerywithParams($query)->toArray();
            $query = "SELECT u.id, u.username FROM ox_user_group ug ".
                     "right join ox_user u on u.id = ug.avatar_id and ug.group_id = ".$group_id.
                     " where u.id in (".implode(',', $userSingleArray).") and ug.avatar_id is null";
            $insertedUser = $this->executeQuerywithParams($query)->toArray();
            $this->beginTransaction();
            try{
                $delete="DELETE FROM ox_user_group where avatar_id not in (".implode(',', $userSingleArray).") and group_id = ".$group_id; 
                $result = $this->executeQuerywithParams($delete);
                $query ="Insert into ox_user_group(avatar_id,group_id) SELECT ou.id,".$group_id." from ox_user as ou LEFT OUTER JOIN ox_user_group as our on ou.id = our.avatar_id AND our.group_id = ".$group_id." WHERE ou.id in (".implode(',', $userSingleArray).") AND our.group_id  is null";
                
                $resultInsert = $this->executeQuerywithParams($query);
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