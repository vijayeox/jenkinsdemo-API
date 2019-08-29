<?php
namespace App\Service;

use App\Model\MenuItemTable;
use App\Model\MenuItem;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\Service\AbstractService;
use Oxzion\ValidationException;
use Zend\Db\Sql\Expression;
use Oxzion\Utils\UuidUtil;
use Exception;
use Group\Service\GroupService;

class MenuItemService extends AbstractService
{
    public function __construct($config, Groupservice $groupService, $dbAdapter, MenuItemTable $table)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
        $this->groupService = $groupService;
    }
    public function saveMenuItem($appUuid, &$data)
    {
        $MenuItem = new MenuItem();
        $data['uuid'] = UuidUtil::uuid();
        $data['app_id'] = $this->getIdFromUuid('ox_app',$appUuid);
        if (!isset($data['id'])) {
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
        try {
            $count = $this->table->save($MenuItem);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
            if (!isset($data['id'])) {
                $id = $this->table->getLastInsertValue();
                $data['id'] = $id;
            }
            $this->commit();
        } catch (Exception $e) {
            switch (get_class($e)) {
             case "Oxzion\ValidationException":
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
    public function updateMenuItem($menuId, &$data)
    {
        $obj = $this->table->get($menuId, array());
        if (is_null($obj)) {
            return 0;
        }
        $data['id'] = $menuId;
        $data['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_modified'] = date('Y-m-d H:i:s');
        $file = $obj->toArray();
        $changedArray = array_merge($obj->toArray(), $data);
        $MenuItem = new MenuItem();
        $MenuItem->exchangeArray($changedArray);
        $MenuItem->validate();
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->save($MenuItem);
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


    public function deleteMenuItem($appUuid, $menuId)
    {
        $appId = $this->getIdFromUuid('ox_app',$appUuid);
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->delete($menuId, ['app_id'=>$appId]);
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

    public function getMenuItems($appUuid=null, $filterArray = array())
    {
        $filterArray['app_id'] = $this->getIdFromUuid('ox_app',$appUuid);
        $userId = AuthContext::get(AuthConstants::USER_ID);
        $orgId = AuthContext::get(AuthConstants::ORG_ID);
        $queryString = " SELECT ox_app_menu.icon,ox_app_menu.name,ox_app_menu.page_id,ox_app_menu.parent_id,ox_app_menu.sequence,ox_app_menu.uuid from ox_app_menu where group_id IS NULL AND ox_app_menu.app_id=".$filterArray['app_id']." union select ox_app_menu.icon,ox_app_menu.name,ox_app_menu.page_id,ox_app_menu.parent_id,ox_app_menu.sequence,ox_app_menu.uuid from ox_app_menu INNER JOIN ox_user_group on ox_user_group.group_id=ox_app_menu.group_id INNER JOIN ox_group on ox_group.id = ox_user_group.group_id where ox_user_group.avatar_id = ".$userId." AND ox_app_menu.app_id=".$filterArray['app_id']." AND ox_group.org_id=".$orgId;
        $resultSet = $this->executeQuerywithParams($queryString);
        $menuList = array();
        if ($resultSet->count()) {
            $menuList = $resultSet->toArray();
            $i = 0;
            foreach ($menuList as $key => $menuItem) {
                if (isset($menuItem['parent_id']) && $menuItem['parent_id'] != 0) {
                    $parentKey = array_search($menuItem['parent_id'], array_column($menuList, 'id'));
                    $menuList[$parentKey]['submenu'][] = $menuItem;
                    unset($menuList[$key]);
                }
            }
        }else{
            return 0;
        }
        return $menuList;
    }
    public function getMenuItem($appUuid, $menuId)
    {
        $appId = $this->getIdFromUuid('ox_app',$appUuid);
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_app_menu')
        ->columns(array("*"))
        ->where(array('id' => $menuId,'app_id'=>$appId));
        $response = $this->executeQuery($select)->toArray();
        if (count($response)==0) {
            return 0;
        }
        return $response[0];
    }
}
