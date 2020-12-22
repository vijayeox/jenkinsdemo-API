<?php
namespace Analytics\Service;

use Analytics\Model\Target;
use Analytics\Model\TargetTable;
use Analytics\Service\QueryService;
use Analytics\Service\WidgetService;
use Exception;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\Service\AbstractService;
use Oxzion\Utils\FilterUtils;
use Oxzion\ValidationException;
use Zend\Db\Sql\Expression;

class TargetService extends AbstractService
{

    private $table;
    private $queryService;
    public function __construct($config, $dbAdapter, TargetTable $table, QueryService $queryService, WidgetService $widgetService)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
        $this->queryService = $queryService;
        $this->widgetService = $widgetService;
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
        } catch (Exception $e) {
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
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
        return $target->getProperty('version');
    }

    public function deleteTarget($uuid, $version)
    {
        $target = new Target($this->table);
        $target->loadByUuid($uuid);
        $target->assign([
            'version' => $version,
            'isdeleted' => 1,
        ]);
        try {
            $this->beginTransaction();
            $target->save();
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
    }

    public function getTarget($uuid, $fullList = null)
    {
        try {
            $sql = $this->getSqlObject();
            $select = $sql->select();
            if (isset($fullList)) {
                $fieldList = array('id', 'uuid', 'type', 'period_type', 'red_limit', 'yellow_limit', 'green_limit', 'trigger_after', 'red_workflow_id', 'yellow_workflow_id', 'yellow_workflow_id', 'is_owner' => (new Expression('IF(created_by = ' . AuthContext::get(AuthConstants::USER_ID) . ', "true", "false")')), 'account_id', 'version', 'isdeleted');
            } else {
                $fieldList = array('uuid', 'type', 'period_type', 'red_limit', 'yellow_limit', 'green_limit', 'trigger_after', 'red_workflow_id', 'yellow_workflow_id', 'yellow_workflow_id', 'is_owner' => (new Expression('IF(created_by = ' . AuthContext::get(AuthConstants::USER_ID) . ', "true", "false")')), 'account_id', 'version', 'isdeleted');
            }
            $select->from('ox_target')
                ->columns($fieldList)
                ->where(array('ox_target.uuid' => $uuid, 'account_id' => AuthContext::get(AuthConstants::ACCOUNT_ID), 'isdeleted' => 0));
            $response = $this->executeQuery($select)->toArray();
            if (count($response) == 0) {
                return 0;
            }
        } catch (Exception $e) {
            throw $e;
        }
        return $response[0];
    }

    public function getTargetList($params = null)
    {
        try {
            $paginateOptions = FilterUtils::paginateLikeKendo($params);
            $where = $paginateOptions['where'];
            if (isset($params['show_deleted']) && $params['show_deleted'] == true) {
                $where .= empty($where) ? "WHERE account_id =" . AuthContext::get(AuthConstants::ACCOUNT_ID) : " AND account_id =" . AuthContext::get(AuthConstants::ACCOUNT_ID);
            } else {
                $where .= empty($where) ? "WHERE isdeleted <>1 AND account_id =" . AuthContext::get(AuthConstants::ACCOUNT_ID) : " AND isdeleted <>1 AND account_id =" . AuthContext::get(AuthConstants::ACCOUNT_ID);
            }
            $sort = $paginateOptions['sort'] ? " ORDER BY " . $paginateOptions['sort'] : '';
            $limit = " LIMIT " . $paginateOptions['pageSize'] . " offset " . $paginateOptions['offset'];

            $cntQuery = "SELECT count(id) as 'count' FROM `ox_target` ";
            $resultSet = $this->executeQuerywithParams($cntQuery . $where);
            $count = $resultSet->toArray()[0]['count'];

            if (isset($params['show_deleted']) && $params['show_deleted'] == true) {
                $query = "SELECT uuid,type,period_type,red_limit,yellow_limit,green_limit,trigger_after,red_workflow_id,yellow_workflow_id,yellow_workflow_id,IF(created_by = " . AuthContext::get(AuthConstants::USER_ID) . ", 'true', 'false') as is_owner,version,account_id,isdeleted FROM `ox_target`" . $where . " " . $sort . " " . $limit;
            } else {
                $query = "SELECT uuid,type,period_type,red_limit,yellow_limit,green_limit,trigger_after,red_workflow_id,yellow_workflow_id,yellow_workflow_id,IF(created_by = " . AuthContext::get(AuthConstants::USER_ID) . ", 'true', 'false') as is_owner,version,account_id FROM `ox_target`" . $where . " " . $sort . " " . $limit;
            }
            $resultSet = $this->executeQuerywithParams($query);
            $result = $resultSet->toArray();
            foreach ($result as $key => $value) {
                unset($result[$key]['id']);
            }
        } catch (Exception $e) {
            throw $e;
        }
        return array('data' => $result, 'total' => $count);
    }

    public function getKRAResult($params)
    {
        if (isset($params['kra_uuid'])) {
            $krauuid = $params['kra_uuid'];
        } else {
            $validationException = new ValidationException();
            $validationException->setErrors(array('message' => 'kra_uuid is required'));
            throw $validationException;
        }
        try {
            $query = 'select q.uuid as queryuuid,t.*,k.type from ox_kra k join ox_query q on k.query_id=q.id join ox_target t on k.target_id = t.id where k.uuid=:uuid';
            $queryParams = [
                'uuid' => $krauuid,
            ];
            $resultSet = $this->executeQueryWithBindParameters($query, $queryParams)->toArray();
            if (count($resultSet) == 0) {
                return 0;
            }
            $data = $resultSet[0];
            $redLimit = $data['red_limit'];
            $yellowLimit = $data['yellow_limit'];
            $greenLimit = $data['green_limit'];
            $type = $data['type'];
            $result = $this->queryService->getQuery($data['queryuuid'], ["data" => 1]);
            $value = $result['query']['data'];
            $ryg = $this->checkRYG($value, $type, $redLimit, $yellowLimit, $greenLimit);
            $result['target']['red_limit'] = $redLimit;
            $result['target']['yellow_limit'] = $yellowLimit;
            $result['target']['green_limit'] = $greenLimit;
            $result['target']['period_type'] = $data['period_type'];
            $result['target']['color'] = $ryg;
        } catch (Exception $e) {
            throw $e;
        }
        return $result;
    }

    public static function checkRYG($value, $type, $red, $yellow, $green)
    {
        $result = "";
        if ($type == 0) {
            if ($value <= $red) {
                $result = "red";
            } else if ($value <= $yellow) {
                $result = "yellow";
            } else {
                $result = "green";
            }
        } else {
            if ($value <= $green) {
                $result = "green";
            } else if ($value <= $yellow) {
                $result = "yellow";
            } else {
                $result = "red";
            }
        }
        return $result;
    }

    public function getWidgetTarget($params)
    {
        $resultSet = 0;
        $validationException = new ValidationException();
        if (isset($params['widgetId'])) {
            $queryParams = [
                'widgetId' => $params['widgetId'],
            ];
        } else {
            $validationException->setErrors(array('message' => 'Widget ID is required'));
            throw $validationException;
        }
        // if (isset($params['targetId'])) {
        //     $queryParams = [
        //         'targetId' => $params['targetId'],
        //     ];
        // } else {
        //     $validationException->setErrors(array('message' => 'Target ID is required'));
        //     throw $validationException;
        // }
        try {
            $query = 'select owt.widget_id, ot.version, ow.uuid as widget_uuid, owt.target_id, owt.trigger_query_id, owt.group_key, owt.group_value, ot.id as target_id, ot.uuid as target_uuid, ot.type, ot.period_type, ot.red_limit, ot.yellow_limit, ot.green_limit, ot.trigger_after, ot.red_workflow_id, ot.yellow_workflow_id, ot.green_workflow_id
            from ox_widget_target as owt
            join ox_target as ot on owt.target_id = ot.id
            join ox_widget as ow on owt.widget_id = ow.id
            where ow.uuid=:widgetId';
            $resultSet = $this->executeQueryWithBindParameters($query, $queryParams)->toArray();
            if (count($resultSet) == 0) {
                return array(0);
            }
        } catch (Exception $e) {
            throw $e;
        }
        return $resultSet;
    }

    public function createWidgetTarget($params)
    {
        if (isset($params['target_id']) && isset($params['widget_id'])) {
            $query = "UPDATE ox_widget_target SET group_key = '" . $params['group_key'] . "', group_value = '" . $params['group_value'] . "' where target_id = " . $params['target_id'] . " and widget_id = " . $params['widget_id'];
        } else {
            $insertData = [];
            if (isset($params['target_uuid'])) {
                $targetData = $this->getTarget($params['target_uuid'], 1);
                $insertData['target_id'] = $targetData['id'];
            }
            if (isset($params['widget_uuid'])) {
                $widgetData = $this->widgetService->getWidgetTableData($params['widget_uuid']);
                $insertData['widget_id'] = $widgetData['id'];
            }
            $query = "INSERT INTO ox_widget_target (target_id, widget_id, trigger_query_id, group_key, group_value) VALUES
                    (" . $insertData['target_id'] . " , " . $insertData['widget_id'] . ", " . (int) $params['trigger_query_id'] . ", '" . $params['group_key'] . "', '" . $params['group_value'] . "')";
        }
        $this->executeUpdateWithBindParameters($query, null);
        return $params;

        // if (isset($params['target_uuid'])) {
        //     $targetData = $this->getTarget($params['target_uuid'], 1);
        //     $insertData['target_id'] = $targetData['id'];
        // }
        // if (isset($params['widget_uuid'])) {
        //     $widgetData = $this->widgetService->getWidgetTableData($params['widget_uuid']);
        //     $insertData['widget_id'] = $widgetData['id'];
        // }
        // // Check if the target is already exist in widgetTarget
        // $targetParams['widgetId'] = $params['target_uuid'];
        // $widgetData = $this->getWidgetTarget($targetParams);
        // if (isset($widgetData)) {
        //     $query = "UPDATE ox_widget_target SET group_key = " . $insertData['group_key'] . " group_value = " . $insertData['group_value'] . " where target_id = " . $insertData['target_id'] . " and widget_id = " . $insertData['widget_id'];
        // } else {
        //     $query = "INSERT INTO ox_widget_target (target_id, widget_id, trigger_query_id, group_key, group_value) VALUES
        //             (" . $insertData['target_id'] . " , " . $insertData['widget_id'] . ", " . (int) $insertData['trigger_query_id'] . ", '" . $insertData['group_key'] . "', '" . $insertData['group_value'] . "')";
        // }
        // $this->executeUpdateWithBindParameters($query, null);
        // return $insertData;
    }

    public function updateWidgetTarget($params)
    {
        // Check if the target is already exist in widgetTarget
        try {
            $targetParams['widgetId'] = $params['target_uuid'];
            $widgetData = $this->getWidgetTarget($params);
            if (isset($widgetData)) {
                if (isset($params['target_uuid'])) {
                    $targetData = $this->getTarget($params['target_uuid'], 1);
                    $insertData['target_id'] = $targetData['id'];
                }
                if (isset($params['widget_uuid'])) {
                    $widgetData = $this->widgetService->getWidgetTableData($params['widget_uuid']);
                    $insertData['widget_id'] = $widgetData['id'];
                }
                $query = "UPDATE ox_widget_target SET group_key = " . $insertData['group_key'] . " group_value = " . $insertData['group_value'] . " where target_id = " . $insertData['target_id'] . " and widget_id = " . $insertData['widget_id'];
            }
            $this->executeUpdateWithBindParameters($query, null);
        } catch (Exception $e) {
            throw $e;
        }
        return $params;
    }
}
