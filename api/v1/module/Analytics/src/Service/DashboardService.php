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

    public function __construct($config, $dbAdapter, DashboardTable $table, $logger)
    {
        parent::__construct($config, $dbAdapter, $logger);
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
//---------------------------------------
//TODO: Handle concurrency
//---------------------------------------
        $updatableColumns = ['name', 'ispublic', 'description', 'dashboard_type', 'isdeleted', 'content'];
        $selectQuery = 'select ' . join(',', $updatableColumns) . ', id, if(created_by=:user, true, false) as is_owner from ox_dashboard where org_id=:organization and uuid=:uuid and (ispublic=true or created_by=:user)';
        $selectQueryParams = array('user' => AuthContext::get(AuthConstants::USER_ID),
                            'organization' => AuthContext::get(AuthConstants::ORG_ID), 
                            'uuid' => $uuid);
        try {
            $selectRs = $this->executeQueryWithBindParameters($selectQuery, $selectQueryParams)->toArray();
            if (count($selectRs) == 0) {
                return 0;
            }
        }
        catch(Exception $e) {
            $this->logger->err($e);
        }

        $updateQuery = '';

        $obj = $this->table->getByUuid($uuid, array('org_id' => AuthContext::get(AuthConstants::ORG_ID), 'isdeleted' => false));
        if (is_null($obj)) {
            return 0;
        }
        $form = new Dashboard();
        $data = array_merge($obj->toArray(), $data); //This is dangerous! What if I send a changed UUID? It gets saved. I can update any column except primary key.
        $form->exchangeArray($data);
        $form->validate();
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->save($form);
            if ($count == 0) {
                $this->logger->warn("Nothing was updated because data did not change!");
                $count = 1; //Fake one row was changed though the row was not updated because data did not change.
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            $this->logger->err($e);
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
        try{
            $query = "select uuid, name, ispublic, description, dashboard_type, date_created, content, if(created_by=?, true, false) as is_owner from ox_dashboard where org_id=? and uuid=? and (ispublic=true or created_by=?)";
            $queryParams = array(AuthContext::get(AuthConstants::USER_ID),
                            AuthContext::get(AuthConstants::ORG_ID), 
                            $uuid, 
                            AuthContext::get(AuthConstants::USER_ID));
            $resultSet = $this->executeQueryWithBindParameters($query, $queryParams)->toArray();
            if (count($resultSet) == 0) {
                return 0;
            }
            $response = [
                'dashboard' => $resultSet[0]
            ];
            return $response;
        }
        catch (Exception $e) {
            $this->logger->err($e);
            return 0;
        }
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

            $query ="Select ox_dashboard.id,ox_dashboard.uuid,ox_dashboard.name,ox_dashboard.ispublic,ox_dashboard.description, ox_dashboard.dashboard_type,IF(ox_dashboard.created_by = ".AuthContext::get(AuthConstants::USER_ID).", 'true', 'false') as is_owner,ox_dashboard.org_id,ox_dashboard.isdeleted, ox_widget_dashboard_mapper.widget_id from ox_dashboard INNER JOIN ox_widget_dashboard_mapper on ox_dashboard.id = ox_widget_dashboard_mapper.dashboard_id ".$where." ".$sort." ".$limit;
            $resultSet = $this->executeQuerywithParams($query);
            $result = $resultSet->toArray();

            foreach ($result as $key => $value) {
                unset($result[$key]['id']);
            }

            return array('data' => $result,
                     'total' => $count);
    }
}

