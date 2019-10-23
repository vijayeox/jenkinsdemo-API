<?php
/**
* ServiceTask Callback Api
*/
namespace Workflow\Service;

use Oxzion\Service\AbstractService;
use Workflow\Model\ActivityInstanceTable;
use Oxzion\Model\ActivityInstance;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\ValidationException;
use Zend\Db\Sql\Expression;
use Oxzion\Service\TemplateService;
use Exception;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Document\DocumentGeneratorImpl;
use Oxzion\AppDelegate\AppDelegateService;
use Oxzion\Utils\RestClient;
use Oxzion\EntityNotFoundException;
use Oxzion\Service\FileService;

class ServiceTaskService extends AbstractService
{
    /**
    * @var ServiceTaskService Instance of Task Service
    */
    private $templateService;
    protected $fileService;
    /**
    * @ignore __construct
    */

    public function setRestClient($restClient)
    {
        $this->restClient = $restClient;

    }

    public function __construct($config, $dbAdapter, Logger $log, TemplateService $templateService,AppDelegateService $appDelegateService,FileService $fileService)
    {
        $this->messageProducer = MessageProducer::getInstance();
        $this->templateService = $templateService;
        $this->fileService = $fileService;
        $this->appDelegateService = $appDelegateService;
        parent::__construct($config, $dbAdapter);
        $this->fileService = $fileService;
        $this->restClient = new RestClient($this->config['job']['jobUrl'],array());
    }

    public function setMessageProducer($messageProducer)
    {
        $this->messageProducer = $messageProducer;
    }

    


    public function runCommand($data)
    {  
        //TODO Execute Service Task Methods
        if (isset($data['variables']) && isset($data['variables']['command'])) {
            $command = $data['variables']['command'];
            unset($data['variables']['command']);
            return $this->processCommand($command,$data['variables']);
        }else if(isset($data['variables']) && isset($data['variables']['commands'])){
            $commands = $data['variables']['commands'];
            unset($data['variables']['commands']);
            $inputData = $data['variables'];
            foreach($commands as $index => $value){
                $commandJson = json_decode($value,true);
                $command = $commandJson['command'];
                unset($commandJson['command']);
                $variables = array_merge($inputData, $commandJson);
                $result = $this->processCommand($command, $variables);
                if(is_array($result)){
                    $inputData = $result;
                }
            }
            return $variables;
        }
        return 1;
    }
    protected function scheduleJob(&$data){
        $this->logger->info("DATA  ------".json_encode($data));
        $jobUrl = $data['jobUrl'];
        $cron  = $data['cron'];
        $url = $data['url'];
        $this->logger->info("jobUrl - $jobUrl, url -$url");
        if(isset($data['fileId'])){
            $data['previous_fileId'] = $data['fileId']; 
        }
        unset($data['jobUrl'],$data['cron'],$data['command'],$data['url']);
        $this->logger->info("JOB DATA ------".json_encode($data));
        $jobPayload = array("job" => array("url" => $this->config['baseUrl'].$jobUrl, "data" => $data),"schedule" => array("cron" => $cron));
        $this->logger->info("JOB PAYLOAD ------".print_r($jobPayload,true));
        $response = $this->restClient->postWithHeader($url,$jobPayload);
        $this->logger->info("Response - ".print_r($response,true));
        if(isset($response['body'])){
            $response = json_decode($response['body'], true);
            if($data['automatic_renewal'] == true){
                $data['automatic_renewal_jobid'] = $response['JobId'];
            }
            //TODO save JobId to file
            return $response;
        }

        //TODO log error
        
    }

    protected function processCommand($command,$data){
        switch ($command) {
            case 'mail':
                return $this->sendMail($data);
                break;
            case 'schedule':
                return $this->scheduleJob($data);
                break;
            case 'delegate':
                return $this->executeDelegate($data);
                break;
            case 'pdf':
                return $this->generatePDF($data);
                break;
            case 'fileSave':
                return $this->fileSave($data);
            default:
                break;
        };
    }
    
    protected function fileSave($data){
      $select = "Select uuid from ox_file where workflow_instance_id=:workflowInstanceId;";
      $selectParams = array("workflowInstanceId" => $data['workflow_instance_id']);
      $result = $this->executeQueryWithBindParameters($select,$selectParams)->toArray();
      return $this->fileService->updateFile($data,$result[0]['uuid']);
    }

    protected function executeDelegate($data){        
        if(isset($data['app_id']) && isset($data['delegate'])){
            $appId = $data['app_id'];
            $delegate = $data['delegate'];
            unset($data['delegate']);
        } else {
            return 0;
        }
        $response = $this->appDelegateService->execute($appId, $delegate, $data);
        return $response;
    }
    protected function sendMail($params)
    {   
        if (isset($params)) {
            $orgId = isset($params['orgId'])?$params['orgId']:1;
            $template = isset($params['template'])?$params['template']:0;
            if ($template) {
                try {
                    $body = $this->templateService->getContent($template, $params);
                } catch (Exception $e) {
                    return;
                }
            } else {
                if (isset($params['body'])) {
                    $body = $params['body'];
                } else {
                    $body = null;
                }
            }
            if (isset($params['to'])) {
                $recepients = $params['to'];
            } else {
                return;
            }
            if (isset($params['subject'])) {
                $subject = $params['subject'];
            } else {
                return;
            }
            if (isset($params['attachments'])) {
                $attachments = $params['attachments'];
            }

            $this->messageProducer->sendTopic(json_encode(array(
                'to' => $recepients,
                'subject' => $subject,
                'body' => $body,
                'attachments' => isset($attachments)?$attachments:null
            )), 'mail');
            return 1;
        } else {
            return;
        }
    }
    protected function generatePDF($params)
    {
        if (isset($params)) {
            $orgId = isset($params['orgid'])?$params['orgid']:1;
            $template = isset($params['template'])?$params['template']:0;
            if ($template) {
                try {
                    $body = $this->templateService->getContent($template, $params);
                } catch (Exception $e) {
                    return;
                }
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
            return array('document_path' => $generatePdf->generateDocument($body, $destination, $options));
        } else {
            return;
        }
    }


    protected function updateOrganizationContext($data){
        $orgId = AuthContext::get(AuthConstants::ORG_ID);
        if(!$orgId && isset($data['orgId'])){
            $orgId = $this->getIdFromUuid('ox_organization', $data['orgId']);
            AuthContext::put(AuthConstants::ORG_ID, $orgId);
        }
    }

    protected function extractFileData(&$data){
        $this->logger->info("File Data  ------".print_r($data,true));
        $this->updateOrganizationContext($data);

        if(isset($data['previous_fileId'])){
            $result = $this->fileService->getFile($data['previous_fileId']);    
            $this->logger->info("JSON FILE DATA  ------".print_r($result,true));
        
            if(count($result) == 0){
                throw new EntityNotFoundException("File ".$data['previous_fileId']." not found");
            }
            return $result[0]['data'];
        }else{
            return;
        }
        
    }
}
