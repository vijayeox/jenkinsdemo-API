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

    public function getPageContent($appId, $pageId)
    {
        $queryString = " SELECT ox_page_content.type, COALESCE(ox_page_content.content,ox_form.template) as content,ox_form.template as form FROM ox_page_content LEFT OUTER JOIN ox_form on ox_page_content.form_id = ox_form.id WHERE ox_page_content.page_id = ".$pageId. " ORDER BY ox_page_content.sequence ";
        $result= $this->runGenericQuery($queryString);
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        $resultSet = $resultSet->toArray();
        if (count($resultSet)==0) {
            return 0;
        }
        return $resultSet;
    }
    public function savePageContent($appId, &$data)
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


    public function deletePageContent($appId, $id)
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
    public function getContent($appId, $id)
    {
        $queryString = "SELECT ox_page_content.type, COALESCE(ox_page_content.content,ox_form.template) as content,ox_form.template as form FROM ox_page_content LEFT OUTER JOIN ox_form on ox_page_content.form_id = ox_form.id WHERE ox_page_content.id = ".$id. " ORDER BY ox_page_content.sequence ";
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
}
