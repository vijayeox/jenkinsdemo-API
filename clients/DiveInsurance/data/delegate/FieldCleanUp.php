<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;

class FieldCleanUp extends AbstractAppDelegate
{
    public function __construct()
    {
        parent::__construct();
        $this->unsetVariables = array (
            'Individual Professional Liability' => array (
                
            ));
    }

//  Data Cleanup is done here
    public function execute(array $data, Persistence $persistenceService)
    {
        $this->logger->info("CLEAN DATA");
        $unsetVar = $this->unsetVariables[$data['product']];
        $this->logger->info("UNSET VARIABLES" . print_r($unsetVar, true));
        for ($i = 0; $i < sizeof($unsetVar); $i++) {
            $this->logger->info("CLEN DATA FOR");
            unset($data[$unsetVar[$i]]);
        }
        $this->logger->info("CLEAN DATA END" . print_r($data, true));
    }
}
