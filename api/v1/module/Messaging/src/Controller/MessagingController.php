<?php
namespace Messaging\Controller;

/**
 * Messaging API
 */
use Exception;
use Messaging\Service\MessagingService;
use Oxzion\Controller\AbstractApiControllerHelper;

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

    /**
     * Create User API
     * @api
     * @link /messaging
     * @method POST
     * @param array $data Array of elements as shown</br>
     * <code>
     *        topic : string,
     *        param1 : string,
     *        param2 : string,
     * </code>
     * @return array Returns a JSON Response with Status Code.</br>
     * <code>
     *        status : "success|error",
     *        data : status code
     * </code>
     */
    public function create($data)
    {
        $this->log->info(__CLASS__ . "->create data - " . print_r($data, true));
        try {
            $response = $this->messagingService->send($data);
            if ($response) {
                $this->log->info(":Message added successfully to the queue - " . print_r($response, true));
                return $this->getSuccessResponseWithData(array("result" => $response), 201);
            } else {
                $response = ['data' => $data, 'errors' => "Failed to send to Queue/Topic"];
                return $this->getErrorResponse("Failed to send to Queue/Topic", 404, $response);
            }
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
        }

    }
}
