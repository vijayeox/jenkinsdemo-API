<?php
namespace FileIndexer\Service;

use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\Service\AbstractService;
use Oxzion\ValidationException;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Messaging\MessageProducer;
use Zend\Log\Logger;
use Exception;

class FileIndexerService extends AbstractService
{
    protected $restClient;
    protected $messageProducer;

    public function __construct($config,AdapterInterface $dbAdapter,MessageProducer $messageProducer)
    {
        parent::__construct($config,$dbAdapter);
        $this->messageProducer = $messageProducer;
    }
    public function setRestClient($restClient)
    {
        $this->restClient = $restClient;
    }

    public function setMessageProducer($messageProducer)
    {
        $this->messageProducer = $messageProducer;
    }

    public function getRelevantDetails($fileId)
    {
        if(isset($fileId))
        {
            $select = "SELECT file.id as id,app.name as app_name, entity.id as entity_id, entity.name as entity_name,
            file.data as file_data, file.uuid as file_uuid, file.is_active, file.parent_id,file.latest,file.org_id,
            CONCAT('{', GROUP_CONCAT(CONCAT('\"', field.name, '\" : \"',COALESCE(field.text, field.name),'\"') SEPARATOR ','), '}') as fields,
            wf_user.user_id, file.workflow_instance_id,
            w.id as workflow_instance_id, w.status,
            w.name as workflow_name, w.activities
            from ox_file as file
            INNER JOIN ox_app_entity as entity ON file.entity_id = entity.id
            INNER JOIN ox_app as app on entity.app_id = app.id
            left join (select wf_user.user_id, idfa.file_id, wf_user.app_id, wf_user.org_id from ox_file_attribute idfa
            inner join ox_field id_fd on id_fd.id = idfa.field_id
            inner JOIN ox_wf_user_identifier as wf_user on idfa.field_value = wf_user.identifier 
            and wf_user.identifier_name = id_fd.name) as wf_user on wf_user.file_id = file.id and wf_user.app_id = app.id 
            and wf_user.org_id = file.org_id 
            INNER JOIN ox_field as field ON field.entity_id = entity.id 
            LEFT JOIN (SELECT wf_inst.id, wf_inst.status,
            act_inst.activity_instance_id,
            wf.name, CONCAT('{', GROUP_CONCAT(CONCAT('\"', activity.name, '\" : \"', act_inst.status, '\"') SEPARATOR ','), '}') as activities
            FROM ox_workflow_instance as wf_inst
            INNER JOIN ox_workflow_deployment as wd on wf_inst.workflow_deployment_id = wd.id 
            INNER JOIN ox_workflow as wf on wd.workflow_id = wf.id
            LEFT JOIN ox_activity_instance as act_inst on wf_inst.id = act_inst.workflow_instance_id
            LEFT JOIN ox_activity as activity on wd.id = activity.workflow_deployment_id
            GROUP BY wf_inst.id, wf_inst.status, act_inst.activity_instance_id, wf.name) w
            ON w.id = file.workflow_instance_id
            where file.id = ".$fileId." and file.latest =1
            GROUP BY wf_user.user_id,file.id,app_name,entity.id, entity.name,file_data,file_uuid,file.latest,file.workflow_instance_id,file.is_active, file.parent_id, file.org_id,w.id, w.status,w.name, w.activities";

            $this->runGenericQuery("SET SESSION group_concat_max_len = 1000000;");
            $this->logger->info("Executing Query - $select");
            $body=$this->executeQuerywithParams($select)->toArray();
            if(isset($body[0]))
                $databody = $this->flattenAndModify($body[0]);
            if(isset($databody['app_name']))
                $app_name = $databody['app_name'];
            if (isset($app_name)&&isset($databody) && count($databody) > 0) {
                $this->messageProducer->sendQueue(json_encode(array('index'=>  $app_name.'_index','body' => $databody,'id' => $fileId, 'operation' => 'Index', 'type' => '_doc')), 'elastic');
                return $databody;
            }
        }
        return null;
    }

    public function deleteDocument($fileId)
    {
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_app')
        ->columns(array('name'))->join('ox_form','ox_form.app_id = ox_app.id',array(),'inner')->join('ox_file','ox_file.form_id = ox_form.id',array(),'inner')
        ->where(array('ox_file.id' => $fileId));
        $response = $this->executeQuery($select)->toArray();
        if (count($response) == 0) {
            return 0;
        }
        $app_name = $response[0]['name'];
        if (isset($app_name)) {
            $this->messageProducer->sendQueue(json_encode(array('index'=>  $app_name.'_index','id' => $fileId, 'operation' => 'Delete', 'type' => '_doc')), 'elastic');
            return array('fileId' => $fileId);
        }
        return null;
    }

