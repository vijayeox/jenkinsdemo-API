<?php
namespace Oxzion\Service;

use Exception;
use Oxzion\AccessDeniedException;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Model\User;
use Oxzion\Model\UserTable;
use Oxzion\Model\Organization;
use Oxzion\Search\Elastic\IndexerImpl;
use Oxzion\Security\SecurityManager;
use Oxzion\ServiceException;
use Oxzion\Service\AbstractService;
use Oxzion\Service\AddressService;
use Oxzion\Service\EmailService;
use Oxzion\Service\TemplateService;
use Oxzion\Utils\BosUtils;
use Oxzion\Utils\FilterUtils;
use Oxzion\Utils\UuidUtil;
use Oxzion\Utils\ArrayUtils;
use Oxzion\Service\UserProfileService;
use Oxzion\Service\EmployeeService;
use Oxzion\ValidationException;
use Oxzion\Service\RoleService;

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
    private $cacheService;
    private $emailService;
    private $messageProducer;
    private $templateService;
    private $addressService;
    private $userProfile;
    private $empService;
    static $userField = array('uuid' => 'ou.uuid', 'username' => 'ou.username', 'firstname' => 'usrp.firstname', 'lastname' => 'usrp.lastname', 'name' => 'ou.name', 'email' => 'usrp.email', 'orgid' => 'ou.orgid', 'date_of_birth' => 'usrp.date_of_birth', 'designation' => 'oxemp.designation', 'phone' => 'usrp.phone', 'address1' => 'oa.address1', 'address2' => 'oa.address2', 'city' => 'oa.city', 'state' => 'oa.state', 'country' => 'oa.country', 'zip' => 'oa.zip', 'id' => 'ou.id', 'gender' => 'usrp.gender', 'website' => 'oxemp.website', 'about' => 'oxemp.about', 'managerid' => 'oxemp.managerid', 'timezone' => 'ou.timezone', 'date_of_join' => 'oxemp.date_of_join', 'interest' => 'oxemp.interest', 'preferences' => 'ou.preferences');

    public function setMessageProducer($messageProducer)
    {
        $this->messageProducer = $messageProducer;
    }

    public function __construct($config, $dbAdapter, UserTable $table = null, AddressService $addressService, EmailService $emailService, TemplateService $templateService, MessageProducer $messageProducer,RoleService $roleService,UserProfileService $userProfile, EmployeeService $empService)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
        $this->addressService = $addressService;
        $this->emailService = $emailService;
        $this->templateService = $templateService;
        $this->addressService = $addressService;
        $this->cacheService = CacheService::getInstance();
        $this->messageProducer = $messageProducer;
        $this->roleService = $roleService;
        $this->userProfile = $userProfile;
        $this->empService = $empService;
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

    public function getRolesofUser($orgId, $id)
    {
        $user_id = $this->getIdFromUuid('ox_user', $id);
        $orgId = $this->getIdFromUuid('ox_organization', $orgId);
        $select = "SELECT oro.uuid, oro.name from ox_user_role as ouo inner join ox_role as oro on ouo.role_id = oro.id where ouo.user_id = (SELECT ou.id from ox_user as ou where ou.id ='" . $user_id . "') and oro.org_id = " . $orgId;
        $resultSet = $this->executeQueryWithParams($select)->toArray();
        return $resultSet;
    }

    public function getUserContextDetails($userName)
    {
        $select = "SELECT ou.id,ou.name,ou.uuid as user_uuid,ou.orgid,org.uuid as org_uuid from ox_user as ou inner join ox_organization as org on ou.orgid = org.id where ou.username = '" . $userName . "'";
        $results = $this->executeQueryWithParams($select)->toArray();
        if (count($results) > 0) {
            $results = $results[0];
        }
        return $results;
    }

    public function getGroups($userName)
    {
        $data = $this->getGroupsFromDb($userName);
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
    public function createUser($params, &$data, $register = false)
    {
        if (!$register) {
            if (isset($params['orgId']) && $params['orgId'] != '') {
                if ((!SecurityManager::isGranted('MANAGE_INSTALL_APP_WRITE') && (!SecurityManager::isGranted('MANAGE_ORGANIZATION_WRITE') &&
                    ($params['orgId'] != AuthContext::get(AuthConstants::ORG_UUID))) && !isset($params['commands']))) {
                    throw new AccessDeniedException("You do not have permissions create user");
                } else {
                    $data['orgid'] = $this->getIdFromUuid('ox_organization', $params['orgId']);
                }
            } else {
                $data['orgid'] = AuthContext::get(AuthConstants::ORG_ID);
            }
        } else {
            if ($org = $this->getIdFromUuid('ox_organization', $params['orgId'])) {
                $orgId = $org;
            } else {
                if (isset($data['orgId']) && $data['orgId'] != '') {
                    $orgId = $params['orgId'];
                } else {
                    if (AuthContext::get(AuthConstants::ORG_ID) != null) {
                        $orgId = AuthContext::get(AuthConstants::ORG_ID);
                    }
                }
            }
            $data['orgid'] = $orgId;
        }
        if (!isset($params['orgId']) || $params['orgId'] == '') {
            $params['orgId'] = AuthContext::get(AuthConstants::ORG_UUID);
        }
        try {
            if (isset($data['id'])) {
                unset($data['id']);
            }
            $select = "SELECT ou.id,ou.uuid,count(ou.id) as org_count,ou.status,ou.username,usrp.email,GROUP_CONCAT(ouo.org_id) as organisation_id from ox_user as ou inner join ox_user_org as ouo on ouo.user_id = ou.id inner join ox_user_profile as usrp on usrp.id = ou.user_profile_id where ou.username = '" . $data['username'] . "' OR usrp.email = '" . $data['email'] . "' GROUP BY ou.id,ou.uuid,ou.status,usrp.email";
            $result = $this->executeQuerywithParams($select)->toArray();
            /*
            ? Is this required?????
             */
            if (count($result) > 1) {
                throw new ServiceException("Username or Email Exists in other Organization", "user.email.exists");
            }
            if (count($result) == 1) {
                $result[0]['organisation_id'] = isset($result[0]['organisation_id']) ? $result[0]['organisation_id'] : null;
                $orgList = explode(',', $result[0]['organisation_id']);
                $result[0]['org_count'] = isset($result[0]['org_count']) ? $result[0]['org_count'] : 0;
                if (in_array($data['orgid'], $orgList)) {
                    $countval = 0;
                    if ($result[0]['username'] == $data['username'] && $result[0]['status'] == 'Active') {
                        throw new ServiceException("Username/Email Exists", "duplicate.username");
                    } else if ($result[0]['email'] == $data['email'] && $result[0]['status'] == 'Active') {
                        throw new ServiceException("Email Exists", "duplicate.email");
                    } else if ($result[0]['status'] == "Inactive") {
                        $data['reactivate'] = isset($data['reactivate']) ? $data['reactivate'] : 0;
                        if ($data['reactivate'] == 1) {
                            $data['status'] = 'Active';
                            $select = "SELECT count(user_id) from ox_user_org where user_id = (SELECT id from ox_user where uuid = '" . $result[0]['uuid'] . "') and org_id = " . $data['orgid'];
                            $userOrg = $this->executeQuerywithParams($select)->toArray();
                            if ($userOrg[0]['count(user_id)'] == 0) {
                                $this->addUserToOrg($result[0]['id'], $data['orgid']);
                            }
                            $orgUuid = $this->getUuidFromId('ox_organization', $data['orgid']);
                            $orgId = $data['orgid'];
                            $countval = $this->updateUser($result[0]['uuid'], $data, $orgUuid);
                            if (isset($data['role'])) {
                                $this->addRoleToUser($result[0]['uuid'], $data['role'], $orgId);
                            }
                            if (isset($countval) == 1) {
                                return $result[0]['uuid'];
                            } else {
                                throw new ServiceException("Failed to Create User", "failed.create.user");
                            }
                        } else {
                            throw new ServiceException("User already exists would you like to reactivate?", "user.already.exists");
                        }
                    }
                } else {
                    throw new ServiceException("Username or Email Exists in other Organization", "user.email.exists");
                }
            }
            if (!isset($data['address1']) || empty($data['address1'])) {
                $addressData = $this->addressService->getOrganizationAddress( $params['orgId']);
                unset($addressData['id']);
                $data = array_merge($data, $addressData);
            }
            $this->userProfile->addUserProfile($data);
            $data['name'] = $data['firstname'] . " " . $data['lastname'];
            $data['uuid'] = UuidUtil::uuid();
            $data['date_created'] = date('Y-m-d H:i:s');
            $setPasswordCode = UuidUtil::uuid();
            $data['password_reset_code'] = $setPasswordCode;
            $data['created_by'] = AuthContext::get(AuthConstants::USER_ID) ? AuthContext::get(AuthConstants::USER_ID) : 1;
            if (isset($data['managerid'])) {
                $data['managerid'] = $this->getIdFromUuid('ox_user', $data['managerid']);
            }
            $this->empService->addEmployeeRecord($data);
            if (isset($data['preferences'])) {
                if(is_string($data['preferences'])){
                    $preferences = json_decode($data['preferences'], true);
                } else {
                    $preferences = $data['preferences'];
                }
                $data['timezone'] = $preferences['timezone'];
                unset($preferences['timezone']);
                $data['preferences'] = json_encode($preferences);
            }
            $password = isset($data['password']) ? $data['password'] : BosUtils::randomPassword();
            if (isset($password)) {
                $data['password'] = md5(sha1($password));
            }
            if (!isset($data['status'])) {
                $data['status'] = 'Active';
            }
            $form = new User($data);
            $form->validate();
            $this->beginTransaction();
            $count = 0;
            $count = $this->table->save($form);
            if ($count == 0) {
                $this->rollback();
                throw new ServiceException("Failed to create a new entity", "failed.create.user");
            }
            $form->id = $data['id'] = $this->table->getLastInsertValue();
            $orgid = $this->getUuidFromId('ox_organization', $data['orgid']); //Template Service
            $this->messageProducer->sendTopic(json_encode(array(
                'username' => $data['username'],
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'email' => $data['email'],
                'orgId' => $orgid,
                'password' => $password,
                'uuid' => $data['uuid'],
                'resetCode' => $setPasswordCode,
                'subject' => isset($data['subject']) ? $data['subject'] : null
            )), 'USER_ADDED');
            $this->addUserToOrg($form->id, $form->orgid);
            if (isset($data['role'])) {
                $this->addRoleToUser($data['uuid'], $data['role'], $form->orgid);
            }
            // $this->emailService->sendUserEmail($form);
            // Code to add the user information to the Elastic Search Index
            // $result = $this->messageProducer->sendTopic(json_encode(array('userInfo' => $data)), 'USER_CREATED');
            // $es = $this->generateUserIndexForElastic($data);
            $this->commit();
            return $count;
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
    }

    public function updateUserProjects($userId, $project, $orgId)
    {
        $projectSingleArray = array_map('current', $project);
        try {
            $delete = "DELETE oxup FROM ox_user_project as oxup
                        inner join ox_project as oxp on oxup.project_id = oxp.id where oxp.uuid not in 
                        ('" . implode("','", $projectSingleArray) . "') and oxup.user_id = " . $userId . " and oxp.org_id =" . $orgId;
            $result = $this->executeQuerywithParams($delete);
            $query = "Insert into ox_user_project(user_id,project_id) SELECT " . $userId . ",oxp.id from ox_project as oxp 
                        LEFT OUTER JOIN ox_user_project as oxup on oxp.id = oxup.project_id and oxup.user_id = " . $userId . 
                        " where oxp.uuid in ('" . implode("','", $projectSingleArray) . "') and oxp.org_id = " . $orgId . " and oxup.user_id is null";
            $resultInsert = $this->runGenericQuery($query);
        } catch (Exception $e) {
            throw $e;
        }
        return 1;
    }

    public function addRoleToUser($id, $role, $orgId)
    {
        $obj = $this->table->getByUuid($id, array());
        if (is_null($obj)) {
            return 0;
        }
        if (!isset($role) || empty($role)) {
            return 2;
        }
        $userId = $obj->id;
        if ($role) {
            $roleSingleArray = array_map('current', $role);
            try {
                $delete = "DELETE our FROM ox_user_role as our
                            inner join ox_role as oro on our.role_id = oro.id where oro.uuid not in ('" . implode("','", $roleSingleArray) . "') and our.user_id = " . $userId . " and oro.org_id =" . $orgId;

                $result = $this->executeQuerywithParams($delete);
                $query = "Insert into ox_user_role(user_id,role_id) SELECT " . $userId . ",oro.id from ox_role as oro LEFT OUTER JOIN ox_user_role as our on oro.id = our.role_id and our.user_id = " . $userId . " where oro.uuid in ('" . implode("','", $roleSingleArray) . "') and oro.org_id = " . $orgId . " and our.user_id is null";
                $resultInsert = $this->runGenericQuery($query);
            } catch (Exception $e) {
                throw $e;
            }
            return 1;
        }
        throw new ServiceException("Failed to create a new entity", "failed.create.user");
    }

    private function getRoleIdList($uuidList)
    {
        $uuidList = array_unique(array_map('current', $uuidList));
        $query = "SELECT id from ox_role where uuid in ('" . implode("','", $uuidList) . "')";
        $result = $this->executeQueryWithParams($query)->toArray();
        return $result;
    }

    public function createAdminForOrg($org, $contactPerson, $orgPreferences)
    {
        $params = array();
        $contactPerson = (object) $contactPerson;
        $orgPreferences = (object) $orgPreferences;
        $preferences = array(
            "soundnotification" => "true",
            "emailalerts" => "false",
            "timezone" => isset($orgPreferences->timezone) ? $orgPreferences->timezone : '',
            "dateformat" => isset($orgPreferences->dateformat) ? $orgPreferences->dateformat : '',
        );
        $data = array(
            "firstname" => $contactPerson->firstname,
            "lastname" => $contactPerson->lastname,
            "email" => $contactPerson->email,
            "phone" => isset($contactPerson->phone) ? $contactPerson->phone : '',
            "company_name" => $org['name'],
            "address1" => $org['address1'],
            "address2" => isset($org['address2']) ? $org['address2'] : null,
            "city" => $org['city'],
            "state" => $org['state'],
            "country" => $org['country'],
            "zip" => $org['zip'],
            "preferences" => json_encode($preferences),
            "username" => $contactPerson->username,
            "date_of_birth" => date('Y-m-d'),
            "orgid" => $org['id'],
            "status" => "Active",
            "timezone" => $preferences['timezone'],
            "gender" => " ",
            "password" => BosUtils::randomPassword(),
        );
        if($org['type'] == Organization::BUSINESS){
            $data["designation"] = "Admin";
            $data["date_of_join"] = date('Y-m-d');
        }
        $params['orgId'] = $org['uuid'];
        $password = $data['password'];
        $this->beginTransaction();
        try {
            $result = $this->createUser($params, $data);
            $select = "SELECT id from `ox_user` where username = '" . $data['username'] . "'";
            $resultSet = $this->executeQueryWithParams($select)->toArray();
            $response = $this->addUserRole($resultSet[0]['id'], 'ADMIN');
            if($response == 2){
                //Did not find admin role so add Add all roles of organization
                $roles = $this->getDataByParams('ox_role', array('name'), array('org_id' => $org['id']))->toArray();
                foreach ($roles as $key => $value) {
                    $this->addUserRole($resultSet[0]['id'], $value);
                }
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
        $data['password'] = $password;
        $data['orgid'] = $org['uuid']; // overriding uuid for Template Service
        $this->messageProducer->sendQueue(json_encode(array(
            'To' => $data['email'],
            'Subject' => $org['name'] . ' created!',
            'body' => $this->templateService->getContent('newAdminUser', $data),
        )), 'mail');
        return $resultSet[0]['id'];
    }

    public function addAppRolesToUser($userId,$appId){
        if (isset($appId)) {
            $appId = $this->getIdFromUuid('ox_app',$appId);
            $result = $this->roleService->getRolesByAppId($appId);
            foreach ($result as $role) {
                $this->addUserRole($userId,$role['name']);
            }
        }
    }

    private function addUserRole($userId, $roleName)
    {
        if (!is_numeric($userId)) {
            $user = $this->getDataByParams('ox_user', array('id', 'orgid'), array('uuid' => $userId))->toArray();
        }else{
           $user = $this->getDataByParams('ox_user', array('id', 'orgid'), array('id' => $userId))->toArray(); 
        }
        if ($user){
            if ($role = $this->getDataByParams('ox_role', array('id'), array('org_id' => $user[0]['orgid'], 'name' => $roleName))->toArray()) {
                if (!$this->getDataByParams('ox_user_role', array(), array('user_id' => $user[0]['id'], 'role_id' => $role[0]['id']))->toArray()) {
                    $data = array(array(
                        'user_id' => $user[0]['id'],
                        'role_id' => $role[0]['id'],
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
    public function updateUser($id, &$data, $orgId = null)
    {
        if (isset($orgId)) {
            if (!SecurityManager::isGranted('MANAGE_ORGANIZATION_WRITE') &&
                ($orgId != AuthContext::get(AuthConstants::ORG_UUID))) {
                throw new AccessDeniedException("You do not have permissions to assign role to user");
            } else {
                $orgId = $this->getIdFromUuid('ox_organization', $orgId);
            }
        }
        $obj = $this->table->getByUuid($id, array());
        if (is_null($obj)) {
            throw new ServiceException("User not found", "user.not.found");
        }
        $select = "SELECT ou.id,ou.password,ou.status,ou.created_by,ou.date_created,ou.user_profile_id,ou.uuid,ou.username,usrp.uuid as userProfileUuid,usrp.address_id,usrp.firstname,usrp.lastname,ou.name,usrp.email,oxemp.designation,ou.orgid,usrp.phone,usrp.date_of_birth,oxemp.date_of_join,oa.address1,oa.address2,oa.city,oa.state,oa.country,oa.zip,oxemp.website,oxemp.about,usrp.gender,oxemp.managerid,oxemp.interest,ou.icon,ou.preferences from ox_user as ou inner join ox_user_profile as usrp on usrp.id = ou.user_profile_id inner join ox_employee as oxemp on oxemp.user_profile_id = usrp.id left join ox_address as oa on usrp.address_id = oa.id where ou.orgid = " . $obj->toArray()['orgid'] . " AND ou.uuid = '" . $id . "'";
        $obj = $this->executeQuerywithParams($select)->toArray();
        $obj = $obj[0];
        $select = "SELECT org_id from ox_user_org where user_id = " . $obj['id'];
        $result = $this->executeQuerywithParams($select)->toArray();
        $orgArray = array_map('current', $result);
        if (isset($orgId)) {
            if (!in_array($orgId, $orgArray)) {
                throw new ServiceException('User does not belong to the organization', 'user.not.found');
            }
        }
        $form = new User();
        if (isset($data['orgid'])) {
            unset($data['orgid']);
        }
        $userdata = array_merge($obj, $data); //Merging the data from the db for the ID
        if (isset($userdata['address_id'])) {
            $this->addressService->updateAddress($userdata['address_id'], $data);
        } else {
            if(!empty($userdata['address1']) || !empty($userdata['city']) || 
                    !empty($userdata['state']) || !empty($userdata['country']) || !empty($userdata['zip'])) {
                            $addressid = $this->addressService->addAddress($data);
                            $userdata['address_id'] = $addressid;
                        }
        }
        $this->logger->info("DATA--------\n".print_r($obj,true));
        $this->logger->info("USER-DATA--------\n".print_r($userdata,true));
        // if ((isset($userdata['firstname']) && $obj['firstname']!=$userdata['firstname']) || (isset($userdata['lastname']) && $obj['lastname']!=$userdata['lastname'])) {
                $this->userProfile->updateUserProfile($userdata['user_profile_id'],$userdata);
        // }    
        $userdata['name'] = $userdata['firstname'] . " " . $userdata['lastname'];
        $userdata['uuid'] = $id;
        if (isset($data['managerid'])) {
            $userdata['managerid'] = $this->getIdFromUuid('ox_user', $data['managerid']);
        }
        $empId = $this->getDataByParams('ox_employee', array('id', 'uuid'), array('user_profile_id' => $userdata['user_profile_id']))->toArray();
        if (isset($empId) && (count($empId) > 0)) {         
            $this->empService->updateEmployeeDetails($empId[0]['id'],$userdata);
        }

        $userdata['modified_id'] = isset($userdata['modified_id']) ? $userdata['modified_id'] : AuthContext::get(AuthConstants::USER_ID);
        $userdata['date_modified'] = date('Y-m-d H:i:s');
        if (isset($userdata['preferences'])) {
            if (!is_array($userdata['preferences'])) {
                $preferences = json_decode($userdata['preferences'], true);
            } else {
                $preferences = $userdata['preferences'];
            }
            if (isset($preferences['timezone'])) {
                $userdata['timezone'] = $preferences['timezone'];
                unset($preferences['timezone']);
            }
            $userdata['preferences'] = json_encode($preferences);
        }
        $form->exchangeArray($userdata);
        $form->validate();
        $this->beginTransaction();
        try {
            $this->table->save($form);
            if (isset($data['role'])) {
                $this->addRoleToUser($form->uuid, $data['role'], $form->orgid);
            }
            if (isset($data['project'])) {
                $this->updateUserProjects($obj['id'], $data['project'], $form->orgid);
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
        $this->logger->info("USER DATA UPDATED----\n".print_r($userdata,true));
        return $userdata;
    }

    private function getOrg($id)
    {
        $select = "SELECT oxo.id,oxo.name from ox_organization oxo where oxo.id =:id";
        $params = array("id" => $id);
        $response = $this->executeQueryWithBindParameters($select,$params)->toArray();
        if (count($response) == 0) {
            return 0;
        }
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
        if (isset($id['orgId'])) {
            if (!SecurityManager::isGranted('MANAGE_ORGANIZATION_WRITE') &&
                ($id['orgId'] != AuthContext::get(AuthConstants::ORG_UUID))) {
                throw new AccessDeniedException("You do not have permissions to delete the user");
            } else {
                $orgId = $this->getIdFromUuid('ox_organization', $id['orgId']);
            }
        } else {
            $orgId = AuthContext::get(AuthConstants::ORG_ID);
        }
        $obj = $this->table->getByUuid($id['userId'], array());
        if (is_null($obj)) {
            throw new ServiceException("User not found", 'user.not.found');
        }
        $select = "SELECT org_id from ox_user_org where user_id = " . $obj->id;
        $result = $this->executeQuerywithParams($select)->toArray();
        $orgArray = array_map('current', $result);
        if (!in_array($orgId, $orgArray)) {
            throw new ServiceException('User does not belong to the organization', 'user.not.found');
        }
        $select = "SELECT contactid from ox_organization where id = " . $orgId;
        $result1 = $this->executeQuerywithParams($select)->toArray();
        if ($result1[0]['contactid'] == $obj->id) {
            throw new ServiceException('Not allowed to delete Admin user', 'admin.user');
        }
        $select = "SELECT count(id) from ox_group where manager_id = " . $obj->id;
        $result2 = $this->executeQuerywithParams($select)->toArray();
        if ($result2[0]['count(id)'] > 0) {
            throw new ServiceException('Not allowed to delete the group manager', 'group.manager');
        }
        $select = "SELECT count(id) from ox_project where manager_id = " . $obj->id;
        $result3 = $this->executeQuerywithParams($select)->toArray();
        if ($result3[0]['count(id)'] > 0) {
            throw new ServiceException('Not allowed to delete the project manager', 'project.manager');
        }
        $org = $this->getOrg($obj->orgid);
        $originalArray = $obj->toArray();
        $form = new User();
        $originalArray['status'] = 'Inactive';
        $originalArray['modified_id'] = AuthContext::get(AuthConstants::USER_ID);
        $originalArray['date_modified'] = date('Y-m-d H:i:s');
        $form->exchangeArray($originalArray);
        $form->validate();
        $result = $this->table->save($form);
        // $delete = "DELETE FROM ox_user_org where user_id = " . $obj->id . " AND org_id = " . $obj->orgid;
        // $result1 = $this->executeQuerywithParams($delete);
        $this->messageProducer->sendTopic(json_encode(array('username' => $obj->username, 'orgname' => $org['name'])), 'USER_DELETED');
        return $result;
    }

    /**
     * GET List User API
     * @api
     * @link /user
     * @method GET
     * @return array $dataget list of Users
     */
    public function getUsers($filterParams = null, $baseUrl = '', $params = null)
    {
        if (isset($params['orgId'])) {
            if (!SecurityManager::isGranted('MANAGE_ORGANIZATION_READ') &&
                ($params['orgId'] != AuthContext::get(AuthConstants::ORG_UUID))) {
                throw new AccessDeniedException("You do not have permissions get the users list");
            } else {
                $orgId = $this->getIdFromUuid('ox_organization', $params['orgId']);
            }
        } else {
            $orgId = AuthContext::get(AuthConstants::ORG_ID);
        }
        $where = "";
        $pageSize = 20;
        $offset = 0;
        $sort = "name";
        $select = "SELECT ou.uuid, ou.username, usrp.firstname, usrp.lastname, ou.name,
                usrp.email, ou.orgid, ou.icon, usrp.date_of_birth,
                oxemp.designation,usrp.phone,oa.address1,oa.address2,oa.city,oa.state,oa.country,oa.zip,usrp.gender,oxemp.website,oxemp.about,
                oxemp.managerid, ou.timezone, oxemp.date_of_join, oxemp.interest, ou.preferences";
        $from = " FROM `ox_user` as ou join ox_user_profile usrp on usrp.id = ou.user_profile_id inner join ox_employee oxemp on oxemp.user_profile_id = usrp.id left join ox_address as oa on usrp.address_id = oa.id ";
        $cntQuery = "SELECT count(ou.id) as org_count " . $from;
        if (count($filterParams) > 0 || sizeof($filterParams) > 0) {
            if (isset($filterParams['filter'])) {
                $filterArray = json_decode($filterParams['filter'], true);
                if (isset($filterArray[0]['filter'])) {
                    $filterlogic = isset($filterArray[0]['filter']['logic']) ? $filterArray[0]['filter']['logic'] : "AND";
                    $filterList = $filterArray[0]['filter']['filters'];
                    $where = " WHERE " . FilterUtils::filterArray($filterList, $filterlogic, self::$userField);
                }
                if (isset($filterArray[0]['sort']) && count($filterArray[0]['sort']) > 0) {
                    $sort = $filterArray[0]['sort'];
                    $sort = FilterUtils::sortArray($sort, self::$userField);
                }
                $pageSize = $filterArray[0]['take'];
                $offset = $filterArray[0]['skip'];
            }
            if (isset($filterParams['exclude'])) {
                $where .= strlen($where) > 0 ? " AND ou.uuid NOT in ('" . implode("','", $filterParams['exclude']) . "') " : " WHERE ou.uuid NOT in ('" . implode("','", $filterParams['exclude']) . "') ";
            }
        }

        $where .= strlen($where) > 0 ? " AND ou.status = 'Active' AND ou.orgid = " . $orgId : " WHERE ou.status = 'Active' AND ou.orgid = " . $orgId;
        $sort = " ORDER BY " . $sort;
        $limit = " LIMIT " . $pageSize . " offset " . $offset;
        $resultSet = $this->executeQuerywithParams($cntQuery . $where);
        $count = $resultSet->toArray()[0]['org_count'];
        $query = $select . " " . $from . " " . $where . " " . $sort . " " . $limit;
        $this->logger->info("Executing GET LIST Query - $query");
        $resultSet = $this->executeQuerywithParams($query);
        $result = $resultSet->toArray();
        for ($x = 0; $x < sizeof($result); $x++) {
            $result[$x]['preferences'] = json_decode($result[$x]['preferences'], true);
            $result[$x]['preferences']['timezone'] = $result[$x]['timezone'];
            $result[$x]['icon'] = $baseUrl . "/user/profile/" . $result[$x]['uuid'];
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
    public function getUser($id, $getAllFields = false)
    {
        $sql = $this->getSqlObject();
        $select = "SELECT ou.uuid,ou.username,usrp.firstname,usrp.lastname,ou.name,usrp.email,ou.orgid,ou.icon,oa.address1,oa.address2,oa.city,oa.state,oa.country,oa.zip,usrp.date_of_birth,oxemp.designation,usrp.phone,usrp.gender,oxemp.website,oxemp.about,oxemp.managerid,ou.timezone,oxemp.date_of_join,oxemp.interest,ou.preferences,ou.password,ou.password_reset_expiry_date,ou.password_reset_code from ox_user as ou inner join ox_user_profile as usrp on usrp.id = ou.user_profile_id inner join ox_employee as oxemp on oxemp.user_profile_id = usrp.id left join ox_address as oa on usrp.address_id = oa.id where ou.id =" . $id . " and ou.status = 'Active'";
        $response = $this->executeQuerywithParams($select)->toArray();
        if (!$response) {
            return $response[0];
        }
        $result = $response[0];
        if (!$getAllFields) {
            unset($result['password']);
            unset($result['password_reset_expiry_date']);
            unset($result['password_reset_code']);
        }
        $getManagerUUID = $sql->select();
        $getManagerUUID->from('ox_user')
            ->columns(array("uuid"))
            ->where(array('ox_user.id' => $result['managerid']));
        $responseUUID = $this->executeQuery($getManagerUUID)->toArray();
        if (isset($responseUUID) && sizeof($responseUUID) > 0) {
            $result['managerid'] = $responseUUID[0]['uuid'];
        } else {
            $result['managerid'] = 0;
        }
        $result['active_organization'] = $this->getActiveOrganization(AuthContext::get(AuthConstants::ORG_ID));
        $result['preferences'] = json_decode($response[0]['preferences'], true);
        $result['preferences']['timezone'] = $response[0]['timezone'];
        $getUUID = $sql->select();
        $getUUID->from('ox_organization')
            ->columns(array("uuid"))
            ->where(array('ox_organization.id' => AuthContext::get(AuthConstants::ORG_ID)));
        $responseUUID = $this->executeQuery($getUUID)->toArray();
        $result['orgid'] = $responseUUID[0]['uuid'];
        if (isset($result)) {
            return $result;
        } else {
            return 0;
        }
    }

    public function getUserByUuid($uuid)
    {
        $select = "SELECT id from `ox_user` where uuid = '" . $uuid . "'";
        $result = $this->executeQueryWithParams($select)->toArray();
        if ($result) {
            return $result[0]['id'];
        } else {
            return 0;
        }
    }

    public function getActiveOrganization($id)
    {
        $select = "SELECT oxo.id,oxo.uuid,oxo.name from ox_organization oxo where oxo.id =:id";
        $params = array("id" => $id);
        $response = $this->executeQueryWithBindParameters($select,$params)->toArray();
        if (count($response) == 0) {
            return 0;
        }
        return $response[0];
    }

    public function getPrivileges($userId, $orgId = null)
    {
        if (!isset($orgId)) {
            $orgId = AuthContext::get(AuthConstants::ORG_ID);
        }
        $data = $this->getPrivilegesFromDb($userId, $orgId);
        return $data;
    }

    private function getPrivilegesFromDb($userId, $orgId)
    {
        $sql = $this->getSqlObject();
        $query = "select privilege_name, permission from ox_role_privilege rp
                    INNER join ox_user_role ur on ur.role_id = rp.role_id
                    where ur.user_id = " . $userId . " and rp.org_id = " . $orgId;
        $results = $this->executeQueryWithParams($query)->toArray();
        $permissions = array();
        foreach ($results as $key => $value) {
            $permissions = array_merge($permissions, $this->addPermissions($value['privilege_name'], $value['permission']));
        }
        return $permissions;
    }

    public function addPermissions($privilegeName, $permission)
    {
        $permissionArray = array();
        if (($permission & 1) != 0) {
            $permissionData = $privilegeName . "_" . 'READ';
            $permissionArray[$permissionData] = true;
        }
        if (($permission & 2) != 0) {
            $permissionData = $privilegeName . "_" . 'WRITE';
            $permissionArray[$permissionData] = true;
        }
        if (($permission & 4) != 0) {
            $permissionData = $privilegeName . "_" . 'CREATE';
            $permissionArray[$permissionData] = true;
        }
        if (($permission & 8) != 0) {
            $permissionData = $privilegeName . "_" . 'DELETE';
            $permissionArray[$permissionData] = true;
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
    public function getUserWithMinimumDetails($id, $orgid=null)
    {
        $o_id = $orgid != null ? $orgid : AuthContext::get(AuthConstants::ORG_UUID);
        $o_id = $this->getIdFromUuid('ox_organization', $o_id);
        $select = "SELECT ou.id,ou.password,ou.created_by,ou.date_created,ou.user_profile_id,ou.uuid,ou.username,usrp.address_id,usrp.firstname,usrp.lastname,ou.name,usrp.email,oxemp.designation,ou.orgid,usrp.phone,usrp.date_of_birth,oxemp.date_of_join,oa.address1,oa.address2,oa.city,oa.state,oa.country,oa.zip,oxemp.website,oxemp.about,usrp.gender,oxemp.managerid,oxemp.interest,ou.icon,ou.preferences from ox_user as ou inner join ox_user_profile as usrp on usrp.id = ou.user_profile_id inner join ox_employee as oxemp on oxemp.user_profile_id = usrp.id left join ox_address as oa on usrp.address_id = oa.id where ou.orgid = " . $o_id . " AND ou.id = " . $id . " AND ou.status = 'Active'";
        $response = $this->executeQuerywithParams($select)->toArray();
        if (empty($response)) {
            return 0;
        }
        $result = $response[0];
        $result['preferences'] = json_decode($response[0]['preferences'], true);
        if (isset($result['timezone'])) {
            $result['preferences']['timezone'] = $result['timezone'];
        }
        $result['orgid'] = $this->getUuidFromId('ox_organization', $result['orgid']);
        $result['managerid'] = $this->getUuidFromId('ox_user', $result['managerid']);
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
        $select = "SELECT ou.id,ou.uuid,ou.username,usrp.firstname,usrp.lastname,ou.name,usrp.email,ou.orgid,oa.address1,oa.address2,oa.city,oa.state,oa.country,oa.zip from ox_user as ou inner join ox_user_profile usrp on usrp.id = ou.user_profile_id LEFT join ox_address as oa on usrp.address_id = oa.id where ou.username = '" . $username . "' OR usrp.email = '" . $username . "'";
        $response = $this->executeQuerywithParams($select)->toArray();
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
    //  TODO CHANGE TO UUID //
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

// check this
    /**
     * @param $searchVal
     * @return array
     */
    public function getUserBySearchName($searchVal)
    {
        $sql = $this->getSqlObject();
        $select = $sql->select()
            ->from('ox_user_profile')
            ->columns(array('id', 'firstname', 'lastname')) // Instead of getting the id from the userTable,
        // we need to get the UUID. Once UUID is added to the table we need to make that change
            ->where(array('firstname LIKE "%' . $searchVal . '%" OR lastname LIKE "%' . $searchVal . '%"'));
        return $result = $this->executeQuery($select)->toArray();
    }

    /**
     * @param $userName
     * @return array|\Zend\Db\ResultSet\ResultSet
     */
    public function getUserDetailsbyUserName($userName, $columns = null)
    {
        $whereCondition = "username = '" . $userName . "'";
        if ($columns) {
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

    private function addUserToOrg($userId, $organizationId)
    {
        $this->logger->info("USERID--- $userId with ORG --- $organizationId");
        if ($user = $this->getDataByParams('ox_user', array('id', 'username'), array('id' => $userId))->toArray()) {
            if ($org = $this->getDataByParams('ox_organization', array('id', 'name'), array('id' => $organizationId, 'status' => 'Active'))->toArray()) {
                if (!$this->getDataByParams('ox_user_org', array(), array('user_id' => $userId, 'org_id' => $organizationId))->toArray()) {
                    $data = array(array(
                        'user_id' => $userId,
                        'org_id' => $organizationId,
                        'default' => 1,
                    ));
                    $result_update = $this->multiInsertOrUpdate('ox_user_org', $data);
                    if ($result_update->getAffectedRows() == 0) {
                        return $result_update;
                    }
                    $message = json_encode(array('orgname' => $org[0]['name'], 'status' => 'Active', 'username' => $user[0]["username"]));
                    $this->logger->info("USERTOORGANIZATION_ADDED-----\n",print_r($message,true));
                    $this->messageProducer->sendTopic($message, 'USERTOORGANIZATION_ADDED');
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
        $query = "SELECT uuid,name from (SELECT DISTINCT app.uuid, app.name , count(NULLIF(urp.privilege_name,NULL)) as app_count from (
                    SELECT DISTINCT ap.uuid, ap.name, op.name as privilege_name, ar.org_id from ox_app as ap
                    INNER JOIN
                    ox_app_registry as ar ON ap.id = ar.app_id INNER JOIN
                    ox_privilege as op ON ar.app_id = op.app_id where ar.org_id =" . $orgId . ") app LEFT JOIN
                    (SELECT DISTINCT orp.privilege_name from ox_role_privilege as orp JOIN
                    ox_user_role as ou on orp.role_id = ou.role_id AND ou.user_id =" . $userId . " and orp.org_id = " . $orgId . ") urp ON app.privilege_name = urp.privilege_name GROUP BY app.uuid,app.name) a WHERE a.app_count = 0 union SELECT oa.uuid, oa.name FROM ox_app oa LEFT JOIN
                    `ox_app_registry` ar on oa.id = ar.app_id and ar.org_id =" . $orgId . " WHERE org_id IS NULL";
        $this->logger->info("Query - " . $query);
        $result = $this->executeQuerywithParams($query);
        $result = $result->toArray();
        $arr = array();
        for ($i = 0; $i < sizeof($result); $i++) {
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

    public function resetPassword($data)
    {
        $resetCode = $data['password_reset_code'];
        $password = md5(sha1($data['new_password']));
        $expiry = date("Y-m-d H:i:s");
        $query = "select id from ox_user where (password_reset_expiry_date > '" . $expiry . "' OR password_reset_expiry_date is NULL) and password_reset_code = '" . $resetCode . "'";
        $result = $this->executeQuerywithParams($query);
        $result = $result->toArray();
        if (count($result) == 0) {
            throw new ServiceException("Invalid Reset Code", "invalid.reset.code");
        }
        $query = "update ox_user set password = '" . $password . "', password_reset_code = NULL, password_reset_expiry_date = NULL where password_reset_code = '" . $resetCode . "'";
        $result = $this->executeQuerywithParams($query);

    }

    public function sendResetPasswordCode($username)
    {
        $resetPasswordCode = UuidUtil::uuid();
        $userDetails = $this->getUserBaseProfile($username);
        if ($username === $userDetails['username']) {
            $userReset['username'] = $userDetails['username'];
            $userReset['email'] = $userDetails['email'];
            $userReset['firstname'] = $userDetails['firstname'];
            $userReset['lastname'] = $userDetails['lastname'];
            $userReset['url'] = $this->config['applicationUrl'] . "/?resetpassword=" . $resetPasswordCode;
            $userReset['password_reset_expiry_date'] = date("Y-m-d H:i:s", strtotime("+24 hours"));
            $userReset['orgId'] = $this->getUuidFromId('ox_organization', $userDetails['orgid']);
            $userDetails['password_reset_expiry_date'] = $userReset['password_reset_expiry_date'];
            $userDetails['password_reset_code'] = $resetPasswordCode;
            $userRecord = $userDetails['firstname']."_".$userDetails['username']."@eoxvantage.";
            if(($userDetails['email'] == $userRecord."com") || ($userDetails['email'] == $userRecord."in")){
                throw new ValidationException("Invalid Email");
            }
            //Code to update the password reset and expiration time
            $userUpdate = $this->updateUser($userDetails['uuid'], $userDetails);
            if ($userUpdate) {
                $subject = $userReset['firstname'] . ', You login details for EOX vantage!';
                $bcc = " ";
                if(isset($this->config['emailConfig'])){
                    $emailConfig = $this->config['emailConfig'];
                    if(isset($emailConfig['resetPassword'])){
                        $subject = isset($emailConfig['resetPassword']['subject']) ? $userReset['firstname'].', '.$emailConfig['resetPassword']['subject'] : $userReset['firstname'] . ', You login details for EOX vantage!';
                    }
                }
                $this->messageProducer->sendQueue(json_encode(array(
                    'to' => $userReset['email'],
                    'subject' => $subject,
                    'body' => $this->templateService->getContent('resetPassword', $userReset),
                )), 'mail');
                $userReset['email']= $this->hideEmailAddress($userReset['email']);
                return $userReset;
            }
            return 0;
        } else {
            return 0;
        }
    }

    public function getOrganizationByUserId($id = null)
    {
        if (empty($id)) {
            $id = AuthContext::get(AuthConstants::USER_ID);
        }
        $queryO = "Select org.id,org.name,org.uuid,oxa.address1,oxa.address2,oxa.city,oxa.state,oxa.country,oxa.zip,org.logo,oxop.labelfile,oxop.languagefile,org.status from ox_organization as org join ox_organization_profile oxop on oxop.id=org.org_profile_id join ox_address as oxa on oxa.id = oxop.address_id LEFT JOIN ox_user_org as uo ON uo.org_id=org.id";
        $where = "where uo.user_id =" . $id . " AND org.status='Active'";
        $resultSet = $this->executeQuerywithParams($queryO, $where);
        return $resultSet->toArray();
    }

    public function getAppsByUserId($id = null)
    {
        $orgId = AuthContext::get(AuthConstants::ORG_ID);
        $userId = $id;
        if (!isset($userId)) {
            $userId = AuthContext::get(AuthConstants::USER_ID);
        }
        $query = "SELECT DISTINCT oa.name,oa.description, oa.uuid, oa.type, oa.logo, oa.category from ox_app as oa INNER JOIN ox_app_registry as oar ON oa.id = oar.app_id INNER JOIN         ox_privilege as op on oar.app_id = op.app_id INNER JOIN ox_role_privilege as orp ON op.name = orp.privilege_name AND orp.org_id =" . $orgId . " INNER JOIN ox_user_role as   our ON orp.role_id = our.role_id AND our.user_id = " . $userId . " union SELECT DISTINCT name,description, uuid, type, logo, category FROM ox_app as oa INNER JOIN ox_app_registry as oar ON oa.id= oar.app_id  WHERE oa.uuid NOT IN (SELECT app_id FROM ox_privilege WHERE app_id IS NOT NULL) AND oar.org_id =" . $orgId;
        $result = $this->executeQuerywithParams($query);
        return $result->toArray();
    }

    public function userProfile($params)
    {
        $select = "SELECT
        emp.about,
        user.username,
        usrp.firstname,
        usrp.lastname,
        user.name,
        usrp.email,
        usrp.date_of_birth,
        emp.designation,
        usrp.gender,
        emp.managerid,
        emp.date_of_join,
        usrp.phone,
        user.preferences,
        user.timezone,
        addr.address1,
        addr.address2,
        addr.city,
        addr.state,
        addr.country,
        addr.zip
    FROM
        ox_user as user
        JOIN ox_user_profile as usrp ON usrp.id = user.user_profile_id
        JOIN ox_employee as emp ON emp.user_profile_id = usrp.id
        LEFT JOIN ox_address as addr ON usrp.address_id = addr.id
        LEFT JOIN ox_user_org ON user.id = ox_user_org.user_id
        JOIN ox_organization as org ON ox_user_org.org_id = org.id
    WHERE
        user.uuid = '" . $params['userId'] . "'
        AND org.uuid = '" . $params['orgId'] . "'";
        $userData = $this->executeQuerywithParams($select)->toArray();
        if (count($userData) == 0) {
            return array('data' => array(), 'role' => array());
        }
        $userData = $userData[0];
        $userData['preferences'] = json_decode($userData['preferences'], true);
        $userData['orgid'] = $this->getUuidFromId('ox_organization', $params['orgId']);
        if(isset($userData['managerid']) && $userData['managerid'] != 0 ){
            $result = $this->getUserWithMinimumDetails($userData['managerid'], $params['orgId']);
            $userData['managerid'] = $this->getUuidFromId('ox_user', $userData['managerid']);
            $userData['manager_name'] = $result['firstname']." ".$result['lastname'];
        } else {
            $userData['manager_name'] = "";
        }
        $userData['role'] = $this->getRolesofUser($params['orgId'], $params['userId']);
        return $userData;
    }

    public function checkUserExists($data){
        $from = "FROM ox_user as u INNER join ox_user_profile usrp on usrp.id = u.user_profile_id ";
        $where = "WHERE (username=:username OR email=:email)";
        $queryParams = array("username" => $data['username'],
            "email" => $data['email']);
        $handleUserIdentifier = false;
        if (isset($data['app_id']) && isset($data['identifier_field'])) {
            if ($app = $this->getIdFromUuid('ox_app', $data['app_id'])) {
                $appId = $app;
            } else {
                $appId = $data['app_id'];
            }
            $from .= " INNER JOIN ox_wf_user_identifier ui ON ui.user_id = u.id";
            $where .= " AND ui.app_id = :appId 
                        OR (ui.identifier = :identifier AND ui.identifier_name = :identifierName)";
            $queryParams = array_merge($queryParams, array("appId" => $appId,
                "identifier" => $data[$data['identifier_field']],
                "identifierName" => $data['identifier_field']));
            
        }
        if (isset($data['date_of_birth'])) {
            $data['date_of_birth'] = date_format(date_create($data['date_of_birth']), "Y-m-d");
        }
        $query = "SELECT u.id,u.uuid,u.username,usrp.email $from $where";
        $this->logger->info("Check user query $query with Params" . json_encode($queryParams));
        $result = $this->executeQuerywithBindParameters($query, $queryParams)->toArray();
        if(count($result) > 0){
            if ($data['username'] == $result[0]['username'] && 
                    $data['email'] == $result[0]['email']) {
                $data['username'] = $result[0]['username'];
                $data['id'] = $result[0]['id'];
                $data['uuid'] = $result[0]['uuid'];
                return 1;
            } else {
                throw new ServiceException("Username/Email Used", "username.exists");
            }
        }
        return 0;
    }

    public function getUsersList($appUUid, $params)
    {
        try {
            $orgId = isset($params['orgId']) ? $this->getIdFromUuid('ox_organization', $params['orgId']) : AuthContext::get(AuthConstants::ORG_ID);
            $appId = $this->getIdFromUuid('ox_app', $appUUid);
            if (!isset($orgId)) {
                $orgId = $params['orgId'];
            } else if ($orgId == 0) {
                throw new ServiceException("Organization does not exist", "orgnot.found");
            }
            $select = "SELECT * from ox_app_registry where org_id = :orgId AND app_id = :appId";
            $selectQuery = array("orgId" => $orgId, "appId" => $appId);
            $result = $this->executeQuerywithBindParameters($select, $selectQuery)->toArray();
            if (count($result) > 0) {
                $where = "";
                $pageSize = 20;
                $offset = 0;
                $sort = "name";
                $select = "SELECT ou.uuid, ou.username, usrp.firstname, usrp.lastname, ou.name,ou.orgid";
                $from = " FROM `ox_user` as ou  inner join ox_user_profile usrp on usrp.id = ou.user_profile_id";
                $where .= strlen($where) > 0 ? " AND ou.status = 'Active' AND ou.orgid = " . $orgId : " WHERE ou.status = 'Active' AND ou.orgid = " . $orgId;
                $sort = " ORDER BY " . $sort;
                $limit = " LIMIT " . $pageSize . " offset " . $offset;
                $query = $select . " " . $from . " " . $where . " " . $sort . " " . $limit;
                $this->logger->info("Executing Query - $query");
                $resultSet = $this->executeQuerywithParams($query);
                $result = $resultSet->toArray();
                for ($x = 0; $x < sizeof($result); $x++) {
                    $result[$x]['icon'] = $this->getBaseUrl() . "/user/profile/" . $result[$x]['uuid'];
                }
                return $result;
            } else {
                throw new ServiceException("App Does not belong to the org", "app.fororgnot.found");
            }
        } catch (Exception $e) {
            throw $e;

        }
    }

    private function hideEmailAddress($email)
    {
        if(filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            list($first, $last) = explode('@', $email);
            $first = str_replace(substr($first, '3'), str_repeat('*', strlen($first)-3), $first);
            $last = explode('.', $last);
            $last_domain = str_replace(substr($last['0'], '1'), str_repeat('*', strlen($last['0'])-1), $last['0']);
            $hideEmailAddress = $first.'@'.$last_domain.'.'.$last['1'];
            return $hideEmailAddress;
        }
    }

    public function getUserDetailsByIdentifier($identifier,$identifierName){
        $select = "SELECT ou.* from ox_user as ou join ox_wf_user_identifier as owi on ou.id = owi.user_id WHERE owi.identifier = :identifier AND owi.identifier_name = :identifierName";
        $selectParams = array("identifier" => $identifier, "identifierName" => $identifierName);
        $result = $this->executeQuerywithBindParameters($select, $selectParams)->toArray();
        if(count($result) > 0){
            return $result[0];
        }else{
            return 0;
        }
    }
    public function getContactUserForOrg($orgId){
         $select = "SELECT ou.uuid as userId,ou.username,ou.firstname,ou.lastname
                    FROM ox_user ou INNER JOIN ox_organization org ON org.contactid = ou.id 
                    WHERE org.uuid=:orgId";
        $selectParams = array("orgId" => $orgId);
        $result = $this->executeQuerywithBindParameters($select, $selectParams)->toArray();
        if(count($result) > 0){
            return $result[0];
        }else{
            return $result;
        }
    }

    public function getPolicyTerm()
    {
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_user')
        ->columns(array('policy_terms'))
        ->where(array('id' => AuthContext::get(AuthConstants::USER_ID),'orgid' => AuthContext::get(AuthConstants::ORG_ID)));
        $result = $this->executeQuery($select)->toArray();
        return array_column($result, 'policy_terms');
    }

    public function updatePolicyTerms()
    {
        $sql = $this->getSqlObject();
        $updatedData['policy_terms'] = "1";
        $update = $sql->update('ox_user')->set($updatedData)
        ->where(array('ox_user.id' => AuthContext::get(AuthConstants::USER_ID),'ox_user.orgid' => AuthContext::get(AuthConstants::ORG_ID)));
        $result = $this->executeUpdate($update);
    }
}
