<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;

class GetExcessApplicationSubmissionNumber extends AbstractAppDelegate
{
    public function __construct()
    {
        parent::__construct();
    }
    
    public function execute(array $data, Persistence $persistenceService)
    {
        $this->logger->info("Executing GetExcessApplicationSubmissionNumber with data- " . json_encode($data, JSON_UNESCAPED_SLASHES));

        $selectQuery = "SELECT value FROM applicationConfig WHERE type ='ExcessSubmissionNumber'";
        $submissionNumber = ($persistenceService->selectQuery($selectQuery))->current()["value"];
        $data['SubmissionNumber'] = $submissionNumber;
        $submissionNumber = $submissionNumber + 1;

        $params = array('value' => $submissionNumber);
        $updateQuery = "UPDATE applicationConfig SET value =:value WHERE type = 'ExcessSubmissionNumber'";
        $this->logger->info("Executing GetExcessApplicationSubmissionNumber update - " . print_r($updateQuery,true));
        $updateSubmissionNumberInsert = $persistenceService->updateQuery($updateQuery, $params);
        $id = $updateSubmissionNumberInsert->getGeneratedValue();

        return $data;
    }
}