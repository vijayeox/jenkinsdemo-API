<?php
use Oxzion\Db\Persistence\Persistence;

require_once __DIR__."/FileBatchProcessing.php";

class RenewalJob extends FileBatchProcessing{

    public $template;
 
    public function __construct(){
        parent::__construct();
    }

    public function execute(array $data,Persistence $persistenceService)
    {
        $this->logger->info("AutoRenewalJob Delegate ---".print_r($data,true));
        $filterParams = array();
        $year = date('Y');
        $endDate = $year.'-06-30';
        
        $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'end_date','operator'=>'eq','value'=> $endDate);
        $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'policyStatus','operator'=>'eq','value'=> 'In Force');
        // $filterParams['filter'][0]['filter']['filters'][] = array('field'=>'automatic_renewal','operator'=>'eq','value'=> false);
        
        $data['filterParams'] = $filterParams;

        $data['entityName'] = 'Individual Professional Liability';
        $this->processRenewal($data,$persistenceService);

        $data['entityName'] = 'Emergency First Response';
        $this->processRenewal($data,$persistenceService);

        return $data;
   }
}
?>