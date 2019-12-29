<?php
/**
 * ServiceTask Callback Api
 */
namespace Oxzion\Service;

use Exception;
use Oxzion\AppDelegate\AppDelegateService;
use Oxzion\Document\DocumentGeneratorImpl;
use Oxzion\EntityNotFoundException;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Service\AbstractService;
use Oxzion\Service\FileService;
use Oxzion\Service\TemplateService;
use Oxzion\ServiceException;
use Oxzion\Utils\RestClient;
use Oxzion\ValidationException;
use Oxzion\Service\WorkflowInstanceService;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;

class CommandService extends AbstractService
{
    /**
     * @var CommandService Instance of Task Service
     */
    private $templateService;
    protected $fileService;
    private $workflowInstanceService;
    /**
     * @ignore __construct
     */

    public function setRestClient($restClient)
    {
        $this->restClient = $restClient;

    }

    public function __construct($config, $dbAdapter, TemplateService $templateService, AppDelegateService $appDelegateService, FileService $fileService, MessageProducer $messageProducer, WorkflowInstanceService $workflowInstanceService,WorkflowService $workflowService)
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
    }

    public function setMessageProducer($messageProducer)
    {
        $this->messageProducer = $messageProducer;
    }

    public function runCommand(&$data,$request)
    {
        $this->logger->info("RUN COMMAND  ------" . json_encode($data));
        //TODO Execute Command Service Methods
        if(isset($data['appId'])){
            $orgId = isset($data['orgId']) ? $this->getIdFromUuid('ox_organization', $data['orgId']) : AuthContext::get(AuthConstants::ORG_ID);
            $select = "SELECT * from ox_app_registry where org_id = :orgId AND app_id = :appId";
            $appId = $this->getIdFromUuid('ox_app', $data['appId']);
            $selectQuery = array("orgId" => $orgId, "appId" => $appId);
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
                if(isset($commandJson['command'])){
                    $command = $commandJson['command'];
                    unset($commandJson['command']);
                    $outputData = array_merge($inputData, $commandJson);
                    $this->logger->info(CommandService::class . print_r($outputData, true));
                    $this->logger->info("COMMAND LIST ------" . $command);
                    $result = $this->processCommand($outputData, $command, $request);
                    if (is_array($result)) {
                        $inputData = $result;
                        $inputData['app_id'] = isset($data['app_id'])?$data['app_id']:null;
                        $inputData['orgId'] = isset($data['orgId'])?$data['orgId']:null;
                        $inputData['workFlowId'] = isset($data['workFlowId'])?$data['workFlowId']:null;
                        $outputData = array_merge($outputData,$result);
                    }
                }
                $this->logger->info(CommandService::class . print_r($inputData, true));
            }
            return $outputData;
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
            default:
                break;
        };
    }

    protected function getRouteData(&$data,$request)
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
        $restClient = new RestClient($headers['Host'],array('exceptions' => false,'timeout' => 0));
        $route = "/app/".$data['app_uuid']."/".$route;
        // print_r($route);exit;
        $response = json_decode($restClient->get($route,array(),$headers),true);
        if($response['status']=='success'){
            $data['result'] = $response['data'];
            if(isset($response['total'])){
                $data['total'] = $response['total'];
            }
        }
    }

    protected function scheduleJob(&$data)
    {
        $this->logger->info("DATA  ------" . json_encode($data));
        if (!isset($data['jobUrl']) || !isset($data['cron']) || !isset($data['url']) || !isset($data['jobName'])) {
            $this->logger->info("jobUrl/Cron/Url/JobName Not Specified");
            throw new EntityNotFoundException("JobUrl or Cron Expression or URL or JobName Not Specified");
        }
        $jobUrl = $data['jobUrl'];
        $cron = $data['cron'];
        $url = $data['url'];

        $this->logger->info("jobUrl - $jobUrl, url -$url");
        unset($data['jobUrl'], $data['cron'], $data['command'], $data['url']);
        $this->logger->info("JOB DATA ------" . json_encode($data));
        $jobPayload = array("job" => array("url" => $this->config['baseUrl'] . $jobUrl, "data" => $data), "schedule" => array("cron" => $cron));
        $this->logger->info("JOB PAYLOAD ------" . print_r($jobPayload, true));
        $response = $this->restClient->postWithHeader($url, $jobPayload);
        $this->logger->info("Response - " . print_r($response, true));
        if (isset($response['body'])) {
            $response = json_decode($response['body'], true);
            $jobName = $data['jobName'];
            unset($data['jobName']);
            $jobData = array("jobId" => $response['JobId'], "jobGroup" => $response['JobGroup']);
            $data[$jobName] = json_encode($jobData);
        }
        $this->logger->info("Schedule JOB DATA - " . print_r($data, true));
        $fileData = json_encode($data);
        $params = array("filedata" => $fileData, "fileUuid" => $data['fileId']);
        $query = "UPDATE ox_file SET data = :filedata where uuid = :fileUuid";
        $this->executeQueryWithBindParameters($query, $params);
        return $response;
    }

    protected function canceljob(&$data)
    {
        try {
            $this->logger->info("DATA  ------" . json_encode($data));
            $url = $data['url'];
            if (!isset($data['jobName'])) {
                throw new EntityNotFoundException("Job Name Not Specified");
            }
            $jobName = $data['jobName'];
            if (!isset($data[$jobName])) {
                throw new EntityNotFoundException("Job " . $jobName . " Not Specified");
            }
            $JobData = json_decode($data[$jobName], true);
            if (!isset($JobData['jobId']) || !isset($JobData['jobGroup'])) {
                throw new EntityNotFoundException("Job Id or Job Group Not Specified");
            }
            $jobPayload = array('jobid' => $JobData['jobId'], 'jobgroup' => $JobData['jobGroup']);
            unset($data['url'], $data[$jobName]);
            $response = $this->restClient->postWithHeader($url, $jobPayload);
        } catch (Exception $e) {
            $this->logger->info("CLEAR JOB RESPONSE ---- " . print_r($e->getMessage(), true));
            if (strpos($e->getMessage(), 'response') !== false) {
                $res = explode('response:', $e->getMessage())[1];
                $res = explode(',"path"', $res)[0];
                $res = $res . "}";
                $res = json_decode($res, true);
                if ($res['status'] == 404) {
                    throw new EntityNotFoundException($res['message']);
                }
            }
            throw $e;
        }
        $this->logger->info("Response - " . print_r($response, true));
        return $response;
    }

    protected function fileSave(&$data)
    {
        try {
            $this->logger->info("File Save Service Start" . print_r($data, true));
            $select = "Select uuid from ox_file where workflow_instance_id=:workflowInstanceId;";
            $selectParams = array("workflowInstanceId" => $data['workflow_instance_id']);
            $result = $this->executeQueryWithBindParameters($select, $selectParams)->toArray();
            if (count($result) == 0) {
                $this->logger->info("File Save ---- Workflow Instance Id Not Found");
                throw new EntityNotFoundException("Workflow Instance Id Not Found");
            }
            $file = $this->fileService->updateFile($data, $result[0]['uuid']);
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
            if(isset($data['appId'])){
                $app_id = $data['appId'];
            }
            $delegate = $data['delegate'];
            unset($data['delegate']);
        } else {
            $this->logger->info("App Id or Delegate Not Specified");
            throw new EntityNotFoundException("App Id or Delegate Not Specified");
        }
        $this->logger->info("DELEGATE ---- " . print_r($delegate, true));
        $this->logger->info("DELEGATE APP ID---- " . print_r($app_id, true));
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
        } else {
            throw new EntityNotFoundException("File Id not provided");
        }

        $result = $this->fileService->getFile($fileId);
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
        if(isset($data['workFlowId'])){
            $params['workFlowId'] = $data['workFlowId'];
        }
        if(isset($data['userId'])){
            $params['userId'] = $data['userId'];
        }
        if(isset($data['appId'])){
            $params['app_id'] = $data['appId'];
        } else {
            if(isset($data['app_id'])){
                $params['app_id'] = $data['app_id'];
            }
        }
        if(isset($data['filter'])){
            $filterParams['filter'] = $data['filter'];
        }
        $fileList = $this->fileService->getFileList($params['app_id'], $params, $filterParams);
        $data['data'] = $fileList['data'];
        return $data;
    }

    protected function getStartForm(&$data)
    {
        if(isset($data['workflow_id']) && isset($data['appId'])){
            $workFlowId = $data['workflow_id'];
            $result = $this->workflowService->getStartForm($data['appId'], $workFlowId);
            $data['data'] = $result;
            return $data;
        } else {
            throw new ServiceException("App and Workflow not Found", "app.for.workflow.not.found");
        }
    }
    protected function verifyUser(&$data){
        if(isset($data['identity_field']) && isset($data['appId']) && isset($data[$data['identity_field']])){
            $select = "SELECT * from ox_wf_user_identifier where identifier_name = :identityField AND app_id = :appId AND identifier = :identifier";
            $selectQuery = array("identityField" => $data['identity_field'], "appId" => $data['app_id'],"identifier"=>$data[$data['identity_field']]);
            $result = $this->executeQuerywithBindParameters($select, $selectQuery)->toArray();
            if(count($result) > 0){
                $data['user_exists'] = '1';
                return $data;
            } else {
                $data['user_exists'] = '0';
                return $data;
            }
        }
    }
}
