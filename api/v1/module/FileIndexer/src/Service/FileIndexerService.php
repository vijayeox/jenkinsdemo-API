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

    public function __construct($config,AdapterInterface $dbAdapter, Logger $log)
    {
        parent::__construct($config,$dbAdapter, $log);
        $this->messageProducer = MessageProducer::getInstance();
    }
    public function setRestClient($restClient)
    {
        $this->restClient = $restClient;
    }

    public function getRelevantDetails($fileId)
    {
        if(isset($fileId))
        {
            $select = "SELECT file.workflow_instance_id, file.activity_id as activity_instance_id,
            file.form_id, form.name as form_name,
            file.org_id, form.app_id, app.name as app_name,
            wf.name as workflow_name, wf_inst.status as worflow_instance_status,
            wf_inst.date_created as workflow_instance_date_created,
            act.name as activity_name, act_inst.status as activity_instance_status,
            act_inst.start_date as activity_instance_start_date,
            act_inst.act_by_date as activity_instance_act_by_date,
            CONCAT('{', GROUP_CONCAT(CONCAT('\"', field.name, '\" : \"',COALESCE(field.text, field.name),'\"') SEPARATOR ','), '}') as fields,
            file.data
            from ox_file as file
            LEFT JOIN ox_workflow_instance as wf_inst on wf_inst.id = file.workflow_instance_id
            LEFT JOIN ox_workflow as wf on wf_inst.workflow_id = wf.id
            LEFT JOIN ox_activity_instance as act_inst on act_inst.id = file.activity_id
            LEFT JOIN ox_activity as act on act.id = act_inst.activity_id
            INNER JOIN ox_form as form on file.form_id = form.id
            INNER JOIN ox_app as app on app.id = form.app_id
            INNER JOIN ox_file_attribute as file_attr on file_attr.fileid = file.id
            INNER  JOIN ox_field as field on file_attr.fieldid = field.id
            where file.id =".$fileId."
            GROUP BY file.workflow_instance_id, file.activity_id, file.form_id, form.name,
            file.org_id, form.app_id, app.name, wf.name, wf_inst.status, wf_inst.date_created,
            act.name, act_inst.status, act_inst.start_date, act_inst.act_by_date, file.data";
            $body=$this->executeQuerywithParams($select)->toArray();
            if(!empty($body))
                $app_name =array_unique(array_column($body, 'app_name'))[0];
            if (isset($app_name)&&isset($body)) {
                $this->messageProducer->sendTopic(json_encode(array('index'=>  $app_name.'_index','body' => $body,'id' => $fileId, 'operation' => 'Index', 'type' => 'file')), 'elastic');
                return $body;
            }
        }
        return null;
    }

    public function deleteDocument($fileId)
    {
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_app')
            ->columns(array('name'))->join('ox_form','ox_form.app_id = ox_app.id',array(),'left')->join('ox_file','ox_file.form_id = ox_form.id',array(),'left')
            ->where(array('ox_file.id' => $fileId));
        $response = $this->executeQuery($select)->toArray();
        if (count($response) == 0) {
            return 0;
        }
        $app_name = $response[0];
        if (isset($app_name)&&isset($body)) {
            $this->messageProducer->sendTopic(json_encode(array('index'=>  $app_name.'_index','id' => $fileId, 'operation' => 'Delete', 'type' => 'file')), 'elastic');
            return $fileId;
        }
        return null;
    }
}
