<?php
namespace Callback\Controller;

use Oxzion\Controller\AbstractApiControllerHelper;
use Oxzion\ValidationException;
use Oxzion\Service\CommunicationService;

class CommunicationCallbackController extends AbstractApiControllerHelper
{
    private $log;
    // /**
    // * @ignore __construct
    // */
    public function __construct()
    {
        $this->logger = $this->getLogger();
    }

    public function sendSmsAction()
    {
        $this->logger->info("Entered sendSms");
        $params = $this->extractPostData();
        $this->communicationService = new CommunicationService($params['communication_client']);
        $messageReturn = $this->communicationService->sendSms($params);
        return $this->getSuccessResponse();
    }

    public function makeCallAction()
    {
        $params = $this->extractPostData();
        // $params['baseurl'] = $this->config['applicationUrl'];
        // $this->messageProducer->sendQueue(json_encode(array(
        //     'to' => $params['email'],
        //     'subject' => 'Your login details for EOX Vantage!!',
        //     'body' => $this->templateService->getContent('newUser', $params)
        // )), 'mail');
        return $this->getSuccessResponse();
    }
}
