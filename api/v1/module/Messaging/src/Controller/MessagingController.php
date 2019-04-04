<?php
namespace Messaging\Controller;

use Oxzion\Controller\AbstractApiControllerHelper;
use Oxzion\Messaging\MessageProducer;
use Zend\Log\Logger;
use Messaging\Service\MessagingService;

class MessagingController extends AbstractApiControllerHelper
{
    /**
     * @ignore __construct
     */
    private $messagingService;
    public function __construct (MessagingService $service, Logger $log)
    {
        $this->messagingService = $service;
    }

    public function create($data)
    {
        $response = $this->messagingService->send($data);
        if($response){
            return $this->getSuccessResponseWithData(array("result" => $response), 201);
        } else {
            $response = ['data' => $data, 'errors' => "Failed to send to Queue/Topic"];
            return $this->getErrorResponse("Sending Message Error", 404, $response);
        }
    }

}
