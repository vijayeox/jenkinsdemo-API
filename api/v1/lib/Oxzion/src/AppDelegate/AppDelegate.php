<?php
namespace Oxzion\AppDelegate;

use Oxzion\Db\Persistence\Persistence;

interface AppDelegate
{
    public function execute(array $data, Persistence $persistenceService);
    public function setUserContext($userId, $username, $orgId,$privilege);
    public function getUserId();
    public function getusername();
    public function getOrgId();
}
