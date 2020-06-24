<?php

namespace File\Controller;

use Oxzion\Model\SubscriberTable;
use Oxzion\Model\Subscriber;
use Oxzion\Service\SubscriberService;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Controller\AbstractApiController;
use Oxzion\ValidationException;
use Zend\InputFilter\Input;
use Exception;

class SubscriberController extends AbstractApiController
{
    /**
    * @var SubscriberService Instance of Subscriber Service
    */
    private $subscriberService;
    /**
    * @ignore __construct
    */
    public function __construct(SubscriberTable $table, SubscriberService $subscriberService, AdapterInterface $dbAdapter)
    {
        parent::__construct($table, Comment::class);
        $this->setIdentifierName('id');
        $this->subscriberService = $subscriberService;
    }

    /**
    * Create Subscriber API
    * @api
    * @link /Subscriber
    * @method POST
    * @param array $data Array of elements as shown
    * <code> {
    *               id : integer,
    *               user_id : integer,
                    file_id: integer,
    *} </code>
    * @return array Returns a JSON Response with Status Code and Created Subscriber.
    */
    public function create($data)
    {
        $params = $this->params()->fromRoute();
        try {
            $response = $this->subscriberService->createSubscriber($data, $params['fileId']);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        if ($response == 0) {
            return $this->getErrorResponse("Failed to create a new entity", 404, $data);
        }
        if ($response == -1) {
            return $this->getErrorResponse("User does not exist", 404, $data);
        }
        return $this->getSuccessResponseWithData($response, 201);
    }
    /**
    * Update Subscriber API
    * @api
    * @link /file/:fileId/subsciber/3
    * @method PUT
    * @param array $id ID of Subscriber to update
    * @param array $data
    * <code> status : "success|error",
    *        data :
                    {
                    integer id,
                    integer file_id,
                    integer user_id,
                    integer orgid,
                    integer created_by,
                    integer modified_by,
                    dateTime date_created (ISO8601 format yyyy-mm-ddThh:mm:ss),
                    dateTime date_modified (ISO8601 format yyyy-mm-ddThh:mm:ss),
                    }
    * </code>
    * @return array Returns a JSON Response with Status Code and Created Subscriber.
    */
    public function update($id, $data)
    {
        $params = $this->params()->fromRoute();
        try {
            $count = $this->subscriberService->updateSubscriber($id, $params['fileId'], $data);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        if ($count == 0) {
            return $this->getErrorResponse("Entity not found for id - $id", 404);
        }
        if ($count == -1) {
            return $this->getErrorResponse("User does not exist", 404, $data);
        }
        return $this->getSuccessResponseWithData($data, 200);
    }

    /**
    * Delete Subscriber API
    * @api
    * @link file/:fileId/subscriber/id
    * @method DELETE
    * @param $id ID of Subscriber to Delete
    * @return array success|failure response
    */
    public function delete($id)
    {
        $params = $this->params()->fromRoute();
        $response = $this->subscriberService->deleteSubscriber($id, $params['fileId']);
        if ($response == 0) {
            return $this->getErrorResponse("Subscriber not found", 404, ['id' => $id]);
        }
        return $this->getSuccessResponse();
    }

    /**
    * Get Subscriber API
    * @api
    * @link file/:fileId/subscriber/id
    * @method GET
    * @param $id ID of Subscriber to GET
    * @return array success|failure response
    */
    public function get($id)
    {
        $params = $this->params()->fromRoute();
        try{
            $response = $this->subscriberService->getSubscriber($id, $params['fileId']);
        }catch(Exception $e){
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse($e->getMessage(), 500);
        }
        if ($response == 0) {
            return $this->getErrorResponse("Subscriber not found", 404, ['id' => $id]);
        }
        return $this->getSuccessResponseWithData($response);
    }

    /**
    * GET List Subscriber API
    * @api
    * @link /file/:fileid/subscriber
    * @method GET
    * @return array $dataget list of Subscriber by User
    * <code>status : "success|error",
    *       data :  {
                    integer id,
                    integer file_id,
                    integer user_id,
                    integer orgid,
                    integer created_by,
                    integer modified_by,
                    dateTime date_created (ISO8601 format yyyy-mm-ddThh:mm:ss),
                    dateTime date_modified (ISO8601 format yyyy-mm-ddThh:mm:ss),
                    }
    * </code>
    */
    public function getList()
    {
        $params = $this->params()->fromRoute();
        $result = $this->subscriberService->getSubscribers($params['fileId']);
        return $this->getSuccessResponseWithData($result);
    }
}
