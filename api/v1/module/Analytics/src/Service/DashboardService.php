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
        $newDashboardUuid = Uuid::uuid4()->toString();
        $queryParams = [
            'uuid'           => $newDashboardUuid,
            'date_created'   => date('Y-m-d H:i:s'),
            'org_id'         => AuthContext::get(AuthConstants::ORG_ID),
            'created_by'     => AuthContext::get(AuthConstants::USER_ID),
            'version'        => 0,
            'isdeleted'      => false,
            'dashboard_type' => 'html',
            'name'           => $data['name'],
            'ispublic'       => isset($data['ispublic']) ? $data['ispublic'] : false,
            'description'    => $data['description'],
            'content'        => $data['content']
        ];
        $query = 'insert into ox_dashboard (uuid, name, ispublic, description, dashboard_type, created_by, date_created, org_id, isdeleted, content, version) values (:uuid, :name, :ispublic, :description, :dashboard_type, :created_by, :date_created, :org_id, :isdeleted, :content, :version)';

        try {
            $this->beginTransaction();
            $result = $this->executeQueryWithBindParameters($query, $queryParams);
            $this->commit();
            return $newDashboardUuid;
        }
        catch (ZendDbException $e) {
            $this->logger->error('Database exception occurred. Query and parameters:');
            $this->logger->error($query);
            $this->logger->error($queryParams);
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

    public function updateDashboard($uuid, $data)
    {
        $updatableColumns = [
            'name', 
            'ispublic', 
            'description', 
            'dashboard_type', 
            'isdeleted', 
            'content'
        ];
        $updateQueryFragment = 'version=:newVersion';
        $version = $data['version'];
        $newVersion = $version + 1;
        $updateQueryParams = [
            'newVersion' => $newVersion,
            'version'    => $version,
            'uuid'       => $uuid,
            'org_id'     => AuthContext::get(AuthConstants::ORG_ID),
            'user_id'    => AuthContext::get(AuthConstants::USER_ID)
        ];
        foreach ($updatableColumns as $index => $column) {
            if (array_key_exists($column, $data)) {
                $updateQueryFragment = "${updateQueryFragment}, ${column}=:${column}";
                $updateQueryParams[$column] = $data[$column];
            }
        }
        $updateQuery = "update ox_dashboard set ${updateQueryFragment} where uuid=:uuid and created_by=:user_id and org_id=:org_id and isdeleted=false and version=:version";
        try {
            $this->beginTransaction();
            $result = $this->executeQueryWithBindParameters($updateQuery, $updateQueryParams);
            if (1 == $result) { //If 1 row is updated.
                $this->commit();
                return [
                    'dashboard' => [
                        'version' => $newVersion
                    ]
                ];
            }
            else {
                $this->rollback();
                //Check whether version number has changed.
                $versionQuery = "select version from ox_dashboard where uuid=:uuid and created_by=:user_id and org_id=:org_id";
                $versionQueryParams = [
                    'uuid' => $uuid,
                    'org_id' => AuthContext::get(AuthConstants::ORG_ID),
                    'user_id' => AuthContext::get(AuthConstants::USER_ID)
                ];
                $versionResult = $this->executeQueryWithBindParameters($versionQuery, $versionQueryParams)->toArray();
                if (isset($versionResult[0])) {
                    $versionFromDatabase = $versionResult[0]['version'];
                    if ($versionFromDatabase != $version) {
                        $this->logger->error('Version number mismatch. Exception thrown.');
                        throw new \Oxzion\VersionMismatchException();
                    }
                }
                return 0;
            }
        }
        catch(ZendDbException $e) {

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

    public function deleteDashboard($uuid, $version)
    {
        $query = 'update ox_dashboard set isdeleted=true where uuid=:uuid and version=:version and org_id=:org_id and created_by=:user_id';
        $queryParams = [
            'uuid'      => $uuid,
            'version'   =>$version,
            'org_id'    =>AuthContext::get(AuthConstants::ORG_ID),
            'user_id'   =>AuthContext::get(AuthConstants::USER_ID)
        ];
        try {
            $this->beginTransaction();
            $result = $this->executeQueryWithBindParameters($query, $queryParams);
            $this->commit();
            return $result;
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
//        $query = 'SELECT uuid,name,description FROM ox_dashboard';
//        $queryParams = [];
//        try {
//            $result = $this->executeQueryWithBindParameters($query, $queryParams)->toArray();
//            return $result;
//        }
//        catch (ZendDbException $e) {
//            $this->logger->error('Database exception occurred. Query and parameters:');
//            $this->logger->error($query);
//            $this->logger->error($queryParams);
//            $this->logger->error($e);
//            return 0;
//        }

        $paginateOptions = FilterUtils::paginate($params);
        $where = $paginateOptions['where'];
        $dashboardConditions = 'ox_dashboard.isdeleted <> 1 AND (ox_dashboard.org_id = ' . AuthContext::get(AuthConstants::ORG_ID) . ') AND (ox_dashboard.created_by =  ' . AuthContext::get(AuthConstants::USER_ID) . ' OR ox_dashboard.ispublic = 1)';
        $where .= empty($where) ? "WHERE ${dashboardConditions}" : " AND ${dashboardConditions}";
        $sort = " ORDER BY ".$paginateOptions['sort'];
        $limit = " LIMIT ".$paginateOptions['pageSize']." offset ".$paginateOptions['offset'];

        $countQuery = "SELECT count(id) as 'count' FROM ox_dashboard ${where}";
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

        $query ='SELECT uuid, name, ispublic, description, dashboard_type, IF(ox_dashboard.created_by = '.AuthContext::get(AuthConstants::USER_ID).', true, false) AS is_owner, org_id, isdeleted from ox_dashboard '.$where.' '.$sort.' '.$limit;
        try {
            $resultSet = $this->executeQuerywithParams($query);
        }
        catch (ZendDbException $e) {
            $this->logger->error('Database exception occurred. Query:');
            $this->logger->error($countQuery);
            $this->logger->error($e);
            return 0;
        }
        $result = $resultSet->toArray();

        return array('data' => $result,
                     'total' => $count);
    }
}

