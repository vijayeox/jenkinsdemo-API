<?php
namespace Oxzion\Service;

use Exception;
use Oxzion\AccessDeniedException;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\Security\SecurityManager;
use Oxzion\ServiceException;
use Oxzion\OxServiceException;
use Oxzion\Service\AbstractService;
use Oxzion\Service\AccountService;
use Oxzion\Service\PaymentService;
use Oxzion\AppDelegate\AppDelegateService;
use Analytics\Service\QueryService;
use Oxzion\Utils\FileUtils;
use Oxzion\Utils\FilterUtils;
use Oxzion\Utils\UuidUtil;

class InvoiceService extends AbstractService
{

    public function __construct($config, $dbAdapter, PaymentService $paymentService,AppDelegateService $appDelegateService)
    {
        parent::__construct($config, $dbAdapter);
        $this->paymentService = $paymentService;
        $this->appDelegateService = $appDelegateService;
    }
    public function setRestClient($restClient)
    {
        $this->restClient = $restClient;
    }
    public function setMessageProducer($messageProducer)
    {
        $this->messageProducer = $messageProducer;
    }

    public function createOrGetCustomer($data)
    {
        if(!isset($data['accountId']) || !isset($data['appId']))
        {
            throw new ServiceException("Account ID or App ID not specified","invalid.parameters");
        }

        $appId = $data['appId'];
        $accountId = $data['accountId'];

        $appId = $this->getIdFromUuid('ox_app',$appId);
        $accountId = $this->getIdFromUuid('ox_account',$accountId);

        $select = "SELECT * FROM ox_app_registry WHERE app_id=:appId AND account_id=:accountId";
        $result = $this->executeQuerywithBindParameters($select, [
            "appId"=> $appId,
            "accountId"=> $accountId
        ])->toArray();

        if(count($result)==0)
        {
            throw new ServiceException("App does not belong to the account","invalid.app.and.account.id");
        }

        $customerId = $this->getCustomerForAccount($appId,$accountId);
        if(!isset($customerId))
        {
            $insert = "INSERT INTO ox_billing_customer (`uuid`,`account_id`,`app_id`) VALUES (:uuid,:accountId,:appId)";
            $insertResult = $this->executeQuerywithBindParameters($insert,[
                "uuid"=> UuidUtil::uuid(),
                "accountId" => $accountId,
                "appId" => $appId
            ]);
            $customerId = $this->getCustomerForAccount($appId,$accountId);
        }
        return [
            "appId" => $data['appId'],
            "accountId" => $data['accountId'],
            "customerId" => $customerId
        ];

    }

    public function createInvoice($data)
    {

        if(!isset($data['accountId']) || !isset($data['totalAmount']) || !isset($data['appId']) || !isset($data['data']))
        {
            throw new ServiceException("Invalid parameters specified in request","invalid.params");
        }
        // $appId = $data['appId'];
        // $accountId = $data['accountId'];
        // $appId = $this->getIdFromUuid('ox_app', $appId);
        // $accountId = $this->getIdFromUuid('ox_account',$accountId);


        // $customerId = $this->getCustomerForAccount($appId,$accountId);
        // if(!isset($customerId))
        // {
        //    $this->createCustomer($data)
        // }

        $customerData = $this->createOrGetCustomer($data);
        $customerId = $customerData['customerId'];
        $totalAmount = $data['totalAmount'];
        $invoiceDate = isset($data['invoiceDate'])?$data['invoiceDate']:date('d-m-Y');

        // Add logic for generating PDF invoice from JSON data here
        // Add logic for checking data format here
        $invoiceUuid = UuidUtil::uuid();
        $data['invoiceUuid'] = $invoiceUuid;
        $this->appDelegateService->execute($data['appId'],'CreateInvoicePDF',$data);

        $data = $data['data'];
        $insert = "INSERT INTO ox_billing_invoice (`uuid`,`customer_id`,`amount`,`data`,`date_created`) VALUES (:uuid,:customerId,:amount,:data,:invoiceDate)";
        $this->executeQuerywithBindParameters($insert,[
            "uuid"=> $invoiceUuid,
            "customerId" => $customerId,
            "amount" => $totalAmount,
            "data" => json_encode($data),
            "invoiceDate" => $invoiceDate
        ]);
        return $data;    
    }

