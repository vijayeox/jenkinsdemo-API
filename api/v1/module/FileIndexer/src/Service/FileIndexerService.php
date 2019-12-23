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
            $select = "SELECT app.name as app_name, entity.id as entity_id, entity.name,
            file.data as file_data, file.uuid as file_uuid, file.is_active, file.parent_id,file.latest,file.org_id,
            CONCAT('{', GROUP_CONCAT(CONCAT('\"', field.name, '\" : \"',COALESCE(field.text, field.name),'\"') SEPARATOR ','), '}') as fields,
            w.user_id, file.workflow_instance_id,
            w.id as workflow_instance_id, w.status,
            w.activity_instance_id,
            w.name as workflow_name, w.activities
            from ox_file as file
            INNER JOIN ox_app_entity as entity ON file.entity_id = entity.id
            INNER JOIN ox_field as field ON field.entity_id = entity.id
            INNER JOIN ox_app as app on entity.app_id = app.id
            LEFT JOIN (SELECT wf_user.user_id,
            wf_inst.id, wf_inst.status,
            act_inst.activity_instance_id,
            wf.name, CONCAT('{', GROUP_CONCAT(CONCAT('\"', activity.name, '\" : \"', act_inst.status, '\"') SEPARATOR ','), '}') as activities
            FROM ox_workflow_instance as wf_inst
            INNER JOIN ox_workflow_deployment as wd on wf_inst.workflow_deployment_id = wd.id 
            INNER JOIN ox_workflow as wf on wd.workflow_id = wf.id
            LEFT JOIN ox_activity_instance as act_inst on wf_inst.id = act_inst.workflow_instance_id
            LEFT JOIN ox_activity as activity on wd.id = activity.workflow_deployment_id
            LEFT JOIN ox_wf_user_identifier as wf_user on wf_user.workflow_instance_id = wf_inst.id
            GROUP BY wf_user.user_id, wf_inst.id, wf_inst.status, act_inst.activity_instance_id, wf.name) w
            ON w.id = file.workflow_instance_id
            where file.id = ".$fileId."
            GROUP BY app_name,entity.id, entity.name,file_data,file_uuid,file.is_active, file.parent_id, file.org_id,w.user_id,w.id, w.status,w.activity_instance_id,w.name, w.activities";
            $this->runGenericQuery("SET SESSION group_concat_max_len = 1000000;");
            $body=$this->executeQuerywithParams($select)->toArray();
            if(!empty($body)){
                if(isset($body[0]['file_data'])){
                    $file_data = json_decode((array_column($body, 'file_data')[0]),true);
                    $databody = array_merge($body[0],$file_data);
                }
                unset($databody['file_data']);
                $app_name = $databody['app_name'];
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
            if (isset($app_name)&&isset($databody)) {
                $this->messageProducer->sendTopic(json_encode(array('index'=>  $app_name.'_index','body' => $databody,'id' => $fileId, 'operation' => 'Index', 'type' => '_doc')), 'elastic');
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
            $this->messageProducer->sendTopic(json_encode(array('index'=>  $app_name.'_index','id' => $fileId, 'operation' => 'Delete', 'type' => 'file')), 'elastic');
            return array('fileId' => $fileId);
        }
        return null;
    }
}
