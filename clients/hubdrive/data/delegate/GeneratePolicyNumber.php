<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;

class GeneratePolicyNumber extends AbstractAppDelegate
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function execute(array $data, Persistence $persistenceService)
    {
        $this->logger->info("Executing Generate Policy Number with data- " . json_encode($data, JSON_UNESCAPED_SLASHES));
        $data['policyNumber'] = isset($data['policyNumber']) ? $data['policyNumber'] : "";
        if($data['policyNumber'] == ""){
            $selectQuery = "SELECT value FROM applicationConfig WHERE type ='PolicyNumber'";
            $policyNumber = ($persistenceService->selectQuery($selectQuery))->current()["value"];
            $data['policyNumber'] = $policyNumber;
            $policyNumber = $policyNumber + 1;

            $params = array('value' => $policyNumber);
            $updateQuery = "UPDATE applicationConfig SET value =:value WHERE type = 'PolicyNumber'";
            $this->logger->info("Executing PolicyNumber update - " . print_r($updateQuery,true));
            $updatepolicyNumber = $persistenceService->updateQuery($updateQuery, $params);
            $id = $updatepolicyNumber->getGeneratedValue();
        }
        return $data;
    }
}