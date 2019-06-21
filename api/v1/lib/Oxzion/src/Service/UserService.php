<?php
namespace Oxzion\Service;

use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\Model\User;
use Oxzion\Model\UserTable;
use Oxzion\Utils\BosUtils;
use Oxzion\Utils\ArrayUtils;
use Oxzion\Service\AbstractService;
use Oxzion\Service\EmailService;
use Oxzion\Service\EmailTemplateService;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Search\Elastic\IndexerImpl;
use Oxzion\Utils\FilterUtils;

class UserService extends AbstractService
{
    const ROLES = '_roles';
    const GROUPS = '_groups';
    const USER_FOLDER = "/users/";
    private $id;

    /**
     * @ignore table
     */
    protected $table;
    protected $config;
    private $cacheService;
    private $emailService;
    private $messageProducer;
    private $emailTemplateService;

    public function setMessageProducer($messageProducer)
    {
		$this->messageProducer = $messageProducer;
    }

    public function __construct($config, $dbAdapter, UserTable $table = null, EmailService $emailService, EmailTemplateService $emailTemplateService) {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
        $this->config = $config;
        $this->emailService = $emailService;
        $this->emailTemplateService = $emailTemplateService;
        $this->cacheService = CacheService::getInstance();
        $this->messageProducer = MessageProducer::getInstance();
    }

    /**
     * Get User's Organization
     * @param string $username Username of user to Login
     * @return integer orgid of the user
     */
    public function getUserOrg($username)
    {
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_user')
            ->columns(array("orgid"))
            ->where(array('ox_user.username' => $username));
        $response = $this->executeQuery($select)->toArray();
        return $response[0]['orgid'];
    }

    public function getUserContextDetails($userName)
    {
        if ($results = $this->cacheService->get($userName)) {
            return $results;
        }
        $sql = $this->getSqlObject();
        $select = $sql->select()
            ->from('ox_user')
            ->columns(array('id', 'name', 'uuid', 'orgid'))
            ->where(array('username = "' . (string) $userName . '"'))->limit(1);
        $results = $this->executeQuery($select);
        $results = $results->toArray();
        if (count($results) > 0) {
            $results = $results[0];
        }
        $this->cacheService->set($userName, $results);
        return $results;
    }

    public function getGroups($userName)
    {
        if ($groupData = $this->cacheService->get($userName . GROUPS)) {
            $data = $groupData;
        } else {
            $data = $this->getGroupsFromDb($userName);
            $this->cacheService->set($userName . GROUPS, $data);
        }
        return $data;
    }

    public function getGroupsFromDb($id)
    {
        $sql = $this->getSqlObject();
        $select = $sql->select()
            ->from('ox_group')
            ->columns(array('id', 'name'))
            ->join('ox_user_group', 'ox_user_group.group_id = ox_group.id', array())
            ->where(array('ox_user_group.avatar_id' => $id));
        //echo "<pre>";print_r($this->executeQuery($select)->toArray());exit();
        return $this->executeQuery($select)->toArray();
    }

