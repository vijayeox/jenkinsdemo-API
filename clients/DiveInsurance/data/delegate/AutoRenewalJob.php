<?php
use Oxzion\Db\Persistence\Persistence;

class AutoRenewalJob extends RenewalBatchProcessing{

    public $template;
 
    public function __construct(){
        parent::__construct();
    }

    public function execute(array $data,Persistence $persistenceService)
    {
        $this->logger->info("AutoRenewalJob Delegate ---".print_r($data,true));
        $filterParams = array();
        $params = array();
        $year = date('Y');
        $params['orgId'] = $data['orgId'];
        $endDate = $year.'-06-30';
        $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'end_date','operator'=>'lte','value'=>$endDate);
        $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'policyStatus','operator'=>'eq','value'=> 'In Force');
        $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'automatic_renewal','operator'=>'eq','value'=> true);
        
        $params['filterParams'] = $filterParams;

        $params['entityName'] = 'Individual Professional Liability';
        $this->processRenewal($params,$persistenceService);

        $params['entityName'] = 'Emergency First Response';
        $this->processRenewal($params,$persistenceService);

        return $data;
   }
}
?>