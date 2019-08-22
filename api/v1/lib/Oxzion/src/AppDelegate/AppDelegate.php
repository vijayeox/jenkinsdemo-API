<?php
namespace Oxzion\AppDelegate;

use Oxzion\Db\Persistence\Persistence;

interface AppDelegate
{
    public function execute(array $data, Persistence $persistenceService=null);
}
