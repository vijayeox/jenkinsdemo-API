<?php
namespace Analytics\Service;

use Oxzion\Service\AbstractService;
use Analytics\Model\WidgetTable;
use Analytics\Model\Widget;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\ValidationException;
use Oxzion\Utils\FilterUtils;
use Oxzion\Analytics\AnalyticsEngine;
use Ramsey\Uuid\Uuid;
use Exception;
use Zend\Db\Exception\ExceptionInterface as ZendDbException;
use Zend\Mvc\Application;
use Webit\Util\EvalMath\EvalMath;

class WidgetService extends AbstractService
{
    private $table;
    private $queryService;

    public function __construct($config, $dbAdapter, WidgetTable $table, $queryService)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
        $this->queryService  = $queryService;
    }

    public function createWidget($data)
    {
        $newWidgetUuid = Uuid::uuid4()->toString();
        if(isset($data['uuid'])){
            $oldWidgetUuid = $data['uuid'];
        }
        else {
            if (!isset($data['visualization_uuid'])) {
                throw new ValidationException('One of "uuid" (existing widget UUID) OR "visualization_uuid" (reference to visualization) is required.');
            }
        }
        if(!isset($data['name'])&&!isset($data['configuration'])){
            $errors = new ValidationException();
            $errors->setErrors(array('name' => 'required','configuration'=>'required' ));
            throw $errors;
        }
        $queryParams = [
            'created_by'    => AuthContext::get(AuthConstants::USER_ID),
            'date_created'  => date('Y-m-d H:i:s'),
            'org_id'        => AuthContext::get(AuthConstants::ORG_ID),
            'version'       => 0,
            'ispublic'      => true,
            'isdeleted'     => false,
            'uuid'          => $newWidgetUuid,
            'name'          => $data['name'],
            'configuration' => json_encode($data['configuration']),
            'expression'    => json_encode($data['expression'])
        ];
        if (isset($data['visualization_uuid'])) {
            $visualizationIdInsert = '(SELECT id FROM ox_visualization ov WHERE ov.uuid=:visualization_uuid AND ov.org_id=:org_id)';
            $queryParams['visualization_uuid'] = $data['visualization_uuid'];
        }
        else {
            $visualizationIdInsert = '(SELECT visualization_id FROM ox_widget ovw WHERE ovw.uuid=:oldWidgetUuid AND ovw.org_id=:org_id)';
            $queryParams['oldWidgetUuid'] = $oldWidgetUuid;
        }

        $query = "INSERT INTO ox_widget (uuid, visualization_id, ispublic, created_by, date_created, org_id, isdeleted, name, configuration, expression, version) VALUES (:uuid, ${visualizationIdInsert}, :ispublic, :created_by, :date_created, :org_id, :isdeleted, :name, :configuration, :expression, :version)";
        try {
            $this->beginTransaction();

            $this->logger->debug('Executing query:', $query);
            $this->logger->debug('Query params:', $queryParams);
            $result = $this->executeQueryWithBindParameters($query, $queryParams);
            if (1 != $result->count()) {
                $this->logger->error('Unexpected result from ox_widget insert statement. Transaction rolled back.', $result);
                $this->logger->error('Query and parameters are:');
                $this->logger->error($query);
                $this->logger->error($queryParams);
                $this->rollback();
                return 0;
            }

            $sequence = 0;
            foreach($data['queries'] as $query) {
                $queryUuid = $query['uuid'];
                $queryConfiguration = json_encode($query['configuration']);
                $query = 'INSERT INTO ox_widget_query (ox_widget_id, ox_query_id, sequence, configuration) VALUES (
(SELECT id FROM ox_widget WHERE uuid=:widgetUuid), (SELECT id FROM ox_query WHERE uuid=:queryUuid), :sequence, :configuration)';
                $queryParams = [
                    'widgetUuid' => $newWidgetUuid,
                    'queryUuid' => $queryUuid,
                    'sequence' => $sequence,
                    'configuration' => $queryConfiguration
                ];
                $this->logger->error('Executing query:');
                $this->logger->error($query);
                $this->logger->error($queryParams);
                $result = $this->executeQueryWithBindParameters($query, $queryParams);
                if (1 != $result->count()) {
                    $this->logger->error('Unexpected result from ox_widget_query insert statement. Transaction rolled back.', $result);
                    $this->logger->error('Query and parameters are:');
                    $this->logger->error($query);
                    $this->logger->error($queryParams);
                    $this->rollback();
                    return 0;
                }
                $sequence++;
            }

            $this->commit();
            return $newWidgetUuid;
        }
        catch (ZendDbException $e) {
            $this->logger->error('Database exception occurred.');
            $this->logger->error($e);
            $this->logger->error("Query and params:");
            $this->logger->error($query);
            $this->logger->error($queryParams);
            try {
                $this->rollback();
            }
            catch (ZendDbException $ee) {
                $this->logger->error('Database exception occurred when rolling back transaction.');
                $this->logger->error($ee);
            }
            return 0;
        }
    }

    public function updateWidget($uuid, $data)
    {
        $obj = $this->table->getByUuid($uuid, array());
        if (is_null($obj)) {
            return 0;
        }
        $form = new Widget();
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

    public function deleteWidget($uuid)
    {
        $obj = $this->table->getByUuid($uuid, array());
        if (is_null($obj)) {
            return 0;
        }
        $form = new Widget();
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

    public function getWidgetByName($name) {
        $query = 'SELECT w.uuid, w.ispublic, w.created_by, w.date_created, w.name, w.configuration, IF(w.created_by=:created_by, true, false) AS is_owner, v.renderer, v.type FROM ox_widget w JOIN ox_visualization v ON w.visualization_id=v.id WHERE w.isdeleted=false AND w.org_id=:org_id AND w.name=:name AND (w.ispublic=true OR w.created_by=:created_by)';
        $queryParams = [
            'created_by' => AuthContext::get(AuthConstants::USER_ID),
            'org_id' => AuthContext::get(AuthConstants::ORG_ID),
            'name' => $name
        ];
        try {
            $resultSet = $this->executeQueryWithBindParameters($query, $queryParams)->toArray();
            if (count($resultSet) == 0) {
                return 0;
            }
            $response = [
                'widget' => $resultSet[0]
            ];
            //Widget configuration value from database is a JSON string. Convert it to object and overwrite JSON string value.
            $response['widget']['configuration'] = json_decode($resultSet[0]["configuration"]);
        }
        catch (ZendDbException $e) {
            $this->logger->error('Database exception occurred.');
            $this->logger->error($e);
            $this->logger->error('Query and params:');
            $this->logger->error($query);
            $this->logger->error($queryParams);
            return 0;
        }
        return $response;
    }

    public function getWidget($uuid,$params)
    {
        $query = 'SELECT w.uuid, w.ispublic, w.date_created, w.name, w.configuration, w.expression, IF(w.created_by=:created_by, true, false) AS is_owner, v.renderer, v.type, q.uuid AS query_uuid, wq.sequence AS query_sequence, wq.configuration AS query_configuration FROM ox_widget w JOIN ox_visualization v on w.visualization_id=v.id JOIN ox_widget_query wq ON w.id=wq.ox_widget_id JOIN ox_query q ON wq.ox_query_id=q.id WHERE w.isdeleted=false and w.org_id=:org_id and w.uuid=:uuid AND (w.ispublic=true OR w.created_by=:created_by) ORDER BY wq.sequence ASC';
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
            $queries = [];
            foreach($resultSet as $row) {
                array_push($queries, [
                    'uuid' => $row['query_uuid'],
                    'sequence' => $row['query_sequence'],
                    'configuration' => json_decode($row['query_configuration'])
                ]);
            }
            $firstRow = $resultSet[0];
            $widget = [
                'uuid' => $firstRow['uuid'],
                'ispublic' => $firstRow['ispublic'],
                'date_created' => $firstRow['date_created'],
                'name' => $firstRow['name'],
                'configuration' => json_decode($firstRow['configuration']),
                'expression' => json_decode($firstRow['expression'],1),
                'is_owner' => $firstRow['is_owner'],
                'renderer' => $firstRow['renderer'],
                'type' => $firstRow['type'],
                'queries' => $queries
            ];
            $response = [
                'widget' => $widget
            ];
            //Widget configuration value from database is a JSON string. Convert it to object and overwrite JSON string value.
         //   $response['widget']['configuration'] = json_decode($resultSet[0]['configuration'],1);
        }
        catch (ZendDbException $e) {
            $this->logger->error('Database exception occurred.');
            $this->logger->error($e);
            $this->logger->error('Query and params:');
            $this->logger->error($query);
            $this->logger->error($queryParams);
            return 0;
        }
        $data = array();
        if(isset($params['data'])) {
            foreach ($resultSet as $row) {
                $query_uuid = $row['query_uuid'];
                $queryData = $this->queryService->executeAnalyticsQuery($query_uuid);
                if (!empty($data) && isset($queryData['data'])) {
                    $data = array_replace_recursive($data, $queryData['data']);
                } else {
                    if (isset($queryData['data'])) {
                        $data = $queryData['data'];
                    }
                }
            }
            //--------------------------------------------------------------------------------------------------------------------------------
//TODO:Fetch data from elastic search and remove hard coded values below.
                // $data = [
                //     ['person'=> 'Bharat', 'sales'=> 4.2],
                //     ['person'=> 'Harsha', 'sales'=> 5.2],
                //     ['person'=> 'Mehul', 'sales'=> 15.2],
                //     ['person'=> 'Rajesh', 'sales'=> 2.9],
                //     ['person'=> 'Ravi', 'sales'=> 2.9],
                //     ['person'=> 'Yuvraj', 'sales'=> 14.2]
                // ];

            $testUuid = $resultSet[0]['uuid'];
            if ($testUuid == '2aab5e6a-5fd4-44a8-bb50-57d32ca226b0') {
                //Sales YTD
                $data = '235436';
            }
            if (($testUuid == 'bacb4ec3-5f29-49d7-ac41-978a99d014d3') || 
                ($testUuid == 'ae8e3919-88a8-4eaf-9e35-d7a4408a1f8c') || 
                ($testUuid == 'e1933370-22bd-4cd8-abc9-fcdc29b6481d')) {
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
            if ($testUuid == 'd5927bc2-d87b-4dd5-b45b-66d7c5fcb3f1') {
                $data = '83.89';
            }
//            if ($testUuid == '45933c62-6933-43da-bbb2-59e6f331e8db') {
//                //Quarterly revenue target
//                $data = [
//                    ['quarter'=> 'Q1 2018', 'revenue'=> 4.2],
//                    ['quarter'=> 'Q2 2018', 'revenue'=> 5.4],
//                    ['quarter'=> 'Q3 2018', 'revenue'=> 3.1],
//                    ['quarter'=> 'Q4 2018', 'revenue'=> 3.8],
//                    ['quarter'=> 'Q1 2019', 'revenue'=> 4.1],
//                    ['quarter'=> 'Q2 2019', 'revenue'=> 4.7]
//                ];
//            }
//            if ($testUuid == '69f7732a-998a-41bb-ab89-aa7c434cb327') {
//                //Revenue YTD
//                $data = '786421';
//            }
//            if ($testUuid == 'de5c309d-6bd6-494f-8c34-b85ac109a301') {
//                //Product sales
//                $data = [
//                    ['product'=>'Audio player', 'sales'=>1.3],
//                    ['product'=>'Video player', 'sales'=>3.2],
//                    ['product'=>'Sports shoe', 'sales'=>2.8],
//                    ['product'=>'Gym cap', 'sales'=>0.87],
//                    ['product'=>'Baseball cap', 'sales'=>0.4]
//                ];
//            }
            if (isset($response['widget']['expression']['expression'])) {
                $expressions = $response['widget']['expression']['expression'];
                if (!is_array($expressions)) {
                    $expressions = array($expressions);
                }
                foreach($expressions as $expression) {
                    $data = $this->evaluteExpression($data,$expression);
                }
            }
            $response['widget']['data'] = $data;
//--------------------------------------------------------------------------------------------------------------------------------
        }
        return $response;
    }


    public function evaluteExpression($data,$expression) {
        $expArray = explode("=",$expression,2);
        if (count($expArray)==2) {
            $colName =  $expArray[0];
            $expression = $expArray[1];
        } else {
            $colName = 'calculated';
        }
        foreach($data as $key1=>$dataset) {
            $m = new EvalMath;
            foreach($dataset as $key2=>$value) {
                if (is_numeric($value)) {
                    $m->evaluate("$key2 = $value");
                }
            }
            $calculated = $m->evaluate($expression);
            $data[$key1][$colName] = $calculated;
        }
        return $data;
    }

    public function getWidgetList($params = null)
    {
        $paginateOptions = FilterUtils::paginate($params);
        $where = $paginateOptions['where'];
        $widgetConditions = '(w.isdeleted <> 1) AND (w.org_id = ' . AuthContext::get(AuthConstants::ORG_ID) . ') AND ((w.created_by =  ' . AuthContext::get(AuthConstants::USER_ID) . ') OR (w.ispublic = 1))';
        $where .= empty($where) ? "WHERE ${widgetConditions}" : " AND ${widgetConditions}";
        $sort = $paginateOptions['sort'] ? (' ORDER BY w.' . $paginateOptions['sort']) : '';
        $limit = ' LIMIT ' . $paginateOptions['pageSize'] . ' OFFSET ' . $paginateOptions['offset'];

        $countQuery = "SELECT COUNT(id) as 'count' FROM ox_widget w ${where}";
        try {
            $resultSet = $this->executeQuerywithParams($countQuery);
        }
        catch (ZendDbException $e) {
            $this->logger->error('Database exception occurred. Query:');
            $this->logger->error($countQuery);
            $this->logger->error($e);
            return 0;
        }
        $count = $resultSet->toArray()[0]['count'];

        $query ='SELECT w.name, w.uuid, IF(w.created_by = ' . AuthContext::get(AuthConstants::USER_ID) . ', true, false) AS is_owner, w.ispublic, w.isdeleted, v.type, v.renderer FROM ox_widget w JOIN ox_visualization v ON w.visualization_id = v.id ' . $where. ' ' . $sort . ' ' . $limit;
        try {
            $resultSet = $this->executeQuerywithParams($query);
        }
        catch (ZendDbException $e) {
            $this->logger->error('Database exception occurred. Query:');
            $this->logger->error($query);
            $this->logger->error($e);
            return 0;
        }
        $result = $resultSet->toArray();

        return array('data' => $result,
                     'total' => $count);
    }
}

