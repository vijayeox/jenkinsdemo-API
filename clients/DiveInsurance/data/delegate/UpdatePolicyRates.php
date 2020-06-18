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
        $data['padi_fee'] = isset($data['padi_fee']) ? $data['padi_fee'] : 0;
        $data['premium'] = isset($data['premium']) ? $data['premium'] : 0;
        $data['tax'] = isset($data['tax']) ? $data['tax'] : 0;
        $total = (float) $data['premium'] + (float) $data['tax'] + (float) $data['padi_fee'];
        $updateQuery = "UPDATE premium_rate_card SET `premium` = " . $data['premium'] . ",`tax` = " . $data['tax'] . ", padi_fee = " . $data['padi_fee'] . ",total = " . $total . " WHERE id = " . $data['id'];
        $result = $persistenceService->updateQuery($updateQuery);
    }
}
