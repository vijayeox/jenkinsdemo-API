<?php
namespace Oxzion\Service;

use Zend\Db\Sql\Sql;
use Bos\Auth\AuthContext;
use Bos\Auth\AuthConstants;
use Bos\Service\AbstractService;
use Bos\ValidationException;
use Oxzion\Model\User;
use Oxzion\Utils\ArrayUtils;

class UserService extends AbstractService
{
    const GROUPS = '_groups';
    const ROLES = '_roles';
    const USER_FOLDER = "/users/";
    private $cacheService;
    private $id;

    /**
     * @ignore table
     */
    private $table;

    public function __construct($config, $dbAdapter, $table = null)
    {
        parent::__construct($config, $dbAdapter);
        $this->cacheService = CacheService::getInstance();
        if ($table) {
            $this->table = $table;
        }
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
        $select->from('avatars')
            ->columns(array("orgid"))
            ->where(array('avatars.username' => $username));
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
            ->from('avatars')
            ->columns(array('id', 'name', 'orgid'))
            ->where(array('username = "' . (string)$userName . '"'))->limit(1);
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
    public function createUser(&$data)
    {
        $form = new User();
        $data['orgid'] = AuthContext::get(AuthConstants::ORG_ID);
        $data['avatar_date_created'] = date('Y-m-d H:i:s');
        $data['uuid'] = uniqid();
        $form->exchangeArray($data);
        $form->validate();

        $count = 0;
        $count = $this->table->save($form);
        if ($count == 0) {
            return 0;
        }
        if ($this->getErrorCode != 0) {
            if ($this->getErrorCode == 1) {
                $this->getFailureResponse("User already exists", 404, $data);
            }
            return 0;
        }
        $id = $this->table->getLastInsertValue();
        $data['id'] = $id;
        // @@TODO $this->sendEmail();
        return $count;
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
        $originalArray = $obj->toArray();
        $form = new User();
        $data = array_merge($originalArray, $data);
        $data['id'] = $id;

        $form->exchangeArray($data);
        $form->validate();
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->save($form);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
            $data['id'] = $id;
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            return 0;
        }
        return $count;
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
        if (is_null($obj)) {
            return 0;
        }
        $originalArray = $obj->toArray();
        $form = new User();
        $originalArray['status'] = 'Inactive';
        $form->exchangeArray($originalArray);
        $form->validate();
        $result = $this->table->save($form);
        return $result;
    }

