<?php
namespace App\Service;

use App\Model\PageTable;
use App\Model\Page;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\Service\AbstractService;
use Oxzion\ValidationException;
use Zend\Db\Sql\Expression;
use Oxzion\Utils\UuidUtil;
use Exception;

class PageService extends AbstractService
{
    public function __construct($config, $dbAdapter, PageTable $table)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
    }
    public function savePage($appId, &$data)
    {
        $page = new Page();
        $data['app_id'] = $appId;
        $data['uuid'] = UuidUtil::uuid();
        if (!isset($data['id'])) {
            $data['created_by'] = AuthContext::get(AuthConstants::USER_ID);
            $data['date_created'] = date('Y-m-d H:i:s');
        }
        $data['icon'] = isset($data['icon']) ? $data['icon'] : "DummyIcon";
        $data['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_modified'] = date('Y-m-d H:i:s');
        $page->exchangeArray($data);
        $page->validate();
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->save($page);
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
    public function updatePage($id, &$data)
    {
        $obj = $this->table->get($id, array());
        if (is_null($obj)) {
            return 0;
        }
        $data['id'] = $id;
        $data['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_modified'] = date('Y-m-d H:i:s');
        $file = $obj->toArray();
        $changedArray = array_merge($obj->toArray(), $data);
        $Page = new Page();
        $Page->exchangeArray($changedArray);
        $Page->validate();
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->save($Page);
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


    public function deletePage($appId, $id)
    {
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->delete($id, ['app_id'=>$appId]);
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

    public function getPages($appId=null, $filterArray = array())
    {
        if (isset($appId)) {
            $filterArray['app_id'] = $appId;
        }
        $resultSet = $this->getDataByParams('ox_app_page', array("*"), $filterArray, null);
        return $resultSet->toArray();
    }
    public function getPage($appId, $id)
    {
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_app_page')
        ->columns(array("*"))
        ->where(array('id' => $id,'app_id'=>$appId));
        $response = $this->executeQuery($select)->toArray();
        if (count($response)==0) {
            return 0;
        }
        return $response[0];
    }
}
