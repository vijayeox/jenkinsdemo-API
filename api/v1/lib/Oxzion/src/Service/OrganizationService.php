<?php
namespace Oxzion\Service;

use Bos\Auth\AuthContext;
use Bos\Auth\AuthConstants;
use Bos\ValidationException;
use Bos\Service\AbstractService;
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
            $this->setupBasicOrg($form,$data['contact']);
            $this->commit();

            $this->uploadOrgLogo($data['uuid'],$files);
            $this->messageProducer->sendTopic(json_encode(array('orgname' => $form->name, 'status' => $form->status)),'ORGANIZATION_ADDED');
        } catch (Exception $e) {
            $this->rollback();
            return 0;
        }
        return $count;
    }




    public function getOrgLogoPath($id,$ensureDir=false){

        $baseFolder = $this->config['DATA_FOLDER'];
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
            $file['name'] = 'logo.png';
            FileUtils::storeFile($file,$destFile); 
            
        }
    }


    private function setupBasicOrg(Organization $org,$contactPerson) {
        // adding basic roles
        $returnArray['roles'] = $this->roleService->createBasicRoles($org->id);

        // adding basic privileges
        $returnArray['privileges'] = $this->privilegeService->createBasicPrivileges($org->id);

        // adding a user
        $returnArray['user'] = $this->userService->createAdminForOrg($org,$contactPerson);

        return true;
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
                case "Bos\ValidationException" :
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
        }

        return $response[0];
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
                if(isset($filterArray[0]['sort'])){
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
            $resultSet = $this->executeQuerywithParams($query);
            return array('data' => $resultSet->toArray(), 
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

        $userArray=json_decode($data['userid'],true);
        if($userArray){
             $userSingleArray= array_unique(array_map('current', $userArray));

             $querystring = "SELECT ouo.user_id , org.id from ox_user_org as ouo inner join ox_organization as org on ouo.org_id = org.id where org.uuid = '".$id."' and ouo.user_id not in (".implode(',', $userSingleArray).")";
             $deletedUser = $this->executeQuerywithParams($querystring)->toArray();

             $query = "SELECT ou.id,ou.orgid = (SELECT org.id from ox_organization as org where org.uuid = '".$id."') from ox_user as ou LEFT OUTER JOIN ox_user_org as our on our.user_id = ou.id AND our.org_id = ou.orgid WHERE ou.id in (".implode(',', $userSingleArray).") AND our.org_id is Null";
             $insertedUser = $this->executeQuerywithParams($query)->toArray();
            
             $this->beginTransaction();
             try{
             $query = "DELETE FROM ox_user_org where user_id not in (".implode(',', $userSingleArray).") and org_id in (SELECT id from ox_organization where uuid = '".$id."')";
             $resultSet = $this->executeQuerywithParams($query);

             $insert = "INSERT into ox_user_org (user_id,org_id) SELECT ou.id,ou.orgid = (SELECT org.id from ox_organization as org where org.uuid = '".$id."') from ox_user as ou LEFT OUTER JOIN ox_user_org as our on our.user_id = ou.id AND our.org_id = ou.orgid WHERE ou.id in (".implode(',', $userSingleArray).") AND our.org_id is Null";
             $resultSet = $this->executeQuerywithParams($insert);
             $this->commit();
            }
            catch(Exception $e){
                $this->rollback();
                throw $e;
            }

            foreach($deletedUser as $key => $value){
                $this->messageProducer->sendTopic(json_encode(array('orgname' => $obj->name , 'status' => 'Active')),'USERTOORGANIZATION_DELETED');
            }
            foreach($insertedUser as $key => $value){
                $this->messageProducer->sendTopic(json_encode(array('orgname' => $obj->name , 'status' => 'Active')),'USERTOORGANIZATION_ADDED');
            }

            return 1;
        }
        return 0;

    }
}
?>