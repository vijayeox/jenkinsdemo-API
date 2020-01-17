<?php
namespace Analytics\Service;

use Oxzion\Service\AbstractService;
use Analytics\Model\Dashboard;
use Analytics\Model\DashboardTable;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\ValidationException;
use Oxzion\Utils\FilterUtils;
use Ramsey\Uuid\Uuid;
use Exception;
use Oxzion\VersionMismatchException;
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
        if(!isset($data['version']))
        {
            throw new Exception("Version is not specified, please specify the version");
        }
        $form = new Dashboard();
        $form->exchangeWithSpecificKey($obj->toArray(), 'value');
        $form->exchangeWithSpecificKey($data,'value',true);
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
        $formArray = $form->toArray();
        return [
            'dashboard' => [
                'version' => $formArray['version']['value'] + 1,
                'data' => $data
            ]
        ];
    }

    public function deleteDashboard($uuid, $version)
    {
        $obj = $this->table->getByUuid($uuid, array());
        if (is_null($obj)) {
            return 0;
        }
        if(!isset($version))
        {
            throw new Exception("Version is not specified, please specify the version");
        }
        $data = array('version' => $version,'isdeleted' => 1);
        $form = new Dashboard();
        $form->exchangeWithSpecificKey($obj->toArray(), 'value');
        $form->exchangeWithSpecificKey($data,'value',true);
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
        $query = 'select uuid, name, ispublic, description, dashboard_type, date_created, content, version, if(created_by=:created_by, true, false) as is_owner from ox_dashboard where org_id=:org_id and uuid=:uuid and (ispublic=true or created_by=:created_by) and isdeleted=false';
        $queryParams = [
            'created_by' => AuthContext::get(AuthConstants::USER_ID),
            'org_id' => AuthContext::get(AuthConstants::ORG_ID),
            'uuid' => $uuid
        ];
        try{
            $resultSet = $this->executeQueryWithBindParameters($query, $queryParams)->toArray();
            if (0 == count($resultSet)) {
                return 0;
            }
            return [
                'dashboard' => $resultSet[0]
            ];
        }
        catch (ZendDbException $e) {
            $this->logger->error('Database exception occurred.');
            $this->logger->error($e);
            return 0;
        }
    }

    public function getDashboardList($params = null)
    {
        $paginateOptions = FilterUtils::paginate($params);
        $where = $paginateOptions['where'];
        if(isset($params['show_deleted']) && $params['show_deleted']==true){
            $dashboardConditions = '(d.org_id = ' . AuthContext::get(AuthConstants::ORG_ID) . ') AND ((d.created_by =  ' . AuthContext::get(AuthConstants::USER_ID) . ') OR (d.ispublic = 1))';
        }
        else{
            $dashboardConditions = 'd.isdeleted <> 1 AND (d.org_id = ' . AuthContext::get(AuthConstants::ORG_ID) . ') AND ((d.created_by =  ' . AuthContext::get(AuthConstants::USER_ID) . ') OR (d.ispublic = 1))';
        }
        $where .= empty($where) ? "WHERE ${dashboardConditions}" : " AND ${dashboardConditions}";
        $sort = $paginateOptions['sort'] ? (' ORDER BY d.' . $paginateOptions['sort']) : '';
        $limit = ' LIMIT ' . $paginateOptions['pageSize'] . ' offset ' . $paginateOptions['offset'];

        $countQuery = "SELECT COUNT(id) as 'count' FROM ox_dashboard d ${where}";
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

        if(isset($params['show_deleted']) && $params['show_deleted']==true){
            $query ='SELECT d.uuid, d.name, d.ispublic, d.description, d.dashboard_type, IF(d.created_by = '.AuthContext::get(AuthConstants::USER_ID).', true, false) AS is_owner, d.org_id, d.isdeleted from ox_dashboard d ' . $where . ' ' . $sort . ' ' . $limit;
        }
        else{
            $query ='SELECT d.uuid, d.name, d.ispublic, d.description, d.dashboard_type, IF(d.created_by = '.AuthContext::get(AuthConstants::USER_ID).', true, false) AS is_owner, d.org_id from ox_dashboard d ' . $where . ' ' . $sort . ' ' . $limit;
        }
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

