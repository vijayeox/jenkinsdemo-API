<?php
use Oxzion\AppDelegate\MailDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Encryption\Crypto;
require_once __DIR__."/DispatchNotification.php";

class DispatchReinstatePolicyMail extends DispatchNotification
{

    public $template;
 
    public function __construct(){
        $this->template = array(
            'Individual Professional Liability' => 'CancelPolicyMailTemplate',
            'Dive Boat' => 'CancelPolicyMailTemplate',
            'Dive Store' => 'CancelPolicyMailTemplate');
        parent::__construct();
        
    }

    protected function execute(array $data)
    {
        $this->logger->info("Dispatch reinstate policy mail notification" .json_encode($data));
        $data['template'] = $this->template[$data['product']];
        $data['subject'] = 'Policy Auto Renewal';
        $response = $this->dispatch($data);
        $data['userCancellationReason'] = '';
        $data['othersCsr'] = '';
        $data['reinstateAmount'] = '';
        $data['csrCancellationReason'] = '';
        $data['cancellationStatus'] = '';
        $data['reasonforRejection'] = '';
        $data['othersUser'] = '';
        return $response;
    }
}