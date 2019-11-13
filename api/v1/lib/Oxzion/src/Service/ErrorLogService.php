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
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->save($errorLog);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
            $data = $errorLog->toArray();
            $data['id'] = $this->table->getLastInsertValue();
            $this->commit();
            return $data;
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            $this->rollback();
            throw $e;
        }
        return $count;
    }
    public function getErrorList($filterParams=array()){
        $where = "";
        $pageSize = 20;
        $offset = 0;
        $sort = "date_created";
        $select = "SELECT id,error_type,error_trace,payload,date_created,params";
        $from = " FROM `ox_error_log` ";
        $cntQuery ="SELECT count(id) as error_count ".$from;
        if(count($filterParams) > 0 || sizeof($filterParams) > 0){
            if(isset($filterParams['filter'])){
               $filterArray = json_decode($filterParams['filter'],true);
               if(isset($filterArray[0]['filter'])){
                    $filterlogic = isset($filterArray[0]['filter']['logic']) ? $filterArray[0]['filter']['logic'] : "AND" ;
                    $filterList = $filterArray[0]['filter']['filters'];
                    $where = " WHERE ".FilterUtils::filterArray($filterList,$filterlogic,self::$userField);
                }
                if(isset($filterArray[0]['sort']) && count($filterArray[0]['sort']) > 0){
                    $sort = $filterArray[0]['sort'];
                    $sort = FilterUtils::sortArray($sort,self::$userField);
                }
                $pageSize = $filterArray[0]['take'];
                $offset = $filterArray[0]['skip'];
            }
           }
            $sort = " ORDER BY ".$sort;
            $limit = " LIMIT ".$pageSize." offset ".$offset;
            $resultSet = $this->executeQuerywithParams($cntQuery.$where);
            $count = $resultSet->toArray()[0]['error_count'];
            $query = $select." ".$from." ".$where." ".$sort." ".$limit;
            $resultSet = $this->executeQuerywithParams($query);
            $result = $resultSet->toArray();
            for($x=0;$x<sizeof($result);$x++) {

            }
        return array('data' => $result,'total' => $count);
    }
}
