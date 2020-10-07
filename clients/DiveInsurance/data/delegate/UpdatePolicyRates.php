<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\DelegateException;
use Oxzion\AppDelegate\UserContextTrait;

class UpdatePolicyRates extends AbstractAppDelegate
{
    use UserContextTrait;
    public function __construct()
    {
        parent::__construct();
    }

    // Premium Calculation values are fetched here
    public function execute(array $data, Persistence $persistenceService)
    {
        $this->logger->info("Update Policy Rates------" . print_r($data, true));
        if (AuthContext::isPrivileged('MANAGE_ADMIN_WRITE')) {
            try {
                $this->updatePolicyRates($data, $persistenceService);
            } catch (Exception $e) {
                $this->logger->info("Premium Rates Update Failed -----" . print_r($e, true));
                throw new DelegateException("Update Failed.Please Try again", 'update_failed');
            }
            return $data;
        } else {
            $this->logger->info("Update Premium Rates : You do not have access to this API");
            throw new DelegateException("You do not have access to this API", 'no_access');
        }
    }
    private function updatePolicyRates($data, $persistenceService)
    {
        $data['padi_fee'] = !empty($data['padi_fee']) ? $data['padi_fee'] : 0;
        $data['premium'] = !empty($data['premium']) ? $data['premium'] : 0;
        $data['tax'] = !empty($data['tax']) ? $data['tax'] : 0;
        $data['downpayment'] = !empty($data['downpayment']) ? $data['downpayment'] : 'NULL';
        $data['installment_count'] = !empty($data['installment_count']) ? $data['installment_count'] : 'NULL';
        $data['installment_amount'] = !empty($data['installment_amount']) ? $data['installment_amount'] : 'NULL';
        $total = (float) $data['premium'] + (float) $data['tax'] + (float) $data['padi_fee'];
        $updateQuery = "UPDATE premium_rate_card SET `premium` = " . $data['premium'] . ",`tax` = " . $data['tax'] . ", padi_fee = " . $data['padi_fee'] . ",total = " . $total . ", downpayment = ".$data['downpayment'].", installment_count = ".$data['installment_count'].", installment_amount = ".$data['installment_amount']." WHERE id = " . $data['id'];
        $this->logger->info(" UpdatePolicyRates Query : $updateQuery");
        $result = $persistenceService->updateQuery($updateQuery);
    }
}
