<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\AppDelegate\WorkflowTrait;


class ChangeLogDelegate extends AbstractAppDelegate
{
	use WorkflowTrait;

    public function __construct(){
        parent::__construct();
    }

    public function execute(array $data,Persistence $persistenceService)
    {
        $rateArray = array();
        if(isset($data['activityInstanceId']) && $data['activityInstanceId'] != "{{activityInstanceId}}"){
            $fileData = $this->getFileDataByActivityInstanceId($data['activityInstanceId']);
            $product = json_decode($fileData['data'],true);
            $data['product'] = $product['product'];
            $rates = $this->getRates($data,$persistenceService);
            foreach ($rates as $key => $value) {
                $rateArray[$value['key']] =  $value['coverage'];
            }
            return $changeLogData = $this->getActivityChangeLog($data['activityInstanceId'],$rateArray); 
        } else {
            if(isset($data['workflowInstanceId'])){
                $fileData = $this->getWorkflowSubmissionData($data['workflowInstanceId']);
                $product = json_decode($fileData['data'],true);
                $data['product'] = $product['product'];
                $rates = $this->getRates($data,$persistenceService);
                foreach ($rates as $key => $value) {
                    $rateArray[$value['key']] =  $value['coverage'];
                }
                return $changeLogData = $this->getWorkflowChangeLog($data['workflowInstanceId'],$rateArray);
            }
        }
        return $data;
    }

     protected function getRates($data,$persistenceService){
        $select = "Select DISTINCT `key`,coverage FROM premium_rate_card WHERE product ='".$data['product']."' AND is_upgrade = 0 ";
        $result = $persistenceService->selectQuery($select);
        if($result->count() > 0){
            $response = array();
            while ($result->next()) {
                $response[] = $result->current();
            }
            return $response;
        }
    }
}
