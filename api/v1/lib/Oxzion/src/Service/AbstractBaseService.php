<?php
namespace Oxzion\Service;

use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Transaction\TransactionManager;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;

abstract class AbstractBaseService 
{
    protected $sql;
    protected $dbAdapter;
    protected $logger;
    protected $config;
    
    protected function __construct($config, $dbAdapter, $log = null)
    {
        $this->dbAdapter = $dbAdapter;
        if ($dbAdapter) {
            $this->sql = new Sql($this->dbAdapter);
        }
        if($log){
            $this->logger = $log;
        }else{
            $this->initLogger(__DIR__."/../../../../logs/".get_class($this).".log");
        }
        $this->config = $config;
    }  

    protected function initLogger($logLocation)
    {
        $this->logger = new Logger();
        $writer = new Stream($logLocation);
        $this->logger->addWriter($writer);
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