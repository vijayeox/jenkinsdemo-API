<?php
/**
 * Alert Api
 */
namespace Alert\Controller;

use Alert\Model\Alert;
use Alert\Model\AlertTable;
use Alert\Service\AlertService;
use Exception;
use Oxzion\Controller\AbstractApiController;
use Oxzion\ValidationException;
use Zend\Db\Adapter\AdapterInterface;

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
    public function __construct(AlertTable $table, AlertService $alertService, AdapterInterface $dbAdapter)
    {
        parent::__construct($table, Alert::class);
        $this->setIdentifierName('alertId');
        $this->alertService = $alertService;
        $this->log = $this->getLogger();
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

        $this->log->info(__CLASS__ . "-> \n Create Alert - " . json_encode($data, true));
        try {
            $count = $this->alertService->createAlert($data);
            if ($count == 0) {
                return $this->getFailureResponse("Failed to create a new entity", $data);
            }
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse($e->getMessage(), 500);
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
        try {
            $result = $this->alertService->getAlerts();
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse($e->getMessage(), 500);
        }
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
            if ($count == 0) {
                return $this->getErrorResponse("Entity not found for id - $id", 404);
            }
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse($e->getMessage(), 500);
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
        try {
            $response = $this->alertService->deleteAlert($id);
            if ($response == 0) {
                return $this->getErrorResponse("Alert not found", 404, ['id' => $id]);
            }
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse($e->getMessage(), 500);
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
        try {
            $params = $this->params()->fromRoute();
            $count = $this->alertService->updateAlertStatus(1, $params[$this->getIdentifierName()]);
            if ($count == 0) {
                return $this->getErrorResponse("Entity not found for id - " . $params[$this->getIdentifierName()], 404);
            }
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse($e->getMessage(), 500);
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
        try {
            $count = $this->alertService->updateAlertStatus(0, $params[$this->getIdentifierName()]);
            if ($count == 0) {
                return $this->getErrorResponse("Entity not found for id - " . $params[$this->getIdentifierName()], 404);
            }
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->getErrorResponse($e->getMessage(), 500);
        }
        return $this->getSuccessResponse();
    }
}
