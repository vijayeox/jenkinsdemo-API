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
        $data['uuid'] = Uuid::uuid4();  
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
            if ($count == 0) {
                $this->rollback();
                return 1;
            }
            $this->commit();
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
            ->columns(array("*"))
            ->where(array('ox_organization.uuid' => $id, 'status' => "Active"));
        $response = $this->executeQuery($select)->toArray();
        if (count($response) == 0) {
            return 0;
        }else{
        $response[0]['contact'] = $this->getOrgContactPersonDetails($id)[0]; 
    }
    
        return $response[0];
    }

    private function getOrgContactPersonDetails($id){
        $userData = array();
        $userSelect = "SELECT ou.firstname,ou.lastname,ou.email,ou.phone from `ox_user` as ou where ou.id = (SELECT og.contactid from `ox_organization` as og WHERE og.uuid = '".$id."')";
        $userData = $this->executeQueryWithParams($userSelect)->toArray();     
        return $userData;
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
            $query ="SELECT * FROM `ox_organization`".$where." ".$sort." ".$limit;
            $resultSet = $this->executeQuerywithParams($query)->toArray();

            for($x=0;$x<sizeof($resultSet);$x++) {
              $resultSet[$x]['contact'] = $this->getOrgContactPersonDetails($resultSet[$x]['uuid'])[0];
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


    public function saveUser($id,$data){
    
        $obj = $this->table->getByUuid($id,array());

        if (is_null($obj)) {
            return 0;
        }
        if(!isset($data['userid']) || empty($data['userid'])) {
            return 2;
        }
        $orgId = $obj->id;
        $userArray=json_decode($data['userid'],true);
        if($userArray){
            $userSingleArray= array_unique(array_map('current', $userArray));

            $querystring = "SELECT u.username from ox_user_org as ouo 
                 inner join ox_user as u on u.id = ouo.user_id
                 where ouo.org_id = ".$orgId." and ouo.user_id not in (".implode(',', $userSingleArray).")";
            $deletedUser = $this->executeQuerywithParams($querystring)->toArray();

            $query = "SELECT ou.username from ox_user as ou 
                LEFT OUTER JOIN ox_user_org as our on our.user_id = ou.id AND our.org_id = ou.orgid 
                WHERE ou.id in (".implode(',', $userSingleArray).") AND our.org_id is Null";
            $insertedUser = $this->executeQuerywithParams($query)->toArray();
            
            $this->beginTransaction();
            try{
                $query = "DELETE uo FROM ox_user_org as uo  
                            inner join ox_organization as org on uo.org_id = org.id 
                            where org.id = ".$orgId." and uo.user_id != org.contactid";
                $resultSet = $this->executeQuerywithParams($query);

                $update = "UPDATE ox_user SET orgid = $orgId WHERE id in (".implode(',', $userSingleArray).") AND orgid is NULL OR orgid = 0";
                $resultSet = $this->executeQuerywithParams($update);

                 
                $insert = "INSERT INTO ox_user_org (user_id,org_id) 
                                select u.id, ".$orgId." from ox_user as u 
                                    left join ox_organization org on org.contactid = u.id and org.id = ".$orgId."
                                    where org.id is null AND
                                    u.id in (".implode(',', $userSingleArray).")";
                                
                $resultSet = $this->executeQuerywithParams($insert);
                 
                $this->commit();
            }
            catch(Exception $e){
                $this->rollback();
                throw $e;
            }

            foreach($deletedUser as $key => $value){
                $this->messageProducer->sendTopic(json_encode(array('orgname' => $obj->name , 'status' => 'Active', 'username'=>$value)),'USERTOORGANIZATION_DELETED');
            }
            foreach($insertedUser as $key => $value){
                $this->messageProducer->sendTopic(json_encode(array('orgname' => $obj->name , 'status' => 'Active', 'username'=>$value)),'USERTOORGANIZATION_ADDED');
            }

            return 1;
        }
        return 0;

    }

        public function getOrgUserList($id,$filterParams = null) {

        if(!isset($id)) {
            return 0;
        }

        $pageSize = 20;
        $offset = 0;
        $where = "";
        $sort = "ox_user.name";


        $query = "SELECT ox_user.id,ox_user.name";
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
            if(sizeof($resultSet) > 0){
                return array('data' => $resultSet, 
                     'total' => $count);
            }else{
                return 0;
            }    
    }
}
?>