<?php
use Oxzion\Db\Persistence\Persistence;
use Oxzion\AppDelegate\FileDelegate;

class PolicyCheck extends FileDelegate
{

    public function __construct()
    {
        parent::__construct();
    }

    public function execute(array $data, Persistence $persistenceService)
    {
        $this->logger->info("Policy Check".print_r($data,true));
        if(!isset($data['padi']) || (isset($data['padi']) && trim($data['padi']) == '')){
            return $data;
        }
        $params = array();
        $filterParams = array();
        $params['status'] = 'Completed';
        $today = date('Y-m-d');
        $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'start_date','operator'=>'lte','value'=>$today);
        $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'end_date','operator'=>'gte','value'=>$today);
        $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'padi','operator'=>'eq','value'=>$data['padi']);
        $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'product','operator'=>'eq','value'=>$data['product']);
        $policyList = $this->getFileList($params,$filterParams);
        if(count($policyList['data']) > 0){
            $data['policy_exists'] = true;
        } else {
            $data['policy_exists'] = false;
        }
        return $data;
    }
}