    public function getInvoiceList($params,$filterParams=null)
    {
        // $appId = $params['appId'];
        // $appId = $this->getIdFromUuid('ox_app',$appId);
        $accountId = AuthContext::get(AuthConstants::ACCOUNT_ID);

        // $customerId = $this->getCustomerForAccount($appId,$accountId);

        // if(!isset($customerId))
        // {
        //     throw new ServiceException("Customer does not exist for account","invalid.customer");
        // }

        $pageSize = 20;
        $offset = 0;
        if(count($filterParams) > 0 || sizeof($filterParams) > 0)
        {
            $filterArray = json_decode($filterParams['filter'], true);
            $pageSize = isset($filterArray[0]['take']) ? $filterArray[0]['take'] : $pageSize;
            $offset = isset($filterArray[0]['skip']) ? $filterArray[0]['skip'] : $offset;
        }

        $countQuery = "SELECT count(obi.id) as count FROM ox_billing_invoice as obi INNER JOIN ox_billing_customer as obc on obi.customer_id=obc.id WHERE  obi.is_settled=0 AND obc.account_id=:accountId";
        $result = $this->executeQueryWithBindParameters($countQuery,[
            "accountId" => $accountId
        ])->toArray();

        $total = $result[0]['count'];
        if($total == 0)
        {
            return array('data' => [], 'total' => 0);
        }
        $select =  "SELECT obi.uuid,obi.amount,obi.data,obi.date_created,oa.name,obc.uuid as customer_id,oa.uuid as app_id,oac.uuid as account_id FROM ox_billing_invoice as obi ";
        $innerJoin1 = "INNER JOIN ox_billing_customer as obc on obi.customer_id=obc.id ";
        $innerJoin2 = "INNER JOIN ox_app as oa on obc.app_id=oa.id ";
        $innerJoin3 = "INNER JOIN ox_account as oac on obc.account_id = oac.id ";
        $where = "WHERE obi.is_settled=0 AND obc.account_id=:accountId LIMIT " .$pageSize." OFFSET ".$offset;
        $query = $select.$innerJoin1.$innerJoin2.$innerJoin3.$where;
        $result = $this->executeQueryWithBindParameters($query,[
            "accountId" => $accountId
        ])->toArray();

        foreach($result as $key => $invoice)
        {
            $result[$key]['data'] = json_decode($result[$key]['data'],true);
        }
        return array('data' => $result, 'total' => $total);

    }

    public function updateInvoice($invoiceUuid,$data)
    {
        if(!isset($data['accountId']) || !isset($data['totalAmount']) || !isset($data['data']))
        {
            throw new ServiceException("Invalid parameters specified in request","invalid.params");
        }
        $accountId = $data['accountId'];
        $accountId = $this->getIdFromUuid('ox_account',$accountId);

        $select = 'SELECT obc.id as "customer_id", obi.id as "invoice_id",obi.uuid as "invoice_uuid",oa.uuid as "app_id" FROM ox_billing_invoice as obi ';
        $innerJoin1 = 'INNER JOIN ox_billing_customer as obc on obi.customer_id=obc.id ';
        $innerJoin2 = 'INNER JOIN ox_app as oa on obc.app_id=oa.id ';
        $where = 'WHERE obc.account_id = :accountId AND obi.uuid=:invoiceUuid';
        $query = $select.$innerJoin1.$innerJoin2.$where;
        $result = $this->executeQuerywithBindParameters($query, [
            "accountId"=> $accountId,
            "invoiceUuid" => $invoiceUuid
        ])->toArray();

        if(count($result)==0)
        {
            throw new ServiceException("Invalid customer or invoice","invalid.customer.or.invoice");
        }
        $customerId = $result[0]['customer_id'];
        $invoiceId = $result[0]['invoice_id'];
        $appId = $result[0]['app_id'];
        $invoiceUuid = $result[0]['invoice_uuid'];

        //Add logic for validating data here
        // Add logic for generating PDF invoice from JSON data here
        $data['appId'] = $appId;
        $data['invoiceUuid'] = $invoiceUuid;
        $this->appDelegateService->execute($data['appId'],'CreateInvoicePDF',$data);
        
        $totalAmount = $data['totalAmount'];
        $invoiceDate = isset($data['invoiceDate'])?$data['invoiceDate']:date('d-m-Y');
        $data = $data['data'];

        $update = "UPDATE ox_billing_invoice SET `amount`=:totalAmount, `date_created`=:invoiceDate,`data`=:data WHERE customer_id=:customerId AND id=:invoiceId";
        $this->executeQueryWithBindParameters($update,[
            "totalAmount"=> $totalAmount,
            "invoiceDate" => $invoiceDate,
            "data" => json_encode($data),
            "customerId" => $customerId,
            "invoiceId" => $invoiceId
        ]);
        return $data;

    }

