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
            'configuration' => $data['configuration']
        ];
        if (isset($data['visualization_uuid'])) {
            $visualizationIdInsert = '(SELECT id FROM ox_visualization ov WHERE ov.uuid=:visualization_uuid AND ov.org_id=:org_id)';
            $queryParams['visualization_uuid'] = $data['visualization_uuid'];
        }
        else {
            $visualizationIdInsert = '(SELECT visualization_id FROM ox_widget ovw WHERE ovw.uuid=:oldWidgetUuid AND ovw.org_id=:org_id)';
            $queryParams['oldWidgetUuid'] = $oldWidgetUuid;
        }

        $query = "INSERT INTO ox_widget (uuid, visualization_id, ispublic, created_by, date_created, org_id, isdeleted, name, configuration, version) VALUES (:uuid, ${visualizationIdInsert}, :ispublic, :created_by, :date_created, :org_id, :isdeleted, :name, :configuration, :version)";
        try {
            $this->beginTransaction();
            $result = $this->executeQueryWithBindParameters($query, $queryParams);
            if (1 == $result->count()) {
                $this->commit();
                return $newWidgetUuid;
            }
            else {
                $this->logger->error('Unexpected result. Transaction rolled back.', $result);
                $this->rollback();
                return 0;
            }
        }
        catch (ZendDbException $e) {
            $this->logger->error('Database exception occurred.');
            $this->logger->error($e);
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
        $query = 'select w.uuid, w.ispublic, w.created_by, w.date_created, w.name, w.configuration, if(w.created_by=:created_by, true, false) as is_owner, v.renderer, v.type from ox_widget w join ox_visualization v on w.visualization_id=v.id where w.isdeleted=false and w.org_id=:org_id and w.name=:name and (w.ispublic=true or w.created_by=:created_by)';
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
            return 0;
        }
        return $response;
    }

    public function getWidget($uuid,$params)
    {
        $query = 'select w.uuid, w.ispublic, w.created_by, w.date_created, w.name, w.configuration, if(w.created_by=:created_by, true, false) as is_owner, v.renderer, v.type from ox_widget w join ox_visualization v on w.visualization_id=v.id where w.isdeleted=false and w.org_id=:org_id and w.uuid=:uuid and (w.ispublic=true or w.created_by=:created_by)';
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
                'widget' => $resultSet[0]
            ];
            //Widget configuration value from database is a JSON string. Convert it to object and overwrite JSON string value.
            $response['widget']['configuration'] = json_decode($resultSet[0]['configuration']);
        }
        catch (ZendDbException $e) {
            $this->logger->error('Database exception occurred.');
            $this->logger->error($e);
            return 0;
        }

        if(isset($params['data'])) {
            
            //--------------------------------------------------------------------------------------------------------------------------------
//TODO:Fetch data from elastic search and remove hard coded values below.
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
            $response['widget']['data'] = $data;
//--------------------------------------------------------------------------------------------------------------------------------
        }
        return $response;
    }


    public function getWidgetList($params = null)
    {
        $paginateOptions = FilterUtils::paginate($params);
        $where = $paginateOptions['where'];
        $where .= empty($where) ? "WHERE ox_widget.isdeleted <>1 AND (ox_widget.org_id =".AuthContext::get(AuthConstants::ORG_ID).") and (ox_widget.created_by = ".AuthContext::get(AuthConstants::USER_ID)." OR ox_widget.ispublic = 1)" : " AND ox_widget.isdeleted <>1 AND (ox_widget.org_id =".AuthContext::get(AuthConstants::ORG_ID).") and (ox_widget.created_by = ".AuthContext::get(AuthConstants::USER_ID)." OR ox_widget.ispublic = 1)";
        $sort = " ORDER BY ox_widget.".$paginateOptions['sort'];
        $limit = " LIMIT ".$paginateOptions['pageSize']." offset ".$paginateOptions['offset'];

        $cntQuery ="SELECT count(id) as 'count' FROM `ox_widget` ";
        $resultSet = $this->executeQuerywithParams($cntQuery.$where);
        $count=$resultSet->toArray()[0]['count'];

        $queryString = "Select ox_widget.name,ox_widget.uuid,IF(ox_widget.created_by = ".AuthContext::get(AuthConstants::USER_ID).", 'true', 'false') as is_owner,ox_widget.ispublic,ox_widget.org_id,ox_widget.isdeleted,ox_visualization.name as type from `ox_widget` inner join ox_visualization on ox_widget.visualization_id = ox_visualization.id ";
        $query =$queryString.$where." ".$sort." ".$limit;
        $resultSet = $this->executeQuerywithParams($query);
        $result = $resultSet->toArray();
        foreach ($result as $key => $value) {
            unset($result[$key]['id']);
        }
        return array('data' => $result,
                 'total' => $count);
    }
}
