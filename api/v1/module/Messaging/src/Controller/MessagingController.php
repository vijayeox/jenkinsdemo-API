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
    private $messagingService;
    public function __construct(MessagingService $service)
    {
        $this->log = $this->getLogger();
        $this->messagingService = $service;
    }

    public function create($data)
    {
        $this->log->info(__CLASS__."->create data - ".print_r($data, true));
        $response = $this->messagingService->send($data);
        $this->log->info(__CLASS__."->create response - ".print_r($response, true));
        if($response){
            return $this->getSuccessResponseWithData(array("result" => $response), 201);
        } else {
            $response = ['data' => $data, 'errors' => "Failed to send to Queue/Topic"];
            return $this->getErrorResponse("Sending Message Error", 404, $response);
        }
    }
}
