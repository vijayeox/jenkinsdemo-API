<?php
namespace Analytics\Service;

use Analytics\Model\Dashboard;
use Analytics\Model\DashboardTable;
use Exception;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\Service\AbstractService;
use Oxzion\Utils\FilterUtils;
use Ramsey\Uuid\Uuid;
use Zend\Db\Exception\ExceptionInterface as ZendDbException;

class DashboardService extends AbstractService
{
    private $table;

    public function __construct($config, $dbAdapter, DashboardTable $table)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
    }

    public function createDashboard($data)
    {
        $form = new Dashboard();
        $data['created_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_created'] = date('Y-m-d H:i:s');
        $data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
        $data['uuid'] = Uuid::uuid4()->toString();
        $check = null;
        try {
            $query = 'Select id from ox_dashboard where isdefault = 1 and org_id=:org_id';
            $queryParams = [
                'org_id' => AuthContext::get(AuthConstants::ORG_ID),
            ];
            $check = $this->executeQueryWithBindParameters($query, $queryParams)->toArray();
            if (count($check) == 0) {
                $data['isdefault'] = 1;
            }
        } catch (Exception $e) {
            throw $e;
        }
        $form->exchangeWithSpecificKey($data, 'value');
        $form->validate();
        $this->beginTransaction();
        $count = 0;
        try {
            if (isset($data['isdefault']) && ($data['isdefault'] == 1)) {
                $query = 'Update ox_dashboard SET isdefault = 0 where isdefault = 1 and org_id=:org_id';
                $queryParams = [
                    'org_id' => AuthContext::get(AuthConstants::ORG_ID),
                ];
                $this->executeUpdateWithBindParameters($query, $queryParams);
            }
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
            throw $e;
        }
        return $data['uuid'];
    }

    public function updateDashboard($uuid, $data)
    {
        $obj = $this->table->getByUuid($uuid, array());
        if (is_null($obj)) {
            return 0;
        }
        if (!isset($data['version'])) {
            throw new Exception("Version is not specified, please specify the version");
        }
        $form = new Dashboard();
        $form->exchangeWithSpecificKey($obj->toArray(), 'value');
        $form->exchangeWithSpecificKey($data, 'value', true);
        $form->updateValidate($data);
        $count = 0;
        try {
            if (isset($data['isdefault']) && $data['isdefault'] == 1) {
                $query = 'Update ox_dashboard SET isdefault = 0 where isdefault = 1 and org_id=:org_id';
                $queryParams = [
                    'org_id' => AuthContext::get(AuthConstants::ORG_ID),
                ];
                $this->executeUpdateWithBindParameters($query, $queryParams);
            }
            $count = $this->table->save2($form);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
        $formArray = $form->toArray();
        return [
            'dashboard' => [
                'version' => $formArray['version']['value'] + 1,
                'data' => $data,
            ],
        ];
    }

    public function deleteDashboard($uuid, $version)
    {
        $obj = $this->table->getByUuid($uuid, array());
        if (is_null($obj)) {
            return 0;
        }
        if (!isset($version)) {
            throw new Exception("Version is not specified, please specify the version");
        }
        $data = array('version' => $version, 'isdeleted' => 1);
        $form = new Dashboard();
        $form->exchangeWithSpecificKey($obj->toArray(), 'value');
        $form->exchangeWithSpecificKey($data, 'value', true);
        $form->updateValidate($data);
        $count = 0;
        try {
            $count = $this->table->save2($form);
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

    public function getDashboard($uuid)
    {
        $query = 'select uuid, name, ispublic, description, dashboard_type, date_created, content, version, if(created_by=:created_by, true, false) as is_owner, isdeleted, filter_configuration from ox_dashboard where org_id=:org_id and uuid=:uuid and (ispublic=true or created_by=:created_by) and isdeleted=false';
        $queryParams = [
            'created_by' => AuthContext::get(AuthConstants::USER_ID),
            'org_id' => AuthContext::get(AuthConstants::ORG_ID),
            'uuid' => $uuid,
        ];
        try {
            $resultSet = $this->executeQueryWithBindParameters($query, $queryParams)->toArray();
            if (0 == count($resultSet)) {
                return 0;
            }
            return [
                'dashboard' => $resultSet[0],
            ];
        } catch (ZendDbException $e) {
            $this->logger->error('Database exception occurred.');
            $this->logger->error($e);
            return 0;
        }
    }

    public function getDashboardList($params = null)
    {
        $paginateOptions = FilterUtils::paginateLikeKendo($params);
        // print_r($params);exit;
        $where = $paginateOptions['where'];
        if (isset($params['show_deleted']) && $params['show_deleted'] == true) {
            $dashboardConditions = '(d.org_id = ' . AuthContext::get(AuthConstants::ORG_ID) . ') AND ((d.created_by =  ' . AuthContext::get(AuthConstants::USER_ID) . ') OR (d.ispublic = 1))';
        } else {
            $dashboardConditions = 'd.isdeleted <> 1 AND (d.org_id = ' . AuthContext::get(AuthConstants::ORG_ID) . ') AND ((d.created_by =  ' . AuthContext::get(AuthConstants::USER_ID) . ') OR (d.ispublic = 1))';
        }
        $where .= empty($where) ? "WHERE ${dashboardConditions}" : " AND ${dashboardConditions}";
        $sort = $paginateOptions['sort'] ? (' ORDER BY d.' . $paginateOptions['sort']) : '';
        if($paginateOptions['pageSize'] != 0) {
            $limit = " LIMIT ".$paginateOptions['pageSize']." offset ".$paginateOptions['offset'];
        } else{
            $limit = " ";
        }


        $countQuery = "SELECT COUNT(id) as 'count' FROM ox_dashboard d ${where}";
        try {
            $resultSet = $this->executeQuerywithParams($countQuery);
        } catch (ZendDbException $e) {
            $this->logger->error('Database exception occurred. Query:');
            $this->logger->error($countQuery);
            $this->logger->error($e);
            return 0;
        }
        $count = $resultSet->toArray()[0]['count'];

        if (isset($params['show_deleted']) && $params['show_deleted'] == true) {
            $query = 'SELECT d.uuid, d.name,d.version, d.ispublic, d.description, d.dashboard_type, IF(d.created_by = ' . AuthContext::get(AuthConstants::USER_ID) . ', true, false) AS is_owner, d.org_id, d.isdeleted, d.isdefault, d.filter_configuration from ox_dashboard d ' . $where . ' ' . $sort . ' ' . $limit;
        } else {
            $query = 'SELECT d.uuid, d.name,d.version, d.ispublic, d.description, d.dashboard_type, IF(d.created_by = ' . AuthContext::get(AuthConstants::USER_ID) . ', true, false) AS is_owner, d.org_id, d.isdefault, d.filter_configuration from ox_dashboard d ' . $where . ' ' . $sort . ' ' . $limit;
        }
        // echo $query;exit;
        try {
            $resultSet = $this->executeQuerywithParams($query);
        } catch (ZendDbException $e) {
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
