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
    public function savePage($routeData, &$data,$id = null)
    {  
        $count = 0;
        $orgId = isset($routeData['orgId'])? $this->getIdFromUuid('ox_organization', $routeData['orgId']) : AuthContext::get(AuthConstants::ORG_ID);
        $data['app_id'] = $this->getIdFromUuid('ox_app', $routeData['appId']);
        $select = "SELECT * from ox_app_registry where org_id = :orgId AND app_id = :appId";
        $selectQuery = array("orgId" => $orgId,"appId" => $data['app_id']);           
        $result = $this->executeQuerywithBindParameters($select,$selectQuery)->toArray();
        if(count($result) > 0){
            $content = isset($data['content'])?$data['content']:false;
            if(!isset($data['uuid'])){
                $data['uuid'] = UuidUtil::uuid();
            }
            if(isset($id)){
                $data['id'] = $id;
                $querySelect = "SELECT * from ox_app_page where app_id = :appId AND uuid = :uuid";
                $whereQuery = array("appId" => $data['app_id'],"uuid" => $id);  
                $queryResult = $this->executeQuerywithBindParameters($querySelect,$whereQuery)->toArray();
                if(count($queryResult)>0){
                    $deleteQuery = "DELETE from ox_page_content where page_id = ?";
                    $whereParams = array($queryResult[0]['id']);
                    $deleteResult = $this->executeQuerywithBindParameters($deleteQuery,$whereParams);
                    $deleteRecord = $this->table->delete($this->getIdFromUuid('ox_app_page', $id), ['app_id'=>$data['app_id']]);
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
                    throw new ServiceException("Page save failed", "page.save.failed");
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
                $this->rollback();
                $this->logger->error($e->getMessage(), $e);
                throw $e;
            }
        }else{
            throw new ServiceException("App Does not belong to the org","app.fororgnot.found");
        }
        return $count;
    }
     
    public function deletePage($appUuid, $pageUuid)
    {
        $select = "SELECT ox_app_page.* from ox_app_page left join ox_app on ox_app.id = ox_app_page.app_id where ox_app.uuid =? AND ox_app_page.uuid =?";
        $whereQuery = array($appUuid,$pageUuid);
        $result = $this->executeQueryWithBindParameters($select,$whereQuery)->toArray();
        $count = 0;
        if(count($result)>0){
            $this->beginTransaction();           
        try {
            $selectQuery = "DELETE from ox_page_content where page_id = ?";
            $selectParams = array($result[0]['id']);
            $resultSet = $this->executeQueryWithBindParameters($selectQuery,$selectParams);
            $count = $this->table->delete($this->getIdFromUuid('ox_app_page', $pageUuid), ['app_id'=>$this->getIdFromUuid('ox_app', $appUuid)]);
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
    public function getPage($appUuid, $pageUuid)
    {     
        try{
            $select = "SELECT ox_app_page.* FROM ox_app_page left join ox_app on ox_app.id = ox_app_page.app_id where ox_app_page.uuid=? and ox_app.uuid=?";
            $whereQuery = array($pageUuid,$appUuid);
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