    public function batchIndexer($appUuid,$startdate = null,$enddate = null)
    {
        $batchSize = $this->config['batch_size'];
        if(!isset($appUuid))
            throw new Exception("Incorrect App Id Specified", 1);
        $appID = $this->getIdFromUuid('ox_app',$appUuid);
        $select = "SELECT id from ox_file ";
        if(isset($startdate) && !isset($enddate))
            $where ="WHERE date_created > '$startdate'";
        elseif(isset($startdate) && isset($enddate))
            $where ="WHERE date_created > '$startdate' && date_created < '$enddate'";
        elseif(!isset($startdate) && isset($enddate))
            $where ="WHERE date_created < '$enddate'";
        else
            return 0;
        $query = $select.$where;
        try {
            $resultSet = $this->executeQuerywithParams($query)->toArray();
            $idlist = $batches = $fileIdsArray =array();
            $total = count($resultSet);
            if ($total > 0) {
                $idlist = array_column($resultSet, 'id');
                $batches = array_chunk($idlist, $batchSize);
                foreach ($batches as $batch) {
                    $fileIdsArray = $batch;
                    $fileIds = implode(',', $batch);
                    //Index list
                    $select = "SELECT file.id as id,app.name as app_name, entity.id as entity_id, entity.name as entity_name,
                    file.data as file_data, file.uuid as file_uuid, file.is_active, file.parent_id,file.latest,file.org_id,
                    CONCAT('{', GROUP_CONCAT(CONCAT('\"', field.name, '\" : \"',COALESCE(field.text, field.name),'\"') SEPARATOR ','), '}') as fields,
                    wf_user.user_id, file.workflow_instance_id,
                    w.id as workflow_instance_id, w.status,
                    w.name as workflow_name, w.activities
                    from ox_file as file
                    INNER JOIN ox_app_entity as entity ON file.entity_id = entity.id
                    INNER JOIN ox_app as app on entity.app_id = app.id
                    left join (select wf_user.user_id, idfa.file_id, wf_user.app_id, wf_user.org_id from ox_file_attribute idfa
                    inner join ox_field id_fd on id_fd.id = idfa.field_id
                    inner JOIN ox_wf_user_identifier as wf_user on idfa.field_value = wf_user.identifier 
                    and wf_user.identifier_name = id_fd.name) as wf_user on wf_user.file_id = file.id and wf_user.app_id = app.id 
                    and wf_user.org_id = file.org_id 
                    INNER JOIN ox_field as field ON field.entity_id = entity.id 
                    LEFT JOIN (SELECT wf_inst.id, wf_inst.status,
                    act_inst.activity_instance_id,
                    wf.name, CONCAT('{', GROUP_CONCAT(CONCAT('\"', activity.name, '\" : \"', act_inst.status, '\"') SEPARATOR ','), '}') as activities
                    FROM ox_workflow_instance as wf_inst
                    INNER JOIN ox_workflow_deployment as wd on wf_inst.workflow_deployment_id = wd.id 
                    INNER JOIN ox_workflow as wf on wd.workflow_id = wf.id
                    LEFT JOIN ox_activity_instance as act_inst on wf_inst.id = act_inst.workflow_instance_id
                    LEFT JOIN ox_activity as activity on wd.id = activity.workflow_deployment_id
                    GROUP BY wf_inst.id, wf_inst.status, act_inst.activity_instance_id, wf.name) w
                    ON w.id = file.workflow_instance_id
                    where file.id in (".$fileIds.") AND app.id =".$appID." and file.latest =1 GROUP BY wf_user.user_id,file.id,app_name,entity.id, entity.name,file_data,file_uuid,file.latest,file.workflow_instance_id,file.is_active, file.parent_id, file.org_id,w.id, w.status,w.name, w.activities";
                    $this->runGenericQuery("SET SESSION group_concat_max_len = 1000000;");
                    $this->logger->info("Executing Query - $select");
                    $bodys=$this->executeQuerywithParams($select)->toArray();
                    foreach ($bodys as $key => $value) {
                        $bodys[$key] = $this->flattenAndModify($value);
                    }
                    $indexIdList = array_column($bodys,'id');

                    //Delete list
                    $select = 'SELECT file.id from ox_file as file
                    INNER JOIN ox_app_entity as entity ON file.entity_id = entity.id
                    INNER JOIN ox_app as app on entity.app_id = app.id
                    where file.id in ('.$fileIds.') AND app.id ='.$appID.' and file.latest =0';
                    $list = $this->executeQuerywithParams($select)->toArray();
                    $deleteIdList = array_column($list, 'id');

                    if(isset($bodys[0]['app_name'])){
                        $app_name = $bodys[0]['app_name'];
                    }
                    if (isset($app_name)&&isset($bodys)) {
                        $this->messageProducer->sendQueue(json_encode(array('index'=>  $app_name.'_index', 'operation' => 'Batch', 'type' => '_doc', 'idlist' => $indexIdList, 'deleteList' => $deleteIdList,'body' => $bodys)), 'elastic');
                    }
                }
                return $bodys;
            }
        }
        catch (ZendDbException $e) {
            $this->logger->error('Database exception occurred.');
            $this->logger->error($e);
            $this->logger->error('Query and params:');
            $this->logger->error($query);
            $this->logger->error($queryParams);
        }
        catch (Exception $e) {
            throw $e;
        }
    }

    public function flattenAndModify($data)
    {
        $databody = array();
        if(!empty($data)){
            if(isset($data['file_data'])){
                $file_data = json_decode($data['file_data'],true);
                $databody = array_merge($data,$file_data);
            }
            unset($databody['file_data']);
        }
        foreach ($databody as $key => $value) {
            if(is_string($value))
            {
                $result = json_decode($value);
                if (json_last_error() === JSON_ERROR_NONE) {
                    if(is_array($result))
                    {
                        $databody[$key] = $result;
                    }
                }
            }
        }
        return $databody;
    }

}
