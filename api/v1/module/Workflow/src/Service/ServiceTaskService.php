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

class ServiceTaskService extends AbstractService
{
    /**
    * @var ServiceTaskService Instance of Task Service
    */
    private $templateService;
    /**
    * @ignore __construct
    */

    public function __construct($config, $dbAdapter, TemplateService $templateService,AppDelegateService $appDelegateService)
    {
        $this->messageProducer = MessageProducer::getInstance();
        $this->templateService = $templateService;
        $this->appDelegateService = $appDelegateService;
        parent::__construct($config, $dbAdapter);
    }

    public function setMessageProducer($messageProducer)
    {
        $this->messageProducer = $messageProducer;
    }


    public function runCommand($data)
    {  
        //TODO Execute Service Task Methods
        if (isset($data['variables']) && isset($data['variables']['command'])) {
            switch ($data['variables']['command']) {
                case 'mail':
                    return $this->sendMail($data['variables']);
                    break;
                case 'schedule':
                    return $this->scheduleJob($data['variables']);
                    break;
                case 'delegate':
                    return $this->executeDelegate($data['variables']);
                    break;
                case 'pdf':
                    return $this->generatePDF($data['variables']);
                    break;
                default:
                    break;
            };
        }
        return 1;
    }
    protected function scheduleJob($data){
        
    }
    protected function executeDelegate($data){        
        if(isset($data['app_id']) && isset($data['delegate'])){
            $appId = $data['app_id'];
            $delegate = $data['delegate'];
        } else {
            return 0;
        }
        $response = $this->appDelegateService->execute($appId, $delegate, $data);
        return $response;
    }
    protected function sendMail($params)
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
                'To' => $recepients,
                'Subject' => $subject,
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
}
