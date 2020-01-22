<?php
use Oxzion\AppDelegate\MailDelegate;

use Oxzion\Db\Persistence\Persistence;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Encryption\Crypto;
require_once __DIR__."/DispatchNotification.php";

class DispatchReinstatePolicyMail extends DispatchNotification
{ 
    public function __construct(){
        $this->template = array(
            'Individual Professional Liability' => 'ReinstatePolicyMailTemplate',
            'Dive Boat' => 'ReinstatePolicyMailTemplate',
            'Emergency First Response' => 'ReinstatePolicyMailTemplate',
            'Dive Store' => 'ReinstatePolicyMailTemplate');
        parent::__construct();
        
    }

    public function execute(array $data, Persistence $persistenceService)
    {
        $this->logger->info("Dispatch reinstate policy mail notification");
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
?>