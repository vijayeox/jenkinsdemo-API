<?php
namespace Analytics\Service;

use Oxzion\Service\AbstractService;
use Analytics\Model\WidgetTable;
use Analytics\Model\Widget;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\ValidationException;
use Zend\Db\Sql\Expression;
use Oxzion\Utils\FilterUtils;
use Ramsey\Uuid\Uuid;
use Exception;

class WidgetService extends AbstractService
{

    private $table;

    public function __construct($config, $dbAdapter, WidgetTable $table)
    {
        parent::__construct($config, $dbAdapter);
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
        $columnList = array('name','uuid','query_id','is_owner' => (new Expression('IF(ox_widget.created_by = '.AuthContext::get(AuthConstants::USER_ID).', "true", "false")')),'visualization_id','ispublic','org_id','isdeleted');
        if(isset($params['config']))
        {
            $columnList = array('name','uuid','query_id','is_owner' => (new Expression('IF(ox_widget.created_by = '.AuthContext::get(AuthConstants::USER_ID).', "true", "false")')),'visualization_id','ispublic','org_id','isdeleted','configuration');
        }
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_widget')->columns($columnList)->where(array('ox_widget.uuid' => $uuid,'ox_widget.org_id' => AuthContext::get(AuthConstants::ORG_ID),'ox_widget.isdeleted' => 0))->join('ox_visualization','ox_visualization.id = ox_widget.visualization_id',array('type' => 'name'));
        $response = $this->executeQuery($select)->toArray();
        if(isset($response[0]))
        {
            $response['widget'] = $response[0];
            unset($response[0]);
        }
        if(isset($response['widget']['configuration']))
        {
            $response['configuration'] = $response['widget']['configuration'];
            unset($response['widget']['configuration']);
        }
        if (count($response) == 0) {
            return 0;
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
