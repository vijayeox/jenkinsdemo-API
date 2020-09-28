<?php
namespace Analytics\Service;

use Oxzion\Service\AbstractService;
use Analytics\Model\TargetTable;
use Analytics\Model\Target;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\ValidationException;
use Zend\Db\Sql\Expression;
use Oxzion\Utils\FilterUtils;
use Analytics\Service\QueryService;
use Exception;

class TargetService extends AbstractService
{

    private $table;
    private $queryService;
    public function __construct($config, $dbAdapter, TargetTable $table, QueryService $queryService)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
        $this->queryService = $queryService;
    }

    public function createTarget($data)
    {
        $target = new Target($this->table);
        $target->setForeignKey('account_id', AuthContext::get(AuthConstants::ACCOUNT_ID));
        $target->assign($data);
        try {
            $this->beginTransaction();
            $target->save();
            $this->commit();
        }
        catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
        return $target->getGenerated();
    }

    public function updateTarget($uuid, $data)
    {
        $target = new Target($this->table);
        $target->loadByUuid($uuid);
        $target->assign($data);
        try {
            $this->beginTransaction();
            $target->save();
            $this->commit();
        }
        catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
        return $target->getProperty('version');
    }

    public function deleteTarget($uuid,$version)
    {
        $target = new Target($this->table);
        $target->loadByUuid($uuid);
        $target->assign([
            'version' => $version,
            'isdeleted' => 1
        ]);
        try {
            $this->beginTransaction();
            $target->save();
            $this->commit();
        }
        catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
    }

    public function getTarget($uuid)
    {
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_target')
            ->columns(array('uuid','type','period_type','red_limit','yellow_limit','green_limit','trigger_after','red_workflow_id','yellow_workflow_id','yellow_workflow_id','is_owner' => (new Expression('IF(created_by = '.AuthContext::get(AuthConstants::USER_ID).', "true", "false")')),'account_id','version','isdeleted'))
            ->where(array('ox_target.uuid' => $uuid,'account_id' => AuthContext::get(AuthConstants::ACCOUNT_ID),'isdeleted' => 0));
        $response = $this->executeQuery($select)->toArray();
        if (count($response) == 0) {
            return 0;
        }
        return $response[0];
    }

    public function getTargetList($params = null)
    {
        $paginateOptions = FilterUtils::paginateLikeKendo($params);
        $where = $paginateOptions['where'];
        if(isset($params['show_deleted']) && $params['show_deleted']==true){
            $where .= empty($where) ? "WHERE account_id =".AuthContext::get(AuthConstants::ACCOUNT_ID) : " AND account_id =".AuthContext::get(AuthConstants::ACCOUNT_ID);
        }
        else{
            $where .= empty($where) ? "WHERE isdeleted <>1 AND account_id =".AuthContext::get(AuthConstants::ACCOUNT_ID) : " AND isdeleted <>1 AND account_id =".AuthContext::get(AuthConstants::ACCOUNT_ID);
        }
        $sort = $paginateOptions['sort'] ? " ORDER BY ".$paginateOptions['sort'] : '';
        $limit = " LIMIT ".$paginateOptions['pageSize']." offset ".$paginateOptions['offset'];

        $cntQuery ="SELECT count(id) as 'count' FROM `ox_target` ";
        $resultSet = $this->executeQuerywithParams($cntQuery.$where);
        $count=$resultSet->toArray()[0]['count'];

        if(isset($params['show_deleted']) && $params['show_deleted']==true){
            $query ="SELECT uuid,type,period_type,red_limit,yellow_limit,green_limit,trigger_after,red_workflow_id,yellow_workflow_id,yellow_workflow_id,IF(created_by = ".AuthContext::get(AuthConstants::USER_ID).", 'true', 'false') as is_owner,version,account_id,isdeleted FROM `ox_target`".$where." ".$sort." ".$limit;
        }
        else{
            $query ="SELECT uuid,type,period_type,red_limit,yellow_limit,green_limit,trigger_after,red_workflow_id,yellow_workflow_id,yellow_workflow_id,IF(created_by = ".AuthContext::get(AuthConstants::USER_ID).", 'true', 'false') as is_owner,version,account_id FROM `ox_target`".$where." ".$sort." ".$limit;
        }
        $resultSet = $this->executeQuerywithParams($query);
        $result = $resultSet->toArray();
        foreach ($result as $key => $value) {
            unset($result[$key]['id']);
        }
        return array('data' => $result,
                 'total' => $count);
    }

    public function getKRAResult($params) {
        if (isset($params['kra_uuid'])) {
            $krauuid = $params['kra_uuid'];
        } else {
            $validationException = new ValidationException();
            $validationException->setErrors(array('message' => 'kra_uuid is required'));
            throw $validationException;
        }
        $query = 'select q.uuid as queryuuid,t.*,k.type from ox_kra k join ox_query q on k.query_id=q.id join ox_target t on k.target_id = t.id where k.uuid=:uuid';
        $queryParams = [
            'uuid' => $krauuid,
        ];
        $resultSet = $this->executeQueryWithBindParameters($query, $queryParams)->toArray();
        if (count($resultSet) == 0) {
            return 0;
        }
        $data= $resultSet[0];
        $redLimit = $data['red_limit'];
        $yellowLimit = $data['yellow_limit'];
        $greenLimit = $data['green_limit'];
        $type = $data['type'];
        $result = $this->queryService->getQuery($data['queryuuid'],["data"=>1]);
         $value = $result['query']['data'];
         $ryg = $this->checkRYG($value,$type,$redLimit,$yellowLimit,$greenLimit);
         $result['target']['red_limit'] = $redLimit;
         $result['target']['yellow_limit'] = $yellowLimit;
         $result['target']['green_limit'] = $greenLimit;
         $result['target']['period_type'] = $data['period_type'];
         $result['target']['color'] = $ryg;
        return $result;
    }

    static public function checkRYG($value,$type,$red,$yellow,$green) {
        $result = "";
        if ($type==0) {
            if ($value<=$red) {
                $result = "red";
            } else if ($value<=$yellow) {
                $result = "yellow";
            } else {
                $result = "green";
            }
        } else {
            if ($value<=$green) {
                $result = "green";
            } else if ($value<=$yellow) {
                $result = "yellow";
            } else {
                $result = "red";
            }

        }
        return $result;
    }
}