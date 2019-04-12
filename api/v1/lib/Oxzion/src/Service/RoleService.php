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

    public function getRoles() {
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_role')
                ->columns(array("*"))
                ->where(array('ox_role.org_id' => AuthContext::get(AuthConstants::ORG_ID)));
        return $this->executeQuery($select)->toArray();
    }

    public function getRole($id) {
        $queryString = "select * from ox_role";
        $where = "where ox_role.id = ".$id." AND ox_role.org_id=".AuthContext::get(AuthConstants::ORG_ID);
        $order = "order by ox_role.name";
        $resultSet = $this->executeQuerywithParams($queryString, $where, null, $order);
        return $resultSet->toArray();
    }

    public function getRolePrivilege($id) {
        return $queryString = $this->getDataByParams(
            array('orp' => 'ox_role_privilege'),
            array('id', 'role_id', 'privilege_name', 'permission', 'org_id', 'app_id'),
            array('orp.role_id' => $id, 'orp.org_id' => AuthContext::get(AuthConstants::ORG_ID)),
            array(
                array(
                    'table' => array('op' => 'ox_app'),
                    'condition' => 'orp.app_id = op.uuid',
                    'fields' => array('name'),
                    'joinMethod' => 'left'
                )
            ),
            'orp.role_id ASC'
        );
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