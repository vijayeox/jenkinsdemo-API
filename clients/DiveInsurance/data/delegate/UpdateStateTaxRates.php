<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\DelegateException;
use Oxzion\AppDelegate\UserContextTrait;

class UpdateStateTaxRates extends AbstractAppDelegate
{
    use UserContextTrait;
    public function __construct()
    {
        parent::__construct();
    }

    public function execute(array $data, Persistence $persistenceService)
    {
        $this->logger->info("Update State Rates------" . print_r($data, true));
        if (AuthContext::isPrivileged('MANAGE_ADMIN_WRITE')) {
            try {
                $this->updateStateTaxRates($data, $persistenceService);
            } catch (Exception $e) {
                $this->logger->info("State Rates Update Failed -----" . print_r($e, true));
                throw new DelegateException("Update Failed.Please Try again", 'update_failed');
            }
            return $data;
        } else {
            $this->logger->info("Update State Rates : You do not have access to this API");
            throw new DelegateException("You do not have access to this API", 'no_access');
        }
    }

    private function updateStateTaxRates(&$data, $persistenceService)
    {
        $updateQuery = "UPDATE state_tax SET percentage = " . $data['percentage'] . " WHERE `id` = " . $data['id'];
        $persistenceService->updateQuery($updateQuery);
    }
}
