<?php
namespace Analytics\Service;

use Oxzion\Service\AbstractService;
use Analytics\Model\QueryTable;
use Analytics\Model\Query;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\ValidationException;
use Zend\Db\Sql\Expression;
use Oxzion\Utils\FilterUtils;
use Ramsey\Uuid\Uuid;
use Oxzion\Analytics\Elastic\AnalyticsEngineImpl;

use Exception;
use Zend\Db\Exception\ExceptionInterface as ZendDbException;

class QueryService extends AbstractService
{

    private $table;
    private $datasourceService;

    public function __construct($config, $dbAdapter, QueryTable $table, $datasourceService)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
        $this->datasourceService = $datasourceService;
    }

    public function createQuery($data)
    {
        $form = new Query();
        $data['uuid'] = Uuid::uuid4()->toString();
        $data['created_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_created'] = date('Y-m-d H:i:s');
        $data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
        $form->exchangeWithSpecificKey($data,'value');
        $form->validate();
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->save2($form);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
            $id = $this->table->getLastInsertValue();
            $data['id'] = $id;
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            return 0;
        }
        return $count;
    }

    public function updateQuery($uuid, $data)
    {
        $obj = $this->table->getByUuid($uuid, array());
        if (is_null($obj)) {
            return 0;
        }
        $form = new Query();
        $data = array_merge($obj->toArray(), $data);
        $form->exchangeWithSpecificKey($data,'value',true);
        $form->validate();
        $count = 0;
        try {
            $count = $this->table->save2($form);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
        } catch (Exception $e) {
            $this->rollback();
            return 0;
        }
        return $count;
    }

    public function deleteQuery($uuid)
    {
        $obj = $this->table->getByUuid($uuid, array());
        if (is_null($obj)) {
            return 0;
        }
        $form = new Query();
        $data['isdeleted'] = 1;
        $data = array_merge($obj->toArray(), $data);
        $form->exchangeWithSpecificKey($data,'value',true);
        $form->validate();
        $count = 0;
        try {
            $count = $this->table->save2($form);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
        } catch (Exception $e) {
            $this->rollback();
            return 0;
        }
        return $count;
    }

    public function getQuery($uuid, $params)
    {
        $query = 'select q.uuid, q.name, q.configuration, q.ispublic, if(q.created_by=:created_by, true, false) as is_owner, q.isdeleted, d.uuid as datasource_uuid from ox_query q join ox_datasource d on d.id=q.datasource_id where q.isdeleted=false and q.org_id=:org_id and q.uuid=:uuid and (q.ispublic=true or q.created_by=:created_by)';
        $queryParams = [
            'created_by' => AuthContext::get(AuthConstants::USER_ID),
            'org_id' => AuthContext::get(AuthConstants::ORG_ID),
            'uuid' => $uuid
        ];
        try {
            $resultSet = $this->executeQueryWithBindParameters($query, $queryParams)->toArray();
            if (count($resultSet) == 0) {
                return 0;
            }
            $response = [
                'query' => $resultSet[0]
            ];
            //Query configuration value from database is a JSON string. Convert it to object and overwrite JSON string value.
            $response['query']['configuration'] = json_decode($resultSet[0]["configuration"]);
        }
        catch (ZendDbException $e) {
            $this->logger->err('Database exception occurred.');
            $this->logger->err($e);
            return 0;
        }

        if(isset($params['data'])) {
//--------------------------------------------------------------------------------------------------------------------------------
//TODO:Fetch data from elastic search and remove hard coded values below.
            if ($uuid == 'bf0a8a59-3a30-4021-aa79-726929469b07') {
                //Sales YTD
                $data = '235436';
            }
            if ($uuid == '3c0c8e99-9ec8-4eac-8df5-9d6ac09628e7') {
                //Sales by sales person
                $data = [
                    ['person'=> 'Bharat', 'sales'=> 4.2],
                    ['person'=> 'Harsha', 'sales'=> 5.2],
                    ['person'=> 'Mehul', 'sales'=> 15.2],
                    ['person'=> 'Rajesh', 'sales'=> 2.9],
                    ['person'=> 'Ravi', 'sales'=> 2.9],
                    ['person'=> 'Yuvraj', 'sales'=> 14.2]
                ];
            }
            if ($uuid == '45933c62-6933-43da-bbb2-59e6f331e8db') {
                //Quarterly revenue target
                $data = [
                    ['quarter'=> 'Q1 2018', 'revenue'=> 4.2],
                    ['quarter'=> 'Q2 2018', 'revenue'=> 5.4],
                    ['quarter'=> 'Q3 2018', 'revenue'=> 3.1],
                    ['quarter'=> 'Q4 2018', 'revenue'=> 3.8],
                    ['quarter'=> 'Q1 2019', 'revenue'=> 4.1],
                    ['quarter'=> 'Q2 2019', 'revenue'=> 4.7]
                ];
            }
            if ($uuid == '69f7732a-998a-41bb-ab89-aa7c434cb327') {
                //Revenue YTD
                $data = '786421';
            }
            if ($uuid == 'de5c309d-6bd6-494f-8c34-b85ac109a301') {
                //Product sales
                $data = [
                    ['product'=>'Audio player', 'sales'=>1.3],
                    ['product'=>'Video player', 'sales'=>3.2],
                    ['product'=>'Sports shoe', 'sales'=>2.8],
                    ['product'=>'Gym cap', 'sales'=>0.87],
                    ['product'=>'Baseball cap', 'sales'=>0.4]
                ];
            }
            $response['query']['data'] = $data;
//--------------------------------------------------------------------------------------------------------------------------------
        }
        return $response;
    }

    public function getQueryList($params = null)
    {
        $paginateOptions = FilterUtils::paginate($params);
        $where = $paginateOptions['where'];
        $where .= empty($where) ? "WHERE ox_query.isdeleted <> 1 AND (org_id =".AuthContext::get(AuthConstants::ORG_ID).") and (created_by = ".AuthContext::get(AuthConstants::USER_ID)." OR ispublic = 1)" : " AND ox_query.isdeleted <> 1 AND(org_id =".AuthContext::get(AuthConstants::ORG_ID).") and (created_by = ".AuthContext::get(AuthConstants::USER_ID)." OR ispublic = 1)";
        $sort = $paginateOptions['sort'] ? " ORDER BY ".$paginateOptions['sort'] : '';
        $limit = " LIMIT ".$paginateOptions['pageSize']." offset ".$paginateOptions['offset'];

        $cntQuery ="SELECT count(id) as 'count' FROM `ox_query` ";
        $resultSet = $this->executeQuerywithParams($cntQuery.$where);
        $count=$resultSet->toArray()[0]['count'];

        $query ="SELECT uuid,name,datasource_id,configuration,ispublic,IF(created_by = ".AuthContext::get(AuthConstants::USER_ID).", 'true', 'false') as is_owner,org_id,isdeleted FROM `ox_query`".$where." ".$sort." ".$limit;
        $resultSet = $this->executeQuerywithParams($query);
        $result = $resultSet->toArray();
        foreach ($result as $key => $value) {
            $result[$key]['configuration'] = json_decode($result[$key]['configuration']);
            unset($result[$key]['id']);
        }
        return array('data' => $result,
                 'total' => $count);
    }

    public function getQueryJson($uuid)
    {
        $statement = "Select configuration as query from ox_query where isdeleted <> 1 AND uuid = '".$uuid."'";
        $resultSet = $this->executeQuerywithParams($statement);
        $result = $resultSet->toArray();
        if($result)
            return $result[0];
        else
            return 0;
    }

    public function executeAnalyticsQuery($uuid) {
        $query = 'select q.uuid, q.name, q.configuration, q.ispublic, q.isdeleted, d.uuid as datasource_uuid from ox_query q join ox_datasource d on d.id=q.datasource_id where q.isdeleted=false and q.org_id=:org_id and q.uuid=:uuid';
        $queryParams = [
            'org_id' => AuthContext::get(AuthConstants::ORG_ID),
            'uuid' => $uuid
        ];
        try {
            $resultSet = $this->executeQueryWithBindParameters($query, $queryParams)->toArray();
            if (count($resultSet) == 0) {
                return 0;
            }
            $config = $resultSet[0]["configuration"];
            $ds_uuid = $resultSet[0]['datasource_uuid'];
            
            $analyticsEngine = $this->datasourceService->getAnalyticsEngine($ds_uuid);
            $parameters = json_decode($config,1);
            $app_name = $parameters['app_name'];
            if (isset($parameters['entity_name'])) {
                $entity_name = $parameters['entity_name'];
            } else {
                $entity_name = null;
            }
            $result = $analyticsEngine->runQuery($app_name,$entity_name,$parameters);

        } catch(Exception $e) {
            return 0;
        }

        return $result;
    }

}
