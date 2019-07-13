<?php
namespace Analytics\Service;

use Oxzion\Service\AbstractService;
use Analytics\Model\DashboardTable;
use Analytics\Model\Dashboard;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\ValidationException;
use Zend\Db\Sql\Expression;
use Oxzion\Utils\FilterUtils;
use Ramsey\Uuid\Uuid;
use Exception;

class DashboardService extends AbstractService
{

    private $table;

    public function __construct($config, $dbAdapter, DashboardTable $table)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
    }

    public function createDashboard(&$data)
    {
        $form = new Dashboard();
        $data['uuid'] = Uuid::uuid4()->toString();
        $data['created_by'] = AuthContext::get(AuthConstants::USER_ID);
        if(isset($data['ispublic']))
            $data['ispublic'] = 0;
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

    public function updateDashboard($id, &$data)
    {
        $obj = $this->table->get($id, array());
        if (is_null($obj)) {
            return 0;
        }
        $form = new Dashboard();
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

    public function deleteDashboard($id)
    {
        $count = 0;
        try {
            $delete = $this->getSqlObject()
                ->delete('widget_dashboard_mapper')
                ->where(['dashboard_id' => $id]);
            $this->executeQueryString($delete);
            $count = $this->table->delete($id);
            if ($count == 0) {
                return 0;
            }
        } catch (Exception $e) {
            $this->rollback();
            return $e->getMessage();
        }
        return $count;
    }

    public function getDashboard($id)
    {
        $query = "Select dashboard.*, widget_dashboard_mapper.widget_id,widget_dashboard_mapper.dimensions from dashboard INNER JOIN widget_dashboard_mapper on dashboard.id = widget_dashboard_mapper.dashboard_id where widget_dashboard_mapper.id =".$id;
        $response = $this->executeQuerywithParams($query)->toArray();
        foreach ($response as $key => $value) {
        if(!empty($result[$key]['dimensions']))
            $result[$key]['dimensions'] = json_decode($result[$key]['dimensions']);
        }
        if (count($response) == 0) {
            return 0;
        }
        return $response[0];
    }

    public function getDashboardList($params = null)
    {

            $paginateOptions = FilterUtils::paginate($params);
            $where = $paginateOptions['where'];
            $where .= empty($where) ? "WHERE (dashboard.org_id =".AuthContext::get(AuthConstants::ORG_ID).") and (dashboard.created_by = ".AuthContext::get(AuthConstants::USER_ID)." OR dashboard.ispublic = 1)" : " AND (dashboard.org_id =".AuthContext::get(AuthConstants::ORG_ID).") and (dashboard.created_by = ".AuthContext::get(AuthConstants::USER_ID)." OR dashboard.ispublic = 1)";
            $sort = " ORDER BY ".$paginateOptions['sort'];
            $limit = " LIMIT ".$paginateOptions['pageSize']." offset ".$paginateOptions['offset'];

            $cntQuery ="SELECT count(id) as 'count' FROM `dashboard` ";
            $resultSet = $this->executeQuerywithParams($cntQuery.$where);
            $count=$resultSet->toArray()[0]['count'];

            $query ="Select dashboard.*, widget_dashboard_mapper.widget_id,widget_dashboard_mapper.dimensions from dashboard INNER JOIN widget_dashboard_mapper on dashboard.id = widget_dashboard_mapper.dashboard_id ".$where." ".$sort." ".$limit;
            $resultSet = $this->executeQuerywithParams($query);
            $result = $resultSet->toArray();
            foreach ($result as $key => $value) {
                if(!empty($result[$key]['dimensions']))
                    $result[$key]['dimensions'] = json_decode($result[$key]['dimensions']);
            }
            return array('data' => $result,
                     'total' => $count);
    }
}
