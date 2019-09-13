<?php
namespace Oxzion\Service;

use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\ValidationException;
use Oxzion\Service\AbstractService;
use Oxzion\Model\Organization;
use Oxzion\Model\OrganizationTable;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Utils\FileUtils;
use Ramsey\Uuid\Uuid;
use Oxzion\Utils\FilterUtils;
use Oxzion\Security\SecurityManager;
use Oxzion\AccessDeniedException;



class OrganizationService extends AbstractService
{

    protected $table;
    private $userService;
    private $roleService;
    protected $modelClass;
    private $messageProducer;
    private $privilegeService;
    static $fieldName = array('name' => 'ox_user.name','id' => 'ox_user.id');

    
    public function setMessageProducer($messageProducer)
    {
        $this->messageProducer = $messageProducer;
    }

    /**
     * @ignore __construct
     */
    public function __construct($config, $dbAdapter, OrganizationTable $table, UserService $userService, RoleService $roleService, PrivilegeService $privilegeService)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
        $this->userService = $userService;
        $this->roleService = $roleService;
        $this->modelClass = new Organization();
        $this->privilegeService = $privilegeService;
        $this->messageProducer = MessageProducer::getInstance();
    }

    /**
     * Create Organization Service
     * @method createOrganization
     * @param array $data Array of elements as shown
     * <code> {
     *               id : integer,
     *               name : string,
     *               logo : string,
     *               status : String(Active|Inactive),
     *   } </code>
     * @return array Returns a JSON Response with Status Code and Created Organization.
     */
    public function createOrganization(&$data,$files)
    {
        $data['uuid'] = Uuid::uuid4()->toString();  
        $data['contact'] = json_decode($data['contact'],true);    
        $data['created_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_created'] = date('Y-m-d H:i:s');
        $data['date_modified'] = date('Y-m-d H:i:s'); 
        $form = new Organization($data);
        $form->validate();
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->save($form);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
            $form->id = $this->table->getLastInsertValue();
            $data['preferences'] = json_decode($data['preferences'],true);
            $userid['id'] = $this->setupBasicOrg($form,$data['contact'],$data['preferences']);

            if(isset($userid['id'])){
                $update = "UPDATE `ox_organization` SET `contactid` = '".$userid['id']."' where uuid = '".$data['uuid']."'";
                $resultSet = $this->executeQueryWithParams($update);
            }else{
                return 0;
            }
            $this->uploadOrgLogo($data['uuid'],$files);
           
            $this->commit();
            $this->messageProducer->sendTopic(json_encode(array('orgname' => $form->name, 'status' => $form->status)),'ORGANIZATION_ADDED');
        } catch (Exception $e) {
            $this->rollback();
            return 0;
        }
        return $count;
    }




    public function getOrgLogoPath($id,$ensureDir=false){

        $baseFolder = $this->config['UPLOAD_FOLDER'];
        //TODO : Replace the User_ID with USER uuid
        $folder = $baseFolder."organization/";
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
    public function uploadOrgLogo($id,$file){
        
        if(isset($file)){

           $destFile = $this->getOrgLogoPath($id,true);
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



    private function setupBasicOrg(Organization $org,$contactPerson,$orgPreferences) {
        
         // adding basic roles
        $returnArray['roles'] = $this->roleService->createBasicRoles($org->id);

         // adding a user
        $returnArray['user'] = $this->userService->createAdminForOrg($org,$contactPerson,$orgPreferences);
       
        return $returnArray['user'];
    }

    /**
     * Update Organization API
     * @method updateOrganization
     * @param array $id ID of Organization to update
     * @param array $data
     * @return array Returns a JSON Response with Status Code and Created Organization.
     */
    public function updateOrganization($id, &$data,$files = null)
    {
        
        $obj = $this->table->getByUuid($id, array());
        if (is_null($obj)) {
            return 2;
        }
        if(isset($data['contactid'])){
            $data['contactid'] = $this->userService->getUserByUuid($data['contactid']);
        }
        $org = $obj->toArray();
        $form = new Organization();
        $changedArray = array_merge($obj->toArray(), $data);
        $changedArray['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $changedArray['date_modified'] = date('Y-m-d H:i:s');
        $form->exchangeArray($changedArray);
        $form->validate();
        $this->beginTransaction();
        $count = 0;

        try {
            $count = $this->table->save($form);
            if(isset($files)){
                $this->uploadOrgLogo($id,$files);
            }   


            $this->commit();
            if ($count == 0) {
                return 1;
            }
            
        }
         catch (Exception $e) {
            switch (get_class($e)) {
                case "Oxzion\ValidationException" :
                    $this->rollback();
                    throw $e;
                    break;
                default:
                    $this->rollback();
                    return 0;
                    break;
            }
        }
        if($obj->name != $data['name']){
             $this->messageProducer->sendTopic(json_encode(array('new_orgname' => $data['name'], 'old_orgname' => $obj->name,'status' => $form->status)),'ORGANIZATION_UPDATED');
        }
        if($form->status == 'InActive'){
            $this->messageProducer->sendTopic(json_encode(array('orgname' => $obj->name,'status' => $form->status)),'ORGANIZATION_DELETED');
        }
        return $count;
    }

    /**
     * Delete Organization Service
     * @method deleteOrganization
     * @link /organization[/:orgId]
     * @param $id ID of Organization to Delete
     * @return array success|failure response
     */
    public function deleteOrganization($id)
    {
       
        $obj = $this->table->getByUuid($id, array());
        if (is_null($obj)) {
            return 0;
        }
        $originalArray = $obj->toArray();
        $form = new Organization();
        $originalArray['status'] = 'Inactive';
        $form->exchangeArray($originalArray);
        $result = $this->table->save($form);
        $this->messageProducer->sendTopic(json_encode(array('orgname' => $originalArray['name'],'status' => $originalArray['status'])),'ORGANIZATION_DELETED');
        return $result;
    }

    /**
     * GET Organization Service
     * @method getOrganization
     * @param $id ID of Organization to GET
     * @return array $data
     * <code> {
     *               id : integer,
     *               name : string,
     *               logo : string,
     *               status : String(Active|Inactive),
     *   } </code>
     * @return array Returns a JSON Response with Status Code and Created Organization.
     */
    public function getOrganization($id)
    {
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_organization')
            ->columns(array("*"))
            ->where(array('ox_organization.id' => $id, 'status' => "Active"));
        $response = $this->executeQuery($select)->toArray();
        if (count($response) == 0) {
            return 0;
        }

        return $response[0];
    }

    public function getOrganizationIdByUuid($uuid){
        $select ="SELECT id from ox_organization where uuid = '".$uuid."'";
        $result = $this->executeQueryWithParams($select)->toArray();
        if(isset($result[0])){
            return $result[0]['id'];
        }else{
            return NULL;
        }       
    }

    /**
     * GET Organization Service
     * @method getOrganization
     * @param $id ID of Organization to GET
     * @return array $data
     * <code> {
     *               id : integer,
     *               name : string,
     *               logo : string,
     *               status : String(Active|Inactive),
     *   } </code>
     * @return array Returns a JSON Response with Status Code and Created Organization.
     */
    public function getOrganizationByUuid($id)
    {
       
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_organization')
            ->columns(array('uuid','name','address','city','state','country','zip','logo','preferences','contactid'))
            ->where(array('ox_organization.uuid' => $id, 'status' => "Active"));
        $response = $this->executeQuery($select)->toArray();

        if (count($response) == 0) {
            return 0;
        }else{
        $response[0]['contactid'] = $this->getOrgContactPersonDetails($id); 
    }
    
        return $response[0];
    }

    private function getOrgContactPersonDetails($id){
        $userData = array();
        $userSelect = "SELECT ou.uuid from `ox_user` as ou where ou.id = (SELECT og.contactid from `ox_organization` as og WHERE og.uuid = '".$id."')";
        $userData = $this->executeQueryWithParams($userSelect)->toArray();    
        return $userData[0]['uuid'];
    }

    /**
     * GET Organization Service
     * @method getOrganizations
     * @return array $data
     * <code> {
     *               id : integer,
     *               name : string,
     *               logo : string,
     *               status : String(Active|Inactive),
     *   } </code>
     * @return array Returns a JSON Response with Status Code and Created Organization.
     */
    public function getOrganizations($filterParams = null)
    {
        $where = "";
        $pageSize = 20;
        $offset = 0;
        $sort = "name";

        $cntQuery ="SELECT count(id) FROM `ox_organization`";

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
            $query ="SELECT uuid,name,address,city,state,country,zip,logo,preferences,contactid FROM `ox_organization`".$where." ".$sort." ".$limit;
            $resultSet = $this->executeQuerywithParams($query)->toArray();

            for($x=0;$x<sizeof($resultSet);$x++) {
              $resultSet[$x]['contactid'] = $this->getOrgContactPersonDetails($resultSet[$x]['uuid']);
            }
            return array('data' => $resultSet, 
                     'total' => $count);
    }

    public function addUserToOrg($userId, $organizationId) {
        if ($user = $this->getDataByParams('ox_user', array('id', 'username'), array('id' => $userId))->toArray()) {
            if ($org = $this->getDataByParams('ox_organization', array('id', 'name'), array('id' => $organizationId, 'status' => 'Active'))->toArray()) {
                if (!$this->getDataByParams('ox_user_org', array(), array('user_id' => $userId, 'org_id' => $organizationId))) {
                    $data = array(array(
                        'user_id' => $userId,
                        'org_id' => $organizationId
                    ));
                    $result_update = $this->multiInsertOrUpdate('ox_user_org', $data);
                    if ($result_update->getAffectedRows() == 0) {
                        return $result_update;
                    }
                    $this->messageProducer->sendTopic(json_encode(array('username' => $user[0]['username'], 'orgname' => $org[0]['name'] , 'status' => 'Active')),'USERTOORGANIZATION_ADDED');
                    return 1;
                } else {
                    $this->messageProducer->sendTopic(json_encode(array('username' => $user[0]['username'], 'orgname' => $org[0]['name'] , 'status' => 'Active')),'USERTOORGANIZATION_ALREADYEXISTS');
                    return 3;
                }
            } else {
                return 2;
            }
        }
        return 0;
    }

    public function getUserIdList($uuidList){
        $uuidList= array_unique(array_map('current', $uuidList));
        $query = "SELECT id from ox_user where uuid in ('".implode("','", $uuidList) . "')";
        $result = $this->executeQueryWithParams($query)->toArray();
        return $result; 
    }


    public function saveUser($id,$data){

        $obj = $this->table->getByUuid($id,array());
        if (is_null($obj)) {
            return 0;
        }
        if(!isset($data['userid']) || empty($data['userid'])) {
            return 2;
        }
        $orgId = $obj->id;
        $userUuidList=json_decode($data['userid'],true);
        $userArray = $this->getUserIdList($userUuidList);
        if($userArray){
            $userSingleArray= array_unique(array_map('current', $userArray));

            $querystring = "SELECT u.username FROM ox_user_org as ouo 
                            inner join ox_user as u on u.id = ouo.user_id 
                            inner join ox_organization as org on ouo.org_id = org.id and org.id =".$orgId."
                            where ouo.org_id =".$orgId." and ouo.user_id not in (".implode(',', $userSingleArray).") and ouo.user_id != org.contactid";
            $deletedUser = $this->executeQuerywithParams($querystring)->toArray();

          
            $query = "SELECT ou.username from ox_user as ou LEFT OUTER JOIN ox_user_org as our on 
                        our.user_id = ou.id AND our.org_id = ou.orgid and our.org_id =".$orgId."
                        WHERE ou.id in (".implode(',', $userSingleArray).") AND our.org_id is Null and ou.id not in (select user_id from  ox_user_org where user_id in (".implode(',', $userSingleArray).") and org_id =".$orgId.")";
            $insertedUser = $this->executeQuerywithParams($query)->toArray();


            $this->beginTransaction();
            try{

                $query = "UPDATE ox_user as ou 
                            inner join ox_organization as org on org.id = ou.orgid
                            and ou.id != org.contactid 
                            SET ou.orgid = NULL WHERE ou.id not in (".implode(',', $userSingleArray).") AND ou.orgid = $orgId";
                $resultSet = $this->executeQuerywithParams($query);

                $select = "SELECT u.id FROM ox_user_org as ouo 
                            inner join ox_user as u on u.id = ouo.user_id 
                            inner join ox_organization as org on ouo.org_id = org.id and org.id =".$orgId."
                            where ouo.org_id =".$orgId." and ouo.user_id not in (".implode(',', $userSingleArray).") and ouo.user_id != org.contactid";
                $userId = $this->executeQuerywithParams($select)->toArray();

                $query = "DELETE ouo FROM ox_user_org as ouo 
                            inner join ox_user as u on u.id = ouo.user_id 
                            inner join ox_organization as org on ouo.org_id = org.id and org.id =".$orgId."
                            where ouo.org_id =".$orgId." and ouo.user_id not in (".implode(',', $userSingleArray).") and ouo.user_id != org.contactid";

                $resultSet = $this->executeQuerywithParams($query);
                $insert = "INSERT INTO ox_user_org (user_id,org_id,`default`)  
                                SELECT ou.id,".$orgId.",case when (ou.orgid is NULL) 
                                    then 1
                                end 
                                from ox_user as ou LEFT OUTER JOIN ox_user_org as our on our.user_id = ou.id AND our.org_id = ou.orgid and our.org_id =".$orgId."
                                WHERE ou.id in (".implode(',', $userSingleArray).") AND our.org_id is Null AND ou.id not in (select user_id from  ox_user_org where user_id in (".implode(',', $userSingleArray).") and org_id =".$orgId.")";
                $resultSet = $this->executeQuerywithParams($insert);


                $update = "UPDATE ox_user SET orgid = $orgId WHERE id in (".implode(',', $userSingleArray).") AND orgid is NULL";
                $resultSet = $this->executeQuerywithParams($update);

                if(count($userId) > 0){
                    $userIdArray= array_unique(array_map('current', $userId));                    
                    $update = "UPDATE ox_user SET orgid = NULL WHERE id in (".implode(',', $userIdArray).")";
                    $resultSet = $this->executeQuerywithParams($update);
                }

                $this->commit();
            }
            catch(Exception $e){
                $this->rollback();
                throw $e;
            }

            foreach($deletedUser as $key => $value){
                $this->messageProducer->sendTopic(json_encode(array('orgname' => $obj->name , 'status' => 'Active', 'username'=>$value["username"])),'USERTOORGANIZATION_DELETED');
            }
            foreach($insertedUser as $key => $value){
                $this->messageProducer->sendTopic(json_encode(array('orgname' => $obj->name , 'status' => 'Active', 'username'=>$value["username"])),'USERTOORGANIZATION_ADDED');
            }

            return 1;
        }
        return 0;

    }

    public function getOrgUserList($id,$filterParams = null,$baseUrl = '') {
        if(!isset($id)) {
            return 0;
        }

        $pageSize = 20;
        $offset = 0;
        $where = "";
        $sort = "ox_user.name";


        $query = "SELECT ox_user.uuid,ox_user.name,ox_user.country,ox_user.designation";
        $from = " FROM ox_user left join ox_user_org on ox_user.id = ox_user_org.user_id left join ox_organization on ox_organization.id = ox_user_org.org_id";
    
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

            $where .= strlen($where) > 0 ? " AND ox_organization.uuid = '".$id."'" : " WHERE ox_organization.uuid = '".$id."'";

            $sort = " ORDER BY ".$sort;
            $limit = " LIMIT ".$pageSize." offset ".$offset;
            $resultSet = $this->executeQuerywithParams($cntQuery.$where);
            $count=$resultSet->toArray()[0]['count(ox_user.id)'];
            $query =$query." ".$from." ".$where." ".$sort." ".$limit;
            $resultSet = $this->executeQuerywithParams($query)->toArray();
            for($x=0;$x<sizeof($resultSet);$x++) {
                $resultSet[$x]['icon'] = $baseUrl . "/user/profile/" . $resultSet[$x]['uuid'];
           }
            if(sizeof($resultSet) > 0){
                return array('data' => $resultSet, 
                     'total' => $count);
            }else{
                return 0;
            }    
    }

    public function getAdminUsers($filterParams, $orgId = null){
        if(!isset($orgId)){
            $orgId = AuthContext::get(AuthConstants::ORG_UUID);
        }
        if(!SecurityManager::isGranted('MANAGE_ORGANIZATION_WRITE') && 
            SecurityManager::isGranted('MANAGE_MYORG_WRITE') && 
            $orgId != AuthContext::get(AuthConstants::ORG_UUID) ) {
            throw new AccessDeniedException("You do not have permissions");
        }
        
        $pageSize = 20;
        $offset = 0;
        $where = "";
        $sort = "name";


        $select = "SELECT DISTINCT ox_user.uuid,ox_user.name ";
        $from = " from ox_user inner join ox_user_role as our on ox_user.id = our.user_id inner join ox_role as oro on our.role_id = oro.id inner join ox_user_org as oug on oro.org_id = oug.org_id";

       $cntQuery ="SELECT count(DISTINCT ox_user.uuid)".$from;

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

            $orgId = $this->getOrganizationIdByUuid($orgId);
            $where .= strlen($where) > 0 ? " AND oro.org_id =".$orgId." and oro.name = 'ADMIN'" : " WHERE oro.org_id =".$orgId." and oro.name = 'ADMIN'";
        
            $sort = " ORDER BY ".$sort;
            $limit = " LIMIT ".$pageSize." offset ".$offset;
            $resultSet = $this->executeQuerywithParams($cntQuery.$where);
            $count=$resultSet->toArray()[0]['count(DISTINCT ox_user.uuid)'];
            $query =$select." ".$from." ".$where." ".$sort." ".$limit;
            $resultSet = $this->executeQuerywithParams($query)->toArray();
            if(sizeof($resultSet) > 0){
                return array('data' => $resultSet, 
                     'total' => $count);
            }else{
                return 0;
            }
    }

}
?>