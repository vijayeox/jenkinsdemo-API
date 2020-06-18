<?php
/**
 * ServiceTask Callback Api
 */
namespace Oxzion\Service;

use Exception;
use Oxzion\AppDelegate\AppDelegateService;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\Document\DocumentGeneratorImpl;
use Oxzion\EntityNotFoundException;
use Oxzion\Messaging\MessageProducer;
use Oxzion\ServiceException;
use Oxzion\Service\AbstractService;
use Oxzion\Service\FileService;
use Oxzion\Service\JobService;
use Oxzion\Service\TemplateService;
use Oxzion\Service\UserService;
use Oxzion\Service\WorkflowInstanceService;
use Oxzion\Utils\RestClient;
use Oxzion\ValidationException;
use Oxzion\Utils\UuidUtil;
class CommandService extends AbstractService
{
    /**
     * @var CommandService Instance of Task Service
     */
    private $templateService;
    protected $fileService;
    private $workflowInstanceService;
    private $userService;
    private $jobService;
    /**
     * @ignore __construct
     */

    public function setRestClient($restClient)
    {
        $this->restClient = $restClient;

    }

    public function __construct($config, $dbAdapter, TemplateService $templateService, AppDelegateService $appDelegateService, FileService $fileService, JobService $jobService, MessageProducer $messageProducer, WorkflowInstanceService $workflowInstanceService, WorkflowService $workflowService, UserService $userService)
    {
        $this->messageProducer = $messageProducer;
        $this->templateService = $templateService;
        $this->fileService = $fileService;
        $this->appDelegateService = $appDelegateService;
        $this->workflowInstanceService = $workflowInstanceService;
        parent::__construct($config, $dbAdapter);
        $this->fileService = $fileService;
        $this->workflowService = $workflowService;
        $this->restClient = new RestClient($this->config['job']['jobUrl'], array());
        $this->userService = $userService;
        $this->jobService = $jobService;
    }

    public function setMessageProducer($messageProducer)
    {
        $this->messageProducer = $messageProducer;
    }

    public function runCommand(&$data, $request)
    {
        $this->logger->info("RUN COMMAND  ------" . json_encode($data));
        //TODO Execute Command Service Methods
        if (isset($data['appId'])) {
            $orgId = isset($data['orgId']) && !empty($data['orgId']) ? $this->getIdFromUuid('ox_organization', $data['orgId']) : AuthContext::get(AuthConstants::ORG_ID);
            $select = "SELECT * from ox_app_registry where org_id = :orgId AND app_id = :appId";
            $appId = $this->getIdFromUuid('ox_app', $data['appId']);
            $selectQuery = array("orgId" => $orgId, "appId" => $appId);
            $this->logger->info("Executing query $select with params - ".json_encode($selectQuery));
            $result = $this->executeQuerywithBindParameters($select, $selectQuery)->toArray();
            if (count($result) == 0) {
                throw new ServiceException("App Does not belong to the org", "app.fororgnot.found");
            }
            $data['app_id'] = $appId;
        }
        if (isset($data['command'])) {
            $this->logger->info("COMMAND  ------" . print_r($data['command'], true));
            $command = $data['command'];
            unset($data['command']);
            return $this->processCommand($data, $command, $request);
        } else if (isset($data['commands'])) {
            $this->logger->info("Command Service - Comamnds");
            $commands = $data['commands'];
            $this->logger->info("COMMAND LIST ------" . print_r($commands, true));
            unset($data['commands']);
            $inputData = $data;
            foreach ($commands as $index => $value) {
                $isArray = is_array($value);
                if (!$isArray) {
                    $commandJson = json_decode($value, true);
                } else {
                    $commandJson = $value;
                }
                $this->logger->info("Command JSON------", print_r($commandJson, true));
                if (isset($commandJson['command'])) {
                    $this->logger->info("COMMAND------", print_r($commandJson, true));
                    $command = $commandJson['command'];
                    unset($commandJson['command']);
                    $outputData = array_merge($inputData, $commandJson);
                    $this->logger->info(CommandService::class . print_r($outputData, true));
                    $this->logger->info("COMMAND LIST ------" . $command);
                    $result = $this->processCommand($outputData, $command, $request);
                    $this->logger->info("Process Command Result" . print_r($result, true));
                    if (is_array($result)) {
                        $inputData = $result;
                        $inputData['app_id'] = isset($data['app_id']) ? $data['app_id'] : null;
                        $inputData['orgId'] = isset($data['orgId']) ? $data['orgId'] : null;
                        $inputData['workFlowId'] = isset($data['workFlowId']) ? $data['workFlowId'] : null;
                        $outputData = array_merge($outputData, $result);
                    }
                }
                $this->logger->info(CommandService::class . print_r($inputData, true));
            }
            if(isset($outputData)){
                $this->logger->info("Process Command Data".print_r($outputData,true));
                return $outputData;
            }
        }
        return 1;
    }

