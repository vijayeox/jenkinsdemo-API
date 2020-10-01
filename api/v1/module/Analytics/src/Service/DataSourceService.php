<?php
namespace Analytics\Service;

use Oxzion\Service\AbstractService;
use Analytics\Model\DataSourceTable;
use Analytics\Model\DataSource;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\ValidationException;
use Zend\Db\Sql\Expression;
use Oxzion\Utils\FilterUtils;
use Ramsey\Uuid\Uuid;
use Exception;

use function GuzzleHttp\json_decode;

class DataSourceService extends AbstractService
{

    private $table;
    private $analyticEngines;

    public function __construct($config, $dbAdapter, DataSourceTable $table, array $analyticEngines)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
        $this->analyticEngines = $analyticEngines;
    }

    public function createDataSource($data)
    {
        $dataSource = new DataSource($this->table);
        $data['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
        $dataSource->setForeignKey('org_id', AuthContext::get(AuthConstants::ORG_ID));
        $dataSource->assign($data);
        try {
            $this->beginTransaction();
            $dataSource->save();
            $this->commit();
        }
        catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
        return $dataSource->getGenerated();
    }

    public function updateDataSource($uuid, $data)
    {
        $dataSource = new DataSource($this->table);
        $dataSource->loadByUuid($uuid);
        $dataSource->assign($data);
        try {
            $this->beginTransaction();
            $dataSource->save();
            $this->commit();
        }
        catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
        return $dataSource->getGenerated();
    }

    public function deleteDataSource($uuid,$version)
    {
        $dataSource = new DataSource($this->table);
        $dataSource->loadByUuid($uuid);
        $dataSource->assign([
            'version' => $version, 
            'isdeleted' => 1
        ]);
        try {
            $this->beginTransaction();
            $dataSource->save();
            $this->commit();
        }
        catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
    }

    public function getDataSource($uuid)
    {
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_datasource')
            ->columns(array('name','type','configuration', 'is_owner' => (new Expression('IF(created_by = '.AuthContext::get(AuthConstants::USER_ID).', "true", "false")')),'org_id','version','isdeleted','uuid'))
            ->where(array('ox_datasource.uuid' => $uuid,'org_id' => AuthContext::get(AuthConstants::ORG_ID),'isdeleted' => 0));
        $response = $this->executeQuery($select)->toArray();
        if (count($response) == 0) {
            return 0;
        }
        return $response[0];
    }

    public function getDataSourceList($params = null)
    {
        $paginateOptions = FilterUtils::paginateLikeKendo($params);
        $where = $paginateOptions['where'];
        if(isset($params['show_deleted']) && $params['show_deleted']==true){
            $where .= empty($where) ? "WHERE org_id =".AuthContext::get(AuthConstants::ORG_ID) : " AND org_id =".AuthContext::get(AuthConstants::ORG_ID);
        }
        else{
            $where .= empty($where) ? "WHERE isdeleted <> 1 AND org_id =".AuthContext::get(AuthConstants::ORG_ID) : " AND isdeleted <> 1 AND org_id =".AuthContext::get(AuthConstants::ORG_ID);
        }
        $sort = $paginateOptions['sort'] ? " ORDER BY ".$paginateOptions['sort'] : '';
        $limit = " LIMIT ".$paginateOptions['pageSize']." offset ".$paginateOptions['offset'];

        $cntQuery ="SELECT count(id) as 'count' FROM `ox_datasource` ";
        $resultSet = $this->executeQuerywithParams($cntQuery.$where);
        $count=$resultSet->toArray()[0]['count'];

        if(isset($params['show_deleted']) && $params['show_deleted']==true){
            $query ="SELECT name,type,configuration,version,IF(created_by = ".AuthContext::get(AuthConstants::USER_ID).", 'true', 'false') as is_owner,version,org_id,isdeleted,uuid FROM `ox_datasource`".$where." ".$sort." ".$limit;
        }
        else{
            $query ="SELECT name,type,configuration,version,IF(created_by = ".AuthContext::get(AuthConstants::USER_ID).", 'true', 'false') as is_owner,version,org_id,uuid FROM `ox_datasource`".$where." ".$sort." ".$limit;
        }
        $resultSet = $this->executeQuerywithParams($query);
        $result = $resultSet->toArray();
        foreach ($result as $key => $value) {
            if(isset($result[$key]['configuration']) && (!empty($result[$key]['configuration']))){
                $result[$key]['configuration'] = json_decode($result[$key]['configuration']);
                unset($result[$key]['id']);
            }
        }
        return array('data' => $result,
                 'total' => $count);
    }

    public function getAnalyticsEngine($uuid) {
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_datasource')
            ->columns(array('id','name','type','configuration','org_id','isdeleted','uuid'))
            ->where(array('uuid' => $uuid,'org_id' => AuthContext::get(AuthConstants::ORG_ID),'isdeleted' => 0));
        $response = $this->executeQuery($select)->toArray();
        if (count($response) == 0) {
            throw new Exception("Error Processing Request", 1);
        }
        $type = $response[0]['type'];
        $dsConfig = json_decode($response[0]['configuration'],1);
        $type = strtoupper($type);
        try{
            switch($type) {
                case 'ELASTIC':
                case 'ELASTICSEARCH':
                    $elasticConfig['elasticsearch'] = $dsConfig['data'];
                    $analyticsObject = $this->analyticEngines["ELASTIC"];
                    $analyticsObject->setConfig($elasticConfig);
                    break;
                case 'MYSQL':
                    $dsConfig = $dsConfig['data'];
                    $analyticsObject = $this->analyticEngines[$type];
                    $analyticsObject->setConfig($dsConfig);
                    break;
                case 'POSTGRES':
                case 'POSTGRESQL':
                    $dsConfig = $dsConfig['data'];
                    $analyticsObject = $this->analyticEngines["POSTGRES"];
                    $analyticsObject->setConfig($dsConfig);
                    break;
                case 'QUICKBOOKS':
                    $dsConfig = $dsConfig['data'];
                    $dsConfig['dsid']=$response[0]['id'];
                    $analyticsObject = $this->analyticEngines[$type];
                    $analyticsObject->setConfig($dsConfig);
                    break;
            }
        }
        catch(Exception $e){
            throw $e;
        }
        return $analyticsObject;
    }
}