    /**
     * @method createUser
     * @param array $data Array of elements as shown</br>
     * <code>
     *        gamelevel : string,
     *        username : string,
     *        password : string,
     *        firstname : string,
     *        lastname : string,
     *        name : string,
     *        role : string,
     *        email : string,
     *        status : string,
     *        dob : string,
     *        designation : string,
     *        sex : string,
     *        managerid : string,
     *        cluster : string,
     *        level : string,
     *        org_role_id : string,
     *        doj : string
     * </code>
     * @return array Returns a JSON Response with Status Code and Created User.</br>
     * <code> status : "success|error",
     *        data : array Created User Object
     * </code>
     */
    public function createUser(&$data) {
        if(!isset($data['orgid'])){
            $data['orgid'] = AuthContext::get(AuthConstants::ORG_ID);
        }
        
        $data['date_created'] = date('Y-m-d H:i:s');
        $data['created_by'] = AuthContext::get(AuthConstants::USER_ID);
        $password = BosUtils::randomPassword();
        if (isset($password))
            $data['password'] = md5(sha1($password));
        $form = new User($data);
        $form->validate();
        $this->beginTransaction();
        try {
            $count = 0;
            $count = $this->table->save($form);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
            $form->id = $data['id'] = $this->table->getLastInsertValue();
            $this->addUserToOrg($form->id, $form->orgid);
            // $this->emailService->sendUserEmail($form);
            // // Code to add the user information to the Elastic Search Index
            // $result = $this->messageProducer->sendTopic(json_encode(array('userInfo' => $data)), 'USER_CREATED');
            // $es = $this->generateUserIndexForElastic($data);
            $this->commit();
            $this->messageProducer->sendTopic(json_encode(array(
                'username' => $data['username'],
                'firstname' => $data['firstname'],
                'email' => $data['email'],
                'password' => $password
            )),'USER_ADDED');
            return $count;
        } catch (Exception $e) {
            $this->rollback();
            return 0;
        }
    }

    public function createAdminForOrg($org,$contactPerson,$orgPreferences) {
        $contactPerson = (object)$contactPerson;
        $orgPreferences = (object)$orgPreferences;
        $preferences = array(
            "soundnotification" => "true",
            "emailalerts" => "false",
            "timezone" => $orgPreferences->timezone,
            "dateformat" => $orgPreferences->dateformat
        );
        $data = array(
            "firstname" => $contactPerson->firstname,
            "lastname" => $contactPerson->lastname,
            "email" => $contactPerson->email,
            "phone" => $contactPerson->phone,
            "company_name" => $org->name,
            "address_1" => $org->address,
            "address_2" => $org->city,
            "country" => $org->country,
            "preferences" => json_encode($preferences),
            "username" => $contactPerson->username,
            "date_of_birth" => ' ',
            "designation" => "Admin",
            "orgid" => $org->id,
            "status" => "Active",
            "timezone" => "United States/New York",
            "gender" => " ",
            "managerid" => "1",
            "date_of_join" => ' ',
            "password" => BosUtils::randomPassword()
        );
        $this->beginTransaction();
        try{
            $result = $this->createUser($data);

            $select = "SELECT id from `ox_user` where username = '".$data['username']."'";
            $resultSet = $this->executeQueryWithParams($select)->toArray();
              
            $this->addUserRole($data['id'], 'ADMIN');
            $this->commit();
        }
        catch(Exception $e){
            $this->rollback();
            return 0;
        }

        $this->messageProducer->sendTopic(json_encode(array(
            'To' => $data['email'],
            'Subject' => $org->name.' created!',
            'body' => $this->emailTemplateService->getContent('newAdminUser', $data)
        )),'mail');


        return $resultSet[0]['id'];
    }

    private function addUserRole($userId, $roleName) {
        if ($user = $this->getDataByParams('ox_user', array('id', 'orgid'), array('id' => $userId))->toArray()) {
            if ($role = $this->getDataByParams('ox_role', array('id'), array('org_id' => $user[0]['orgid'], 'name' => $roleName))->toArray()) {
                if (!$this->getDataByParams('ox_user_role', array(), array('user_id' => $userId, 'role_id' => $role[0]['id']))->toArray()) {
                    $data = array(array(
                        'user_id' => $userId,
                        'role_id' => $role[0]['id']
                    ));
                    $result = $this->multiInsertOrUpdate('ox_user_role', $data);
                    if ($result->getAffectedRows() == 0) {
                        return $result;
                    }
                    return 1;
                } else {
                    return 3;
                }
            } else {
                return 2;
            }
        }
        return 0;
    }

    private function generateUserIndexForElastic($data)
    {
        //    print_r($data);exit;
        $elasticIndex = new IndexerImpl($this->config);
        $appId = 'user';
        $id = $data['id'];
        return $elasticIndex->index($appId, $id, 'type', $data);
    }