    /**
     * GET List User API
     * @api
     * @link /user
     * @method GET
     * @return array $dataget list of Users
     */
    public function getUsers($group_id = null)
    {
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('avatars')
            ->columns(array("*"))
            ->where(array('avatars.orgid' => AuthContext::get(AuthConstants::ORG_ID)));
        if ($group_id) {
            $select->join('groups_avatars', 'avatars.id = groups_avatars.avatarid', array('groupid', 'avatarid'), 'left')
                ->where(array('groups_avatars.groupid' => $group_id));
        }
        return $this->executeQuery($select)->toArray();
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
        $select->from('avatars')
            ->columns(array("*"))
            ->where(array('avatars.orgid' => AuthContext::get(AuthConstants::ORG_ID), 'avatars.id' => $id));
        $response = $this->executeQuery($select)->toArray();
        if (!$response) {
            return $response[0];
        }
        $result = $response[0];
        $groups = $this->getGroupsFromDb($id);
        $result['group'] = $groups;
        if (isset($result)) {
            return $result;
        } else {
            return 0;
        }
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
        $select->from('avatars')
            ->columns(array('username', 'firstname', 'lastname', 'name', 'email', 'designation', 'phone','dob','doj','country','website','about','sex','interest','address','icon'))
            ->where(array('avatars.orgid' => AuthContext::get(AuthConstants::ORG_ID), 'avatars.id' => $id));
        $response = $this->executeQuery($select)->toArray();
        if (!$response) {
            return $response[0];
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
        $delete->where(['user_id' => $userId, 'manager_id' => $managerId,]);
        $result = $this->executeUpdate($delete);
        if ($result->getAffectedRows() == 0) {
            return $result;
        }
        return 1;
    }

    public function addUserToGroup($userid, $groupid)
    {
        $sql = $this->getSqlObject();
        $queryString = "select id from avatars";
        $where = "where id =" . $userid;
        $resultSet = $this->executeQuerywithParams($queryString, $where, null, null);
        if ($resultSet) {
            $query = "select id from groups";
            $where = "where id =" . $groupid;
            $result = $this->executeQuerywithParams($query, $where, null, null);
            if ($result) {
                $query = "select id from ox_user_group";
                $where = "where avatar_id =" . $userid;
                $notexist_result = $this->executeQuerywithParams($query, $where, null, null)->toArray();
                if (!$notexist_result) {
                    $data = array(array('avatar_id' => $userid, 'group_id' => $groupid));
                    $result_update = $this->multiInsertOrUpdate('ox_user_group', $data, array());
                    if ($result_update->getAffectedRows() == 0) {
                        return $result_update;
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

    public function addUserToProject($userid, $projectid)
    {
        $sql = $this->getSqlObject();
        $queryString = "select id from avatars";
        $where = "where id =" . $userid;
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
     * @return \Oxzion\Utils\Array
     */
    public function getAppsWithoutAccessForUser()
    {
        $orgId = AuthContext::get(AuthConstants::ORG_ID);
        $userId = AuthContext::get(AuthConstants::USER_ID);
        $userRole = implode(array_column($this->getRolesFromDb($userId), 'role_id'), ",");
        $sql = $this->getSqlObject();

        //Code to get the name for which the logged in user has access to for the organization
        $select1 = $sql->select();
        $select1->from('ox_app')
            ->columns(array("id", "name"))
            ->join('ox_app_registry', 'ox_app_registry.app_id = ox_app.id', array(), 'left')
            ->join('ox_role_privilege', 'ox_role_privilege.app_id = ox_app.id', array(), 'left')
            ->join('ox_user_role', 'ox_user_role.role_id = ox_role_privilege.role_id', array(), 'left')
            ->where(array('ox_app_registry.org_id = ' . $orgId))
            ->where(array('ox_user_role.role_id IN (' . $userRole . ')'))
            ->group(array('ox_app.name'))
            ->order(array('ox_app.name'));
        $result1 = array_column($this->executeQuery($select1)->toArray(), 'id');

        //Code to get the list of all the apps available for the organization
        $select2 = $sql->select();
        $select2->from('ox_app')
            ->columns(array("id"))
            ->join('ox_app_registry', 'ox_app_registry.app_id = ox_app.id', array(), 'left')
            ->where(array('ox_app_registry.org_id = ' . $orgId))
            ->order(array('ox_app.name'));
        $result2 = array_column($this->executeQuery($select2)->toArray(), 'id');
        //Code to get the difference of the two array
        return $blackListedApps = ArrayUtils::checkDiffMultiArray($result1, $result2);
    }

    /**
     * @ignore getUserFolder
     */
    protected function getUserFolder($id)
    {
        return $this->config['DATA_FOLDER'] . "organization/" . AuthContext::get(AuthConstants::ORG_ID) . self::USER_FOLDER . $id;
    }

    /**
     * @ignore getFileName
     */
    protected function getFileName($file)
    {
        $fileName = explode('-', $file, 2);
        return $fileName[1];
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
     * @param $searchVal
     * @return array
     */
    public function getUserBySearchName($searchVal)
    {
        $sql = $this->getSqlObject();
        $select = $sql->select()
            ->from('avatars')
            ->columns(array('id', 'firstname', 'lastname'))// Instead of getting the id from the userTable,
            // we need to get the UUID. Once UUID is added to the table we need to make that change
            ->where(array('firstname LIKE "%' . $searchVal . '%" OR lastname LIKE "%' . $searchVal . '%"'));
        return $result = $this->executeQuery($select)->toArray();
    }

    /**
     * @param $userName
     * @return array|\Zend\Db\ResultSet\ResultSet
     */
    public function getUserDetailsbyUserName($userName)
    {
        $whereCondition = "username = '" . $userName . "'";
        $columnList = array('*');
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
            ->from('avatars')
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

    public function addUserToOrg($userId, $organizationId)
    {
        $sql = $this->getSqlObject();
        $queryString = "select id from avatars";
        $where = "where id =" . $userId;
        $resultSet = $this->executeQuerywithParams($queryString, $where, null, null);
        if ($resultSet) {
            $query = "select id from ox_organization";
            $where = "where id=" . $organizationId;
            $result = $this->executeQuerywithParams($query, $where, null, null);
            if ($result) {
                $query = "select * from ox_user_org";
                $where = "where user_id =" . $userId . " and org_id =" . $organizationId;
                $endresult = $this->executeQuerywithParams($query, $where, null, null)->toArray();
                if (!$endresult) {
                    $data = array(array('user_id' => $userId, 'org_id' => $organizationId));
                    $result_update = $this->multiInsertOrUpdate('ox_user_org', $data, array());
                    if ($result_update->getAffectedRows() == 0) {
                        return $result_update;
                    }
                    return 1;
                }
                else {
                    return 3;
                }
            }
            else {
                return 2;
            }    
        }
        return 0;
    }
}
