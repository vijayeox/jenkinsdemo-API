<?php
namespace Oxzion\Service;

use Bos\Auth\AuthContext;
use Bos\Auth\AuthConstants;
use Oxzion\Model\Role;
use Oxzion\Model\RoleTable;
use Oxzion\Model\PrivilegeTable;
use Bos\Service\AbstractService;
use Exception;
use Oxzion\Utils\FilterUtils;


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

    public function createRole($roleId,&$data){
        $rolename=$data['name'];
        $org_id = AuthContext::get(AuthConstants::ORG_ID);
        try{
            $this->beginTransaction();
            if(isset($roleId)){
                $update = "UPDATE `ox_role` SET `name`= '".$data['name']."' WHERE `id` = '".$roleId."' AND name not in ('ADMIN', 'MANAGER', 'EMPLOYEE') AND org_id = ".$org_id ;
                $result1 = $this->runGenericQuery($update);

                $update = "UPDATE `ox_role` SET `description`= '".$data['description']."' WHERE `id` = '".$roleId."' AND org_id = ".$org_id ;
                $result1 = $this->runGenericQuery($update);

            }else{
                $insert = "INSERT into `ox_role` (`name`,`description`,`org_id`) VALUES ('".$rolename."','".$data['description']."',".$org_id.")";
                $result1 = $this->runGenericQuery($insert);
                $roleId = $result1->getGeneratedValue();
            }
            $this->updateRolePrivileges($roleId, $data['privileges']);
            $this->commit();
        }catch(Exception $e){
            $this->rollback();
            return 0;
        }
        return 1;   
    }

    protected function updateRolePrivileges($roleId, $privileges) {
        $org_id = AuthContext::get(AuthConstants::ORG_ID);
        $privilegeArray=json_decode($privileges,true);
        try{
            $this->beginTransaction();
            for($i=0;$i<sizeof($privilegeArray);$i++){
                if(isset($privilegeArray[$i]['id'])){
                $update = "UPDATE `ox_role_privilege` SET `permission` = ".$privilegeArray[$i]['permission']." WHERE `privilege_name`= '".$privilegeArray[$i]['name']."' AND role_id =".$roleId." AND org_id =".$org_id;
                $updateResult = $this->runGenericQuery($update);
                }
                else{
                    $insert="INSERT INTO `ox_role_privilege` (`role_id`,`privilege_name`,`permission`,`org_id`) VALUES (".$roleId.",'".$privilegeArray[$i]['name']."',".$privilegeArray[$i]['permission'].",".$org_id.")";
                    $resultSet = $this->runGenericQuery($insert);
                }
            }
            $this->commit();
            
        }
        catch(Exception $e){
            return 0;
        }
    }

    protected function createSystemRoleForOrg(&$data) {
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
            $this->updateDefaultRolePrivileges($form);
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

    public function getRoles($filterParams = null) {

        $pageSize = 20;
        $offset = 0;
        $where = "";
        $sort = "name";
        

        $cntQuery ="SELECT count(id) FROM `ox_role`";

        if(count($filterParams) > 0 || sizeof($filterParams) > 0){
                $filterArray = json_decode($filterParams['filter'],true); 
                if(isset($filterArray[0]['filter'])){
                  $filterlogic = isset($filterArray[0]['filter']['logic']) ? $filterArray[0]['filter']['logic'] : "AND" ;
                   $filterList = $filterArray[0]['filter']['filters'];
                   $where = " WHERE ".FilterUtils::filterArray($filterList,$filterlogic);
                }
                if(isset($filterArray[0]['sort']) && count($filterArray[0]['sort']) > 0){
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

    public function getRole($id) {
        $query = "SELECT * FROM ox_role WHERE ox_role.id =".$id." AND ox_role.org_id=".AuthContext::get(AuthConstants::ORG_ID);
        $result = $this->executeQuerywithParams($query);
        $queryString = "select ox_role_privilege.id, ox_role_privilege.privilege_name,ox_role_privilege.permission, ox_role_privilege.app_id,ox_app.name from ox_role_privilege,ox_app where ox_role_privilege.role_id = ".$id." AND ox_role_privilege.org_id=".AuthContext::get(AuthConstants::ORG_ID)." AND ox_role_privilege.app_id = ox_app.uuid";
        $result1 = $this->executeQuerywithParams($queryString);
        return array('data' => $result->toArray(), 
                     'privileges' => $result1->toArray());

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
            if (!$this->createSystemRoleForOrg($basicRole)) {
                return 0;
            }
        }
        return count($basicRoles);
    }

    protected function updateDefaultRolePrivileges(Role $role) {
        $Privileges = array();
        $privileges = $this->privilegeService->getPrivilegesByOrgid($role->org_id);
        if (!$privileges)
            return 0;
        foreach ($privileges as $privilege)
            $Privileges = array(
                'role_id' => $role->id,
                'privilege_name' => $privilege['name'],
                'permission' => $privilege['permission_allowed'],
                'org_id' => $privilege['org_id'],
                'app_id' => $privilege['app_id']
            );
        if ($Privileges) {
            $result = $this->multiInsertOrUpdate('ox_role_privilege', $Privileges);
        }
        // echo "<pre>";print_r($result);exit();
        return count($privileges);
    }

}
?>