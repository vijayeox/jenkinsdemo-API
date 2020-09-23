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
use Ramsey\Uuid\Uuid;
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
        $form = new Target();
        $data['uuid'] = Uuid::uuid4()->toString();
        $data['created_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_created'] = date('Y-m-d H:i:s');
        $data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
        // $form->exchangeWithSpecificKey($data,'value');
        $form->validate();
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $form->save($form);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
            $id = $this->table->getLastInsertValue();
            $data['id'] = $id;
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
        return $count;
    }

    public function updateTarget($uuid, $data)
    {
        $obj = $this->table->getByUuid($uuid, array());
        if (is_null($obj)) {
            return 0;
        }
        if(!isset($data['version']))
        {
            throw new Exception("Version is not specified, please specify the version");
        }
        $form = new Target();
        // $form->exchangeWithSpecificKey($obj->toArray(), 'value');
        // $form->exchangeWithSpecificKey($data,'value',true);
        $form->updateValidate();
        $count = 0;
        try {
            $count = $form->save($form);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
        return $count;
    }

    public function deleteTarget($uuid,$version)
    {
        $obj = $this->table->getByUuid($uuid, array());
        if (is_null($obj)) {
            return 0;
        }
        if(!isset($version))
        {
            throw new Exception("Version is not specified, please specify the version");
        }
        $data = array('version' => $version,'isdeleted' => 1);
        $form = new Target();
        // $form->exchangeWithSpecificKey($obj->toArray(), 'value');
        // $form->exchangeWithSpecificKey($data,'value',true);
        $form->updateValidate($data);
        $count = 0;
        try {
            $count = $form->save($form);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
        return $count;
    }

    public function getTarget($uuid)
    {
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_target')
            ->columns(array('uuid','type','period_type','red_limit','yellow_limit','green_limit','trigger_after','red_workflow_id','yellow_workflow_id','yellow_workflow_id','is_owner' => (new Expression('IF(created_by = '.AuthContext::get(AuthConstants::USER_ID).', "true", "false")')),'org_id','version','isdeleted'))
            ->where(array('ox_target.uuid' => $uuid,'org_id' => AuthContext::get(AuthConstants::ORG_ID),'isdeleted' => 0));
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
            $where .= empty($where) ? "WHERE org_id =".AuthContext::get(AuthConstants::ORG_ID) : " AND org_id =".AuthContext::get(AuthConstants::ORG_ID);
        }
        else{
            $where .= empty($where) ? "WHERE isdeleted <>1 AND org_id =".AuthContext::get(AuthConstants::ORG_ID) : " AND isdeleted <>1 AND org_id =".AuthContext::get(AuthConstants::ORG_ID);
        }
        $sort = $paginateOptions['sort'] ? " ORDER BY ".$paginateOptions['sort'] : '';
        $limit = " LIMIT ".$paginateOptions['pageSize']." offset ".$paginateOptions['offset'];

        $cntQuery ="SELECT count(id) as 'count' FROM `ox_target` ";
        $resultSet = $this->executeQuerywithParams($cntQuery.$where);
        $count=$resultSet->toArray()[0]['count'];

        if(isset($params['show_deleted']) && $params['show_deleted']==true){
            $query ="SELECT uuid,type,period_type,red_limit,yellow_limit,green_limit,trigger_after,red_workflow_id,yellow_workflow_id,yellow_workflow_id,IF(created_by = ".AuthContext::get(AuthConstants::USER_ID).", 'true', 'false') as is_owner,version,org_id,isdeleted FROM `ox_target`".$where." ".$sort." ".$limit;
        }
        else{
            $query ="SELECT uuid,type,period_type,red_limit,yellow_limit,green_limit,trigger_after,red_workflow_id,yellow_workflow_id,yellow_workflow_id,IF(created_by = ".AuthContext::get(AuthConstants::USER_ID).", 'true', 'false') as is_owner,version,org_id FROM `ox_target`".$where." ".$sort." ".$limit;
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
