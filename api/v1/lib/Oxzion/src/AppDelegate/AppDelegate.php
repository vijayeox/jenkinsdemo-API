<?php
namespace Oxzion\AppDelegate;

use Oxzion\Db\Persistence\Persistence;
use Zend\Log\Logger;

interface AppDelegate
{
    public function setLogger(Logger $logger);
    public function execute(array $data, Persistence $persistenceService);
}