    /**
     * @method updateUser
     * @param array $id ID of User to update
     * @param array $data
     * <code>
     *        gamelevel : string,
     *        username : string,
     *        password : string,
     *        firstname : string,
     *        lastname : string,
     *        name : string,
     *        role : string,
     *        email : string,
     *        status : string,
     *        dob : string,
     *        designation : string,
     *        sex : string,
     *        managerid : string,
     *        cluster : string,
     *        level : string,
     *        org_role_id : string,
     *        doj : string
     * </code>
     * @return array Returns a JSON Response with Status Code and Created User.
     */
    public function updateUser($id, &$data)
    {
        $obj = $this->table->get($id, array());
        if (is_null($obj)) {
            return 0;
        }
        $form = new User();
        $userdata = array_merge($obj->toArray(), $data); //Merging the data from the db for the ID
        $userdata['id'] = $id;
        $userdata['modified_id'] = AuthContext::get(AuthConstants::USER_ID);
        $userdata['date_modified'] = date('Y-m-d H:i:s');
        if (isset($userdata['preferences']) && is_array($userdata['preferences'])) {
            $userdata['preferences'] = json_encode($userdata['preferences']);
        }
        $form->exchangeArray($userdata);
        $form->validate();

        $count = 0;
        $this->beginTransaction();
        try {
            $this->table->save($form);
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            return 0;
        }
        return $form->toArray();
    }

    private function getOrg($id){
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_organization')
            ->columns(array("id", "name"))
            ->where(array('ox_organization.id' => $id));
        $response = $this->executeQuery($select)->toArray();
        return $response[0];
    }

    /**
     * Delete User Service
     * @method deleteUser
     * @param $id ID of User to Delete
     * @return array success|failure response
     */
    public function deleteUser($id)
    {
        $obj = $this->table->get($id, array());
        $org = $this->getOrg($obj->orgid);
        if (is_null($obj)) {
            return 0;
        }
        $originalArray = $obj->toArray();
        $form = new User();
        $originalArray['status'] = 'Inactive';
        $originalArray['modified_id'] = AuthContext::get(AuthConstants::USER_ID);
        $originalArray['date_modified'] = date('Y-m-d H:i:s');
        $form->exchangeArray($originalArray);
        $form->validate();
        $result = $this->table->save($form);
        $this->messageProducer->sendTopic(json_encode(array('username' => $obj->username ,'orgname' => $org['name'] )),'USER_DELETED');
        return $result;
    }

    /**
     * GET List User API
     * @api
     * @link /user
     * @method GET
     * @return array $dataget list of Users
     */
    public function getUsers($filterParams = null)
    {   
            $where = "";
            $pageSize = 20;
            $offset = 0;
            $sort = "name";

            $cntQuery ="SELECT count(id) FROM `ox_user` ";

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
            $query ="SELECT * FROM `ox_user`".$where." ".$sort." ".$limit;
            $resultSet = $this->executeQuerywithParams($query);
            $result = $resultSet->toArray();
            for($x=0;$x<sizeof($result);$x++) {
                 $result[$x]['preferences'] = json_decode($result[$x]['preferences'],true);
            }
            return array('data' => $result,
                     'total' => $count);
    }

    /**
     * GET User Service
     * @method  getUser
     * @param $id ID of User to View
     * @return array $data
     * @return array Returns a JSON Response with Status Code and Created User.
     */
    public function getUser($id)
    {
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_user')
            ->columns(array("*"))
            ->where(array('ox_user.orgid' => AuthContext::get(AuthConstants::ORG_ID), 'ox_user.id' => $id, 'status' => 'Active'));
        $response = $this->executeQuery($select)->toArray();
        if (!$response) {
            return $response[0];
        }
        $result = $response[0];

        $result['active_organization'] = $this->getActiveOrganization(AuthContext::get(AuthConstants::ORG_ID));
        $result['preferences'] = json_decode($response[0]['preferences']);
        if (isset($result)) {
            return $result;
        } else {
            return 0;
        }
    }


