<?php
use Oxzion\AppDelegate\MailDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Encryption\Crypto;
use Oxzion\AppDelegate\CommentTrait;

require_once __DIR__."/DispatchNotification.php";


class CsrRejection extends DispatchNotification {

    use CommentTrait;
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
            $subject = 'Dive Store Insurance Application on Hold - '.$data['business_padi'];
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
        if(isset($data['rejectionReason']) && $data['rejectionReason'] != ""){
            $comments = array();
            if(is_array($data['rejectionReason'])){
                $comments['text'] = "Rejection Reason : <br><br>".$this->getRejectionReason($data['rejectionReason']);
                $data['rejectionReason'] = json_encode($data['rejectionReason']);
            }else{
                $rejectionReason = json_decode($data['rejectionReason'],true);
                $comments['text'] = "Rejection Reason : <br><br>".$this->getRejectionReason($rejectionReason,$comments);
            }
            $this->createComment($comments,$data['fileId']);
        }
        $response = $this->dispatch($data);
        return $response;
    }

    protected function getRejectionReason($data){
        $comments = "<ol>";
        foreach($data as $value){
            $comments .= '<li>'.$value['reason'].'</li><br>';
        }
        $comments .= '<ol>';
        return $comments;
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
