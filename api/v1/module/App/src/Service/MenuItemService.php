<?php
namespace App\Service;

use App\Model\MenuItemTable;
use App\Model\MenuItem;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\Service\AbstractService;
use Oxzion\ValidationException;
use Zend\Db\Sql\Expression;
use Exception;

class MenuItemService extends AbstractService{

    public function __construct($config, $dbAdapter, MenuItemTable $table){
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
    }
    public function saveMenuItem($appId,&$data){
        $MenuItem = new MenuItem();
        $data['app_id'] = $appId;
        if(!isset($data['id'])){
            $data['created_by'] = AuthContext::get(AuthConstants::USER_ID);
            $data['date_created'] = date('Y-m-d H:i:s');
        }
        $data['icon'] = isset($data['icon']) ? $data['icon'] : "DummyIcon";
        $data['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_modified'] = date('Y-m-d H:i:s');
        $MenuItem->exchangeArray($data);
        $MenuItem->validate();
        $this->beginTransaction();
        $count = 0;
        try{
            $count = $this->table->save($MenuItem);
            if($count == 0){
                $this->rollback();
                return 0;
            }
            if(!isset($data['id'])){
                $id = $this->table->getLastInsertValue();
                $data['id'] = $id;
            }
            $this->commit();
        }catch(Exception $e){
            switch (get_class ($e)) {
             case "Oxzion\ValidationException" :
                $this->rollback();
                throw $e;
                break;
             default:
                $this->rollback();
                return 0;
                break;
            }
        }
        return $count;
    }
    public function updateMenuItem($id,&$data){
        $obj = $this->table->get($id,array());
        if(is_null($obj)){
            return 0;
        }
        $data['id'] = $id;
        $data['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_modified'] = date('Y-m-d H:i:s');
        $file = $obj->toArray();
        $changedArray = array_merge($obj->toArray(),$data);
        $MenuItem = new MenuItem();
        $MenuItem->exchangeArray($changedArray);
        $MenuItem->validate();
        $this->beginTransaction();
        $count = 0;
        try{
            $count = $this->table->save($MenuItem);
            if($count == 0){
                $this->rollback();
                return 0;
            }
            $this->commit();
        }catch(Exception $e){
            $this->rollback();
            return 0;
        }
        return $count;
    }


    public function deleteMenuItem($appId,$id){
        $this->beginTransaction();
        $count = 0;
        try{
            $count = $this->table->delete($id, ['app_id'=>$appId]);
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

    public function getMenuItems($appId=null,$filterArray = array()) {
        if(isset($appId)){
            $filterArray['app_id'] = $appId;
        }
        $resultSet = $this->getDataByParams('ox_app_menu',array("*"),$filterArray,null);
        if($resultSet->count()){
            $menuList = $resultSet->toArray();
            $i = 0;
            foreach ($menuList as $key => $menuItem) {
                if(isset($menuItem['parent_id']) && $menuItem['parent_id'] != 0){
                    $parentKey = array_search($menuItem['parent_id'], array_column($menuList, 'id'));
                    $menuList[$parentKey]['submenu'][] = $menuItem;
                    unset($menuList[$key]);
                }
            }
        }
        return $menuList;
    }
    public function getMenuItem($appId,$id) {
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_app_menu')
        ->columns(array("*"))
        ->where(array('id' => $id,'app_id'=>$appId));
        $response = $this->executeQuery($select)->toArray();
        if(count($response)==0){
            return 0;
        }
        return $response[0];
    }
}
?>