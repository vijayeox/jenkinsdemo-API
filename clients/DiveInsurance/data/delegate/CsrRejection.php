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
            'Dive Store' => 'CsrRejectionTemplate');
        parent::__construct();
    }

    public function execute(array $data,Persistence $persistenceService)
    {
        $this->logger->info("Rejection Policy Notification");
        $data['template'] = $this->template[$data['product']];
        $data['subject'] = 'Rejection of Policy';
        $response = $this->dispatch($data);
        return $response;
    }
}
?>