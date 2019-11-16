<?php
/**
 * ServiceTask Callback Api
 */
namespace Workflow\Service;

use Exception;
use Oxzion\AppDelegate\AppDelegateService;
use Oxzion\Document\DocumentGeneratorImpl;
use Oxzion\EntityNotFoundException;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Service\AbstractService;
use Oxzion\Service\FileService;
use Oxzion\Service\TemplateService;
use Oxzion\Utils\RestClient;
use Oxzion\ValidationException;

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

    public function __construct($config, $dbAdapter, TemplateService $templateService, AppDelegateService $appDelegateService, FileService $fileService, MessageProducer $messageProducer)
    {
        $this->messageProducer = $messageProducer;
        $this->templateService = $templateService;
        $this->fileService = $fileService;
        $this->appDelegateService = $appDelegateService;
        parent::__construct($config, $dbAdapter);
        $this->fileService = $fileService;
        $this->restClient = new RestClient($this->config['job']['jobUrl'], array());
    }

    public function setMessageProducer($messageProducer)
    {
        $this->messageProducer = $messageProducer;
    }

    public function runCommand(&$data)
    {
        $this->logger->info("RUN COMMAND  ------" . json_encode($data));
        //TODO Execute Service Task Methods
        if (isset($data['variables']) && isset($data['variables']['command'])) {
            $this->logger->info("COMMAND  ------" . print_r($data['variables']['command'], true));
            $command = $data['variables']['command'];
            unset($data['variables']['command']);
            return $this->processCommand($data['variables'], $command);
        } else if (isset($data['variables']) && isset($data['variables']['commands'])) {
            $this->logger->info("Service Task Service - Comamnds");
            $commands = $data['variables']['commands'];
            $this->logger->info("COMMAND LIST ------" . print_r($commands, true));
            unset($data['variables']['commands']);
            $inputData = $data['variables'];
            foreach ($commands as $index => $value) {
                $commandJson = json_decode($value, true);
                $command = $commandJson['command'];
                unset($commandJson['command']);
                $variables = array_merge($inputData, $commandJson);
                $this->logger->info(ServiceTaskService::class . print_r($variables, true));
                $this->logger->info("COMMAND LIST ------" . $command);
                $result = $this->processCommand($variables, $command);
                if (is_array($result)) {
                    $inputData = $result;
                }
                $this->logger->info(ServiceTaskService::class . print_r($inputData, true));
            }
            return $variables;
        }
        return 1;
    }

    protected function processCommand(&$data, $command)
    {
        $this->logger->info("PROCESS COMMAND : command --- " . $command);
        switch ($command) {
            case 'mail':
                $this->logger->info("SEND MAIL");
                return $this->sendMail($data);
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
            case 'file':
                $this->logger->info("FILE DATA");
                return $this->extractFileData($data);
            default:
                break;
        };
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

    protected function fileSave($data)
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
            return $this->fileService->updateFile($data, $result[0]['uuid']);
        } catch (Exception $e) {
            $this->logger->info("File Save ---- Exception" . print_r($e->getMessage(), true));
            throw $e;
        }
    }

    protected function executeDelegate($data)
    {
        $this->logger->info("EXECUTE DELEGATE ---- " . print_r($data, true));
        if (isset($data['app_id']) && isset($data['delegate'])) {
            $appId = $data['app_id'];
            $delegate = $data['delegate'];
            unset($data['delegate']);
        } else {
            $this->logger->info("App Id or Delegate Not Specified");
            throw new EntityNotFoundException("App Id or Delegate Not Specified");
        }
        $this->logger->info("DELEGATE ---- " . print_r($delegate, true));
        $this->logger->info("DELEGATE APP ID---- " . print_r($appId, true));
        $response = $this->appDelegateService->execute($appId, $delegate, $data);
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
            if (count($errors) > 0) {
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
        if (count($result) == 0) {
            throw new EntityNotFoundException("File " . $fileId . " not found");
        }
        return $result['data'];

    }
}