    protected function processCommand(&$data, $command, $request)
    {
        $this->logger->info("PROCESS COMMAND : command --- " . $command);
        switch ($command) {
            case 'mail':
                $this->logger->info("SEND MAIL");
                return $this->sendMail($data);
                break;
            case 'route':
                $this->logger->info("Get Route Data");
                $this->getRouteData($data, $request);
                break;
            case 'schedule':
                $this->logger->info("SCHEDULE JOB");
                return $this->scheduleJob($data);
                break;
            case 'cancelJob':
                $this->logger->info("CLEAR JOB");
                return $this->cancelJob($data);
                break;
            case 'delegate':
                $this->logger->info("DELEGATE");
                return $this->executeDelegate($data);
                break;
            case 'pdf':
                $this->logger->info("PDF");
                return $this->generatePDF($data);
                break;
            case 'fileSave':
                $this->logger->info("FILE SAVE");
                return $this->fileSave($data);
                break;
            case 'file':
                $this->logger->info("FILE DATA");
                return $this->extractFileData($data);
                break;
            case 'filelist':
                $this->logger->info("FILE LIST");
                return $this->getFileWithParams($data);
                break;
            case 'startform':
                $this->logger->info("START FORM");
                return $this->getStartForm($data);
                break;
            case 'verify_user':
                $this->logger->info("Verify User");
                return $this->verifyUser($data);
                break;
            case 'get_user':
                $this->logger->info("Get User Identifiers By UserId");
                return $this->getUserIdentifier($data);
                break;
            case 'getuserdata':
                $this->logger->info("GET User Data");
                return $this->getUserData($data);
                break;
            case 'getuserlist':
                $this->logger->info("GET User LIST");
                return $this->getUserList($data);
                break;
            case 'startWorkflow':
                $this->logger->info("START WORKFLOW");
                return $this->startWorkflow($data);
                break;
            case 'submitActivity':
                $this->logger->info("SUBMIT ACTIVITY");
                return $this->submitActivity($data);
                break;
            case 'processFileData':
                $this->logger->info("Process File Data");
                return $this->processFileData($data);
                break;
            case 'submitWorkflow':
                $this->logger->info("SUBMIT WORKFLOW");
                return $this->submitWorkflow($data);
                break;
            case 'claimForm':
                $this->logger->info("Claim Form");
                return $this->claimActivityInstance($data);
                break;
            case 'activityInstanceForm':
                $this->logger->info("Activity Instance Form");
                return $this->getActivityInstanceForm($data);
                break;
            default:
                break;
        };
    }

    private function enqueue($data,$topic, $queue = null){
        $this->logger->info("ENQUEUE ------ DATA IS:  " . print_r($data, true));
        $this->logger->info("ENQUEUE ------ TOPIC IS:  " . print_r($topic, true));
        $orgId = AuthContext::get(AuthConstants::ORG_UUID);
        $orgIdAdded = false;
        if(isset($orgId)){
            $data['orgId'] = $orgId;
            $orgIdAdded = true;
        }
        if($topic){
            $this->logger->info("ENQUEUE ------ send topic ");
            $this->messageProducer->sendTopic(json_encode($data), $topic);
        }else if($queue){
            $this->logger->info("ENQUEUE ------ sendqueue ");
            $this->messageProducer->sendQueue(json_encode($data), $queue);
        }
        if($orgIdAdded){
            unset($data['orgId']);
        }
        return $data;
    }

