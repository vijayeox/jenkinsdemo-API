<?php
namespace Group\Service;

use Oxzion\Service\AbstractService;
use Group\Model\GroupTable;
use Group\Model\Group;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\ValidationException;
use Zend\Db\Sql\Expression;
use Exception;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Service\OrganizationService;
use Zend\Log\Logger;
use Oxzion\Utils\FileUtils;
use Ramsey\Uuid\Uuid;
use Oxzion\Utils\FilterUtils;
use Oxzion\AccessDeniedException;
use Oxzion\Security\SecurityManager;




class GroupService extends AbstractService {

    private $table;
    private $organizationService;
    protected $logger;
    static $fieldName = array('name' => 'ox_user.name','id' => 'ox_user.id');


    public function __construct($config, $dbAdapter, GroupTable $table,$organizationService, Logger $log) {
        parent::__construct($config, $dbAdapter);
        parent::initLogger(__DIR__ . '/../../../../logs/group.log');
        $this->table = $table;
        $this->messageProducer = MessageProducer::getInstance();
        $this->organizationService = $organizationService;
        $this->logger = $log;
    }

    public function setMessageProducer($messageProducer)
    {
		$this->messageProducer = $messageProducer;
    }

    public function getGroupsforUser($userId,$data) {
        if(isset($data['org_id'])){
            if(!SecurityManager::isGranted('MANAGE_ORGANIZATION_WRITE') && 
                ($data['org_id'] != AuthContext::get(AuthConstants::ORG_UUID))) {
                throw new AccessDeniedException("You do not have permissions to get group users");
            }
        }

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
    public function getGroupByUuid($id,$data)
    {
        if(isset($data['org_id'])){
            if(!SecurityManager::isGranted('MANAGE_ORGANIZATION_WRITE') && 
                ($data['org_id'] != AuthContext::get(AuthConstants::ORG_UUID))) {
                throw new AccessDeniedException("You do not have permissions to get the group");
            }
        }
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
        if(isset($data['org_id'])){
            if(SecurityManager::isGranted('MANAGE_ORGANIZATION_WRITE')){
                $data['org_id'] = $this->organizationService->getOrganizationIdByUuid($data['org_id']);
            }else{
                unset($data['org_id']);
            }
        }
        if(!isset($data['org_id'])){
            $data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
        }
        $form = new Group();
        $data['uuid'] = Uuid::uuid4()->toString();   
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
            $this->logger->err(__CLASS__.$e->getMessage());
            $this->rollback();
            return 0;
        }
        $this->messageProducer->sendTopic(json_encode(array('groupname' => $data['name'], 'orgname'=> $org['name'])),'GROUP_ADDED');
        return $count;
    }



