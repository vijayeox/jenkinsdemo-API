<?php
namespace Group\Service;

use Exception;
use Group\Model\Group;
use Group\Model\GroupTable;
use Oxzion\AccessDeniedException;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Security\SecurityManager;
use Oxzion\ServiceException;
use Oxzion\OxServiceException;
use Oxzion\Service\AbstractService;
use Oxzion\Service\AccountService;
use Oxzion\Utils\FileUtils;
use Oxzion\Utils\FilterUtils;
use Oxzion\Utils\UuidUtil;

class GroupService extends AbstractService
{
    private $table;
    private $accountService;
    private $userService;

    public static $fieldName = array('name' => 'ox_user.name', 'id' => 'ox_user.id');

    public function __construct($config, $dbAdapter, GroupTable $table, $accountService, $messageProducer, $userService)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
        $this->messageProducer = $messageProducer;
        $this->accountService = $accountService;
        $this->userService = $userService;
    }

    public function setMessageProducer($messageProducer)
    {
        $this->messageProducer = $messageProducer;
    }

    /**
     * ! DEPRECATED
     * This function will not work as expected since the $params are not even used in the function. Also the parent function which calls this function at this time is deprecated.
     *
     **/
    public function getGroupsforUser($userId, $data)
    {
        try {
            if (isset($params['accountId'])) {
                if (!SecurityManager::isGranted('MANAGE_ACCOUNT_WRITE') && ($params['accountId'] != AuthContext::get(AuthConstants::ACCOUNT_UUID))) {
                    throw new AccessDeniedException("You do not have permissions to get the group list");
                } else {
                    $accountId = $this->getIdFromUuid('ox_account', $params['accountId']);
                }
            } else {
                $accountId = AuthContext::get(AuthConstants::ACCOUNT_ID);
            }
            $queryString = "select usr_grp.id, usr_grp.avatar_id, usr_grp.group_id, grp.name, grp.manager_id, grp.parent_id from ox_user_group as usr_grp left join ox_group as grp on usr_grp.group_id = grp.id";
            $where = "where avatar_id = (SELECT id from ox_user where uuid = '" . $userId . "') AND ox_group.account_id = " . $accountId;
            $order = "order by grp.name";
            $resultSet = $this->executeQuerywithParams($queryString, $where, null, $order);
        } catch (Exception $e) {
            throw $e;
        }
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

    public function getGroupByUuid($id, $params)
    {
        if (isset($params['accountId'])) {
            if (!SecurityManager::isGranted('MANAGE_ACCOUNT_WRITE') && ($params['accountId'] != AuthContext::get(AuthConstants::ACCOUNT_UUID))) {
                throw new AccessDeniedException("You do not have permissions to get the group list");
            } else {
                $accountId = $this->getIdFromUuid('ox_account', $params['accountId']);
            }
        } else {
            $accountId = AuthContext::get(AuthConstants::ACCOUNT_ID);
        }
        try {
            $sql = "SELECT g.uuid, g.name, g.description, g.logo, a.uuid as accountId,
                        p.uuid as parentId, u.uuid as managerId 
                        FROM ox_group g 
                        inner join ox_account a on a.id = g.account_id 
                        left join ox_group p on p.id = g.parent_id
                        left join ox_user u on g.manager_id = u.id
                        where g.uuid =:id and g.status = 'Active' and g.account_id = :accountId";
            $params = ['id' => $id, 'accountId' => $accountId];
            $response = $this->executeQueryWithBindParameters($sql, $params)->toArray();
            if (count($response) == 0) {
                return array();
            }
        } catch (Exception $e) {
            throw $e;
        }
        return $response[0];
    }

    public function createGroup(&$inputData, $files, $accountId = null)
    {
        $data = $inputData;
        if (isset($accountId)) {
            if (!SecurityManager::isGranted('MANAGE_ACCOUNT_WRITE') && ($accountId != AuthContext::get(AuthConstants::ACCOUNT_UUID))) {
                throw new AccessDeniedException("You do not have permissions create group");
            } else {
                $data['account_id'] = $this->getIdFromUuid('ox_account', $accountId);
            }
        } else {
            $data['account_id'] = AuthContext::get(AuthConstants::ACCOUNT_ID);
            $accountId = AuthContext::get(AuthConstants::ACCOUNT_UUID);
        }
        try {
            $data['name'] = isset($data['name']) ? $data['name'] : null;
            $select = "SELECT name,uuid,status from ox_group where name = '" . $data['name'] . "' AND account_id = " . $data['account_id'];
            $result = $this->executeQuerywithParams($select)->toArray();
            if (count($result) > 0) {
                if ($data['name'] == $result[0]['name'] && $result[0]['status'] == 'Active') {
                    throw new ServiceException("Group already exists", "group.exists", OxServiceException::ERR_CODE_NOT_ACCEPTABLE);
                } else if ($result[0]['status'] == 'Inactive') {
                    $data['reactivate'] = isset($data['reactivate']) ? $data['reactivate'] : null;
                    if ($data['reactivate'] == 1) {
                        $data['status'] = 'Active';
                        unset($inputData['reactivate']);
                        $accountId = $this->getUuidFromId('ox_account', $data['account_id']);
                        $count = $this->updateGroup($result[0]['uuid'], $data, $files, $accountId);
                        return;
                    } else {
                        throw new ServiceException("Group already exists would you like to reactivate?", "inactive.group.already.exists", OxServiceException::ERR_CODE_NOT_ACCEPTABLE);
                    }
                }
            }
            $form = new Group();
            $data['uuid'] = UuidUtil::uuid();
            $data['created_id'] = AuthContext::get(AuthConstants::USER_ID);
            $data['date_created'] = date('Y-m-d H:i:s');
            $data['managerId'] = isset($data['managerId']) ? $data['managerId'] : null;
            $select = "SELECT id from ox_user where uuid = '" . $data['managerId'] . "'";
            $result = $this->executeQueryWithParams($select)->toArray();
            if ($result) {
                $data['manager_id'] = $result[0]["id"];
            }
            if (isset($data['parentId'])) {
                $data['parent_id'] = $this->getIdFromUuid('ox_group', $data['parentId']);
            }
            $account = $this->accountService->getAccount($data['account_id']);
            $sql = $this->getSqlObject();
            $form->exchangeArray($data);
            $form->validate();
            $this->beginTransaction();
            $count = 0;
            $count = $this->table->save($form);
            if ($count == 0) {
                throw new ServiceException("Failed to create a new entity", "failed.group.create", OxServiceException::ERR_CODE_UNPROCESSABLE_ENTITY);
            }
            $id = $this->table->getLastInsertValue();
            $data['id'] = $id;
            $insert = $sql->insert('ox_user_group');
            $insert_data = array('avatar_id' => $data['manager_id'], 'group_id' => $data['id']);
            $insert->values($insert_data);
            $result = $this->executeUpdate($insert);
            $this->uploadGroupLogo($accountId, $data['uuid'], $files);
            $inputData['uuid'] = $data['uuid'];
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
        $this->messageProducer->sendTopic(json_encode(array('groupname' => $data['name'], 'accountName' => $account['name'])), 'GROUP_ADDED');
    }

    public function getGroupLogoPath($accountId, $id, $ensureDir = false)
    {
        $baseFolder = $this->config['UPLOAD_FOLDER'];
        //TODO : Replace the User_ID with USER uuid
        $folder = $baseFolder . "account/" . $accountId . "/group/";
        if (isset($id)) {
            $folder = $folder . $id . "/";
        }
        if ($ensureDir && !file_exists($folder)) {
            FileUtils::createDirectory($folder);
        }
        return $folder;
    }

    /**
     * createUpload
     * Upload files from Front End and store it in temp Folder
     *  @param files Array of files to upload
     *  @return JSON array of filenames
     * ? Not sure what this function is doing, It will cause exception. We need to revisit this and find out what this the purpose of this method
     */
    public function uploadGroupLogo($accountId, $id, $file)
    {
        if (isset($file)) {
            $image = FileUtils::convetImageTypetoPNG($file);
            if ($image) {
                if (FileUtils::fileExists($destFile)) {
                    imagepng($image, $destFile . '/logo.png');
                    $image = null;
                } else {
                    mkdir($destFile);
                    imagepng($image, $destFile . '/logo.png');
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

    public function getGroupList($filterParams = null, $params = null)
    {
        if (isset($params['accountId'])) {
            if (!SecurityManager::isGranted('MANAGE_ACCOUNT_WRITE') && ($params['accountId'] != AuthContext::get(AuthConstants::ACCOUNT_UUID))) {
                throw new AccessDeniedException("You do not have permissions to get the groups list");
            } else {
                $accountId = $this->getIdFromUuid('ox_account', $params['accountId']);
            }
        } else {
            $accountId = AuthContext::get(AuthConstants::ACCOUNT_ID);
        }
        $where = "";
        $pageSize = 20;
        $offset = 0;
        $sort = "name";
        $fieldMap = ['name' => 'g.name', 'description' => 'g.description'];
        try {
            $cntQuery = "SELECT count(g.id) as count FROM `ox_group` g";
            if (count($filterParams) > 0 || sizeof($filterParams) > 0) {
                if (isset($filterParams['filter'])) {
                    $filterArray = json_decode($filterParams['filter'], true);
                    if (isset($filterArray[0]['filter'])) {
                        $filterlogic = isset($filterArray[0]['filter']['logic']) ? $filterArray[0]['filter']['logic'] : "AND";
                        $filterList = $filterArray[0]['filter']['filters'];
                        $where = " WHERE " . FilterUtils::filterArray($filterList, $filterlogic, $fieldMap);
                    }
                    if (isset($filterArray[0]['sort']) && count($filterArray[0]['sort']) > 0) {
                        $sort = $filterArray[0]['sort'];
                        $sort = FilterUtils::sortArray($sort);
                    }
                    $pageSize = $filterArray[0]['take'];
                    $offset = $filterArray[0]['skip'];
                }
                if (isset($filterParams['exclude'])) {
                    $where .= strlen($where) > 0 ? " AND g.uuid NOT in ('" . implode("','", $filterParams['exclude']) . "') " : " WHERE g.uuid NOT in ('" . implode("','", $filterParams['exclude']) . "') ";
                }
            }
            $where .= strlen($where) > 0 ? " AND " : " WHERE ";
            $where .= " g.status = 'Active' AND g.account_id = " . $accountId;
            $sort = " ORDER BY " . $sort;
            $limit = " LIMIT " . $pageSize . " offset " . $offset;
            $resultSet = $this->executeQuerywithParams($cntQuery . $where);
            $count = $resultSet->toArray()[0]['count'];
            $query = "SELECT g.uuid,g.name,parent.uuid as parentId,a.uuid as accountId,u.uuid as managerId,g.description,g.logo 
                        FROM `ox_group` g 
                        INNER JOIN ox_user u on g.manager_id = u.id
                        inner join ox_account a on a.id = g.account_id
                        LEFt OUTER JOIN ox_group parent on parent.id = g.parent_id" . $where . " " . $sort . " " . $limit;
            $resultSet = $this->executeQuerywithParams($query);
            $resultSet = $resultSet->toArray();
        } catch (Exception $e) {
            throw $e;
        }
        return array('data' => $resultSet, 'total' => $count);
    }

    public function updateGroup($id, &$inputData, $files = null, $accountId = null)
    {
        $data = $inputData;
        if (isset($accountId)) {
            if (!SecurityManager::isGranted('MANAGE_ACCOUNT_WRITE') && ($accountId != AuthContext::get(AuthConstants::ACCOUNT_UUID))) {
                throw new AccessDeniedException("You do not have permissions to edit the group");
            } else {
                $data['account_id'] = $this->getIdFromUuid('ox_account', $accountId);
            }
        }
        $obj = $this->table->getByUuid($id, array());
        if (is_null($obj)) {
            throw new ServiceException("Updating non existent Group", "non.existent.group", OxServiceException::ERR_CODE_NOT_FOUND);
        }
        if (isset($accountId)) {
            if ($data['account_id'] != $obj->account_id) {
                throw new ServiceException("Group does not belong to the account", "Group.not.found", OxServiceException::ERR_CODE_NOT_FOUND);
            }
        }
        $account = $this->accountService->getAccount($obj->account_id);
        $form = new Group();
        $data = array_merge($obj->toArray(), $data);
        $data['modified_id'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_modified'] = date('Y-m-d H:i:s');
        $data['managerId'] = isset($data['managerId']) ? $data['managerId'] : null;
        $user_manager_uuid = $data['managerId'];
        $select = "SELECT id from ox_user where uuid = '" . $data['managerId'] . "'";
        $result = $this->executeQueryWithParams($select)->toArray();
        if ($result) {
            $data['manager_id'] = $result[0]["id"];
        }
        if(isset($data['parentId'])){
            $data['parent_id'] = $this->getIdFromUuid('ox_group', $data['parentId']);
            $data['parent_id'] = $data['parent_id'] == 0 ? null : $data['parent_id'];
        }
        $form->exchangeArray($data);
        $form->validate();
        $count = 0;
        try {
            $count = $this->table->save($form);
            if (isset($files)) {
                $this->uploadGroupLogo($account['uuid'], $id, $files);
            }
            $this->messageProducer->sendTopic(json_encode(array('old_groupName' => $obj->name, 'accountName' => $account['name'], 'new_groupName' => $data['name'])), 'GROUP_UPDATED');
            if ($count === 1) {
                $select = "SELECT count(id) as users from ox_user_group where avatar_id =" . $data['manager_id'] . " AND group_id = (SELECT id from ox_group where uuid = '" . $id . "')";
                $query = $this->executeQuerywithParams($select)->toArray();
                if ($query[0]['users'] === '0') {
                    $this->saveUser(["accountId" => $account['uuid'], "groupId" => $id], ["userIdList" => [["uuid" => $user_manager_uuid]]], true);
                }
            } else {
                throw new ServiceException("Failed to Update", "failed.update.group", OxServiceException::ERR_CODE_UNPROCESSABLE_ENTITY);
            }
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
    }

    public function deleteGroup($params)
    {
        if (isset($params['accountId'])) {
            if (!SecurityManager::isGranted('MANAGE_ACCOUNT_WRITE') && ($params['accountId'] != AuthContext::get(AuthConstants::ACCOUNT_UUID))) {
                throw new AccessDeniedException("You do not have permissions to delete the group");
            } else {
                $accountId = $this->getIdFromUuid('ox_account', $params['accountId']);
            }
        } else {
            $accountId = AuthContext::get(AuthConstants::ACCOUNT_ID);
        }
        try {
            $obj = $this->table->getByUuid($params['groupId'], array());
            if (is_null($obj)) {
                throw new ServiceException("Entity not found", "group.not.found", OxServiceException::ERR_CODE_NOT_FOUND);
            }
            if ($accountId != $obj->account_id) {
                throw new ServiceException("Group does not belong to the account", "group.not.found", OxServiceException::ERR_CODE_NOT_FOUND);
            }
            $select = "SELECT count(id) from ox_group where parent_id = " . $obj->id;
            $result = $this->executeQuerywithParams($select)->toArray();
            if ($result[0]['count(id)'] > 0) {
                throw new ServiceException("Please remove the child groups before deleting the parent group", "delete.parent.group", OxServiceException::ERR_CODE_NOT_ACCEPTABLE);
            }
            $account = $this->accountService->getAccount($obj->account_id);
            $originalArray = $obj->toArray();
            $form = new Group();
            $originalArray['status'] = 'Inactive';
            $form->exchangeArray($originalArray);
            $result = $this->table->save($form);
        } catch (Exception $e) {
            throw $e;
        }
        $this->messageProducer->sendTopic(json_encode(array('groupName' => $obj->name, 'accountName' => $account['name'])), 'GROUP_DELETED');
        return $result;
    }

    public function getUserList($params, $filterParams = null)
    {
        if (isset($params['accountId'])) {
            if (!SecurityManager::isGranted('MANAGE_ACCOUNT_WRITE') && ($params['accountId'] != AuthContext::get(AuthConstants::ACCOUNT_UUID))) {
                throw new AccessDeniedException("You do not have permissions to get the user list of group");
            } else {
                $accountId = $this->getIdFromUuid('ox_account', $params['accountId']);
            }
        } else {
            $accountId = AuthContext::get(AuthConstants::ACCOUNT_ID);
        }
        $pageSize = 20;
        $offset = 0;
        $where = "";
        try {
            $sort = "ox_user.name";
            $query = "SELECT ox_user.uuid,ox_user.name,case when (ox_group.manager_id = ox_user.id)
                then 1
                end as is_manager";
            $from = " FROM ox_user left join ox_user_group on ox_user.id = ox_user_group.avatar_id left join ox_group on ox_group.id = ox_user_group.group_id";
            $cntQuery = "SELECT count(ox_user.id)" . $from;
            if (count($filterParams) > 0 || sizeof($filterParams) > 0) {
                $filterArray = json_decode($filterParams['filter'], true);
                if (isset($filterArray[0]['filter'])) {
                    $filterlogic = isset($filterArray[0]['filter']['logic']) ? $filterArray[0]['filter']['logic'] : "AND";
                    $filterList = $filterArray[0]['filter']['filters'];
                    $where = " WHERE " . FilterUtils::filterArray($filterList, $filterlogic, self::$fieldName);
                }
                if (isset($filterArray[0]['sort']) && count($filterArray[0]['sort']) > 0) {
                    $sort = $filterArray[0]['sort'];
                    $sort = FilterUtils::sortArray($sort, self::$fieldName);
                }
                $pageSize = $filterArray[0]['take'];
                $offset = $filterArray[0]['skip'];
            }
            $where .= strlen($where) > 0 ? " AND ox_group.uuid = '" . $params['groupId'] . "' AND ox_group.status = 'Active' AND ox_group.account_id = " . $accountId : " WHERE ox_group.uuid = '" . $params['groupId'] . "' AND ox_group.status = 'Active' AND ox_group.account_id = " . $accountId;
            $sort = " ORDER BY " . $sort;
            $limit = " LIMIT " . $pageSize . " offset " . $offset;
            $resultSet = $this->executeQuerywithParams($cntQuery . $where);
            $count = $resultSet->toArray()[0]['count(ox_user.id)'];
            $query = $query . " " . $from . " " . $where . " " . $sort . " " . $limit;
            $resultSet = $this->executeQuerywithParams($query);
        } catch (Exception $e) {
            throw $e;
        }
        return array('data' => $resultSet->toArray(), 'total' => $count);
    }

    public function saveUser($params, $data, $addUsers = false)
    {
        if (isset($params['accountId'])) {
            if (!SecurityManager::isGranted('MANAGE_ACCOUNT_WRITE') && ($params['accountId'] != AuthContext::get(AuthConstants::ACCOUNT_UUID))) {
                throw new AccessDeniedException("You do not have permissions to add users to group");
            } else {
                $params['accountId'] = $this->getIdFromUuid('ox_account', $params['accountId']);
            }
        }
        $obj = $this->table->getByUuid($params['groupId'], array());
        if (is_null($obj)) {
            $this->logger->info("Invalid group id - " . $params['groupId']);
            throw new ServiceException("Entity not found", "group.not.found", OxServiceException::ERR_CODE_NOT_FOUND);
        }
        if (isset($params['accountId'])) {
            if ($params['accountId'] != $obj->account_id) {
                throw new ServiceException("Group does not belong to the account", "group.not.found", OxServiceException::ERR_CODE_NOT_FOUND);
            }
        }
        $account = $this->accountService->getAccount($obj->account_id);
        if (!isset($data['userIdList']) || empty($data['userIdList'])) {
            throw new ServiceException("Select Users", "select.user", OxServiceException::ERR_CODE_NOT_ACCEPTABLE);
        }
        if ($addUsers) {
            $query = "SELECT ox_user.uuid FROM ox_user_group " .
            "inner join ox_user on ox_user.id = ox_user_group.avatar_id " .
            "where ox_user_group.id = " . $obj->id;
            $groupUsers = $this->executeQuerywithParams($query)->toArray();
            foreach (array_diff(array_column($data['userIdList'], 'uuid'), array_column($groupUsers, 'uuid')) as $userUuid) {
                $groupUsers[] = array('uuid' => $userUuid);
            }
            $data['userIdList'] = $groupUsers;
        }
        $userArray = $this->userService->getUserIdList($data['userIdList']);
        $group_id = $obj->id;
        if ($userArray) {
            $userSingleArray = array_unique(array_map('current', $userArray));
            $queryString = "SELECT ox_user.id, ox_user.username FROM ox_user_group " .
            "inner join ox_user on ox_user.id = ox_user_group.avatar_id " .
            "where ox_user_group.id = " . $group_id .
            " and ox_user_group.avatar_id not in (" . implode(',', $userSingleArray) . ")";
            $deletedUser = $this->executeQuerywithParams($queryString)->toArray();
            $query = "SELECT u.id, u.username FROM ox_user_group ug " .
            "right join ox_user u on u.id = ug.avatar_id and ug.group_id = " . $group_id .
            " where u.id in (" . implode(',', $userSingleArray) . ") and ug.avatar_id is null";
            $insertedUser = $this->executeQuerywithParams($query)->toArray();
            $this->beginTransaction();
            try {
                $delete = "DELETE FROM ox_user_group where avatar_id not in (" . implode(',', $userSingleArray) . ") and group_id = " . $group_id;
                $result = $this->executeQuerywithParams($delete);
                $query = "INSERT into ox_user_group(avatar_id,group_id) SELECT ou.id," . $group_id . " from ox_user as ou LEFT OUTER JOIN ox_user_group as our on ou.id = our.avatar_id AND our.group_id = " . $group_id . " WHERE ou.id in (" . implode(',', $userSingleArray) . ") AND our.group_id  is null";
                $resultInsert = $this->executeQuerywithParams($query);
                $this->commit();
            } catch (Exception $e) {
                $this->rollback();
                throw $e;
            }
            foreach ($deletedUser as $key => $value) {
                $this->messageProducer->sendTopic(json_encode(array('groupName' => $obj->name, 'accountName' => $account['name'], 'username' => $value['username'])), 'USERTOGROUP_DELETED');
            }
            foreach ($insertedUser as $key => $value) {
                $this->messageProducer->sendTopic(json_encode(array('groupName' => $obj->name, 'accountName' => $account['name'], 'username' => $value['username'])), 'USERTOGROUP_ADDED');
            }
            $queryString = "SELECT ox_user.username, up.firstname, up.lastname, up.email 
                            FROM ox_user_group 
                            inner join ox_user on ox_user.id = ox_user_group.avatar_id 
                            inner join ox_person up on up.id = ox_user.person_id
                            where ox_user_group.group_id = " . $group_id;
            $groupUsers = $this->executeQuerywithParams($queryString)->toArray();
            if (count($groupUsers) > 0) {
                $this->messageProducer->sendTopic(json_encode(array('groupName' => $obj->name, 'usernames' => array_column($groupUsers, 'username'))), 'USERTOGROUP_UPDATED');
            }
            return 1;
        }
        throw new ServiceException("Entity not found", "group.not.found", OxServiceException::ERR_CODE_NOT_FOUND);
    }
}
