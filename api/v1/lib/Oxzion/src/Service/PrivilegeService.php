<?php
namespace Oxzion\Service;

use Bos\Auth\AuthContext;
use Bos\Auth\AuthConstants;
use Oxzion\Model\Privilege;
use Oxzion\Model\PrivilegeTable;
use Bos\ValidationException;
use Bos\Service\AbstractService;

class PrivilegeService extends AbstractService {

    protected $table;
    private $roleService;
    protected $modelClass;

    public function __construct($config, $dbAdapter, PrivilegeTable $table, RoleService $roleService) {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
        $this->roleService = $roleService;
        $this->modelClass = new Privilege();
    }

    public function createPrivilege(&$data) {
        if (!$data['org_id']) {
            $data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
        }

        $form = new Privilege($data);
        $form->validate();
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->save($form);
            if($count == 0) {
                $this->rollback();
                return 0;
            }
            $form->id = $data['id'] = $this->table->getLastInsertValue();
            $this->updateRolePrivileges($form);
            $this->commit();
        } catch(Exception $e) {
            $this->rollback();
            return 0;
        }
        return $count;
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
            $group = "group by op.name";
            $resultSet = $this->executeQuerywithParams($queryString, $where, $group, $order);
        } catch (ValidationException $e) {
            return $response = ['data' => $appId, 'errors' => $e->getErrors()];
        }
        return $resultSet->toArray();
    }

    public function getAppId()
    {
        try{
            $userId = AuthContext::get(AuthConstants::USER_ID);
            $query = "select ox_role_privilege.app_id from ox_role_privilege RIGHT JOIN ox_user_role on ox_role_privilege.role_id = ox_user_role.role_id";
            $where = "where ox_user_role.user_id = ".$userId." AND ox_role_privilege.privilege_name = 'MANAGE_ROLE'";
            $resultSet = $this->executeQuerywithParams($query, $where);
            $appIdArray= $resultSet->toArray();
            $appId = array_unique(array_column($appIdArray,'app_id'));
            return $appId;
        }
        catch (ValidationException $e) {
            return 0;
        }
    }

    public function getPrivilegesByOrgid($orgid) {
        return $this->getDataByParams('ox_privilege', array(), array('org_id' => $orgid));
    }

    public function createBasicPrivileges($orgid) {
        $basicPrivileges = $this->getPrivilegesByOrgid(null);
        foreach ($basicPrivileges as $basicPrivilege) {
            unset($basicPrivilege['id']);
            $basicPrivilege['org_id'] = $orgid;
            if (!$this->createPrivilege($basicPrivilege)) {
                return 0;
            }
        }
        return count($basicPrivileges);
    }

    public function updateRolePrivileges(Privilege $privilege) {
        $roles = $this->roleService->getRolesByOrgid($privilege->org_id);
        if (!$roles)
            return 0;
        $rolePrivilege = array(
            'role_id' => null,
            'privilege_name' => $privilege->name,
            'permission' => $privilege->permission_allowed,
            'org_id' => $privilege->org_id,
            'app_id' => $privilege->app_id
        );
        foreach ($roles as $role)
            $rolePrivileges[] = array_merge($rolePrivilege, array('role_id' => $role['id']));
        if ($rolePrivileges) {
            $result = $this->multiInsertOrUpdate('ox_role_privilege', $rolePrivileges);
        }
        // echo "<pre>";print_r($result);exit();
        return count($roles);
    }

}
?>