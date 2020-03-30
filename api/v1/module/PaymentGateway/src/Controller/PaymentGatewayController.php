<?php
/**
 * Payment Api
 */
namespace PaymentGateway\Controller;

use Oxzion\Controller\AbstractApiController;
use Oxzion\ServiceException;
use Oxzion\Service\PaymentService;
use Oxzion\ValidationException;
use PaymentGateway\Model\Payment;
use PaymentGateway\Model\PaymentTable;
use Zend\Db\Adapter\AdapterInterface;
use Exception;

class PaymentGatewayController extends AbstractApiController
{
    /**
     * @var PaymentService Instance of Payment Service
     * @method string getString()
     */
    private $paymentService;
    /**
     * @ignore __construct
     */
    public function __construct(PaymentTable $table, PaymentService $paymentService, AdapterInterface $dbAdapter)
    {
        parent::__construct($table, Payment::class);
        $this->setIdentifierName('paymentId');
        $this->paymentService = $paymentService;
    }
    /**
     * Create Payment API
     * @api
     * @link /payment/app/$appId
     * @method POST
     * @param array $data Array of elements as shown
     * <code> {
     * "payment_client": "authorize",
     * "api_url": "https://api.demo.convergepay.com/hosted‐payments/transaction_token",
     * "server_instance_name": "demo",
     * "payment_config": "{\"merchant_id\": \"927092398\",\"user_id\": \"u9910idjki109\",\"pincode\": \"8989\" }"
     *   } </code>
     * @return array Returns a JSON Response with Status Code and Created Payment.
     */
    public function create($data)
    {
        try {
            $appId = $this->params()->fromRoute()['appId'];
            $this->log->info(__CLASS__ . "-> Create Payment Data - " . json_encode($data, true));
            $count = $this->paymentService->createPayment($data, $appId);
        } catch (ValidationException $e) {
            $response = ['data' => $data, 'errors' => $e->getErrors()];
            return $this->getErrorResponse("Validation Errors", 404, $response);
        }
        return $this->getSuccessResponseWithData($data, 201);
    }

    public function update($id, $data)
    {
        $appId = $this->params()->fromRoute()['appId'];
        $this->log->info(__CLASS__ . "-> Update Payment Data - " . json_encode($data, true) . " For - " . json_encode($id, true));
        try {
            $count = $this->paymentService->updatePayment($id, $data, $appId);
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
     * Delete Payment API
     * @api
     * @method DELETE
     * @link /payment[/:paymentId]
     * @param $id ID of Payment to Delete
     * @return array success|failure response
     */
    public function delete($id)
    {
        $this->log->info(__CLASS__ . "-> Delete Payment - " . json_encode($id, true));
        $appUuid = $this->params()->fromRoute()['appId'];
        $response = $this->paymentService->deletePayment($id, $appUuid);
        if ($response == 0) {
            return $this->getErrorResponse("Payment not found", 404, ['id' => $id]);
        }
        return $this->getSuccessResponse();
    }

    public function getList()
    {
        $appId = $this->params()->fromRoute()['appId'];
        $response = $this->paymentService->getPaymentDetails($appId);
        if (empty($response)) {
            return $this->getErrorResponse("No Payment details for the App", 404, ['id' => $appId]);
        }
        return $this->getSuccessResponseWithData($response, 200);
    }

    // Initiate Payment Process
    public function initiatePaymentProcessAction()
    {
        $appId = $this->params()->fromRoute()['appId'];
        $data = $this->extractPostData();
        try {
            $response = $this->paymentService->initiatePaymentProcess($appId, $data);
            if (empty($response)) {
                return $this->getErrorResponse("Payment Initialization Failed", 404, ['id' => $appId]);
            }
            $response = $response;
        } catch (Exception $e) {
            return $this->getErrorResponse($e->getMessage(), 400, ['id' => $appId]);
        } catch (ServiceException $e) {
            return $this->getErrorResponse($e->getMessage(), 400, ['id' => $appId]);
        }
        return $this->getSuccessResponseWithData($response, 201);
    }

    public function updateTransactionStatusAction()
    {
        $appId = $this->params()->fromRoute()['appId'];
        $transactionId = $this->params()->fromRoute()['transactionId'];
        $data = $this->extractPostData();
        $response = $this->paymentService->processPayment($appId, $transactionId, $data);
        if (empty($response)) {
            return $this->getErrorResponse("Transaction Details Failed", 404, ['id' => $appId]);
        }
        $response = $response;
        return $this->getSuccessResponseWithData($response, 201);
    }
}
