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
use Oxzion\Service\AbstractService;
use Oxzion\Service\OrganizationService;
use Oxzion\Utils\FileUtils;
use Oxzion\Utils\FilterUtils;
use Oxzion\Utils\UuidUtil;

class GroupService extends AbstractService
{
    private $table;
    private $organizationService;

    public static $fieldName = array('name' => 'ox_user.name', 'id' => 'ox_user.id');

    public function __construct($config, $dbAdapter, GroupTable $table, $organizationService, $messageProducer)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
        $this->messageProducer = $messageProducer;
        $this->organizationService = $organizationService;
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
            if (isset($params['orgId'])) {
                if (!SecurityManager::isGranted('MANAGE_ORGANIZATION_WRITE') && ($params['orgId'] != AuthContext::get(AuthConstants::ORG_UUID))) {
                    throw new AccessDeniedException("You do not have permissions to get the group list");
                } else {
                    $orgId = $this->getIdFromUuid('ox_organization', $params['orgId']);
                }
            } else {
                $orgId = AuthContext::get(AuthConstants::ORG_ID);
            }
            $queryString = "select usr_grp.id, usr_grp.avatar_id, usr_grp.group_id, grp.name, grp.manager_id, grp.parent_id from ox_user_group as usr_grp left join ox_group as grp on usr_grp.group_id = grp.id";
            $where = "where avatar_id = (SELECT id from ox_user where uuid = '" . $userId . "') AND ox_group.org_id = " . $orgId;
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
        if (isset($params['orgId'])) {
            if (!SecurityManager::isGranted('MANAGE_ORGANIZATION_WRITE') && ($params['orgId'] != AuthContext::get(AuthConstants::ORG_UUID))) {
                throw new AccessDeniedException("You do not have permissions to get the group list");
            } else {
                $orgId = $this->getIdFromUuid('ox_organization', $params['orgId']);
            }
        } else {
            $orgId = AuthContext::get(AuthConstants::ORG_ID);
        }
        try {
            $sql = $this->getSqlObject();
            $select = $sql->select();
            $select->from('ox_group')
                ->columns(array('uuid', 'name', 'parent_id', 'org_id', 'manager_id', 'description', 'logo'))
                ->where(array('ox_group.uuid' => $id, 'status' => "Active", 'ox_group.org_id' => $orgId));
            $response = $this->executeQuery($select)->toArray();
            if (count($response) == 0) {
                return array();
            }
        } catch (Exception $e) {
            throw $e;
        }
        return $response[0];
    }

    public function createGroup(&$data, $files, $orgId = null)
    {
        if (isset($orgId)) {
            if (!SecurityManager::isGranted('MANAGE_ORGANIZATION_WRITE') && ($orgId != AuthContext::get(AuthConstants::ORG_UUID))) {
                throw new AccessDeniedException("You do not have permissions create group");
            } else {
                $data['org_id'] = $this->getIdFromUuid('ox_organization', $orgId);
            }
        } else {
            $data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
        }
        try {
            $data['name'] = isset($data['name']) ? $data['name'] : null;
            $select = "SELECT name,uuid,status from ox_group where name = '" . $data['name'] . "' AND org_id = " . $data['org_id'];
            $result = $this->executeQuerywithParams($select)->toArray();
            if (count($result) > 0) {
                if ($data['name'] == $result[0]['name'] && $result[0]['status'] == 'Active') {
                    throw new ServiceException("Group already exists", "group.exists");
                } else if ($result[0]['status'] == 'Inactive') {
                    $data['reactivate'] = isset($data['reactivate']) ? $data['reactivate'] : null;
                    if ($data['reactivate'] == 1) {
                        $data['status'] = 'Active';
                        $orgId = $this->getUuidFromId('ox_organization', $data['org_id']);
                        $count = $this->updateGroup($result[0]['uuid'], $data, $files, $orgId);
                        return;
                    } else {
                        throw new ServiceException("Group already exists would you like to reactivate?", "group.already.exists");
                    }
                }
            }
            $form = new Group();
            $data['uuid'] = UuidUtil::uuid();
            $data['created_id'] = AuthContext::get(AuthConstants::USER_ID);
            $data['date_created'] = date('Y-m-d H:i:s');
            $data['manager_id'] = isset($data['manager_id']) ? $data['manager_id'] : null;
            $user_manager_uuid = $data['manager_id'];
            $select = "SELECT id from ox_user where uuid = '" . $data['manager_id'] . "'";
            $result = $this->executeQueryWithParams($select)->toArray();
            if ($result) {
                $data['manager_id'] = $result[0]["id"];
            }
            if (isset($data['parent_id'])) {
                $data['parent_id'] = $this->getIdFromUuid('ox_group', $data['parent_id']);
            }
            $org = $this->organizationService->getOrganization($data['org_id']);
            $sql = $this->getSqlObject();
            $form->exchangeArray($data);
            $form->validate();
            $this->beginTransaction();
            $count = 0;
            $count = $this->table->save($form);
            $this->uploadGroupLogo($org['uuid'], $data['uuid'], $files);
            if ($count == 0) {
                $this->rollback();
                throw new ServiceException("Failed to create a new entity", "failed.group.create");
            }
            $id = $this->table->getLastInsertValue();
            $data['id'] = $id;
            $insert = $sql->insert('ox_user_group');
            $insert_data = array('avatar_id' => $data['manager_id'], 'group_id' => $data['id']);
            $insert->values($insert_data);
            $result = $this->executeUpdate($insert);
            $this->commit();
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            $this->rollback();
            throw $e;
        }
        $this->messageProducer->sendTopic(json_encode(array('groupname' => $data['name'], 'orgname' => $org['name'])), 'GROUP_ADDED');
        return $count;
    }

    public function getGroupLogoPath($orgId, $id, $ensureDir = false)
    {
        $baseFolder = $this->config['UPLOAD_FOLDER'];
        //TODO : Replace the User_ID with USER uuid
        $folder = $baseFolder . "organization/" . $orgId . "/group/";
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
    public function uploadGroupLogo($orgId, $id, $file)
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
        if (isset($params['orgId'])) {
            if (!SecurityManager::isGranted('MANAGE_ORGANIZATION_WRITE') && ($params['orgId'] != AuthContext::get(AuthConstants::ORG_UUID))) {
                throw new AccessDeniedException("You do not have permissions to get the groups list");
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
        try {
            $cntQuery = "SELECT count(id) FROM `ox_group`";
            if (count($filterParams) > 0 || sizeof($filterParams) > 0) {
                if (isset($filterParams['filter'])) {
                    $filterArray = json_decode($filterParams['filter'], true);
                    if (isset($filterArray[0]['filter'])) {
                        $filterlogic = isset($filterArray[0]['filter']['logic']) ? $filterArray[0]['filter']['logic'] : "AND";
                        $filterList = $filterArray[0]['filter']['filters'];
                        $where = " WHERE " . FilterUtils::filterArray($filterList, $filterlogic);
                    }
                    if (isset($filterArray[0]['sort']) && count($filterArray[0]['sort']) > 0) {
                        $sort = $filterArray[0]['sort'];
                        $sort = FilterUtils::sortArray($sort);
                    }
                    $pageSize = $filterArray[0]['take'];
                    $offset = $filterArray[0]['skip'];
                }
                if (isset($filterParams['exclude'])) {
                    $where .= strlen($where) > 0 ? " AND uuid NOT in ('" . implode("','", $filterParams['exclude']) . "') " : " WHERE uuid NOT in ('" . implode("','", $filterParams['exclude']) . "') ";
                }
            }
            $where .= strlen($where) > 0 ? " AND status = 'Active' AND org_id = " . $orgId : " WHERE status = 'Active'AND org_id = " . $orgId;
            $sort = " ORDER BY " . $sort;
            $limit = " LIMIT " . $pageSize . " offset " . $offset;
            $resultSet = $this->executeQuerywithParams($cntQuery . $where);
            $count = $resultSet->toArray()[0]['count(id)'];
            $query = "SELECT uuid,name,parent_id,org_id,manager_id,description,logo FROM `ox_group`" . $where . " " . $sort . " " . $limit;
            $resultSet = $this->executeQuerywithParams($query);
            $resultSet = $resultSet->toArray();
            for ($x = 0; $x < sizeof($resultSet); $x++) {
                $select = "SELECT uuid from ox_user where id = '" . $resultSet[$x]['manager_id'] . "'";
                $result = $this->executeQueryWithParams($select)->toArray();
                $resultSet[$x]['manager_id'] = $result[0]["uuid"];
                if (isset($resultSet[$x]['parent_id'])) {
                    $selectParentUUID = "SELECT uuid from ox_group where id = '" . $resultSet[$x]['parent_id'] . "'";
                    $result = $this->executeQueryWithParams($selectParentUUID)->toArray();
                    $resultSet[$x]['parent_id'] = $result[0]["uuid"];
                }
            }
        } catch (Exception $e) {
            throw $e;
        }
        return array('data' => $resultSet, 'total' => $count);
    }

    public function updateGroup($id, &$data, $files = null, $orgId = null)
    {
        if (isset($orgId)) {
            if (!SecurityManager::isGranted('MANAGE_ORGANIZATION_WRITE') && ($orgId != AuthContext::get(AuthConstants::ORG_UUID))) {
                throw new AccessDeniedException("You do not have permissions to edit the group");
            } else {
                $data['org_id'] = $this->getIdFromUuid('ox_organization', $orgId);
            }
        }
        $obj = $this->table->getByUuid($id, array());
        if (is_null($obj)) {
            throw new ServiceException("Updating non existent Group", "non.existent.group");
        }
        if (isset($orgId)) {
            if ($data['org_id'] != $obj->org_id) {
                throw new ServiceException("Group does not belong to the organization", "Group.not.found");
            }
        }
        $org = $this->organizationService->getOrganization($obj->org_id);
        $form = new Group();
        $data = array_merge($obj->toArray(), $data);
        $data['modified_id'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_modified'] = date('Y-m-d H:i:s');
        $data['manager_id'] = isset($data['manager_id']) ? $data['manager_id'] : null;
        $user_manager_uuid = $data['manager_id'];
        $select = "SELECT id from ox_user where uuid = '" . $data['manager_id'] . "'";
        $result = $this->executeQueryWithParams($select)->toArray();
        if ($result) {
            $data['manager_id'] = $result[0]["id"];
        }
        $data['parent_id'] = $this->getIdFromUuid('ox_group', $data['parent_id']);
        $data['parent_id'] = $data['parent_id'] == 0 ? null : $data['parent_id'];
        $form->exchangeArray($data);
        $form->validate();
        $count = 0;
        try {
            $count = $this->table->save($form);
            if (isset($files)) {
                $this->uploadGroupLogo($org['uuid'], $id, $files);
            }
            $this->messageProducer->sendTopic(json_encode(array('old_groupname' => $obj->name, 'orgname' => $org['name'], 'new_groupname' => $data['name'])), 'GROUP_UPDATED');
            if ($count === 1) {
                $select = "SELECT count(id) as users from ox_user_group where avatar_id =" . $data['manager_id'] . " AND group_id = (SELECT id from ox_group where uuid = '" . $id . "')";
                $query = $this->executeQuerywithParams($select)->toArray();
                if ($query[0]['users'] === '0') {
                    $this->saveUser(["orgId" => $orgId, "groupId" => $id], ["userid" => [["uuid" => $user_manager_uuid]]], true);
                }
            } else {
                throw new ServiceException("Failed to Update", "failed.update.group");
            }
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
        return $count;
    }

    public function deleteGroup($params)
    {
        if (isset($params['orgId'])) {
            if (!SecurityManager::isGranted('MANAGE_ORGANIZATION_WRITE') && ($params['orgId'] != AuthContext::get(AuthConstants::ORG_UUID))) {
                throw new AccessDeniedException("You do not have permissions to delete the group");
            } else {
                $orgId = $this->getIdFromUuid('ox_organization', $params['orgId']);
            }
        } else {
            $orgId = AuthContext::get(AuthConstants::ORG_ID);
        }
        try {
            $obj = $this->table->getByUuid($params['groupId'], array());
            if (is_null($obj)) {
                throw new ServiceException("Entity not found", "group.not.found");
            }
            if ($orgId != $obj->org_id) {
                throw new ServiceException("Group does not belong to the organization", "group.not.found");
            }
            $select = "SELECT count(id) from ox_group where parent_id = " . $obj->id;
            $result = $this->executeQuerywithParams($select)->toArray();
            if ($result[0]['count(id)'] > 0) {
                throw new ServiceException("Please remove the child groups before deleting the parent group", "delete.parent.group");
            }
            $org = $this->organizationService->getOrganization($obj->org_id);
            $originalArray = $obj->toArray();
            $form = new Group();
            $originalArray['status'] = 'Inactive';
            $form->exchangeArray($originalArray);
            $result = $this->table->save($form);
        } catch (Exception $e) {
            throw $e;
        }
        $this->messageProducer->sendTopic(json_encode(array('groupname' => $obj->name, 'orgname' => $org['name'])), 'GROUP_DELETED');
        return $result;
    }

    public function getUserList($params, $filterParams = null)
    {
        if (isset($params['orgId'])) {
            if (!SecurityManager::isGranted('MANAGE_ORGANIZATION_WRITE') && ($params['orgId'] != AuthContext::get(AuthConstants::ORG_UUID))) {
                throw new AccessDeniedException("You do not have permissions to get the user list of group");
            } else {
                $orgId = $this->getIdFromUuid('ox_organization', $params['orgId']);
            }
        } else {
            $orgId = AuthContext::get(AuthConstants::ORG_ID);
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
            $where .= strlen($where) > 0 ? " AND ox_group.uuid = '" . $params['groupId'] . "' AND ox_group.status = 'Active' AND ox_group.org_id = " . $orgId : " WHERE ox_group.uuid = '" . $params['groupId'] . "' AND ox_group.status = 'Active' AND ox_group.org_id = " . $orgId;
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
        if (isset($params['orgId'])) {
            if (!SecurityManager::isGranted('MANAGE_ORGANIZATION_WRITE') && ($params['orgId'] != AuthContext::get(AuthConstants::ORG_UUID))) {
                throw new AccessDeniedException("You do not have permissions to add users to group");
            } else {
                $params['orgId'] = $this->getIdFromUuid('ox_organization', $params['orgId']);
            }
        }
        $obj = $this->table->getByUuid($params['groupId'], array());
        if (is_null($obj)) {
            $this->logger->info("Invalid group id - " . $params['groupId']);
            throw new ServiceException("Entity not found", "group.not.found");
        }
        if (isset($params['orgId'])) {
            if ($params['orgId'] != $obj->org_id) {
                throw new ServiceException("Group does not belong to the organization", "group.not.found");
            }
        }
        $org = $this->organizationService->getOrganization($obj->org_id);
        if (!isset($data['userid']) || empty($data['userid'])) {
            throw new ServiceException("Enter User Ids", "select.user");
        }
        if ($addUsers) {
            $query = "SELECT ox_user.uuid FROM ox_user_group " .
            "inner join ox_user on ox_user.id = ox_user_group.avatar_id " .
            "where ox_user_group.id = " . $obj->id;
            $groupUsers = $this->executeQuerywithParams($query)->toArray();
            foreach (array_diff(array_column($data['userid'], 'uuid'), array_column($groupUsers, 'uuid')) as $userUuid) {
                $groupUsers[] = array('uuid' => $userUuid);
            }
            $data['userid'] = $groupUsers;
        }
        $userArray = $this->organizationService->getUserIdList($data['userid']);
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
                $query = "Insert into ox_user_group(avatar_id,group_id) SELECT ou.id," . $group_id . " from ox_user as ou LEFT OUTER JOIN ox_user_group as our on ou.id = our.avatar_id AND our.group_id = " . $group_id . " WHERE ou.id in (" . implode(',', $userSingleArray) . ") AND our.group_id  is null";
                $resultInsert = $this->executeQuerywithParams($query);
                $this->commit();
            } catch (Exception $e) {
                $this->rollback();
                throw $e;
            }
            foreach ($deletedUser as $key => $value) {
                $this->messageProducer->sendTopic(json_encode(array('groupname' => $obj->name, 'orgname' => $org['name'], 'username' => $value['username'])), 'USERTOGROUP_DELETED');
            }
            foreach ($insertedUser as $key => $value) {
                $this->messageProducer->sendTopic(json_encode(array('groupname' => $obj->name, 'orgname' => $org['name'], 'username' => $value['username'])), 'USERTOGROUP_ADDED');
            }
            $queryString = "SELECT ox_user.username, ox_user.firstname, ox_user.lastname, ox_user.email FROM ox_user_group " .
                "inner join ox_user on ox_user.id = ox_user_group.avatar_id " .
                "where ox_user_group.group_id = " . $group_id;
            $groupUsers = $this->executeQuerywithParams($queryString)->toArray();
            if (count($groupUsers) > 0) {
                $this->messageProducer->sendTopic(json_encode(array('groupname' => $obj->name, 'usernames' => array_column($groupUsers, 'username'))), 'USERTOGROUP_UPDATED');
            }
            return 1;
        }
        throw new ServiceException("Entity not found", "group.not.found");
    }
}
