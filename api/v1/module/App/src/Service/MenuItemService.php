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
        $this->logger->info("In saveMenuItem params - $appUuid, ".json_encode($data));
        $MenuItem = new MenuItem();
        $data['uuid'] = UuidUtil::uuid();
        $data['app_id'] = $this->getIdFromUuid('ox_app',$appUuid);
        if(isset($data['page_id'])){
            $data['page_id'] = $this->getIdFromUuid('ox_app_page',$data['page_id']);
        }
        if (!isset($data['id'])) {
            $data['created_by'] = AuthContext::get(AuthConstants::USER_ID);
            $data['date_created'] = date('Y-m-d H:i:s');
        }
        $data['icon'] = isset($data['icon']) ? $data['icon'] : "fas fa-border-all";
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
            $this->logger->error($e->getMessage(), $e);
            $this->rollback();
            throw $e;
        }
        return $count;
    }
    public function updateMenuItem($menuUuid, &$data)
    {
        $this->logger->info("In updateMenuItem params - $menuUuid, ".json_encode($data));
        $obj = $this->table->getByUuid($menuUuid, array());
        if (is_null($obj)) {
            return 0;
        }
        $data['id'] = $this->getIdFromUuid('ox_app_menu',$menuUuid);
        $data['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_modified'] = date('Y-m-d H:i:s');
        $file = $obj->toArray();
        $changedArray = array_merge($obj->toArray(), $data);
        $MenuItem = new MenuItem();
        $MenuItem->exchangeArray($changedArray);
        $pageId = $this->getUuidFromId('ox_app_page', $file['page_id']);
        if($pageId != 0){
            $data['page_id'] = $pageId;
        } 
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
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
        return $count;
    }


    public function deleteMenuItem($appUuid, $menuUuid)
    {
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->delete($this->getIdFromUuid('ox_app_menu',$menuUuid), ['app_id'=>$this->getIdFromUuid('ox_app',$appUuid)]);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
        return $count;
    }

    public function getMenuItems($appUuid=null, $filterArray = array())
    {
        $filterArray['app_id'] = $this->getIdFromUuid('ox_app',$appUuid);
        $queryString = "SELECT ox_app_menu.icon,ox_app_menu.name,ox_app_page.uuid as page_id,
                        ox_app_menu.parent_id,ox_app_menu.sequence,ox_app_menu.uuid,ox_app_menu.privilege_name
                        FROM ox_app_menu
                        INNER JOIN ox_app_registry ON ox_app_registry.app_id = ox_app_menu.app_id
                        LEFT JOIN ox_app_page ON ox_app_page.id = ox_app_menu.page_id
                        where ox_app_registry.org_id = :orgId
                        AND ox_app_menu.app_id= :appId order by ox_app_menu.sequence;";
        $whereQuery = array("orgId" => AuthContext::get(AuthConstants::ORG_ID),"appId" => $filterArray['app_id']);
        $this->logger->info("Get Menu Query $queryString with params".json_encode($whereQuery));
        $resultSet = $this->executeQueryWithBindParameters($queryString,$whereQuery);
        $menuList = array();
        if ($resultSet->count()) {
            $menuList = $resultSet->toArray();
            $i = 0;
            foreach ($menuList as $key => $menuItem) {
                if(isset($menuItem['privilege_name']) && $menuItem['privilege_name']!="" && (!AuthContext::isPrivileged($menuItem['privilege_name']))){
                    unset($menuList[$key]);
                    continue;
                }
                if (isset($menuItem['parent_id']) && $menuItem['parent_id'] != 0) {
                    $parentKey = array_search($menuItem['parent_id'], array_column($menuList, 'id'));
                    $menuList[$parentKey]['submenu'][] = $menuItem;
                    unset($menuList[$key]);
                }
            }
        }else{
            return 0;
        }
        return array_values($menuList);
    }
    public function getMenuItem($appUuid, $menuUuid)
    {
        try{
            $select = "SELECT ox_app_menu.* FROM ox_app_menu left join ox_app on ox_app.id = ox_app_menu.app_id where ox_app_menu.uuid=? and ox_app.uuid=?";
            $whereQuery = array($menuUuid,$appUuid);
            $response = $this->executeQueryWithBindParameters($select,$whereQuery)->toArray();
            if (count($response)==0) {
                return 0;
            }
            return $response[0];
        }catch(Exception $e){
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
    }
}
