<?php

namespace App\Controller;

use App\Model\Payment;
use App\Model\PaymentTable;
use App\Service\PaymentService;
use Oxzion\Controller\AbstractApiController;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Log\Logger;
use Oxzion\ValidationException;

class PaymentController extends AbstractApiController
{
    /**
     * @var PaymentService Instance of PaymentService Service
     */
    private $paymentService;

    /**
     * @ignore __construct
     */
    public function __construct(PaymentTable $table, PaymentService $paymentService, Logger $log, AdapterInterface $dbAdapter)
    {
        parent::__construct($table, $log, __CLASS__, null);
        $this->setIdentifierName('appId');
        $this->paymentService = $paymentService;
    }

    /**
     * POST Payment the CSV fuction
     * @api
     * @link /app/1/createpayment
     * @method POST
     * @param array $data
     * @return Status mesassge based on success and failure
     * <code>
     * status : "success|error",
     *       data :  {
     *              String stored_procedure_name
     *              int: org_id
     *              string: app_id
     *              string: app_name
     *       }
     * </code>
     */

    public function create($data)
    {
        $appId = $this->params()->fromRoute()['appId'];
        $param = $this->extractPostData();
        try {
            $count = $this->paymentService->createpayment($appId, $param);
            $param['id'] = $count;
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getMessage()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        return $this->getSuccessResponseWithData($param, 200);

    }

    /**
     * Update Payment API
     * @api
     * @link /app/appId/updatepayment[/:id]
     * @method PUT
     * @param array $id ID of Payment to update
     * @param array $data
     * @return array Returns a JSON Response with Status Code and Created Payment.
     */
    public function update($id, $data)
    {
        print_r($id);exit;
        $paymentId = $this->params()->fromRoute()['paymentId'];
        try {
            $count = $this->paymentService->updatePayment($paymentId, $data);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        if ($count == 0) {
            return $this->getErrorResponse("Entity not found for id - $id", 404);
        }
        return $this->getSuccessResponseWithData($data, 200);
    }

}
