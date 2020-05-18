<?php
use Oxzion\AppDelegate\MailDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Encryption\Crypto;
require_once __DIR__."/DispatchNotification.php";


class CsrRejection extends DispatchNotification {

    public $template;
 
    public function __construct(){
        $this->template = array(
            'Individual Professional Liability' => 'CsrRejectionTemplate',
            'Dive Boat' => 'CsrRejectionTemplate',
            'Dive Store' => 'CsrRejectionTemplate',
            'Emergency First Response' => 'CsrRejectionTemplate');
        parent::__construct();
    }

    public function execute(array $data,Persistence $persistenceService)
    {
        $this->logger->info("Rejection Policy Notification");
        $data['template'] = $this->template[$data['product']];
        $data['subject'] = 'Rejection of Policy';
        if(isset($data['state'])){
            $data['state_in_short'] = $this->getStateInShort($data['state'],$persistenceService);
        }
        $response = $this->dispatch($data);
        return $response;
    }

    protected function getStateInShort($state,$persistenceService){
        $selectQuery = "Select state_in_short FROM state_license WHERE state ='".$state."'";
        $resultSet = $persistenceService->selectQuery($selectQuery);
        if($resultSet->count() == 0){
            return $state;
        }else{
            while ($resultSet->next()) {
                $stateDetails[] = $resultSet->current();
            }       
            if(isset($stateDetails) && count($stateDetails)>0){
                 $state = $stateDetails[0]['state_in_short'];
            } 
        }
        return $state;
    }
}
?>