    protected function getRouteData(&$data, $request)
    {
        $this->logger->info("EXECUTE DELEGATE ---- " . print_r($data, true));
        if (isset($data['app_id']) && isset($data['route'])) {
            $app_id = $data['app_id'];
        } else {
            $this->logger->info("App Id or Delegate Not Specified");
            throw new EntityNotFoundException("App Id or Delegate Not Specified");
        }
        $this->logger->info("DELEGATE ---- " . print_r($route, true));
        $this->logger->info("DELEGATE APP ID---- " . print_r($app_id, true));
        $headers = $request->getHeaders()->toArray();
        $restClient = new RestClient($headers['Host'], array('exceptions' => false, 'timeout' => 0));
        $route = "/app/" . $data['app_uuid'] . "/" . $route;
        $response = json_decode($restClient->get($route, array(), $headers), true);
        if ($response['status'] == 'success') {
            $data['result'] = $response['data'];
            if (isset($response['total'])) {
                $data['total'] = $response['total'];
            }
        }
    }

    protected function scheduleJob(&$data)
    {
        $this->logger->info("DATA  ------" . json_encode($data));
        if (!isset($data['jobUrl']) || !isset($data['cron']) || !isset($data['jobName'])) {
            $this->logger->info("jobUrl/Cron/JobName Not Specified");
            throw new EntityNotFoundException("JobUrl or Cron Expression or JobName Not Specified");
        }
        
        try
        {
            $jobUrl = $data['jobUrl'];
            $cron = $data['cron'];
            $jobGroup = $data['jobName'];
            if(isset($data['fileId'])){
                $jobName = $data['fileId'];
            }else{
                $jobName = $data['uuid'];
            }            
            $appId = $data['app_id'];
            $orgId = isset($data['org_id']) ? $data['org_id'] : AuthContext::get(AuthConstants::ORG_ID);
            unset($data['jobUrl'], $data['cron'], $data['command'], $data['url']);
            $this->logger->info("JOB DATA ------" . json_encode($data));
            $jobPayload = array("job" => array("url" => $this->config['internalBaseUrl'] . $jobUrl, "data" => $data), "schedule" => array("cron" => $cron));
            $this->logger->info("JOB PAYLOAD ------" . print_r($jobPayload, true));
            $response = $this->jobService->scheduleNewJob($jobName, $jobGroup, $jobPayload, $cron, $appId, $orgId);
        } catch (Exception $e) {
            $this->logger->error("Job Schedule ---- Exception - ".$e->getMessage() , $e);
        }
        if (isset($response) && isset($response['job_id'])) {
            $jobData = array("jobId" => $response['job_id'], "jobGroup" => $response['group_name']);
            $data[$jobGroup] = json_encode($jobData);
        }
        $this->logger->info("Schedule JOB DATA - " . print_r($data, true));
        $this->fileService->updateFile($data, $data['fileId']);
        return $data;
    }

    protected function canceljob(&$data)
    {
        try 
        {
            $this->logger->info("DATA  ------" . json_encode($data));
            if (!isset($data['jobName'])) {
                $this->logger->warn("Job Name not specified, so job not cancelled");
                return $data;
            }
            $jobName = $data['jobName'];
            if (!isset($data[$jobName])) {
                $this->logger->warn("Job Details not found, so job not cancelled");
                return $data;
            }
            $JobData = (is_array($data[$jobName]) ? $data[$jobName] : json_decode($data[$jobName], true));
            if (!isset($JobData['jobId']) || !isset($JobData['jobGroup'])) {
                $this->logger->warn("Job Id or Job Group Not Specified, so job not cancelled");
                return $data;
            }
            $appId = $data['app_id'];
            $groupName = $JobData['jobGroup'];
            $jobPayload = array('jobid' => $JobData['jobId'], 'jobgroup' => $JobData['jobGroup']);
            $data[$jobName] = array();
            $response = $this->jobService->cancelJobId($JobData['jobId'], $appId, $groupName);
            $this->logger->info("Response - " . print_r($response, true));
        } 
        catch (Exception $e) {
            $this->logger->info("CLEAR JOB RESPONSE ---- " . print_r($e->getMessage(), true));
            if (strpos($e->getMessage(), 'response') !== false) {
                $res = explode('response:', $e->getMessage())[1];
                $res = explode(',"path"', $res)[0];
                $res = $res . "}";
                $res = json_decode($res, true);
                if ($res['status'] == 404) {
                    $this->logger->warn($res['message']."JOB NOT FOUND ---- " . $e->getMessage(), $e);
                }
            }
            $this->logger->warn("JOB ---- " . $e->getMessage(), $e);
        }
        $this->logger->info("Cancel Job Data - " . print_r($data, true));
        return $data;
    }

