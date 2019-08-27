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

    public function updateDashboard($uuid, &$data)
    {
        $obj = $this->table->getByUuid($uuid, array());
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

    public function deleteDashboard($uuid)
    {
        $id = $this->getIdFromUuid('ox_dashboard',$uuid);
        $obj = $this->table->getByUuid($uuid, array());
        if (is_null($obj)) {
            return 0;
        }
        $form = new Dashboard();
        $data['isdeleted'] = 1;
        $data = array_merge($obj->toArray(), $data);
        $form->exchangeArray($data);
        $form->validate();
        $count = 0;
        try {
            $count = $this->table->save($form);
            $delete = $this->getSqlObject()
                ->delete('ox_widget_dashboard_mapper')
                ->where(['dashboard_id' => $id]);
            $this->executeQueryString($delete);
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

    public function getDashboard($uuid)
    {
        $id = $this->getIdFromUuid('ox_dashboard',$uuid);
        try{
            $query = "Select ox_dashboard.uuid,ox_dashboard.name,ox_dashboard.ispublic,ox_dashboard.description, ox_dashboard.dashboard_type,IF(ox_dashboard.created_by = ".AuthContext::get(AuthConstants::USER_ID).", 'true', 'false') as is_owner,ox_dashboard.org_id,ox_dashboard.isdeleted, ox_widget_dashboard_mapper.widget_id from ox_dashboard INNER JOIN ox_widget_dashboard_mapper on ox_dashboard.id = ox_widget_dashboard_mapper.dashboard_id where ox_dashboard.isdeleted <> 1 AND ox_widget_dashboard_mapper.id =".$id;
            $response = $this->executeQuerywithParams($query)->toArray();
        }
        catch (Exception $e) {
            return 0;
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
            $where .= empty($where) ? "WHERE ox_dashboard.isdeleted <> 1 AND (ox_dashboard.org_id =".AuthContext::get(AuthConstants::ORG_ID).") and (ox_dashboard.created_by = ".AuthContext::get(AuthConstants::USER_ID)." OR ox_dashboard.ispublic = 1)" : " AND ox_dashboard.isdeleted <> 1 AND (ox_dashboard.org_id =".AuthContext::get(AuthConstants::ORG_ID).") and (ox_dashboard.created_by = ".AuthContext::get(AuthConstants::USER_ID)." OR ox_dashboard.ispublic = 1)";
            $sort = " ORDER BY ".$paginateOptions['sort'];
            $limit = " LIMIT ".$paginateOptions['pageSize']." offset ".$paginateOptions['offset'];

            $cntQuery ="SELECT count(id) as 'count' FROM `ox_dashboard` ";
            $resultSet = $this->executeQuerywithParams($cntQuery.$where);
            $count=$resultSet->toArray()[0]['count'];

            $query ="Select ox_dashboard.uuid,ox_dashboard.name,ox_dashboard.ispublic,ox_dashboard.description, ox_dashboard.dashboard_type,IF(ox_dashboard.created_by = ".AuthContext::get(AuthConstants::USER_ID).", 'true', 'false') as is_owner,ox_dashboard.org_id,ox_dashboard.isdeleted, ox_widget_dashboard_mapper.widget_id from ox_dashboard INNER JOIN ox_widget_dashboard_mapper on ox_dashboard.id = ox_widget_dashboard_mapper.dashboard_id ".$where." ".$sort." ".$limit;
            $resultSet = $this->executeQuerywithParams($query);
            $result = $resultSet->toArray();

            foreach ($result as $key => $value) {
                unset($result[$key]['id']);
            }

            return array('data' => $result,
                     'total' => $count);
    }
}
