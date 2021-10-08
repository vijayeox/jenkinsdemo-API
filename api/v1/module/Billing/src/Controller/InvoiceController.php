<?php

namespace Billing\Controller;

// use Oxzion\Model\App;
// use Oxzion\Model\AppTable;
use Oxzion\Service\InvoiceService;
use Exception;
use Oxzion\AccessDeniedException;
use Oxzion\Controller\AbstractApiController;
// use Oxzion\Service\FileService;
// use Zend\Db\Adapter\AdapterInterface;
use Oxzion\AppDelegate\AppDelegateService;

class InvoiceController extends AbstractApiController
{
    /**
     * @var AppService Instance of AppService Service
     */
    private $appService;

    /**
     * @ignore __construct
     */
    public function __construct($config,InvoiceService $invoiceService,AppDelegateService $appDelegateService)
    {
        // parent::__construct($table, App::class);
        $this->setIdentifierName('invoiceId');
        $this->invoiceService = $invoiceService;
        $this->appDelegateService = $appDelegateService;
    $this->config = $config;
        $this->log = $this->getLogger();
    }
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * Create Invoice API
     * @api
     * @link /invoice
     * @method POST
     */
    public function create($data)
    {
        $this->log->info(__CLASS__ . "-> Create Invoice - " . print_r($data, true));
        try {
            $returnData = $this->invoiceService->createInvoice($data);
            return $this->getSuccessResponseWithData($returnData, 201);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
    }


    /**
     * GET List App API
     * @api
     * @link /invoice
     * @method GET
     */
    public function getList()
    {
        $params = $this->params()->fromRoute();
        $queryParams = $this->params()->fromQuery();
        $this->log->info(__CLASS__ . "-> Get Invoice List - " . print_r($params, true));
        try {
            $response = $this->invoiceService->getInvoiceList($params,$queryParams);
            return $this->getSuccessResponseDataWithPagination($response['data'], $response['total']);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
    }

    /**
     * Update App API
     * @api
     * @link /invoice[/:invoiceId]
     * @method PUT
     */
    public function update($uuid, $data)
    {
        $this->log->info(__CLASS__ . "-> Update Invoice - ${uuid}, " . print_r($data, true));
        try {
            $returnData = $this->invoiceService->updateInvoice($uuid, $data);
            return $this->getSuccessResponseWithData($returnData, 200);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
    }


 
    // public function delete($uuid)
    // {
    //     $this->log->info(__CLASS__ . "-> Delete App for ID ${uuid}.");
    //     try {
    //         $this->appService->deleteApp($uuid);
    //         return $this->getSuccessResponse();
    //     } catch (Exception $e) {
    //         $this->log->error($e->getMessage(), $e);
    //         return $this->exceptionToResponse($e);
    //     }
    // }

    /**
     * GET App API
     * @api
     * @link /invoice[/:invoiceId]
     * @method GET
     */
    public function get($uuid)
    {
        $this->log->info(__CLASS__ . "-> Get Invoice for ID- ${uuid}.");
        try {
            $response = $this->invoiceService->getInvoice($uuid);
            return $this->getSuccessResponseWithData($response);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
    }

    public function invoicePaymentAction()
    {
        $params = $this->extractPostData();
        $this->log->info(__CLASS__ . "-> Pay Invoice - " . print_r($params['invoiceId'], true));
        try {
        $returnData = $this->invoiceService->invoicePayment($params);
            return $this->getSuccessResponseWithData($returnData, 201);
        } catch (Exception $e) {
            $this->log->error($e->getMessage(), $e);
            return $this->exceptionToResponse($e);
        }
    }

    // public function createCustomerAction()
    // {
    //     $params = $this->extractPostData();
    //     $this->log->info(__CLASS__ . "-> Create Customer - " . print_r($params, true));
    //     try {
    //         $returnData = $this->invoiceService->createCustomer($params);
    //         return $this->getSuccessResponseWithData($returnData, 201);
    //     } catch (Exception $e) {
    //         $this->log->error($e->getMessage(), $e);
    //         return $this->exceptionToResponse($e);
    //     }

    // }










}
