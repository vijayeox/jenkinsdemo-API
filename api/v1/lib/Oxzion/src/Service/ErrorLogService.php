<?php
namespace Oxzion\Service;

use Oxzion\Model\ErrorLogTable;
use Oxzion\Model\ErrorLog;
use Oxzion\Auth\AuthContext;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Auth\AuthConstants;
use Oxzion\Service\AbstractService;
use Oxzion\Service\UserCacheService;
use Oxzion\Service\UserService;
use Oxzion\ValidationException;
use Zend\Db\Sql\Expression;
use Oxzion\Workflow\WorkFlowFactory;
use Oxzion\ServiceException;
use Oxzion\Utils\RestClient;
use Exception;

class ErrorLogService extends AbstractService
{
    private $messageProducer;
    private $cacheService;
    private $workFlowFactory;
    public function __construct($config, $dbAdapter, ErrorLogTable $table,UserCacheService $userCacheService,WorkFlowFactory $workFlowFactory)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
        $this->messageProducer = MessageProducer::getInstance($config,$this);
        $this->cacheService = $userCacheService;
        $this->restClient = new RestClient(null);
        $this->workFlowFactory = $workFlowFactory;
        $this->incidentManager = $this->workFlowFactory->getIncidentManager();
    }
    private function getAuthHeader($userId)
    {

        $headers = array("Authorization" => "Bearer $this->authToken");
        return $headers;
    }
    public function saveError($type='untraced',$errorTrace=null,$payload = null,$params = null,$appUUid = null)
    {
        $this->logger->info("Entering to saving Error method in ErrorLogService");
        $errorLog = new ErrorLog();
        if(AuthContext::get(AuthConstants::USER_ID) !== null){
            $params = (null !== json_decode($params,true))?json_decode($params,true):$params;
            $params['user_id'] = AuthContext::get(AuthConstants::USER_ID);
            if(!is_string($params)){
                $params = json_encode($params);
            }
        }
        if(isset($appUUid)){
            if ($app = $this->getIdFromUuid('ox_app', $appUUid)) {
                $appId = $app;
            } else {
                $appId = null;
            }
        } else {
            $appId = null;
        }
        $errorLog->exchangeArray(array('error_type'=>$type,'error_trace'=>$errorTrace,'payload'=>$payload,'params'=>$params,'date_created'=>date('Y-m-d H:i:s'),'app_id'=>$appId));
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->save($errorLog);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
            $data = $errorLog->toArray();
            $data['id'] = $this->table->getLastInsertValue();
            $this->commit();
            return $data;
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            $this->rollback();
            throw $e;
        }
        return $count;
    }
    public function getErrorList($filterParams=array(),$appUUid = null){
        $where = "";
        $pageSize = 20;
        $offset = 0;
        $sort = "date_created";
        $select = "SELECT id,error_type,error_trace,payload,date_created,params";
        $from = " FROM `ox_error_log` ";
        $cntQuery ="SELECT count(id) as error_count ".$from;
        if(count($filterParams) > 0 || sizeof($filterParams) > 0){
            if(isset($filterParams['filter'])){
                $filterArray = json_decode($filterParams['filter'],true);
                if(isset($filterArray[0]['filter'])){
                    $filterlogic = isset($filterArray[0]['filter']['logic']) ? $filterArray[0]['filter']['logic'] : "AND" ;
                    $filterList = $filterArray[0]['filter']['filters'];
                    $where = " WHERE ".FilterUtils::filterArray($filterList,$filterlogic,self::$userField);
                }
                if(isset($filterArray[0]['sort']) && count($filterArray[0]['sort']) > 0){
                    $sort = $filterArray[0]['sort'];
                    $sort = FilterUtils::sortArray($sort,self::$userField);
                }
                if(isset($filterArray[0]['take'])){
                    $pageSize = $filterArray[0]['take'];  
                }
                if(isset($filterArray[0]['take'])){
                    $offset = $filterArray[0]['skip'];
                }
            }
        }
        $sort = " ORDER BY ".$sort;
        $limit = " LIMIT ".$pageSize." offset ".$offset;
        if(isset($appUUid)){
            $appId = $this->getIdFromUuid('ox_app', $appUUid);
            if(!isset($where)){
                $where = " AND ";
            } else {
                $where = " WHERE  ";
            }
            $where .= "app_id = $appId";
        } else {
            if(!isset($where)){
                $where = " AND ";
            } else {
                $where = " WHERE  ";
            }
            $where .= "app_id is NULL";
        }
        $resultSet = $this->executeQuerywithParams($cntQuery.$where);
        $count = $resultSet->toArray()[0]['error_count'];
        $query = $select." ".$from." ".$where." ".$sort." ".$limit;
        $resultSet = $this->executeQuerywithParams($query);
        $result = $resultSet->toArray();
        return array('data' => $result,'total' => $count);
    }
    public function resolveWorkflowIncident($params)
    {
        $this->logger->info("Resolve Workflow Instance");
        try {
            $data = json_decode($params,true);
            $result = $this->incidentManager->resolveIncident($data['incidentId']);
            return $result;
        } catch (Exception $e){
            // print_r($e->getMessage());exit;
            throw new ServiceException("Incident resolution failed","incident.resolution.failed");
        }
    }

    public function retryError($id,$errorRequest,$appUUid = null){
        if(isset($appUUid)){
            if ($app = $this->getIdFromUuid('ox_app', $appUUid)) {
                $appId = $app;
            } else {
                throw new Exception("Invalid AppId $appUUid passed");
            }
        }
        $obj = $this->table->get($id, array());
        $error = $obj->toArray();
        if(is_array($error)){
            if(isset($error['params'])){
                $params = (null !== json_decode($error['params'],true))?json_decode($error['params'],true):$error['params'];
                switch ($error['error_type']) {
                    case 'activemq_topic':
                            $this->messageProducer->sendTopic($error['payload'],$params['to']);
                        break;
                    case 'activemq_queue':
                            $this->messageProducer->sendQueue($error['payload'],$params['to']);
                        break;
                    case 'form':
                        if(isset($error['params'])){
                            if(isset($params['cache_id'])){
                                $cacheId = $params['cache_id'];
                                $formPayload = $this->cacheService->getCache($cacheId);
                                try{
                                    $response = $this->restClient->postWithHeader($this->config['baseUrl'].$params['route'], $error['params'],array("Authorization"=>$errorRequest->getHeaders()->get('Authorization')->getFieldValue()));
                                } catch (Exception $e){
                                    $storeError = $this->saveError($error['error_type'],$e->getTraceAsString(),$error['payload'],$error['params']);
                                }  
                            }
                        }
                        break;
                    case 'schedule_job':
                        if(isset($error['params'])){
                            $response = $this->restClient->postWithHeader($this->config['job']['jobUrl']."setupjob", $error['params']);
                        }
                        break;
                    case 'serviceTaskFailure':
                        if(isset($error['params'])){
                            $response = $this->resolveWorkflowIncident($error['params']);
                        }
                        break;
                    default:
                        break;
                }
            }
        }
    }
}