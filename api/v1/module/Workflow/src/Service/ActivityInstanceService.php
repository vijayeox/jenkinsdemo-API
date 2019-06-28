<?php
/**
* File Api
*/
namespace Workflow\Service;

use Oxzion\Service\AbstractService;
use Workflow\Model\ActivityInstanceTable;
use Oxzion\Model\ActivityInstance;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\ValidationException;
use Zend\Db\Sql\Expression;
use Zend\Log\Logger;
use Exception;

class ActivityInstanceService extends AbstractService {
    /**
    * @var ActivityInstanceService Instance of Task Service
    */
    private $activityinstanceService;
    /**
    * @ignore __construct
    */

    public function __construct($config, $dbAdapter, ActivityInstanceTable $table,Logger $log) {
        parent::__construct($config, $dbAdapter,$log);
        $this->table = $table;
    }

    public function createActivityInstanceEntry($data){	
        // Org Id from workflow instance based on the Id
        $query = "SELECT * FROM `ox_workflow_instance` WHERE id = '".$data['processInstanceId']."';";
        $resultSet = $this->executeQuerywithParams($query)->toArray();	
        $orgId = $resultSet[0]['org_id'];

         // Org Id from workflow instance based on the Id
         $data['group_id'] = NULL;
        if(isset($data['group_name'])){
            $query1 = "SELECT * FROM `ox_group` WHERE `name` = '".$data['group_name']."';";
            $resultSet = $this->executeQuerywithParams($query1)->toArray();
            $data['group_id'] = $resultSet[0]['id'];
        }
        $query2 = "SELECT * FROM `ox_form` WHERE `name` = '".$data['name']."';";
        $resultSet = $this->executeQuerywithParams($query2)->toArray();
        $data['form_id'] = $resultSet[0]['id'];
         
        // $data['start_date'] =  now();

		$this->beginTransaction();		
		try {	
			$insert = "INSERT INTO `ox_activity_instance` (`workflow_instance_id`,`activity_instance_id`,`assignee`,`group_id`,`form_id`,`status`,`start_date`,`org_id`) VALUES ('" .$data['processInstanceId']."','" .$data['activityInstanceId']."','" .$data['assignee']."','" .$data['group_id']."','" .$data['form_id']."','" .$data['status']."',now(),'" .$orgId."')";		
            $resultSet = $this->runGenericQuery($insert);
            $this->commit();  		
		} catch (Exception $e) {
            $this->logger->info(ActivityInstanceService::class."Creation of Activity Instance Entry Failed".$e->getMessage());
            print_r($e->getMessage());		
			$this->rollback();		
			return 0;		
		}     		
	}
}
?>