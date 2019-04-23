<?php
namespace Oxzion\Service;

use Bos\Auth\AuthContext;
use Bos\Auth\AuthConstants;
use Oxzion\Model\Role;
use Oxzion\Model\RoleTable;
use Oxzion\Model\PrivilegeTable;
use Bos\Service\AbstractService;

class RoleService extends AbstractService {

    protected $table;
    protected $modelClass;
    private $privilegeService;

    public function __construct($config, $dbAdapter, RoleTable $table, PrivilegeTable $privilegeTable) {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
        $this->modelClass = new Role();
        $this->privilegeService = new PrivilegeService($config, $dbAdapter, $privilegeTable, $this);
    }

    public function createRole(&$data) {
        if (!$data['org_id']) {
            $data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
        }

        $form = new Role($data);
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

    public function updateRole($id,&$data){
        $obj = $this->table->get($id,array());
        if (is_null($obj)) {
            return 0;
        }
        $form = new Role();
        $data = array_merge($obj->toArray(), $data); //Merging the data from the db for the ID
        $data['id'] = $id;
        $data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
        $form->exchangeArray($data);
        $form->validate();
        $count = 0;
        try {
            $count = $this->table->save($form);
            if($count == 0) {
                $this->rollback();
                return 0;
            }
        } catch(Exception $e) {
            $this->rollback();
            return 0;
        }
        return $count;
    }


    public function deleteRole($id){
        $this->beginTransaction();
        $count = 0;
        try{
            $count = $this->table->delete($id);
            if($count == 0){
                $this->rollback();
                return 0;
            }
            $this->commit();
        }catch(Exception $e){
            $this->rollback();
        }
        return $count;
    }

    public function getRoles($q,$f,$pg,$psz,$sort) {
        $cntQuery ="SELECT count(id) FROM `ox_role`";
            if(empty($q)){
                $where = " WHERE org_id =".AuthContext::get(AuthConstants::ORG_ID);
            }
            else{
                $where = " WHERE org_id =".AuthContext::get(AuthConstants::ORG_ID)." AND ".$f." like '".$q."%'";   
            }
            $offset = ($pg - 1) * $psz;
            $sort = " ORDER BY ".$sort;
            $limit = " LIMIT ".$psz." offset ".$offset;
            $resultSet = $this->executeQuerywithParams($cntQuery.$where);
            $count=$resultSet->toArray()[0]['count(id)'];
            $query ="SELECT * FROM `ox_role`".$where." ".$sort." ".$limit;
            $resultSet = $this->executeQuerywithParams($query);
            return array('data' => $resultSet->toArray(), 
                     'pagination' => array('page' => $pg,
                                            'noOfPages' => ceil($count/$psz),
                                            'pageSize' => $psz));        
    }

    public function getRole($id) {
        $queryString = "select * from ox_role";
        $where = "where ox_role.id = ".$id." AND ox_role.org_id=".AuthContext::get(AuthConstants::ORG_ID);
        $order = "order by ox_role.name";
        $resultSet = $this->executeQuerywithParams($queryString, $where, null, $order);
        return $resultSet->toArray();
    }

    public function getRolePrivilege($id) {
            $queryString = "select ox_role_privilege.id,ox_role_privilege.role_id, ox_role_privilege.privilege_name,ox_role_privilege.permission,ox_role_privilege.org_id, ox_role_privilege.app_id,ox_app.name from ox_role_privilege,ox_app";
            $where = "where ox_role_privilege.role_id = ".$id." AND ox_role_privilege.org_id=".AuthContext::get(AuthConstants::ORG_ID)." AND ox_role_privilege.app_id = ox_app.uuid"; 
            $order = "order by ox_role_privilege.role_id";
            $resultSet = $this->executeQuerywithParams($queryString, $where, null, $order);
            return $resultSet->toArray();
    }

    public function getRolesByOrgid($orgid) {
        return $this->getDataByParams('ox_role', array(), array('org_id' => $orgid));
    }

    public function createBasicRoles($orgid) {
        $basicRoles = $this->getRolesByOrgid(0);
        foreach ($basicRoles as $basicRole) {
            unset($basicRole['id']);
            $basicRole['org_id'] = $orgid;
            if (!$this->createRole($basicRole)) {
                return 0;
            }
        }
        return count($basicRoles);
    }

    public function updateRolePrivileges(Role $role) {
        $privileges = $this->privilegeService->getPrivilegesByOrgid($role->org_id);
        if (!$privileges)
            return 0;
        foreach ($privileges as $privilege)
            $rolePrivileges[] = array(
                'role_id' => $role->id,
                'privilege_name' => $privilege['name'],
                'permission' => $privilege['permission_allowed'],
                'org_id' => $privilege['org_id'],
                'app_id' => $privilege['app_id']
            );
        if ($rolePrivileges) {
            $result = $this->multiInsertOrUpdate('ox_role_privilege', $rolePrivileges);
        }
        // echo "<pre>";print_r($result);exit();
        return count($privileges);
    }

}
?>