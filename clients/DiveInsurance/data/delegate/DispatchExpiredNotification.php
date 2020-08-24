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
            parent::__construct();
    }

    public function execute(array $data,Persistence $persistenceService)
    {
        $data['template'] = $this->template[$data['product']];
        if($data['product'] == 'Dive Store'){
            $data['subject'] = 'PADI Endorsed Insurance Expired – '.$data['business_padi'];
        }else{
            $data['subject'] = 'PADI Endorsed Insurance Expired – '.$data['padi'];
        }
        $response = $this->dispatch($data);
        return $response;
    }
}
?>

