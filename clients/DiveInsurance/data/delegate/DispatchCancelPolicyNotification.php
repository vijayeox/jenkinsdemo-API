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
            'Dive Boat' => 'CancelPolicyMailTemplate',
            'Dive Store' => 'CancelPolicyMailTemplate');
        $this->document = array('docs' => ['Cancellation_Approval']);
        parent::__construct();
    }

    public function execute(array $data,Persistence $persistenceService)
    {
        $this->logger->info("Dispatch Cancel Policy Notification");
        $data['template'] = $this->template[$data['product']];
        if(isset($data['documents']) && is_string($data['documents'])){
            $data['documents'] = json_decode($data['documents'],true);
        }
        $this->logger->info("ARRAY DOCUMENT --- ".print_r($document,true));
        $this->logger->info("REQUIRED DOCUMENT --- ".print_r($this->document,true));
        $file = $this->destination.$data['documents']['Cancellation_Approval'];
        $data['subject'] = 'Cancel Policy';
        $data['document'] =$fileData;
        $response = $this->dispatch($data);
        return $response;
    }
}
?>