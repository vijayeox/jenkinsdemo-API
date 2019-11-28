<?php
namespace App\Service;

use App\Model\EntityTable;
use App\Model\Entity;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\Service\AbstractService;
use Oxzion\ValidationException;
use Zend\Db\Sql\Expression;
use Oxzion\Utils\UuidUtil;
use Oxzion\Utils\FileUtils;
use App\Service\EntityContentService;
use Oxzion\ServiceException;
use Oxzion\Service\WorkflowService;
use Exception;
use Oxzion\EntityNotFoundException;

class EntityService extends AbstractService
{
    public function __construct($config,WorkflowService $workflowService, $dbAdapter, EntityTable $table)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
        $this->workflowService = $workflowService;
    }
    public function saveEntity($appUuid, &$data,$id = null)
    {
        $count = 0;
        $data['app_id'] = $this->getIdFromUuid('ox_app', $appUuid);
        $data['uuid'] = isset($data['uuid'])?$data['uuid']:UuidUtil::uuid();
        $entity = new Entity();
        try{
            if (!isset($data['id'])) {
                $data['created_by'] = AuthContext::get(AuthConstants::USER_ID);
                $data['date_created'] = date('Y-m-d H:i:s');
            } else {
                $querySelect = "SELECT * from ox_app_entity where app_id = '".$data['app_id']."' AND id = ".$data['id'];
                $queryResult = $this->executeQuerywithParams($querySelect)->toArray();
                if(count($queryResult)==0){
                    throw new EntityNotFoundException("Entity not found for -".$data['id']. " for app $appUuid");
                }
                $data = array_merge($queryResult[0],$data);
                $data['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
                $data['date_modified'] = date('Y-m-d H:i:s');
            }
        }
        catch (Exception $e) {
            throw $e;
        }
        $entity->exchangeArray($data);
        $entity->validate();
        $this->beginTransaction();
        try {
            $count = $this->table->save($entity);
            if (!isset($data['id'])) {
                $id = $this->table->getLastInsertValue();
                $data['id'] = $id;
            }
            if($count == 0){
                $this->rollback();
                throw new ServiceException("Entity save failed", 'entity.save.failed');
                
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
        $entity->validate();
        return $count;
    }
     
    public function deleteEntity($appUuid, $id)
    {
        $result = $this->getEntity($appUuid, $id);
        if($result){
            $this->beginTransaction();
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
    }else{
        throw new ServiceException("Entity Not Found","entity.not.found");
    }
        return $count;
    }
    
    public function getEntitys($appUuid=null, $filterArray = array())
    {
        $query = "select ox_app_entity.* from ox_app_entity left join ox_app on ox_app.id=ox_app_entity.app_id where (ox_app.id=? or ox_app.uuid=?)";
        $queryParams = array($appUuid,$appUuid);
        $resultSet = $this->executeQueryWithBindParameters($query, $queryParams)->toArray();
        if (count($resultSet)==0) {
            return array();
        }
        return $resultSet;
    }
    public function getEntity($appId, $id)
    {
        $query = "select ox_app_entity.* from ox_app_entity left join ox_app on ox_app.id=ox_app_entity.app_id where (ox_app.id=? or ox_app.uuid=?) AND (ox_app_entity.id=? or ox_app_entity.uuid=?)";
        $queryParams = array($appId,$appId,$id,$id);
        $resultSet = $this->executeQueryWithBindParameters($query, $queryParams)->toArray();
        if (count($resultSet)==0) {
            return 0;
        }
        return $resultSet[0];
    }

    public function getEntityByName($appId, $entityName){
        $queryString = "SELECT en.* FROM ox_app_entity AS en INNER JOIN ox_app AS ap ON ap.id = en.app_id WHERE ap.uuid = :appId and en.name = :entityName";
        $params = array("entityName" => $entityName, "appId" => $appId);
        $result = $this->executeQueryWithBindParameters($queryString, $params)->toArray();
        if (count($result) == 0) {
            return null;
        }
        return $result[0];
    }

    public function deployWorkflow($appId, $id,$params, $file = null)
    {
        $baseFolder = $this->config['UPLOAD_FOLDER'];
        $entity = $this->getEntity($appId,$id);
        if($entity){
            if (isset($file)) {
                $workFlowStorageFolder = $baseFolder."app/".$appId."/entity/";
                $fileName = FileUtils::storeFile($file, $workFlowStorageFolder);
                return $this->workflowService->deploy($workFlowStorageFolder.$fileName, $appId, $params,$entity['id']);
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }
}
