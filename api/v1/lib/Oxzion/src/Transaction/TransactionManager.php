<?php
namespace Oxzion\Transaction;

class TransactionManager
{
    const CONTEXT_KEY = 'TRANSACTION_MANAGER';
    private $dbAdapter;
    private $rollbackOnly;
    private $transactionCount;

    public static function getInstance($dbAdapter)
    {
        if (!isset($_REQUEST[self::CONTEXT_KEY])) {
            $_REQUEST[self::CONTEXT_KEY] = new TransactionManager($dbAdapter);
        }

        return $_REQUEST[self::CONTEXT_KEY];
    }
    private function __construct($dbAdapter)
    {
        $this->dbAdapter = $dbAdapter;
        $this->rollbackOnly = false;
        $this->transactionCount = 0;
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
        if ($this->transactionCount > 0) {
            $this->transactionCount--;
        }
        if (!$this->rollbackOnly && $this->transactionCount == 0) {
            $this->dbAdapter->getDriver()->getConnection()->commit();
        }
    }

    public function rollback()
    {
        if ($this->dbAdapter->getDriver()->getConnection()->inTransaction()) {
            $this->dbAdapter->getDriver()->getConnection()->rollback();
            $this->transactionCount = 0;
        }
    }
}