    public function getUserByUuid($uuid){
        $select = "SELECT id from `ox_user` where uuid = '".$uuid."'";
        $result = $this->executeQueryWithParams($select)->toArray();
        if($result){
        return $result[0]['id'];
    }else{
        return 0;
    }

    }

    public function getActiveOrganization($id)
    {
        $sql = $this->getSqlObject();
        $select = $sql->select()
            ->from('ox_organization')
            ->columns(array('id', 'name'))
            ->where(array('ox_organization.id' => $id));
        $result = $this->executeQuery($select)->toArray();
        return $result[0];
    }

    public function getPrivileges($userId)
    {
        // if($roleData = $this->cacheService->get($userId.PRIVILEGESS)){
        // $data = $roleData;
        // } else {
        $data = $this->getPrivilegesFromDb($userId);
        // $this->cacheService->set($userId.PERMISSIONS, $data);
        // }
        return $data;
    }

    private function getPrivilegesFromDb($userId)
    {
        $sql = $this->getSqlObject();
        $select = $sql->select()
            ->from('ox_role_privilege')
            ->columns(array('privilege_name', 'permission'))
            ->join('ox_user_role', 'ox_role_privilege.role_id = ox_user_role.role_id', array())
            ->where(array('ox_user_role.user_id' => $userId));
        $results = $this->executeQuery($select)->toArray();
        $permissions = array();
        foreach ($results as $key => $value) {
            $permissions = array_merge($permissions, $this->addPermissions($value['privilege_name'], $value['permission']));
        }
        return array_unique($permissions);
    }

    public function addPermissions($privilegeName, $permission)
    {
        $permissionArray = array();
        if (($permission & 1) != 0) {
            $permissionArray[] = $privilegeName . "_" . 'READ';
        }
        if (($permission & 2) != 0) {
            $permissionArray[] = $privilegeName . "_" . 'WRITE';
        }
        if (($permission & 4) != 0) {
            $permissionArray[] = $privilegeName . "_" . 'CREATE';
        }
        if (($permission & 8) != 0) {
            $permissionArray[] = $privilegeName . "_" . 'DELETE';
        }
        return $permissionArray;
    }

    /**
     * GET User Service
     * @method  getUserWithMinimumDetails
     * @param $id ID of User to View
     * @return array with minumum information required to use for the User.
     * @return array Returns a JSON Response with Status Code and Created User.
     */
    public function getUserWithMinimumDetails($id)
    {
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_user')
            ->columns(array('id', 'uuid', 'username', 'firstname', 'lastname', 'name', 'email', 'designation', 'phone', 'date_of_birth', 'date_of_join', 'country', 'website', 'about', 'gender', 'interest', 'address', 'icon', 'preferences'))
            ->where(array('ox_user.orgid' => AuthContext::get(AuthConstants::ORG_ID), 'ox_user.id' => $id, 'status' => 'Active'));
        $response = $this->executeQuery($select)->toArray();
        if (empty($response)) {
            return 0;
        }
        $result = $response[0];
        $result['preferences'] = json_decode($response[0]['preferences']);
        if (isset($result)) {
            return $result;
        } else {
            return 0;
        }
    }

    /**
     * GET User Profile
     * @method  getUserBaseProfile
     * @param $username USERNAME or EMAIL  of User to View
     * @return array with base information required to use for the User login.
     * @return array Returns a JSON Response with Status Code and Existing User data.
     */
    public function getUserBaseProfile($username)
    {
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_user')
            ->columns(array('id', 'uuid', 'username', 'firstname', 'lastname', 'name', 'email'))
            ->where(array('ox_user.username' => $username, 'ox_user.email' => $username), 'OR');

        $response = $this->executeQuery($select)->toArray();
        if (!$response) {
            return 0;
        }
        $result = $response[0];
        if (isset($result)) {
            return $result;
        } else {
            return 0;
        }
    }

