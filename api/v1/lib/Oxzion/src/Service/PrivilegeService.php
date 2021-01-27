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
        if (isset($params['accountId'])) {
            $accountId = $this->getIdFromUuid('ox_account', $params['accountId']);
        } else {
            $accountId = AuthContext::get(AuthConstants::ACCOUNT_ID);
        }
        $select = "SELECT orp.privilege_name,orp.permission,oa.name 
                    FROM ox_role_privilege orp 
                    inner join ox_role r on r.id = orp.role_id
                    left join ox_app as oa on oa.id = orp.app_id
                    WHERE r.name = 'ADMIN' and r.account_id = :accountId 
                    ORDER BY orp.privilege_name";
        $queryParams = ['accountId' => $accountId];
        $resultSet = $this->executeQueryWithBindParameters($select, $queryParams);
        $masterPrivilege = $resultSet->toArray();
        if (isset($params['roleId'])) {
            $roleId = $this->getIdFromUuid('ox_role', $params['roleId']);
            $select = "SELECT orp.privilege_name,orp.permission 
                        FROM ox_role_privilege orp 
                        WHERE orp.account_id = :accountId AND orp.role_id = :roleId 
                        ORDER BY orp.privilege_name";
            $queryParams['roleId'] = $roleId;
            $resultSet = $this->executeQueryWithBindParameters($select, $queryParams);
            $rolePrivilege = $resultSet->toArray();
            return array('masterPrivilege' => $masterPrivilege, 'rolePrivilege' => $rolePrivilege);
        }
        
        return array('masterPrivilege' => $masterPrivilege);
    }

    public function getAppPrivilegeForUser($appId)
    {
        $userId = AuthContext::get(AuthConstants::USER_ID);
        $accountId = AuthContext::get(AuthConstants::ACCOUNT_ID);
        $queryString = "SELECT distinct op.name, op.permission_allowed 
                        from ox_privilege as op
                        LEFT JOIN ox_role_privilege as orp on orp.app_id = op.app_id
                        LEFT JOIN ox_role as orl on orl.id = orp.role_id
                        LEFT JOIN ox_user_role as our on our.role_id = orl.id
                        LEFT JOIN ox_account_user au on au.id = our.account_user_id
                        LEFT JOIN  ox_user as av on av.id = au.user_id 
                        LEFT JOIN ox_person up on up.id = av.person_id  
                        where av.id = :userId and op.app_id = :appId and au.account_id = :accountId 
                        order by op.name";
        $params = ['userId' => $userId,
                    'accountId' => $accountId,
                    'appId' => $appId];
        $resultSet = $this->executeQueryWithBindParameters($queryString, $params);
        return $resultSet->toArray();
    }

    public function getDefaultPrivileges()
    {
        $query = "SELECT p.* from ox_privilege p 
                    left join ox_app ap on ap.id = p.app_id and ap.isdefault=1";
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
            $result = $this->executeUpdateWithBindParameters($queryString, $params);

            //delete from ox_privilege
            $queryString = "DELETE FROM ox_privilege WHERE app_id = :appid AND name NOT IN (" . $list . ")";
            $params = array("appid" => $appId);
            $this->logger->info("Executing query $queryString with params " . json_encode($params));
            $result = $this->executeUpdateWithBindParameters($queryString, $params);

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
                        $result = $this->executeUpdateWithBindParameters($query, $params);

                        $query = "INSERT into ox_role_privilege (role_id, privilege_name, permission, account_id, app_id)
                                SELECT r.id, '" . $value['name'] . "', " . $value['permission'] . ", r.account_id, reg.app_id
                                FROM ox_role AS r 
                                INNER JOIN ox_app_registry AS reg ON r.account_id = reg.account_id
                                WHERE reg.app_id = :appId and r.name = 'ADMIN'";
                        $params = array("appId" => $appId);
                        $result = $this->executeUpdateWithBindParameters($query, $params);
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