    protected function fileSave(&$data)
    {
        try {
            $this->logger->info("File Save Service Start" . print_r($data, true));
            if(isset($data['workflow_instance_id'])){
                $select = "Select ox_file.uuid from ox_file join ox_workflow_instance on ox_workflow_instance.file_id = ox_file.id where ox_workflow_instance.id=:workflowInstanceId;";
                $selectParams = array("workflowInstanceId" => $data['workflow_instance_id']);
                $result = $this->executeQueryWithBindParameters($select, $selectParams)->toArray();
                if (count($result) == 0) {
                    $this->logger->info("File Save ---- Workflow Instance Id Not Found");
                    throw new EntityNotFoundException("Workflow Instance Id Not Found");
                }
                $file = $this->fileService->updateFile($data, $result[0]['uuid']);
            }else if(isset($data['fileId'])){
                $file = $this->fileService->updateFile($data, $data['fileId']);
            }else if(isset($data['uuid'])){
                $file = $this->fileService->updateFile($data, $data['uuid']);
            }else{
                $file = $this->fileService->createFile($data);
            }
            return $data;
        } catch (Exception $e) {

            $this->logger->info("File Save ---- Exception" . print_r($e->getMessage(), true));
            throw $e;
        }
    }

    protected function executeDelegate(&$data)
    {
        $this->logger->info("EXECUTE DELEGATE ---- " . print_r($data, true));
        if (isset($data['app_id']) && isset($data['delegate'])) {
            $app_id = $data['app_id'];
            if (isset($data['appId'])) {
                $app_id = $data['appId'];
            }
            $delegate = $data['delegate'];
            unset($data['delegate']);
        } else {
            $this->logger->info("App Id or Delegate Not Specified");
            throw new EntityNotFoundException("App Id or Delegate Not Specified");
        }
        if(isset($data['async']) && $data['async'] == 'true'){            
            unset($data['async']);
            $temp = $data;
            $temp['commands'] = array('command' => 'delegate', 'delegate' => $delegate);
            $this->logger->info("EXECUTE DELEGATE ---- enqueue");
            $this->enqueue($temp, 'COMMANDS');
        }
        $this->logger->info("DELEGATE ---- " . print_r($delegate, true));
        $this->logger->info("DELEGATE APP ID---- " . print_r($app_id, true));
        $this->logger->info("DELEGATE DATA ---- " . print_r($data, true));
        $response = $this->appDelegateService->execute($app_id, $delegate, $data);
        return $response;
    }

    protected function sendMail($params)
    {
        if (isset($params)) {
            $orgId = isset($params['orgId']) ? $params['orgId'] : 1;
            $template = isset($params['template']) ? $params['template'] : 0;
            if ($template) {
                $body = $this->templateService->getContent($template, $params);
            } else {
                if (isset($params['body'])) {
                    $body = $params['body'];
                } else {
                    $body = null;
                }
            }
            $errors = array();
            if (isset($params['to'])) {
                $recepients = $params['to'];
            } else {
                $errors['to'] = 'required';
            }
            if (isset($params['subject'])) {
                $subject = $params['subject'];
            } else {
                $errors['subject'] = 'required';
            }
            if (isset($params['attachments'])) {
                $attachments = $params['attachments'];
            }
            if (count($errors) > (int) 0) {
                $validationException = new ValidationException();
                $validationException->setErrors($errors);
                throw $validationException;
            }
            $payload = json_encode(array(
                'to' => $recepients,
                'subject' => $subject,
                'body' => $body,
                'attachments' => isset($attachments) ? $attachments : null,
            ));
            $this->logger->info("Payload for mail -> $payload");
            $this->messageProducer->sendQueue($payload, 'mail');
            return 1;
        } else {
            return;
        }
    }

