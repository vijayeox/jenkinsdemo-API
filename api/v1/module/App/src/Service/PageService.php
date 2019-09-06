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
use App\Service\PageContentService;
use Oxzion\ServiceException;
use Exception;

class PageService extends AbstractService
{
    public function __construct($config, PageContentService $pageContentService ,$dbAdapter, PageTable $table)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
        $this->pageContentService = $pageContentService;
    }
    public function savePage($appUuid, &$data,$id = null)
    {
        $count = 0;
        $orgId = AuthContext::get(AuthConstants::ORG_ID);
        $data['app_id'] = $this->getIdFromUuid('ox_app', $appUuid);
        $select = "SELECT * from ox_app_registry where org_id = '".$orgId."' AND app_id = ".$data['app_id'];
        $result = $this->executeQuerywithParams($select)->toArray();
        if(count($result) > 0){
            $content = isset($data['content'])?$data['content']:false;
            $data['uuid'] = UuidUtil::uuid();
            if(isset($id)){
                $querySelect = "SELECT * from ox_app_page where app_id = '".$data['app_id']."' AND id = ".$id;
                $queryResult = $this->executeQuerywithParams($querySelect)->toArray();
                if(count($queryResult)>0){
                    $deleteQuery = "DELETE from ox_page_content where page_id = ".$queryResult[0]['id'];
                    $deleteResult = $this->executeQuerywithParams($deleteQuery);
                    $deleteRecord = $this->table->delete($id, ['app_id'=>$data['app_id']]);
                }else{
                    return 0;
                }
            }
            $page = new Page();
            if (!isset($data['id'])) {
                $data['created_by'] = AuthContext::get(AuthConstants::USER_ID);
                $data['date_created'] = date('Y-m-d H:i:s');
            }
            $data['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
            $data['date_modified'] = date('Y-m-d H:i:s');
            $page->exchangeArray($data);
            $page->validate();
            $this->beginTransaction();
            try {
                unset($data['content']);
                $count = $this->table->save($page);
                if ($count == 0) {
                    $this->rollback();
                    return 0;
                }
                if (!isset($data['id'])) {
                    $id = $this->table->getLastInsertValue();
                    $data['id'] = $id;
                }
                if($content){
                    $pageContent = $this->pageContentService->savePageContent($data['id'],$content);
                }   
                $this->commit();
            } catch (Exception $e) {
                print_r($e->getMessage());
                $this->rollback();
                throw $e;
            }
        }else{
            throw new ServiceException("App Does not belong to the org","app.fororgnot.found");
        }
        return $count;
    }
     
    public function deletePage($appUuid, $id)
    {
        $appId = $this->getIdFromUuid('ox_app', $appUuid);
        $select = "SELECT * from ox_app_page where app_id = '".$appId."' AND id = ".$id;
        $result = $this->executeQuerywithParams($select)->toArray();
        $count = 0;
        if(count($result)>0){
            $this->beginTransaction();           
        try {
            $selectQuery = "DELETE from ox_page_content where page_id = ".$result[0]['id'];
            $resultSet = $this->executeQuerywithParams($selectQuery);
            $count = $this->table->delete($id, ['app_id'=>$appId]);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
        }
    }else{
        throw new ServiceException("Page Not Found","page.not.found");
    }
        return $count;
    }
    
    public function getPages($appUuid=null, $filterArray = array())
    {
        if (isset($appUuid)) {
            $filterArray['app_id'] = $this->getIdFromUuid('ox_app', $appUuid);
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
