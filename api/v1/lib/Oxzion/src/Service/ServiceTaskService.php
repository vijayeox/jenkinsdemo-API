<?php
/**
 * ServiceTask Callback Api
 */
namespace Oxzion\Service;

use Exception;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\Service\CommandService;
use Oxzion\Model\ServiceTaskInstanceTable;
use Oxzion\Model\ServiceTaskInstance;

class ServiceTaskService extends AbstractService
{
    private $commandService;
	public function __construct($config, $dbAdapter,ServiceTaskInstanceTable $table, CommandService $commandService)
    {
        parent::__construct($config, $dbAdapter);
        $this->commandService = $commandService;
        $this->table = $table;
    }

	public function executeServiceTask($data,$request){
		$this->commandService->updateOrganizationContext($data['variables']);
        $variables = isset($data['variables']) ? $data['variables'] : null;
        $response = $this->commandService->runCommand($variables, $request);
        $serviceTaskInstance = $this->createServiceTaskInstance($data,$response);
        return $response;
	}
	private function createServiceTaskInstance(&$data,$completionData)
    {
    	$taskInfo = array();
        $serviceTaskInstance = new ServiceTaskInstance();
        if(isset($data['processInstanceId'])){
        	$select = "SELECT ox_file.id as file_id,ox_workflow_instance.id as workflow_instance_id from ox_file
            inner join ox_workflow_instance on ox_workflow_instance.file_id = ox_file.id
            where ox_workflow_instance.process_instance_id=:workflowInstanceId";
            $whereQuery = array("workflowInstanceId" => $data['processInstanceId']);
            $result = $this->executeQueryWithBindParameters($select, $whereQuery)->toArray();
            if(count($result) > 0){
            	$taskInfo = array_merge($taskInfo,$result[0]);
            }
        }
        $taskInfo['name'] = $data['activityName'];
        $taskInfo['task_id'] = $data['activityInstanceId'];
        $taskInfo['start_data'] = json_encode($data);
        $taskInfo['completion_data'] = json_encode($completionData);
        $taskInfo['date_executed'] = date('Y-m-d H:i:s');
        $this->logger->info("ServiceTaskInstance BEFCHANGE" . print_r($data, true));
        $serviceTaskInstance->exchangeArray($taskInfo);
        $serviceTaskInstance->validate();
        $this->logger->info("ServiceTaskInstance AFTERFCHANGE" . print_r($serviceTaskInstance, true));
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->save($serviceTaskInstance);
            $this->logger->info("ServiceTaskInstance CREATED");
            if ($count == 0) {
                $this->logger->info("ServiceTaskInstance ROLLBACK");
                return 0;
            }
            if (!isset($data['id'])) {
                $id = $this->table->getLastInsertValue();
                $data['id'] = $id;
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
        return $count;
    }

}
?>