<?php
use Oxzion\AppDelegate\MailDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Encryption\Crypto;
require_once __DIR__."/DispatchDocument.php";


class DispatchNewPolicy extends DispatchDocument {

    public $template = array();
 
    public function __construct(){
        $this->template = array(
            'Individual Professional Liability' => 'COIPolicyMailTemplate',
            'Dive Boat' => 'diveBoatPolicyMailTemplate',
            'Dive Store' => 'diveStorePolicyMailTemplate');
    }

    public function execute(array $data,Persistence $persistenceService)
    {
        $data['template'] = $this->template[$data['product']];
        $data['subject'] = 'Certificate Of Insurance';
        $response = $this->dispatch($data);
        return $response;
    }
}
?>