    protected function generatePDF(&$params)
    {
        if (isset($params)) {
            $orgId = isset($params['orgid']) ? $params['orgid'] : 1;
            $template = isset($params['template']) ? $params['template'] : 0;
            if ($template) {
                $body = $this->templateService->getContent($template, $params);

            } else {
                if (isset($params['body'])) {
                    $body = $params['body'];
                } else {
                    $body = null;
                }
            }
            if (!$body) {
                return;
            }
            if (isset($params['options'])) {
                $options = $params['options'];
            } else {
                $options = null;
            }
            if (isset($params['destination'])) {
                $destination = $params['destination'];
            } else {
                return;
            }
            $generatePdf = new DocumentGeneratorImpl();
            $params['document_path'] = $generatePdf->generateDocument($body, $destination, $options);
            return $params;
        } else {
            return;
        }
    }

    protected function extractFileData(&$data)
    {
        $this->logger->info("File Data  ------" . print_r($data, true));
        if (isset($data['fileId_fieldName']) && isset($data[$data['fileId_fieldName']])) {
            $fileId = $data[$data['fileId_fieldName']];
        } else if (isset($data['fileId'])) {
            $fileId = $data['fileId'];
        } else if(isset($data['workflowInstanceId'])){
            $file = $this->fileService->getFileByWorkflowInstanceId($data['workflowInstanceId']);
            if(isset($file)){
                $data['data'] = $file['data'];
                $data['fileId'] = $file['fileId'];
                return $data;
            } else {
                throw new EntityNotFoundException("File not Found");
            }
        } else {
            throw new EntityNotFoundException("File Id not provided");
        }

        $result = $this->fileService->getFile($fileId, true);
        $this->logger->info("EXTRACT FILE DATA result" . print_r($result, true));
        if ($result == 0) {
            throw new EntityNotFoundException("File " . $fileId . " not found");
        }
        $data['data'] = $result['data'];
        return $data;
    }

    protected function getFileWithParams(&$data)
    {
        $params = array();
        $filterParams = array();
        if (isset($data['workflowId'])) {
            $params['workflowId'] = $data['workflowId'];
        }
        if (isset($data['orgId'])) {
            $params['orgId'] = $data['orgId'];
        }
        if (isset($data['userId'])) {
            $params['userId'] = $data['userId'];
        }
        if (isset($data['appId']) && (UuidUtil::isValidUuid($data['appId']))) {
            $params['app_id'] = $data['appId'];
        } else if (isset($data['app_id'])) {
            $params['app_id'] = $data['app_id'];
        }
        if (isset($data['workflowStatus'])) {
            $params['workflowStatus'] = $data['workflowStatus'];
        }
        if (isset($data['filter'])) {
            $filterParams['filter'] = $data['filter'];
        }
        $fileList = $this->fileService->getFileList($params['app_id'], $params, $filterParams);
        $data['data'] = $fileList['data'];
        return $data;
    }

    protected function getStartForm(&$data)
    {
        if (isset($data['workflow_id']) && isset($data['appId'])) {
            $workFlowId = $data['workflow_id'];
            $result = $this->workflowService->getStartForm($data['appId'], $workFlowId);
            // print_r($result);exit;
            $data['template'] = $result['template'];
            $data['formName'] = $result['formName'];
            $data['id'] = $result['id'];
            return $data;
        } else {
            throw new ServiceException("App and Workflow not Found", "app.for.workflow.not.found");
        }
    }

