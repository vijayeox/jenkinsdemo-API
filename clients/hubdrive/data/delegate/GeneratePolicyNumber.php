<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\AppDelegate\FileTrait;

class GeneratePolicyNumber extends AbstractAppDelegate
{
    use FileTrait;

    public function __construct()
    {
        parent::__construct();
    }
    
    public function execute(array $data, Persistence $persistenceService)
    {
        $this->logger->info("Executing Generate Policy Number with data- " . json_encode($data, JSON_UNESCAPED_SLASHES));
        
        $fileUUID = isset($data['uuid']) ? $data['uuid'] : $data['fileId'];
        $currentAccount = isset($data['accountId']) ? $data['accountId'] : null;
        $accountId = isset($data['accountName']) ? $this->getAccountByName($data['accountName']) : (isset($currentAccount) ? $currentAccount : AuthContext::get(AuthConstants::ACCOUNT_UUID));
        $fileData = $this->getFile($data['fileId'],false,$data['accountId']);
        $file = $fileData['data'];
        $file['policyNumber'] = isset($file['policyNumber']) ? $file['policyNumber'] : "";
        if($file['policyNumber'] == ""){
            $selectQuery = "SELECT value FROM applicationConfig WHERE type ='PolicyNumber'";
            $policyNumber = ($persistenceService->selectQuery($selectQuery))->current()["value"];
            $data['policyNumber'] = $file['policyNumber'] = $policyNumber;
            $policyNumber = $policyNumber + 1;

            $params = array('value' => $policyNumber);
            $updateQuery = "UPDATE applicationConfig SET value =:value WHERE type = 'PolicyNumber'";
            $this->logger->info("Executing PolicyNumber update - " . print_r($updateQuery,true));
            $updatepolicyNumber = $persistenceService->updateQuery($updateQuery, $params);
            $id = $updatepolicyNumber->getGeneratedValue();
        }
        $file['policyStatus'] = "Quote Approved";
        $this->saveFile($file, $fileUUID);
        return $data;
    }
}