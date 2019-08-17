<?php
namespace Oxzion\Service;

use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\Model\Privilege;
use Oxzion\Model\PrivilegeTable;
use Oxzion\ValidationException;
use Oxzion\Service\AbstractService;

class PrivilegeService extends AbstractService
{
    protected $table;
    private $roleService;
    protected $modelClass;

    public function __construct($config, $dbAdapter, PrivilegeTable $table, RoleService $roleService)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
        $this->roleService = $roleService;
        $this->modelClass = new Privilege();
    }


    public function getMasterPrivilegeList($params = null)
    {
        if (isset($params['orgId'])) {
            $orgId = $this->getIdFromUuid('ox_organization', $params['orgId']);
        } else {
            $orgId = AuthContext::get(AuthConstants::ORG_ID);
        }

        $select = "SELECT orp.privilege_name,orp.permission,oa.name FROM ox_role_privilege orp left join ox_app as oa on oa.id = orp.app_id
                        WHERE orp.org_id = ".$orgId." AND orp.role_id = (SELECT r.id FROM ox_role r WHERE r.name = 'ADMIN' and r.org_id = ".$orgId.") ORDER BY orp.id";
        $resultSet = $this->executeQuerywithParams($select);
        $masterPrivilege = $resultSet->toArray();

        if (isset($params['roleId'])) {
            $roleId = $this->getIdFromUuid('ox_role', $params['roleId']);
            $select = "SELECT orp.privilege_name,orp.permission FROM ox_role_privilege orp WHERE orp.org_id = ".$orgId." AND orp.role_id =".$roleId." ORDER BY orp.id";
            $resultSet = $this->executeQuerywithParams($select);
            $rolePrivilege = $resultSet->toArray();

            return array('masterPrivilege' => $masterPrivilege,
                         'rolePrivilege' => $rolePrivilege);
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
        }
        return $resultSet->toArray();
    }

    public function getAppId()
    {
        try {
            $userId = AuthContext::get(AuthConstants::USER_ID);
            $query = "select ox_role_privilege.app_id from ox_role_privilege RIGHT JOIN ox_user_role on ox_role_privilege.role_id = ox_user_role.role_id";
            $where = "where ox_user_role.user_id = ".$userId." AND ox_role_privilege.privilege_name = 'MANAGE_ROLE'";
            $resultSet = $this->executeQuerywithParams($query, $where);
            $appIdArray= $resultSet->toArray();
            $appId = array_unique(array_column($appIdArray, 'app_id'));
            return $appId;
        } catch (ValidationException $e) {
            return 0;
        }
    }

    public function getDefaultPrivileges()
    {
        $query = "select p.* from ox_privilege p left join ox_app ap on ap.id = p.app_id and ap.isdefault=1";
        $result = $this->executeQuerywithParams($query);
        return $result;
    }
}
