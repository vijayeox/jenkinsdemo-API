<?php
use Oxzion\Db\Persistence\Persistence;
use Oxzion\AppDelegate\FileDelegate;

class UpdatePolicyStatusOnExpiration extends FileDelegate{

    
    public function __construct(){
        parent::__construct();
    }

    public function execute(array $data,Persistence $persistenceService)
    {
        $this->logger->info("UpdatePolicyStatusOnExpiration Delegate ---".print_r($data,true));
        $fileData = array();
        $filterParams = array();
        $params = array();
        $params['orgId'] = $data['orgId'];
        $today = date('Y-m-d');
        $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'end_date','operator'=>'lte','value'=>$today);
        $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'policyStatus','operator'=>'eq','value'=> 'In Force');
        try{
            if($data['flag'] == 'notEqualTo'){
                $params['entityName'] = 'Individual Professional Liability';
                $this->updateFieldValueOnFiles($params,'policyStatus','In Force','Expired',$filterParams);

                $params['entityName'] = 'Emergency First Response';
                $this->updateFieldValueOnFiles($params,'policyStatus','In Force','Expired',$filterParams);

                $params['entityName'] = 'Dive Store';
                $this->updateFieldValueOnFiles($params,'policyStatus','In Force','Expired',$filterParams);
            }else if($data['flag'] == 'equalTo'){
                $params['entityName'] = 'Dive Boat';
                $this->updateFieldValueOnFiles($params,'policyStatus','In Force','Expired',$filterParams);
            }

            
            
        }
        catch(Exception $e){
            $this->logger->error($e->getMessage(),$e);
            throw $e;
        }
        return $data;
    }
}
?>