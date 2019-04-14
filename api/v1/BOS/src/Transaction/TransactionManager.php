<?php
namespace Bos\Transaction;

class TransactionManager{
    const CONTEXT_KEY = 'TRANSACTION_MANAGER';
    private $dbAdapter;
    private $rollbackOnly;

    public static function getInstance($dbAdapter){
        if(!isset($_REQUEST[self::CONTEXT_KEY])){
            $_REQUEST[self::CONTEXT_KEY] = new TransactionManager($dbAdapter);
        }

        return $_REQUEST[self::CONTEXT_KEY];
    }
    private function __construct($dbAdapter){
        $this->dbAdapter = $dbAdapter;
        $this->rollbackOnly = false;
    }

    public function setRollbackOnly($rollbackOnly){
        $this->rollbackOnly = $rollbackOnly;
    }

    public function getRollbackOnly(){
        return $this->rollbackOnly;
    }

    public function beginTransaction()
    {
        $this->dbAdapter->getDriver()->getConnection()->beginTransaction();
    }

    public function commit()
    {
        if(!$this->rollbackOnly){
            $this->dbAdapter->getDriver()->getConnection()->commit();
        }
    }

    public function rollback()
    {
        if($this->dbAdapter->getDriver()->getConnection()->inTransaction()){
            $this->dbAdapter->getDriver()->getConnection()->rollback();
        }
    }
    
}
?>