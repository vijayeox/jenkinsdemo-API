<?php
use Oxzion\AppDelegate\MailDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Encryption\Crypto;
require_once __DIR__."/DispatchDocument.php";


class DispatchCancelPolicyNotification extends DispatchDocument {

    public $template;

    public function __construct(){
        $this->template = array(
            'Individual Professional Liability' => 'CancelPolicyMailTemplate',
            'Emergency First Response' => 'CancelPolicyMailTemplate',
            'Dive Boat' => 'CancelPolicyMailTemplate',
            'Group Professional Liability' => 'CancelPolicyMailTemplate',
            'NotApproved' => 'CancelPolicyNotApprovedMailTemplate',
            'Dive Store' => 'CancelPolicyMailTemplate');
        parent::__construct();
    }

    public function execute(array $data,Persistence $persistenceService)
    {
        $this->logger->info("Dispatch Cancel Policy Notification");
        if($data['cancellationStatus'] =="approved")
        {
            $this->logger->info("Dispatch Cancel Policy Notification -- approved");
            $data['template'] = $this->template[$data['product']];
            if(isset($data['documents']) && is_string($data['documents'])){
                $data['documents'] = json_decode($data['documents'],true);
            }
            $this->logger->info("ARRAY DOCUMENT --- ".json_encode($data['documents']));
            $this->logger->info("REQUIRED DOCUMENT --- cancel_doc");
            $fileData = array();
            if(isset($data['documents']['cancel_doc'])){
                $file = $this->destination.$data['documents']['cancel_doc'];
                if(file_exists($file)){
                     array_push($fileData, $file);
                }else{
                    $this->logger->error("Cancellation Document Not Found - ".$file);
                    throw new DelegateException('Cancellation Document Not Found','file.not.found',0,array($file));
                }
            }else{
                $this->logger->error("Cancellation Document Not Found".$error);
                throw new DelegateException('Cancellation Document Not Found','file.not.found');
            }
            $mailData = array();
            $mailData = $data;
            $mailData['email'] = $data['email'];
            if(isset($data['padi'])){
                $mailData['subject'] = 'PADI Endorsed Insurance Cancellation – '.$data['padi'];
            } else {
                if(isset($data['business_padi'])){
                    $mailData['subject'] = 'PADI Endorsed Insurance Cancellation – '.$data['business_padi'];
                }
            }
            $mailData['template'] = $data['template'];
            $mailData['document'] = $fileData;
            $response = $this->dispatch($mailData);
            $data['autoRenewalJob'] = '';
            unset($data['confirmReinstatePolicy']);
            return $data;
        }
        else{
            $this->logger->info("Dispatch Cancel Policy Notification -- not approved");
            $data['template'] = $this->template['NotApproved'];
            $data['subject'] = 'Request for cancellation of policy';
            $response = $this->dispatch($data);
            $data['policyStatus'] = "In Force";
            unset($data['confirmReinstatePolicy']);
            return $data;
        }
    }
}
?>
