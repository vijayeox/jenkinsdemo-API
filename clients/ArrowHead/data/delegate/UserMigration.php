<?php

use Oxzion\AppDelegate\AbstractDocumentAppDelegate;
use Oxzion\Db\Persistence\Persistence;

class UserMigration extends AbstractDocumentAppDelegate
{
    protected $template;

    public function __construct()
    {
        parent::__construct();
    }

    public function execute(array $data, Persistence $persistenceService)
    {
        $this->logger->info("User Migration Delegate ---".print_r($data,true));
        try {
            $role = implode(",", $data['role']);
            $insertQuery = "INSERT INTO user (`uuid`,`username`,`firstname`,`lastname`,`email`,`role`,`producer_code`) VALUES ('".$data['uuid']."','".$data['username']."','".$data['firstname']."','".$data['lastname']."','".$data['email']."','".$role."', NULL);";
            $persistenceService->insertQuery($insertQuery);
            return true;
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
            $persistenceService->rollback();
            throw $e;
        }
    }
}
