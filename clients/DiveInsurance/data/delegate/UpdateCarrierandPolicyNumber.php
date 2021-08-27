<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\DelegateException;
use Oxzion\AppDelegate\UserContextTrait;

class UpdateCarrierandPolicyNumber extends AbstractAppDelegate
{
    use UserContextTrait;
    public function __construct()
    {
        parent::__construct();
    }

    // State Tax values are fetched here
    public function execute(array $data, Persistence $persistenceService)
    {
        $this->logger->info("Update Carrier/Policy Number ------" . print_r($data, true));
        if (AuthContext::isPrivileged('MANAGE_ADMIN_WRITE')) {
            try {
                $this->updateCarrierPolicyNumber($data, $persistenceService);
            } catch (Exception $e) {
                $this->logger->info("Carrier/Policy Number Update Failed -----" . print_r($e, true));
                throw new DelegateException("Update Failed.Please Try again", 'update_failed');
            }
            return $data;
        } else {
            $this->logger->info("Update Carrier/Policy Number : You do not have access to this API");
            throw new DelegateException("You do not have access to this API", 'no_access');
        }
    }


    private function updateCarrierPolicyNumber(&$data, $persistenceService)
    {
        $updateQuery = "UPDATE carrier_policy SET carrier = '" . $data['carrier'] . "',policy_number = '" . $data['policy_number'] . "' WHERE id = " . $data['id'];
        $result = $persistenceService->updateQuery($updateQuery);
    }
}
