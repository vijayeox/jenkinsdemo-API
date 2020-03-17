<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\AppDelegate\FileTrait;

class GroupAddMembers extends AbstractAppDelegate{
    use FileTrait;
    public function __construct(){
        parent::__construct();
    }

    // Premium Calculation values are fetched here
    public function execute(array $data,Persistence $persistenceService)
    {
        $params = array();
        $filterParams = array();
        $params['status'] = 'In Force';
        $today = date('Y-m-d');
        $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'start_date','operator'=>'lte','value'=>$today);
        $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'end_date','operator'=>'gte','value'=>$today);
        $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'padi','operator'=>'eq','value'=>$data['padi']);
        $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'product','operator'=>'eq','value'=>'Individual Professional Liability');
        $policyList = $this->getFileList($params,$filterParams);
        if(count($policyList['data']) > 0){
            $data = array_merge($data,$policyList['data'][0]);
            $data['policy_exists'] = true;
            $data['verified'] = true;
        } else {
            $data['policy_exists'] = false;
            $data['verified'] = false;
        }
        return $data;

    }
}