    /**
     * @method assignManagerToUser
     * @param $id ID of User to assign a manager
     * @param $id ID of User to set as Manager
     * @return array success|failure response
     */
    public function assignManagerToUser($userId, $managerId)
    {
        $queryString = "Select user_id, manager_id from ox_user_manager";
        $where = "where user_id = " . $userId . " and manager_id = " . $managerId;
        $resultSet = $this->executeQuerywithParams($queryString, $where, null, null);
        $getUserManager = $resultSet->toArray();
        if (empty($getUserManager)) {
            $sql = $this->getSqlObject();
            $insert = $sql->insert('ox_user_manager');
            $data = array('user_id' => $userId, 'manager_id' => $managerId, 'created_id' => AuthContext::get(AuthConstants::USER_ID), 'date_created' => date('Y-m-d H:i:s'));
            $insert->values($data);
            $result = $this->executeUpdate($insert);
            if ($result->getAffectedRows() == 0) {
                return $result;
            }
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * @method removeManagerForUser
     * @param $id ID of User to remove a manager
     * @param $id ID of User to remove as Manager
     * @return array success|failure response
     */
    public function removeManagerForUser($userId, $managerId)
    {
        $sql = $this->getSqlObject();
        $delete = $sql->delete('ox_user_manager');
        $delete->where(['user_id' => $userId, 'manager_id' => $managerId]);
        $result = $this->executeUpdate($delete);
        if ($result->getAffectedRows() == 0) {
            return $result;
        }
        return 1;
    }

    public function addUserToProject($userid, $projectid)
    {
        $sql = $this->getSqlObject();
        $queryString = "select id from ox_user";
        $where = "where id =" . $userid . " and status='Active'";
        $resultSet = $this->executeQuerywithParams($queryString, $where, null, null);
        if ($resultSet) {
            $query = "select id from ox_project";
            $where = "where id=" . $projectid;
            $result = $this->executeQuerywithParams($query, $where, null, null);
            if ($result) {
                $query = "select * from ox_user_project";
                $where = "where user_id =" . $userid . " and project_id =" . $projectid;
                $endresult = $this->executeQuerywithParams($query, $where, null, null)->toArray();
                if (!$endresult) {
                    $data = array(array('user_id' => $userid, 'project_id' => $projectid));
                    $result_update = $this->multiInsertOrUpdate('ox_user_project', $data, array());
                    if ($result_update->getAffectedRows() == 0) {
                        return $result_update;
                    }
                    return 1;
                }
            }
        }
        return 0;
    }

    public function removeUserFromGroup($userid)
    {
        $sql = $this->getSqlObject();
        $queryString = "select avatar_id from ox_user_group";
        $where = "where avatar_id =" . $userid;
        $resultSet = $this->executeQuerywithParams($queryString, $where, null, null)->toArray();
        if (!empty($resultSet)) {
            $delete = $sql->delete('ox_user_group');
            $delete->where(['avatar_id' => $userid]);
            $result = $this->executeUpdate($delete);
            return 1;
        } else {
            return 0;
        }
    }

    public function removeUserFromProject($userid, $projectid)
    {
        $sql = $this->getSqlObject();
        $queryString = "select user_id from ox_user_project";
        $where = "where user_id =" . $userid . " and project_id =" . $projectid;
        $resultSet = $this->executeQuerywithParams($queryString, $where, null, null)->toArray();
        if (!empty($resultSet)) {
            $delete = $sql->delete('ox_user_project');
            $delete->where(['user_id' => $userid, 'project_id' => $projectid]);
            $result = $this->executeUpdate($delete);
            return 1;
        } else {
            return 0;
        }
    }

    public function getUserNameFromAuth()
    {
        return $userName = AuthContext::get(AuthConstants::USERNAME);
    }

    /**
     * @param $searchVal
     * @return array
     */
    public function getUserBySearchName($searchVal)
    {
        $sql = $this->getSqlObject();
        $select = $sql->select()
            ->from('ox_user')
            ->columns(array('id', 'firstname', 'lastname')) // Instead of getting the id from the userTable,
        // we need to get the UUID. Once UUID is added to the table we need to make that change
            ->where(array('firstname LIKE "%' . $searchVal . '%" OR lastname LIKE "%' . $searchVal . '%"'));
        return $result = $this->executeQuery($select)->toArray();
    }

    /**
     * @param $userName
     * @return array|\Zend\Db\ResultSet\ResultSet
     */
    public function getUserDetailsbyUserName($userName,$columns = null)
    {
        $whereCondition = "username = '" . $userName . "'";
        if($columns){
            $columnList = $columns;
        } else {
            $columnList = array('*');
        }
        return $userDetail = $this->getUserContextDetailsByParams($whereCondition, $columnList);
    }

    /**
     * @param $whereCondition
     * @param $columnList
     * @return array|\Zend\Db\ResultSet\ResultSet
     */
    public function getUserContextDetailsByParams($whereCondition, $columnList)
    {
        $sql = $this->getSqlObject();
        $select = $sql->select()
            ->from('ox_user')
            ->columns($columnList)
            ->where(array($whereCondition))
            ->limit(1);
        $results = $this->executeQuery($select);
        $results = $results->toArray();
        if (count($results) > 0) {
            $results = $results[0];
        }
        return $results;
    }

    public function addUserToOrg($userId, $organizationId) {
        if ($this->getDataByParams('ox_user', array('id'), array('id' => $userId))->toArray()) {
            if ($this->getDataByParams('ox_organization', array('id'), array('id' => $organizationId, 'status' => 'Active'))->toArray()) {
                if (!$this->getDataByParams('ox_user_org', array(), array('user_id' => $userId, 'org_id' => $organizationId))->toArray()) {
                    $data = array(array(
                        'user_id' => $userId,
                        'org_id' => $organizationId,
                        'default' => 1
                    ));
                    $result_update = $this->multiInsertOrUpdate('ox_user_org', $data);
                    if ($result_update->getAffectedRows() == 0)
                        return $result_update;
                    return 1;
                } else {
                    return 3;
                }
            } else {
                return 2;
            }
        }
        return 0;
    }

    public function getUserAppsAndPrivileges()
    {
        $privilege = AuthContext::get(AuthConstants::PRIVILEGES);
        foreach ($privilege as $key => $value) {
            $privilege[$key] = strtolower($value);
            $privilege[$key] = ucfirst($privilege[$key]);
            $privilege[$key] = implode('_', array_map('ucfirst', explode('_', $privilege[$key])));
            $privilege[$key] = str_replace('_', '', $privilege[$key]);
            $privilege[$key] = 'priv' . $privilege[$key] . ':true';
        }
        $whiteListedApps = $this->getAppsByUserId();
        $responseArray = array('privilege' => $privilege, 'whiteListedApps' => $whiteListedApps);
        return $responseArray;
    }

    /**
     * @return \Oxzion\Utils\Array
     */
    public function getAppsWithoutAccessForUser()
    {
        $orgId = AuthContext::get(AuthConstants::ORG_ID);
        $userId = AuthContext::get(AuthConstants::USER_ID);
        $userRole = implode(array_column($this->getRolesFromDb($userId), 'role_id'), ",");

        $query = "SELECT DISTINCT app.uuid, app.name from (
                    SELECT DISTINCT ap.uuid, ap.name, op.name as privilege_name, ar.org_id from ox_app as ap
                    INNER JOIN
                    ox_app_registry as ar ON ap.id = ar.app_id INNER JOIN
                    ox_privilege as op ON ar.app_id = op.app_id where ar.org_id =".$orgId.") app LEFT JOIN
                    (SELECT DISTINCT orp.privilege_name from ox_role_privilege as orp JOIN
                    ox_user_role as ou on orp.role_id = ou.role_id AND ou.user_id =".$userId." and orp.org_id = ".$orgId.") urp ON app.privilege_name = urp.privilege_name WHERE urp.privilege_name IS NULL union SELECT oa.uuid, oa.name FROM ox_app oa LEFT JOIN
                    `ox_app_registry` ar on oa.id = ar.app_id and ar.org_id =".$orgId." WHERE org_id IS NULL";
        $result = $this->executeQuerywithParams($query);
        $result= $result->toArray();
        $arr = array();
        for($i=0;$i<sizeof($result);$i++){
            $arr[$result[$i]['name']] = $result[$i]['uuid'];
        }
         return $arr;
    }

    /**
     * @param $userId
     * @return array
     */
    private function getRolesFromDb($userId)
    {
        $sql = $this->getSqlObject();
        $select = $sql->select()
            ->from('ox_user_role')
            ->columns(array('role_id'))
            ->where(array('ox_user_role.user_id' => $userId));
        return $this->executeQuery($select)->toArray();
    }

    /**
     * @ignore getUserFolder
     */
    protected function getUserFolder($id)
    {
        return $this->config['UPLOAD_FOLDER'] . "organization/" . AuthContext::get(AuthConstants::ORG_ID) . self::USER_FOLDER . $id;
    }

    /**
     * @ignore getFileName
     */
    protected function getFileName($file)
    {
        $fileName = explode('-', $file, 2);
        return $fileName[1];
    }

    public function sendResetPasswordCode($email)
    {
        $resetPasswordCode = BosUtils::randomPassword(); // I am using the randomPassword generator to do this since it is similar to a password generation
        $userId = AuthContext::get(AuthConstants::USER_ID);
        $userDetails = $this->getUser($userId);
        if ($email === $userDetails['email']) {
            $userReset['id'] = $id = $userDetails['id'];
            $userReset['email'] = $userDetails['email'];
            $userReset['firstname'] = $userDetails['firstname'];
            $userReset['lastname'] = $userDetails['lastname'];
            $userReset['password_reset_code'] = $resetPasswordCode;
            $userReset['password_reset_expiry_date'] = date("Y-m-d H:i:s", strtotime("+30 minutes"));
            //            print_r($userReset);exit;
            //Code to update the password reset and expiration time
            $userUpdate = $this->updateUser($id, $userReset);
            if ($userUpdate) {
                $this->emailService->sendPasswordResetEmail($userReset);
                return $userReset;
            }
            return 0;
        } else {
            return 0;
        }
    }

    public function getOrganizationByUserId($id=null) {
        if(empty($id))
        {
            $id = AuthContext::get(AuthConstants::USER_ID);
        }
        $queryO = "Select org.id,org.name,org.address,org.city,org.state,org.zip,org.logo,org.labelfile,org.languagefile,org.status from ox_organization as org LEFT JOIN ox_user_org as uo ON uo.org_id=org.id";
        $where = "where uo.user_id =".$id." AND org.status='Active'";
        $resultSet = $this->executeQuerywithParams($queryO, $where);
        return $resultSet->toArray();
    }

    public function getAppsByUserId($id=null) {
        $orgId = AuthContext::get(AuthConstants::ORG_ID);
        $userId = $id;
        if(!isset($userId)){
            $userId = AuthContext::get(AuthConstants::USER_ID);
        }
        $query = "SELECT DISTINCT oa.name,oa.description, oa.uuid, oa.type, oa.logo, oa.category from ox_app as oa INNER JOIN ox_app_registry as oar ON oa.id = oar.app_id INNER JOIN         ox_privilege as op on oar.app_id = op.app_id INNER JOIN ox_role_privilege as orp ON op.name = orp.privilege_name AND orp.org_id =".$orgId." INNER JOIN ox_user_role as   our ON orp.role_id = our.role_id AND our.user_id = ".$userId." union SELECT DISTINCT name,description, uuid, type, logo, category FROM ox_app as oa INNER JOIN ox_app_registry as oar ON oa.id= oar.app_id  WHERE oa.uuid NOT IN (SELECT app_id FROM ox_privilege WHERE app_id IS NOT NULL) AND oar.org_id =".$orgId;

        $result = $this->executeQuerywithParams($query);
        return $result->toArray();

    }
}
