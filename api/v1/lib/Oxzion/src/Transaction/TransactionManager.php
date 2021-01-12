<?php
namespace Oxzion\Transaction;

class TransactionManager
{
    const CONTEXT_KEY = 'TRANSACTION_MANAGER';
    private $dbAdapter;
    private $rollbackOnly;
    private $forceRollback;
    private $transactionCount;

    public static function getInstance($dbAdapter)
    {
        $params = "";
        if(isset($dbAdapter->getDriver()->getConnection()->getConnectionParameters()['database'])){
            $params = $dbAdapter->getDriver()->getConnection()->getConnectionParameters()['database'];
        }

        $key = self::CONTEXT_KEY."-$params";
        if (!isset($_REQUEST[$key])) {
            $_REQUEST[$key] = new TransactionManager($dbAdapter);
        }

        return $_REQUEST[$key];
    }
    private function __construct($dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
        $this->rollbackOnly = false;
        $this->forceRollback = false;
        $this->transactionCount = 0;
    }

    public function setForceRollback($forceRollback) {
        $this->forceRollback = $forceRollback;
    }

    public function getForceRollback() {
        return $this->forceRollback;
    }

    public function setRollbackOnly($rollbackOnly)
    {
        $this->rollbackOnly = $rollbackOnly;
    }

    public function getRollbackOnly()
    {
        return $this->rollbackOnly;
    }

    public function beginTransaction()
    {
        if ($this->transactionCount == 0) {
            $this->dbAdapter->getDriver()->getConnection()->beginTransaction();
        }

        $this->transactionCount++;
    }

    public function commit()
    {
        if (!$this->rollbackOnly && $this->transactionCount == 1) {
            $this->dbAdapter->getDriver()->getConnection()->commit();
        }
        if ($this->transactionCount > 0) {
            $this->transactionCount--;
        }
    }

    public function rollback($forceRollback = false)
    {
        if($this->rollbackOnly && !$forceRollback && $this->forceRollback){
            $this->transactionCount--;
            return;
        }
        if ($this->dbAdapter->getDriver()->getConnection()->inTransaction()) {
            $this->dbAdapter->getDriver()->getConnection()->rollback();
            $this->transactionCount = 0;
        }
    }
}
