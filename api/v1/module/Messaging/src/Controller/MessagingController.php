<?php
namespace Messaging\Controller;

use Oxzion\Controller\AbstractApiControllerHelper;
use Oxzion\Messaging\MessageProducer;
use Messaging\Service\MessagingService;

class MessagingController extends AbstractApiControllerHelper
{
    /**
     * @ignore __construct
     */
    private $logger;
    private $messagingService;
    public function __construct(MessagingService $service)
    {
        $this->logger = $log;
        $this->messagingService = $service;
    }

    public function create($data)
    {
        $this->logger->info(__CLASS__."->create data - ".print_r($data, true));
        $response = $this->messagingService->send($data);
        $this->logger->info(__CLASS__."->create response - ".print_r($response, true));
        if($response){
            return $this->getSuccessResponseWithData(array("result" => $response), 201);
        } else {
            $response = ['data' => $data, 'errors' => "Failed to send to Queue/Topic"];
            return $this->getErrorResponse("Sending Message Error", 404, $response);
        }
    }
}
