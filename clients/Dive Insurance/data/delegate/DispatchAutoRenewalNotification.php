<?php
use Oxzion\AppDelegate\MailDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Encryption\Crypto;
require_once __DIR__."/DispatchNotification.php";


class DispatchAutoRenewalNotification extends DispatchNotification {

    public $template = array();
 
    public function __construct(){
        $this->template = array(
            'Individual Professional Liability' => 'COIAutoRenewalNotiMailTemplate');
    }

    public function execute(array $data,Persistence $persistenceService)
    {
        $data['template'] = $this->template[$data['product']];
        $data['subject'] = 'Policy Auto Renewal';
        $response = $this->dispatch($data);
        return $response;
    }
}
?>