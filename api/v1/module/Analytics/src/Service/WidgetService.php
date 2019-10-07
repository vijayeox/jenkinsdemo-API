<?php
namespace Analytics\Service;

use Oxzion\Service\AbstractService;
use Analytics\Model\WidgetTable;
use Analytics\Model\Widget;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\ValidationException;
use Oxzion\Utils\FilterUtils;
use Ramsey\Uuid\Uuid;
use Exception;

class WidgetService extends AbstractService
{
    private $table;

    public function __construct($config, $dbAdapter, WidgetTable $table, $logger)
    {
        parent::__construct($config, $dbAdapter, $logger);
        $this->table = $table;
    }

    public function createWidget(&$data)
    {
        $form = new Widget();
        $data['uuid'] = Uuid::uuid4()->toString();
        $data['created_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_created'] = date('Y-m-d H:i:s');
        $data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
        $form->exchangeArray($data);
        $form->validate();
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->save($form);
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

    public function updateWidget($uuid, &$data)
    {
        $obj = $this->table->getByUuid($uuid, array());
        if (is_null($obj)) {
            return 0;
        }
        $form = new Widget();
        $data = array_merge($obj->toArray(), $data);
        $form->exchangeArray($data);
        $form->validate();
        $count = 0;
        try {
            $count = $this->table->save($form);
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
        $form->exchangeArray($data);
        $form->validate();
        $count = 0;
        try {
            $count = $this->table->save($form);
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

    public function getWidget($uuid,$params)
    {
        $query = "select w.uuid, w.ispublic, w.created_by, w.date_created, w.name, w.configuration, if(w.created_by=:created_by, true, false) as is_owner, v.renderer, v.type from ox_widget w join ox_visualization v on w.visualization_id=v.id where w.isdeleted=false and w.org_id=:org_id and w.uuid=:uuid and (w.ispublic=true or w.created_by=:created_by)";
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
            $response['widget']['configuration'] = json_decode($resultSet[0]["configuration"]);
        }
        catch(Exception $e) {
            $this->logger->err($e);
            return 0;
        }

        if(isset($params['data'])) {
//--------------------------------------------------------------------------------------------------------------------------------
//TODO:Fetch data from elastic search and remove hard coded values below.
            if (0 == strcmp($uuid, "2aab5e6a-5fd4-44a8-bb50-57d32ca226b0")) { //Sales YTD
                $response['widget']['data'] = '235436';
            }
            if ((0 == strcmp($uuid, "bacb4ec3-5f29-49d7-ac41-978a99d014d3")) || (0 == strcmp($uuid, "ae8e3919-88a8-4eaf-9e35-d7a4408a1f8c"))) { //Sales by sales person
                $response['widget']['data'] = [
                    ['person'=> 'Bharat', 'sales'=> 4.2],
                    ['person'=> 'Harsha', 'sales'=> 5.2],
                    ['person'=> 'Mehul', 'sales'=> 15.2],
                    ['person'=> 'Rajesh', 'sales'=> 2.9],
                    ['person'=> 'Ravi', 'sales'=> 2.9],
                    ['person'=> 'Yuvraj', 'sales'=> 14.2]
                ];
            }
            if (0 == strcmp($uuid, "08bd8fc1-2336-423d-842a-7217a5482dc9")) { //Quarterly revenue 
                $response['widget']['data'] = [
                    ['quarter'=> 'Q1 2018', 'revenue'=> 4.2],
                    ['quarter'=> 'Q2 2018', 'revenue'=> 5.4],
                    ['quarter'=> 'Q3 2018', 'revenue'=> 3.1],
                    ['quarter'=> 'Q4 2018', 'revenue'=> 3.8],
                    ['quarter'=> 'Q1 2019', 'revenue'=> 4.1],
                    ['quarter'=> 'Q2 2019', 'revenue'=> 4.7]
                ];
            }
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
