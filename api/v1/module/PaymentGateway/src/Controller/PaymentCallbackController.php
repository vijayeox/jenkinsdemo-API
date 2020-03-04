<?php
/**
 * Payment callback Api
 */
namespace PaymentGateway\Controller;

use Oxzion\Controller\AbstractApiControllerHelper;
use Oxzion\Service\PaymentService;
use Oxzion\ValidationException;
use PaymentGateway\Model\Payment;
use PaymentGateway\Model\PaymentTable;
use Zend\Db\Adapter\AdapterInterface;
use Exception;


class PaymentCallbackController extends AbstractAPIControllerHelper
{

    /**
     * @var PaymentService Instance of Payment Service
     * @method string getString()
     */
    private $paymentService;
    /**
     * @ignore __construct
     */
    public function __construct(PaymentService $paymentService)
    {
        $this->setIdentifierName('paymentId');
        $this->paymentService = $paymentService;
        $this->log = $this->getLogger();
    }

    public function forteWebhookCallbackAction()
    {
        $data = $this->extractPostData();
        $this->log->info("Entered webhookcallback with data ".print_r($data, true));
        // $username = $data['username'];
        // try {
        //     $responseData = $this->paymentService->sendResetPasswordCode($username);
        //     if ($responseData === 0) {
        //         return $this->getErrorResponse("The username entered does not match your profile username", 404);
        //     }
        // } catch (Exception $e) {
        //     $response = ['data' => $data, 'errors' => $e->getErrors()];
        //     return $this->getErrorResponse("Something went wrong with password reset, please contact your administrator", 500);
        // }
        $this->log->info("Exit webhookcallback with data ".print_r($data, true));
        return $this->getSuccessResponseWithData($data, 200);

    }
    

}
    