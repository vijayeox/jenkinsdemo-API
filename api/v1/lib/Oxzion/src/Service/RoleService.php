<?php
namespace Oxzion\Service;

use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\Model\Role;
use Oxzion\Model\RoleTable;
use Oxzion\Model\PrivilegeTable;
use Oxzion\Service\AbstractService;
use Oxzion\Utils\UuidUtil;
use Exception;
use Oxzion\Utils\FilterUtils;

class RoleService extends AbstractService
{
    protected $table;
    protected $modelClass;
    private $privilegeService;

    public function __construct($config, $dbAdapter, RoleTable $table, PrivilegeTable $privilegeTable)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
        $this->modelClass = new Role();
        $this->privilegeService = new PrivilegeService($config, $dbAdapter, $privilegeTable, $this);
    }

    public function saveRole($roleId, &$data)
    {
        if (isset($roleId)) {
            $obj = $this->table->getByUuid($roleId, array());
            if (isset($obj)) {
                $roleId = $obj->id;
            } else {
                return 0;
            }
        } else {
            $roleId = null;
        }
        $rolename=$data['name'];
        $org_id = isset($data['org_id']) ? $data['org_id'] : AuthContext::get(AuthConstants::ORG_ID);
        $data['description'] = isset($data['description'])?$data['description']:'';
        $data['privileges'] = isset($data['privileges'])?$data['privileges']:array();
        $count = 0;
        try {
            $this->beginTransaction();
            if (isset($roleId)) {
                $update = "UPDATE `ox_role` SET `name`= '".$data['name']."' WHERE `id` = '".$roleId."' AND name not in ('ADMIN', 'MANAGER', 'EMPLOYEE') AND org_id = ".$org_id ;
                $result1 = $this->runGenericQuery($update);
                $update = "UPDATE `ox_role` SET `description`= '".$data['description']."' WHERE `id` = '".$roleId."' AND org_id = ".$org_id ;
                $result1 = $this->runGenericQuery($update);
                $count = $result1->getAffectedRows() + 1;
            } else {
                $data['uuid'] = UuidUtil::uuid();
                $insert = "INSERT into `ox_role` (`name`,`description`,`uuid`,`org_id`)
                 VALUES ('".$rolename."','".$data['description']."','".$data['uuid']."',".$org_id.")";
                $result1 = $this->runGenericQuery($insert);
                $count = $result1->getAffectedRows();
                if ($count > 0) {
                    $roleId = $result1->getGeneratedValue();
                    $data['id'] = $roleId;
                }
            }

            if ($count > 0) {
                $this->updateRolePrivileges($roleId, $data['privileges']);
                $this->commit();
            } else {
                $this->rollback();
            }
        } catch (Exception $e) {
            $this->rollback();
            return 0;
        }
        return $count;
    }

    protected function updateRolePrivileges($roleId, &$privileges)
    {
        // $privileges = json_decode($privileges,true);
        $orgId = AuthContext::get(AuthConstants::ORG_ID);
        try {
            $delete = "DELETE from `ox_role_privilege` where role_id =".$roleId."";
            $result = $this->runGenericQuery($delete);
            for ($i=0;$i<sizeof($privileges);$i++) {
                $appId = isset($privileges[$i]['app_id'])?$privileges[$i]['app_id']:'NULL';
                $insert="INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`org_id`,`app_id`)
                             SELECT ".$roleId.",'".$privileges[$i]['privilege_name']."',".$privileges[$i]['permission'].",".$orgId.
                                ", app_id from ox_privilege where name = '".$privileges[$i]['privilege_name']."'";
                $resultSet = $this->runGenericQuery($insert);
                $privilegeId = $resultSet->getGeneratedValue();
                $privileges[$i]['id'] = $privilegeId;
            }
        } catch (Exception $e) {
            return 0;
        }
    }

    protected function createSystemRoleForOrg(&$data)
    {
        if (!$data['org_id']) {
            $data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
        }
        $data['privileges'] = array();
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->saveRole(null, $data);
            if ($count == 0) {
                return 0;
            }
            $count = $this->updateDefaultRolePrivileges($data);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            return 0;
        }
        return $count;
    }

    public function deleteRole($id)
    {
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->deleteByUuid($id);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
        }
        return $count;
    }

    public function getRoles($filterParams = null)
    {
        $pageSize = 20;
        $offset = 0;
        $where = "";
        $sort = "name";
        

        $cntQuery ="SELECT count(id) FROM `ox_role`";

        if (count($filterParams) > 0 || sizeof($filterParams) > 0) {
            $filterArray = json_decode($filterParams['filter'], true);
            if (isset($filterArray[0]['filter'])) {
                $filterlogic = isset($filterArray[0]['filter']['logic']) ? $filterArray[0]['filter']['logic'] : "AND" ;
                $filterList = $filterArray[0]['filter']['filters'];
                $where = " WHERE ".FilterUtils::filterArray($filterList, $filterlogic);
            }
            if (isset($filterArray[0]['sort']) && count($filterArray[0]['sort']) > 0) {
                $sort = $filterArray[0]['sort'];
                $sort = FilterUtils::sortArray($sort);
            }
            $pageSize = $filterArray[0]['take'];
            $offset = $filterArray[0]['skip'];
        }


        $where .= strlen($where) > 0 ? " AND org_id =".AuthContext::get(AuthConstants::ORG_ID) : "WHERE org_id =".AuthContext::get(AuthConstants::ORG_ID);

        $sort = " ORDER BY ".$sort;
        $limit = " LIMIT ".$pageSize." offset ".$offset;
        $resultSet = $this->executeQuerywithParams($cntQuery.$where);
        $count=$resultSet->toArray()[0]['count(id)'];
        $query ="SELECT * FROM `ox_role`".$where." ".$sort." ".$limit;
        $resultSet = $this->executeQuerywithParams($query);
        return array('data' => $resultSet->toArray(),
                     'total' => $count);
    }

    public function getRole($id)
    {
        $query = "SELECT * FROM ox_role WHERE ox_role.id =".$id." AND ox_role.org_id=".AuthContext::get(AuthConstants::ORG_ID);
        $result = $this->executeQuerywithParams($query);
        $queryString = "select ox_role_privilege.id, ox_role_privilege.privilege_name,ox_role_privilege.permission, ox_role_privilege.app_id,ox_app.name 
                            from ox_role_privilege left outer join ox_app on ox_role_privilege.app_id = ox_app.id 
                            where ox_role_privilege.role_id = ".$id." AND ox_role_privilege.org_id=".AuthContext::get(AuthConstants::ORG_ID).
                            " order by ox_role_privilege.privilege_name";
        $result1 = $this->executeQuerywithParams($queryString);
        $resp = $result->toArray();
        if (count($resp) > 0) {
            $resp = $resp[0];
            $resp['privileges'] = $result1->toArray();
        } else {
            $resp = array();
        }
        
        return $resp;
    }

    public function getRolePrivilege($id)
    {
        $queryString = "select ox_role_privilege.id,ox_role_privilege.role_id, ox_role_privilege.privilege_name,ox_role_privilege.permission,ox_role_privilege.org_id, ox_role_privilege.app_id,ox_app.name from ox_role_privilege,ox_app";
        $where = "where ox_role_privilege.role_id = ".$id." AND ox_role_privilege.org_id=".AuthContext::get(AuthConstants::ORG_ID)." AND ox_role_privilege.app_id = ox_app.id";
        $order = "order by ox_role_privilege.role_id";
        $resultSet = $this->executeQuerywithParams($queryString, $where, null, $order);
        return $resultSet->toArray();
    }

    public function getRolesByOrgid($orgid)
    {
        return $this->getDataByParams('ox_role', array(), array('org_id' => $orgid));
    }

    public function createBasicRoles($orgid)
    {
        $basicRoles = $this->getRolesByOrgid(null);
        foreach ($basicRoles as $basicRole) {
            unset($basicRole['id']);
            $basicRole['org_id'] = $orgid;
            if (!$this->createSystemRoleForOrg($basicRole)) {
                return 0;
            }
        }
        return count($basicRoles);
    }

    protected function updateDefaultRolePrivileges($role)
    {
        $count = 0;
        $query = "INSERT into ox_role_privilege (role_id, privilege_name, permission, org_id, app_id) 
                    SELECT ".$role['id'].", rp.privilege_name,rp.permission,".$role['org_id'].
                    ",rp.app_id from ox_role_privilege rp left join ox_role r on rp.role_id = r.id 
                    where r.name = '".$role['name']."' and r.org_id is NULL";
        $count = $this->runGenericQuery($query);
        if (!$count) {
            return 0;
        } else {
            return 1;
        }
    }
}
