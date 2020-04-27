<?php
use Oxzion\AppDelegate\MailDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Encryption\Crypto;
use Oxzion\AppDelegate\FileDelegate;

class UpdatePolicyStatusOnExpiration extends FileDelegate{

    public $template;
 
    public function __construct(){
        parent::__construct();
    }

    public function execute(array $data,Persistence $persistenceService)
    {
        $this->logger->info("UpdatePolicyStatusOnExpiration Delegate ---".print_r($data,true));
        $fileData = array();
        $filterParams = array();
        if($data['flag'] == 'notEqualTo'){
            $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'product','operator'=>'neq','value'=> 'Dive Boat');
        }else if($data['flag'] == 'equalTo'){
            $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'product','operator'=>'eq','value'=> 'Dive Boat');
        }
        $params = array();
        $params = $data['orgId'];
        $today = date('Y-m-d');
        $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'end_date','operator'=>'lte','value'=>$today);
        $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'policyStatus','operator'=>'eq','value'=> 'In Force');
        $policyList = $this->getFileList($params,$filterParams);
        $this->logger->info("Policy List".print_r($policyList));
        if(count($policyList['data']) > 0){
            foreach($policyList['data'] as $file){
                $file['policyStatus'] = 'Expired';    
                $this->saveFile($file,$file['uuid']);
            }
        }
        return $data;
    }
}
?>