    public function getGroupLogoPath($orgId,$id,$ensureDir=false){

        $baseFolder = $this->config['UPLOAD_FOLDER'];
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
            $image = FileUtils::convetImageTypetoPNG($file);
            if($image){
                if(FileUtils::fileExists($destFile)){
                    imagepng($image, $destFile.'/logo.png');
                    $image = null;
                }
                else {
                    mkdir($destFile);
                    imagepng($image, $destFile.'/logo.png');
                    $image = null;
               }
            } 
        }
    }



     /**
     * GET Group Service
     * @method getGroup
     * @return array $data
     * <code> {
     *               id : integer,
     *               name : string,
     *               logo : string,
     *               status : String(Active|Inactive),
     *   } </code>
     * @return array Returns a JSON Response with Status Code and Created Group.
     */
    public function getGroupList($filterParams = null)
    {
        if(isset($filterParams['org_id'])){
            if(!SecurityManager::isGranted('MANAGE_ORGANIZATION_WRITE') && 
                ($filterParams['org_id'] != AuthContext::get(AuthConstants::ORG_UUID))) {
                throw new AccessDeniedException("You do not have permissions to get the group list");
            }
        }

        $where = "";
        $pageSize = 20;
        $offset = 0;
        $sort = "name";

        $cntQuery ="SELECT count(id) FROM `ox_group`";

        if(count($filterParams) > 0 || sizeof($filterParams) > 0){
                $filterArray = json_decode($filterParams['filter'],true); 
                if(isset($filterArray[0]['filter'])){
                   $filterlogic = isset($filterArray[0]['filter']['logic']) ? $filterArray[0]['filter']['logic'] : "AND" ;
                   $filterList = $filterArray[0]['filter']['filters'];
                   $where = " WHERE ".FilterUtils::filterArray($filterList,$filterlogic);
                }
                if(isset($filterArray[0]['sort']) && count($filterArray[0]['sort']) > 0){
                    $sort = $filterArray[0]['sort'];
                    $sort = FilterUtils::sortArray($sort);
                }
                
                $pageSize = $filterArray[0]['take'];
                $offset = $filterArray[0]['skip'];            
            }

            $where .= strlen($where) > 0 ? " AND status = 'Active'" : " WHERE status = 'Active'";
            
            $sort = " ORDER BY ".$sort;
            $limit = " LIMIT ".$pageSize." offset ".$offset;
            $resultSet = $this->executeQuerywithParams($cntQuery.$where);
            $count=$resultSet->toArray()[0]['count(id)'];
            $query ="SELECT * FROM `ox_group`".$where." ".$sort." ".$limit;
            $resultSet = $this->executeQuerywithParams($query);
            return array('data' => $resultSet->toArray(), 
                     'total' => $count);
    }

    public function updateGroup ($id, &$data,$files = null) {
        if(isset($data['org_id'])){
            if(!SecurityManager::isGranted('MANAGE_ORGANIZATION_WRITE') && 
                ($data['org_id'] != AuthContext::get(AuthConstants::ORG_UUID))) {

                throw new AccessDeniedException("You do not have permissions to edit the group");
            }
            else{
                $data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
            }
        }
        $obj = $this->table->getByUuid($id,array());
        if (is_null($obj)) {
            return 2;
        }
        $org = $this->organizationService->getOrganization($obj->org_id);
        $form = new Group();
        $data = array_merge($obj->toArray(), $data);
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

    public function deleteGroup($id,$data) {
        if(isset($data['org_id'])){
            if(!SecurityManager::isGranted('MANAGE_ORGANIZATION_WRITE') && 
                ($data['org_id'] != AuthContext::get(AuthConstants::ORG_UUID))) {
                throw new AccessDeniedException("You do not have permissions to delete the group");
            }
        }
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

    public function getUserList($id,$filterParams = null) {
        if(isset($filterParams['org_id'])){
            if(!SecurityManager::isGranted('MANAGE_ORGANIZATION_WRITE') && 
                ($filterParams['org_id'] != AuthContext::get(AuthConstants::ORG_UUID))) {
                throw new AccessDeniedException("You do not have permissions to get the group users list");
            }
        }

        if(!isset($id)) {
            return 0;
        }

        $pageSize = 20;
        $offset = 0;
        $where = "";
        $sort = "ox_user.name";


        $query = "SELECT ox_user.id,ox_user.name";
        $from = " FROM ox_user left join ox_user_group on ox_user.id = ox_user_group.avatar_id left join ox_group on ox_group.id = ox_user_group.group_id";
    
        $cntQuery ="SELECT count(ox_user.id)".$from;

        if(count($filterParams) > 0 || sizeof($filterParams) > 0){
                $filterArray = json_decode($filterParams['filter'],true); 
                if(isset($filterArray[0]['filter'])){
                   $filterlogic = isset($filterArray[0]['filter']['logic']) ? $filterArray[0]['filter']['logic'] : "AND" ;
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

            $where .= strlen($where) > 0 ? " AND ox_group.uuid = '".$id."'" : " WHERE ox_group.uuid = '".$id."'";

            $sort = " ORDER BY ".$sort;
            $limit = " LIMIT ".$pageSize." offset ".$offset;
            $resultSet = $this->executeQuerywithParams($cntQuery.$where);
            $count=$resultSet->toArray()[0]['count(ox_user.id)'];
            $query =$query." ".$from." ".$where." ".$sort." ".$limit;
            $resultSet = $this->executeQuerywithParams($query);
            return array('data' => $resultSet->toArray(), 
                     'total' => $count);
    
    }

    public function saveUser($id,$data) {

        if(isset($data['org_id'])){
            if(!SecurityManager::isGranted('MANAGE_ORGANIZATION_WRITE') && 
                ($data['org_id'] != AuthContext::get(AuthConstants::ORG_UUID))) {
                throw new AccessDeniedException("You do not have permissions to add users to group");
            }
        }

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