    public function getInvoice($invoiceUuid)
    {
        $accountId = AuthContext::get(AuthConstants::ACCOUNT_ID);
        $select =  "SELECT obi.uuid,obi.amount,obi.data,obi.date_created,oa.name,obc.uuid as customer_id,oa.uuid as app_id,oac.uuid as account_id FROM ox_billing_invoice as obi ";
        $innerJoin1 = "INNER JOIN ox_billing_customer as obc on obi.customer_id=obc.id ";
        $innerJoin2 = "INNER JOIN ox_app as oa on obc.app_id=oa.id ";
        $innerJoin3 = "INNER JOIN ox_account as oac on obc.account_id = oac.id ";
        $where = "WHERE obc.account_id=:accountId AND obi.uuid=:invoiceUuid";
        $query = $select.$innerJoin1.$innerJoin2.$innerJoin3.$where;
        $result = $this->executeQueryWithBindParameters($query,[
            "accountId" => $accountId,
            "invoiceUuid" => $invoiceUuid
        ])->toArray();

        if(count($result) == 0)
        {
            throw new ServiceException("Invalid customer or invoice","invalid.customer.or.invoice");
        }
        $result[0]['data'] = json_decode($result[0]['data'],true);
        return $result[0];
    }

    public function invoicePayment($data)
    {
        $invoiceUuid = $data['invoiceId'];

        if(!isset($invoiceUuid))
        {
            throw new ServiceException("Invoice not specified","invalid.invoice");
        }
        $accountId = AuthContext::get(AuthConstants::ACCOUNT_ID);

        $select =  "SELECT obi.uuid as invoice_id, obi.amount,obi.data,obi.date_created,obi.is_settled,oa.uuid as app_id ";
        $from = "FROM ox_billing_invoice as obi ";
        $join1 = "INNER JOIN ox_billing_customer as obc on obi.customer_id=obc.id ";
        $join2 = "INNER JOIN ox_app as oa on oa.id=obc.app_id ";
        $where = "WHERE obc.account_id =:accountId AND obi.uuid=:invoiceUuid";
        $result = $this->executeQueryWithBindParameters($select.$from.$join1.$join2.$where,[
            "accountId" => $accountId,
            "invoiceUuid" => $invoiceUuid
        ])->toArray();

        if(count($result) == 0)
        {
            throw new ServiceException("Invalid invoice for customer","invalid.invoice");
        }
        
        $invoiceAmount = $result[0]['amount'];
        $invoiceId = $result[0]['invoice_id'];
        $appId = $result[0]['app_id'];
        $invoiceData = $result[0]['data'];
        
        $data['amount'] = $invoiceAmount;
        $data['invoiceData'] = $invoiceData;
        $data['invoiceId'] = $invoiceId;
        $data['appId'] = $appId;

        $this->paymentService->initiatePaymentProcess($data['appId'],$data);
        $transactionData = array("transactionId" => $data['token'],"data"=>$data['transaction']['data']);
        $this->paymentService->processPayment($data['appId'],$data['transaction']['id'],$transactionData);

        unset($data['transaction']['data']);
        unset($data['transaction']['token']);
        
        return $data;
    
    }

    public function getCustomerForAccount($appId,$accountId)
    {
        $select = "SELECT * FROM ox_billing_customer WHERE app_id=:appId AND account_id=:accountId";
        $result = $this->executeQueryWithBindParameters($select,[
            "appId"=>$appId,
            "accountId" => $accountId
        ])->toArray();

        if(count($result) == 0)
        {
            return null;
        }

        return $result[0]['id'];
    }

}
