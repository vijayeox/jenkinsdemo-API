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
        if($data['product'] == 'Dive Store'){
            $subject = 'Dive Store Insurance Application on Hold - '.$data['padi'];
            $data['productType'] = 'Dive Store';
        }else if($data['product'] == 'Dive Boat'){
            $subject = 'Dive Boat Insurance Application on Hold - '.$data['padi'];
            $data['productType'] = 'Dive Boat';
        }else{
            $subject = 'PADI Professional Liability Insurance Application on Hold – '.$data['padi'];
            $data['productType'] = 'Endorsed Professional Liability';
        }
        $data['subject'] = $subject;
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