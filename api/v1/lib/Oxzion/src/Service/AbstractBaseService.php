<?php
namespace Oxzion\Service;

use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Transaction\TransactionManager;
use Logger;

abstract class AbstractBaseService 
{
    protected $sql;
    protected $dbAdapter;
    protected $logger;
    protected $config;
    
    protected function __construct($config, $dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
        if ($dbAdapter) {
            $this->sql = new Sql($this->dbAdapter);
        }
        $this->logger = Logger::getLogger(get_class($this));
        $this->config = $config;
    }  

    public function beginTransaction()
    {
        $transactionManager = TransactionManager::getInstance($this->dbAdapter);
        $transactionManager->beginTransaction();
    }

    public function commit()
    {
        $transactionManager = TransactionManager::getInstance($this->dbAdapter);
        $transactionManager->commit();
    }

    public function rollback()
    {
        $transactionManager = TransactionManager::getInstance($this->dbAdapter);
        $transactionManager->rollback();
    }

    protected function getSqlObject()
    {
        return $this->sql;
    }

    protected function getAdapter()
    {
        return $this->dbAdapter;
    }

}

?>