    protected function verifyUser(&$data)
    {
        if (isset($data['identifier_field']) && isset($data['appId']) && isset($data[$data['identifier_field']])) {
            $appId = UuidUtil::isValidUuid($data['appId'])?$this->getIdFromUuid('ox_app',$data['appId']):$data['appId'];
            $select = "SELECT * from ox_wf_user_identifier where identifier_name = :identityField AND app_id = :appId AND identifier = :identifier";
            $selectQuery = array("identityField" => $data['identifier_field'], "appId" => $appId, "identifier" => $data[$data['identifier_field']]);
            $result = $this->executeQuerywithBindParameters($select, $selectQuery)->toArray();
            if (count($result) > 0) {
                $data['user_exists'] = '1';
                return $data;
            } else {
                $data['user_exists'] = '0';
                return $data;
            }
        }
        if (isset($data['email'])) {
            $select = "SELECT * from ox_user where email = :email";
            $selectQuery = array("email" => $data['email']);
            $result = $this->executeQuerywithBindParameters($select, $selectQuery)->toArray();
            if (count($result) > 0) {
                $data['user_exists'] = '1';
                return $data;
            }
        }
    }
    protected function getUserIdentifier(&$data)
    {
        $userId = isset($data['user_id']) ? $this->getIdFromUuid('ox_user', $data['user_id']) : AuthContext::get(AuthConstants::USER_ID);
        if (isset($data['appId']) && isset($userId)) {
            $select = "SELECT * from ox_wf_user_identifier where app_id = :appId AND user_id = :user_id";
            $selectQuery = array("appId" => $data['app_id'], "user_id" => $userId);
            $result = $this->executeQuerywithBindParameters($select, $selectQuery)->toArray();
            if (count($result) > 0) {
                foreach ($result as $key => $value) {
                    $data[$value['identifier_name']] = $value['identifier'];
                }
                return $data;
            } else {
                $data['user_exists'] = '0';
                return $data;
            }
        } else {
            return $data;
        }
    }

    public function getUserData(&$data)
    {
        $userId = isset($data['user_id']) ? $this->getIdFromUuid('ox_user', $data['user_id']) : AuthContext::get(AuthConstants::USER_ID);
        if (isset($data['appId']) && isset($userId)) {
            $select = "SELECT * from ox_wf_user_identifier where app_id = :appId AND user_id = :user_id";
            $selectQuery = array("appId" => $data['app_id'], "user_id" => $userId);
            $result = $this->executeQuerywithBindParameters($select, $selectQuery)->toArray();
            if (count($result) > 0) {
                $result = $this->userService->getUserWithMinimumDetails($userId);
                unset($result['uuid']);
                unset($result['username']);
                $data = array_merge($data, $result);
                return $data;
            } else {
                $data['user_exists'] = '0';
                return $data;
            }
        } else {
            return $data;
        }
    }

    protected function getUserList(&$data)
    {
        if (isset($data['appId'])) {
            $data['userlist'] = $this->userService->getUsersList($data['appId'], $data);
        }
    }

    protected function startWorkflow(&$data)
    {
        $startWorkflow = $this->workflowInstanceService->startWorkflow($data);
        return $startWorkflow;
    }

    protected function submitActivity(&$data)
    {
        $submitActivity = $this->workflowInstanceService->submitActivity($data);
        return $submitActivity;
    }

    protected function submitWorkflow(&$data)
    {
        if(isset($data['activityInstanceId'])){
           return $this->submitActivity($data);
        }
        return $this->startWorkflow($data);
    }

// verify
    protected function processFileData(&$data){
        $this->logger->info("Process File Data--");
        if(isset($data['data'])){
            $this->logger->info("Process File Data --");
            $fileData = $data['data'];
            if(isset($fileData['uuid'])){
                unset($fileData['uuid']);
            }
            unset($data['data']);
            $processedData = array_merge($data,$fileData);
            $this->logger->info("Processed Data".print_r($processedData,true));
            foreach ($processedData as $key => $value) {
                if(is_array($value)){
                    $processedData[$key] = json_encode($value);
                }
            }
            return $processedData;
        } else {
            return $data;
        }
    }

    protected function claimActivityInstance(&$data){
        $this->logger->info("claimForm");
        if(isset($data['workflowInstanceId']) && isset($data['activityInstanceId'])){
           $result = $this->workflowInstanceService->claimActivityInstance($data);    
        }
    }

    protected function getActivityInstanceForm(&$data){
        $this->logger->info("InstanceForm");
        if(isset($data['workflowInstanceId']) && isset($data['activityInstanceId'])){
            $result = $this->workflowInstanceService->getActivityInstanceForm($data); 
            return $result;   
        }
    }
}