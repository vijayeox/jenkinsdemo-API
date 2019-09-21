<?php
/**
 * Alert Api
 */
namespace Alert\Controller;

use Zend\Log\Logger;
use Alert\Model\AlertTable;
use Alert\Model\Alert;
use Alert\Service\AlertService;
use Oxzion\Controller\AbstractApiController;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\ValidationException;

class AlertController extends AbstractApiController
{
    /**
    * @var AlertService Instance of Alert Service
    * @method string getString()
    */
    private $alertService;
    /**
     * @ignore __construct
     */
    public function __construct(AlertTable $table, AlertService $alertService, Logger $log, AdapterInterface $dbAdapter)
    {
        parent::__construct($table, $log, __CLASS__, Alert::class);
        $this->setIdentifierName('alertId');
        $this->alertService = $alertService;
    }
    /**
    * Create Alert API
    * @api
    * @link /alert
    * @method POST
    * @param array $data Array of elements as shown
    * <code> {
    *               id : integer,
    *               name : string,
    *               status : string,
    *               description : string,
    *   } </code>
    * @return array Returns a JSON Response with Status Code and Created Alert.
    */
    public function create($data)
    {
        try {
            $count = $this->alertService->createAlert($data);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        if ($count == 0) {
            return $this->getFailureResponse("Failed to create a new entity", $data);
        }
        return $this->getSuccessResponseWithData($data, 201);
    }
    /**
    * GET List Alert API
    * @api
    * @link /alert
    * @method GET
    * @return array $dataget list of Alerts by User
    * <code>
    * {
    *  integer id,
    *  string name,
    *  string status,
    *  string description,
    * }
    * </code>
    */
    public function getList()
    {
        $result = $this->alertService->getAlerts();
        return $this->getSuccessResponseWithData($result);
    }
    /**
    * Update Alert API
    * @api
    * @link /alert[/:alertId]
    * @method PUT
    * @param array $id ID of Alert to update
    * @param array $data
    * <code>
    * {
    *  integer id,
    *  string name,
    *  string status,
    *  string description
    * }
    * </code>
    * @return array Returns a JSON Response with Status Code and Created Alert.
    */
    public function update($id, $data)
    {
        try {
            $count = $this->alertService->updateAlert($id, $data);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        if ($count == 0) {
            return $this->getErrorResponse("Entity not found for id - $id", 404);
        }
        return $this->getSuccessResponseWithData($data, 200);
    }
    /**
    * Delete Alert API
    * @api
    * @method DELETE
    * @link /alert[/:alertId]
    * @param $id ID of Alert to Delete
    * @return array success|failure response
    */
    public function delete($id)
    {
        $response = $this->alertService->deleteAlert($id);
        if ($response == 0) {
            return $this->getErrorResponse("Alert not found", 404, ['id' => $id]);
        }
        return $this->getSuccessResponse();
    }
    /**
    * Accept Alert API
    * @api
    * @method POST
    * @link /alert/alertId/accept
    * @return array success|failure response
    */

    public function acceptAction()
    {
        $params = $this->params()->fromRoute();
        $count = $this->alertService->updateAlertStatus(1, $params[$this->getIdentifierName()]);
        if ($count==0) {
            return $this->getErrorResponse("Entity not found for id - ".$params[$this->getIdentifierName()], 404);
        }
        return $this->getSuccessResponse();
    }
    /**
    * Decline Alert API
    * @api
    * @method POST
    * @link /alert/alertId/decline
    * @return array success|failure response
    */
    
    public function declineAction()
    {
        $params = $this->params()->fromRoute();
        $count = $this->alertService->updateAlertStatus(0, $params[$this->getIdentifierName()]);
        if ($count==0) {
            return $this->getErrorResponse("Entity not found for id - ".$params[$this->getIdentifierName()], 404);
        }
        return $this->getSuccessResponse();
    }
}
