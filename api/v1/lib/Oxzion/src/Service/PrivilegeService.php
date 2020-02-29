<?php
namespace Oxzion\Service;

use Exception;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\Model\Privilege;
use Oxzion\Model\PrivilegeTable;
use Oxzion\Service\AbstractService;
use Oxzion\ValidationException;

class PrivilegeService extends AbstractService
{
    protected $table;
    protected $modelClass;

    public function __construct($config, $dbAdapter, PrivilegeTable $table)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
        $this->modelClass = new Privilege();
    }

    public function getMasterPrivilegeList($params = null)
    {
        if (isset($params['orgId'])) {
            $orgId = $this->getIdFromUuid('ox_organization', $params['orgId']);
        } else {
            $orgId = AuthContext::get(AuthConstants::ORG_ID);
        }
        try {
            $select = "SELECT orp.privilege_name,orp.permission,oa.name FROM ox_role_privilege orp left join ox_app as oa on oa.id = orp.app_id
                        WHERE orp.org_id = " . $orgId . " AND orp.role_id = (SELECT r.id FROM ox_role r WHERE r.name = 'ADMIN' and r.org_id = " . $orgId . ") ORDER BY orp.id";
            $resultSet = $this->executeQuerywithParams($select);
            $masterPrivilege = $resultSet->toArray();
            if (isset($params['roleId'])) {
                $roleId = $this->getIdFromUuid('ox_role', $params['roleId']);
                $select = "SELECT orp.privilege_name,orp.permission FROM ox_role_privilege orp WHERE orp.org_id = " . $orgId . " AND orp.role_id =" . $roleId . " ORDER BY orp.id";
                $resultSet = $this->executeQuerywithParams($select);
                $rolePrivilege = $resultSet->toArray();
                return array('masterPrivilege' => $masterPrivilege, 'rolePrivilege' => $rolePrivilege);
            }
        } catch (Exception $e) {
            throw $e;
        }
        return array('masterPrivilege' => $masterPrivilege);
    }

    public function getAppPrivilegeForUser($appId)
    {
        try {
            $userId = AuthContext::get(AuthConstants::USER_ID);
            $queryString = "select op.name, op.permission_allowed from ox_privilege as op
                            LEFT JOIN ox_role_privilege as orp on orp.app_id = op.app_id
                            LEFT JOIN ox_role as orl on orl.id = orp.role_id
                            LEFT JOIN ox_user_role as our on our.role_id = orl.id
                            LEFT JOIN  ox_user as av on av.id = our.user_id ";
            $where = "where av.id = " . $userId . " and op.app_id = '" . $appId . "'";
            $order = "order by av.firstname";
            $group = "group by op.name, op.permission_allowed";
            $resultSet = $this->executeQuerywithParams($queryString, $where, $group, $order);
        } catch (ValidationException $e) {
            return $response = ['data' => $appId, 'errors' => $e->getErrors()];
        } catch (Exception $e) {
            throw $e;
        }
        return $resultSet->toArray();
    }

    public function getAppId()
    {
        try {
            $userId = AuthContext::get(AuthConstants::USER_ID);
            $query = "select ox_role_privilege.app_id from ox_role_privilege RIGHT JOIN ox_user_role on ox_role_privilege.role_id = ox_user_role.role_id";
            $where = "where ox_user_role.user_id = " . $userId . " AND ox_role_privilege.privilege_name = 'MANAGE_ROLE'";
            $resultSet = $this->executeQuerywithParams($query, $where);
            $appIdArray = $resultSet->toArray();
            $appId = array_unique(array_column($appIdArray, 'app_id'));
            return $appId;
        } catch (ValidationException $e) {
            throw $e;
        }
    }

    public function getDefaultPrivileges()
    {
        $query = "select p.* from ox_privilege p left join ox_app ap on ap.id = p.app_id and ap.isdefault=1";
        $result = $this->executeQuerywithParams($query);
        return $result;
    }

    public function saveAppPrivileges($appId, $privileges)
    {
        $this->logger->info("appid - $appId, privileges - " . json_encode($privileges));
        $this->beginTransaction();
        try {
            $privilegearray = array_unique(array_column($privileges, 'name'));
            $list = "'" . implode("', '", $privilegearray) . "'";

            $queryString = "DELETE rp FROM ox_role_privilege as rp INNER JOIN ox_app as ap ON rp.app_id = ap.id WHERE ap.id = :appid AND rp.privilege_name NOT IN (" . $list . ")";
            $params = array("appid" => $appId);
            $this->logger->info("Executing query $queryString with params " . json_encode($params));
            $result = $this->executeQueryWithBindParameters($queryString, $params);

            //delete from ox_privilege
            $queryString = "DELETE FROM ox_privilege WHERE app_id = :appid AND name NOT IN (" . $list . ")";
            $params = array("appid" => $appId);
            $this->logger->info("Executing query $queryString with params " . json_encode($params));
            $result = $this->executeQueryWithBindParameters($queryString, $params);

            //get difference of the list and table privileges
            $queryString = "SELECT pr.name FROM ox_privilege as pr
            WHERE pr.app_id = :appid AND pr.name IN (" . $list . ")";
            $params = array("appid" => $appId);
            $this->logger->info("Executing query $queryString with params " . json_encode($params));
            $result = $this->executeQueryWithBindParameters($queryString, $params)->toArray();
            $existingprivileges = array_column($result, 'name');
            $sql = $this->getSqlObject();

            $query = "SELECT count(*) from ox_role_privilege where app_id = :appId ";
            $params = array("appId" => $appId);
            $queryresult = $this->executeQueryWithBindParameters($query, $params)->toArray();
            //if any new privileges to be added
            if (!(empty($privileges))) {
                foreach ($privileges as $value) {
                    if (!in_array($value['name'], $existingprivileges)) {
                        $query = "INSERT INTO ox_privilege (name, permission_allowed, app_id) VALUES (:name, :permission,:appid)";
                        $params = array("name" => $value['name'], "permission" => $value['permission'], "appid" => $appId);
                        $this->logger->info("Executing query $query with params - " . json_encode($params));
                        $result = $this->executeQueryWithBindParameters($query, $params);

                        $query = "INSERT into ox_role_privilege (role_id, privilege_name, permission, org_id, app_id)
                        SELECT r.id, '" . $value['name'] . "', " . $value['permission'] . ", r.org_id, reg.app_id
                        FROM ox_role AS r INNER JOIN
                        ox_app_registry AS reg ON r.org_id = reg.org_id
                        WHERE reg.app_id = :appId and r.name = 'ADMIN'";
                        $params = array("appId" => $appId);
                        $result = $this->executeQueryWithBindParameters($query, $params);
                    }
                }
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
    }
}
