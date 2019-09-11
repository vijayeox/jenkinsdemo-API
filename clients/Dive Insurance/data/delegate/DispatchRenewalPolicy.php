<?php
use Oxzion\AppDelegate\MailDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Encryption\Crypto;
require_once __DIR__."/DispatchDocument.php";


class DispatchRenewalPolicy extends DispatchDocument {

    public $template = array();
 
    public function __construct(){
        $this->template = array(
            'Dive Store' => 'diveStoreRenewalMailTemplate');
    }

    public function execute(array $data,Persistence $persistenceService)
    {
        $data['template'] = $this->template[$data['product']];
        $data['subject'] = 'Renewal Policy';
        $response = $this->dispatch($data);
        return $response;
    }
}
?>