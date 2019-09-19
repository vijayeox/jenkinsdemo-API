<?php
namespace App\Service;

use App\Model\PageContentTable;
use App\Model\PageContent;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\Service\AbstractService;
use Oxzion\ValidationException;
use Zend\Db\Sql\Expression;
use Zend\Db\ResultSet\ResultSet;
use Exception;

class PageContentService extends AbstractService
{
    public function __construct($config, $dbAdapter, PageContentTable $table)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
    }

    public function getPageContent($appUuid, $pageId)
    {
        $appId = $this->getIdFromUuid('ox_app', $appUuid);
        $resultSet = new ResultSet();
        $select = "SELECT * FROM ox_app_page where id = ".$pageId." AND app_id = ".$appId;
        $selectResult = $this->executeQuerywithParams($select)->toArray();
        if(count($selectResult)>0){
            $queryString = " SELECT ox_page_content.type,ox_form.id as form_id, COALESCE(ox_page_content.content,ox_form.template) as content FROM ox_page_content LEFT OUTER JOIN ox_form on ox_page_content.form_id = ox_form.id WHERE ox_page_content.page_id = ".$pageId. " ORDER BY ox_page_content.sequence ";
            $result= $this->runGenericQuery($queryString);
            $resultSet->initialize($result);
            $resultSet = $resultSet->toArray();
            if (count($resultSet)==0) {
                return 0;
            }
        }else{
            return 0;
        }

        $result = array();
        foreach($resultSet as $resultArray){
            if($resultArray['type'] == 'List' || $resultArray['type'] == 'Form' || $resultArray['type'] == 'DocumentViewer'){ 
                $resultArray['content'] = json_decode($resultArray['content']);
            }else{
                $resultArray['content'] = $resultArray['content'];
            }  
            $result[] = $resultArray;
        }
        $content = array('content' => $result); 
        return array_merge($selectResult[0],$content);
    }
    public function savePageContent($pageId, &$data)
    { 
        $this->beginTransaction();
        $counter=0;
        try{
            $select = "DELETE from ox_page_content where page_id = ".$pageId;
            $result = $this->executeQuerywithParams($select);
            foreach($data as $key => $value){ 
                if($value['type'] == 'List' || $value['type'] == 'DocumentViewer'){
                    $value['content'] = json_encode($value['content']);
                }
                unset($value['id']);
                if (!isset($value['id'])) {
                    $value['created_by'] = AuthContext::get(AuthConstants::USER_ID);
                    $value['date_created'] = date('Y-m-d H:i:s');
                }
                $value['page_id'] = $pageId;
                $value['sequence'] = $key+1;
                $counter+=$this->savePageContentInternal($value);
            }
            $this->commit(); 
        }
        catch(Exception $e){
            print_r($e->getMessage());
            $this->rollback();
            throw $e;
        }
        return $counter;
    }

    public function createPageContent(&$data)
    {
        $page = new PageContent();
        if (!isset($data['id'])) {
            $data['created_by'] = AuthContext::get(AuthConstants::USER_ID);
            $data['date_created'] = date('Y-m-d H:i:s');
        }
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
                $this->rollback();
                return 0;
            }
        return $count;
    }

    public function updatePageContent($id, &$data)
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
        $PageContent = new PageContent();
        $PageContent->exchangeArray($changedArray);
        $PageContent->validate();
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->save($PageContent);
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

    public function deletePageContent($id)
    {
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->delete($id);
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

    public function getPageContents($appId=null, $filterArray = array())
    {
        $resultSet = $this->getDataByParams('ox_page_content', array("*"), $filterArray, null);
        return $resultSet->toArray();
    }
    public function getContent($id)
    {
        $queryString = "SELECT * FROM ox_page_content WHERE id = ".$id;
        $result= $this->runGenericQuery($queryString);
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $resultSet = $resultSet->toArray();
        if (isset($resultSet[0])) {
            return $resultSet[0];
        } else {
            return array();
        }
    }

    private function savePageContentInternal($data){
        $page = new PageContent();
        $page->exchangeArray($data);
        $page->validate();
        $count = 0;
        $count = $this->table->save($page);
        if ($count == 0) {
            return 0;
        }
        if (!isset($data['id'])) {
            $id = $this->table->getLastInsertValue();
            $data['id'] = $id;
        }
        return $count;
    }
}
