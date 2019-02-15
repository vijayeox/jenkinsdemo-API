<?php
namespace Oxzion\Service;

use Oxzion\Model\RoleTable;
use Oxzion\Model\Role;
use Bos\Auth\AuthContext;
use Bos\Auth\AuthConstants;
use Bos\Service\AbstractService;
use Bos\ValidationException;
use Zend\Db\Sql\Expression;
use Exception;

class RoleService extends AbstractService{

    public function __construct($config, $dbAdapter, RoleTable $table){
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
    }

    public function createRole(&$data){
        $role = new Role();
        $data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
        $role->exchangeArray($data);
        $role->validate();
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->save($role);
            if($count == 0) {
                $this->rollback();
                return 0;
            }
            $id = $this->table->getLastInsertValue();
            $data['id'] = $id;
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
        $order = "order by ox_role.id";
        $resultSet = $this->executeQuerywithParams($queryString, $where, null, $order);
        return $resultSet->toArray();
    }
}
?>