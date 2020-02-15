<?php
use Oxzion\AppDelegate\MailDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Encryption\Crypto;
require_once __DIR__."/DispatchNotification.php";


class DispatchAutoRenewalNotification extends DispatchNotification {

    public $template;
 
    public function __construct(){
        $this->template = array(
            'Individual Professional Liability' => 'COIAutoRenewalNotiMailTemplate',
            'Emergency First Response' => 'EFR_AutoRenewal_Notification',
            'Dive Boat' => 'DiveBoat_AutoRenewal_Notification',
            'Dive Store' => 'DiveStore_AutoRenewal_Notification');
        parent::__construct();
    }

    public function execute(array $data,Persistence $persistenceService)
    {
        $this->logger->info("DispatchAutoRenewalNotification");
        $data['template'] = $this->template[$data['product']];
        $data['subject'] = 'Policy Auto Renewal';
        $response = $this->dispatch($data);
        return $response;
    }
}
?>