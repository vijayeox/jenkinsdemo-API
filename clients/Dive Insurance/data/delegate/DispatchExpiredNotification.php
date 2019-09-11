<?php
use Oxzion\AppDelegate\MailDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Encryption\Crypto;
require_once __DIR__."/DispatchNotification.php";


class DispatchExpiredNotification extends DispatchNotification {

    public $template = array();
 
    public function __construct(){
        $this->template = array(
            'Individual Professional Liability' => 'COIExpiredNoticeMailTemplate');
    }

    public function execute(array $data,Persistence $persistenceService)
    {
        $data['template'] = $this->template[$data['product']];
        $data['subject'] = 'Policy Expired!!!!!';
        $response = $this->dispatch($data);
        return $response;
    }
}
?>