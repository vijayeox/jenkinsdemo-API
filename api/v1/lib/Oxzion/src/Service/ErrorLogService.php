<?php
namespace Oxzion\Service;

use Oxzion\Model\ErrorLogTable;
use Oxzion\Model\ErrorLog;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\Service\AbstractService;
use Oxzion\ValidationException;
use Zend\Db\Sql\Expression;
use Oxzion\ServiceException;
use Exception;

class ErrorLogService extends AbstractService
{
    public function __construct($config, $dbAdapter, ErrorLogTable $table)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
    }
    public function saveError($type,$errorTrace=null,$payload = null,$params = null)
    {
        $this->logger->info("Entering to saving Error method in ErrorLogService");
        $errorLog = new ErrorLog();
        $errorLog->exchangeArray(array('error_type'=>$type,'error_trace'=>$errorTrace,'payload'=>$payload,'params'=>$params,'date_created'=>date('Y-m-d H:i:s')));
        // $errorLog->validate();
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->save($errorLog);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
            $this->commit();
            return $id;
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            $this->rollback();
            throw $e;
        }
        return $count;
    }
}
