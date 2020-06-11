<?php

use Oxzion\AppDelegate\TemplateAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\DelegateException;
use Oxzion\AppDelegate\UserContextTrait;

class UpdateSurplusLines extends TemplateAppDelegate
{
    use UserContextTrait;
    public function __construct()
    {
        parent::__construct();
    }

    public function execute(array $data, Persistence $persistenceService)
    {
        $this->logger->info("Update Surplus Lines------" . print_r($data, true));
        if (AuthContext::isPrivileged('MANAGE_ADMIN_WRITE')) {
            if ($data['product'] == 'Individual Professional Liability') {
                $product = 'IPL';
            } else if ($data['product'] == 'Emergency First Response') {
                $product = 'EFR';
            } else if ($data['product'] == 'Dive Boat') {
                $product = 'DiveBoat';
            } else if ($data['product'] == 'Dive Store') {
                $product = 'DiveStore';
            } else if ($data['product'] == 'Group Professional Liability') {
                $product = 'Group';
            }

            try {
                $destinationPath = $this->destination . AuthContext::get(AuthConstants::ORG_UUID) . '/SurplusLines/' . $product;
                $this->logger->info("Surplus Lines Update Destination Path-----" . print_r($destinationPath, true));
                $this->updateSurplusLines($data, $product, $destinationPath, $persistenceService);
            } catch (Exception $e) {
                $this->logger->info("Surplus Lines Update Failed -----" . print_r($e, true));
                throw new DelegateException("Update Failed.Please Try again", 'update_failed');
            }
            return $data;
        } else {
            $this->logger->info("Update Surplus Lines : You do not have access to this API");
            throw new DelegateException("You do not have access to this API", 'no_access');
        }
    }

    private function updateSurplusLines(&$data, $product, $destinationPath, $persistenceService)
    {
        $this->logger->info("Surplus Lines Update Path-----" . print_r($destinationPath, true));
        $filename = $this->getStateInShort($data['state'], $persistenceService);
        $filePath = $destinationPath . '/' . $data['year'] . '/' . $filename . '.tpl';
        chmod($filePath, 0777);
        $this->logger->info("Surplus Lines Update File Path-----" . print_r($filePath, true));
        $fp = fopen($filePath, 'w');
        fwrite($fp, $data['surplusLine']);
        fclose($fp);
    }

    private function getStateInShort($state, $persistenceService)
    {
        $selectQuery = "SELECT state_in_short FROM state_license WHERE state = '" . $state . "'";
        $result = $persistenceService->selectQuery($selectQuery);
        while ($result->next()) {
            $state = $result->current();
        }
        return $state['state_in_short'];
    }
}
