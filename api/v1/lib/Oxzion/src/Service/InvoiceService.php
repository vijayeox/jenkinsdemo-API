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
use Oxzion\Service\FileService;
use Oxzion\Service\PaymentService;
use Oxzion\AppDelegate\AppDelegateService;
use Analytics\Service\QueryService;
use Oxzion\Utils\FileUtils;
use Oxzion\Utils\FilterUtils;
use Oxzion\Utils\UuidUtil;
use Oxzion\Utils\ArtifactUtils;
use Oxzion\Document\DocumentBuilder;

class InvoiceService extends AbstractService
{

    public function __construct($config, $dbAdapter, PaymentService $paymentService,AppDelegateService $appDelegateService,FileService $fileService,DocumentBuilder $documentBuilder)
    {
        parent::__construct($config, $dbAdapter);
        $this->paymentService = $paymentService;
        $this->appDelegateService = $appDelegateService;
        $this->fileService = $fileService;
        $this->documentBuilder = $documentBuilder;
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
        if(!isset($data['appId']))
        {
            throw new ServiceException(" App ID not specified","invalid.parameters");
        }

        $appId = $data['appId'];
        if(isset($data['accountId']))
        {
            $accountId = $this->getIdFromUuid('ox_account',$data['accountId']);
        }
        else
        {
            $accountId = AuthContext::get(AuthConstants::ACCOUNT_ID);
            $data['accountId'] = AuthContext::get(AuthConstants::ACCOUNT_UUID);
        }
        $appId = $this->getIdFromUuid('ox_app',$appId);

        $select = "SELECT * FROM ox_app_registry WHERE app_id=:appId AND account_id=:accountId";
        $result = $this->executeQuerywithBindParameters($select, [
            "appId"=> $appId,
            "accountId"=> $accountId
        ])->toArray();

        if(count($result)==0)
        {
            throw new ServiceException("App does not belong to the account","invalid.app.and.account.id");
        }

        $customerData = $this->getCustomerForAccount($appId,$accountId);
        $customerId = $customerData['customer_id'];
        $appName = $customerData['app_name'];
        $customerName = $customerData['customer_name'];

        if(!isset($customerId))
        {
            $insert = "INSERT INTO ox_billing_customer (`uuid`,`account_id`,`app_id`) VALUES (:uuid,:accountId,:appId)";
            $insertResult = $this->executeQuerywithBindParameters($insert,[
                "uuid"=> UuidUtil::uuid(),
                "accountId" => $accountId,
                "appId" => $appId
            ]);
            $customerData = $this->getCustomerForAccount($appId,$accountId);
            $customerId = $customerData['customer_id'];
            $appName = $customerData['app_name'];
            $customerName = $customerData['customer_name'];
        }
        return [
            "appUuid" => $data['appId'],
            "accountUuid" => $data['accountId'],
            "customerId" => $customerId,
            "appName" =>$appName,
            "customerName" =>$customerName
        ];

    }

    public function createInvoice($data)
    {
        $fileId = isset($data['fileId']) ? $data['fileId'] : (isset($data['uuid']) ? $data['uuid'] : null);
        $data['entity_name'] = "Invoice";

        if(isset($fileId))
        {
            return $this->updateInvoice($data['invoiceUuid'],$data);
        }

        else {
            if(!isset($data['total']) || !isset($data['appId']) || !isset($data['accountId']) || !isset($data['ledgerData']) || !isset($data['subtotal']))
            {
                throw new ServiceException("Invalid parameters specified in request","invalid.params");
            }

            
            $invoiceAmount = $data['total'];
            $amountPaid = isset($data['amountPaid'])?$data['amountPaid']:0.0;
            $amountDue = $invoiceAmount - $amountPaid;

            if($amountDue <=0) {
                throw new ServiceException("Amount paid must be lesser than total","invalid.amount");
            }


            $data = $this->formatInvoiceData($data);
            $customerData = $this->createOrGetCustomer($data);
            $customerId = $customerData['customerId'];

            $data['appName'] = $customerData['appName'];
            $data['customerName'] = $customerData['customerName'];
    
            $invoiceUuid = UuidUtil::uuid();
            $data['invoiceUuid'] = $invoiceUuid;
            try {
                
                $pdfGenerationDelegatePath = $this->config['DELEGATE_FOLDER'].$data['appId']."/CreateInvoicePDF.php";
                if(file_exists($pdfGenerationDelegatePath))
                {

                    $this->appDelegateService->execute($data['appId'],'CreateInvoicePDF',$data);
                }
                else{
                    $pdfTemplatePath = $this->config['TEMPLATE_FOLDER']."Invoice.tpl";
                    $pdfTemplatePathForAccount = $this->config['TEMPLATE_FOLDER'].$data['accountId']."/".$data['appId']."/";
                    FileUtils::copy($pdfTemplatePath,"Invoice.tpl",$pdfTemplatePathForAccount);;
                    $this->generatePDF($data);
                    FileUtils::deleteFile("Invoice.tpl",$pdfTemplatePathForAccount);
                }

                $data['invoicePDFPath'] = $data['accountId']."/invoice/".$data['appId']."/".$invoiceUuid.".pdf";
                $data['paymentStatus'] = "pending";
                $filedata = $data;
                $file = $this->fileService->createFile($filedata);
                $data['fileId'] = $filedata['uuid'];
                $data['uuid'] = $filedata['uuid'];

                $invoiceData = [
                    "amountPaid" => $data['amountPaid'],
                    "ledgerData" => $data['ledgerData'],
                    "subtotal" => $data['subtotal'],
                    "total" => $data['total'],
                    "tax" => $data['tax'],
                    "invoiceNumber" =>$data['invoiceNumber'],
                    "invoiceDate" =>$data['invoiceDate'],
                    "invoiceDueDate" =>$data['invoiceDueDate'],
                    "appId" => $data['appId'],
                    "accountId" => $data["accountId"],
                    "invoiceUuid" => $data['invoiceUuid'],
                    "accountNumber" => $data['accountNumber'],
                    "fileId" => $data['fileId']
                ];

                $insert = "INSERT INTO ox_billing_invoice (`uuid`,`customer_id`,`amount`,`data`,`date_created`,`created_by`) VALUES (:uuid,:customerId,:amount,:data,:invoiceDate,:createdBy)";
                $this->executeQuerywithBindParameters($insert,[
                    "uuid"=> $invoiceUuid,
                    "customerId" => $customerId,
                    "amount" => $invoiceAmount,
                    "data" => json_encode($invoiceData),
                    "invoiceDate" => $data['invoiceDate'],
                    "createdBy" => AuthContext::get(AuthConstants::USER_ID)
                ]);


                return $data;   
            }
            catch (Exception $e) {
                throw new ServiceException("Invoice template not found for App: ".$data['appId'],"template.missing");
            }
        }
        
    }

    public function getInvoiceList($params,$filterParams=null)
    {

        if(isset($filterParams['getCreatedInvoices']))
        {
            return $this->getCreatedInvoices($filterParams);
        }

        else{

            return $this->getInvoicesForAccounts($filterParams);
        }
    }

    public function updateInvoice($invoiceUuid,$data)
    {

        if(!isset($data['total']) || !isset($data['accountId']) || !isset($data['ledgerData']) || !isset($data['subtotal']))
        {
            throw new ServiceException("Invalid parameters specified in request","invalid.params");
        }

        $userId = AuthContext::get(AuthConstants::USER_ID);
        // $accountId = $data['accountId'];
        // $accountId = $this->getIdFromUuid('ox_account',$accountId);
        

        $select = "SELECT oa.uuid,obi.customer_id FROM ox_billing_invoice as obi
                    INNER JOIN ox_billing_customer as obc on obi.customer_id=obc.id 
                    INNER JOIN ox_app as oa on obc.app_id=oa.id 
                    WHERE obi.created_by=:createdBy AND obi.uuid=:invoiceUuid";
        // $select = 'SELECT obc.id as "customer_id", obi.id as "invoice_id",obi.uuid as "invoice_uuid",oa.uuid as "app_id" FROM ox_billing_invoice as obi ';
        // $innerJoin1 = 'INNER JOIN ox_billing_customer as obc on obi.customer_id=obc.id ';
        // $innerJoin2 = 'INNER JOIN ox_app as oa on obc.app_id=oa.id ';
        // $where = 'WHERE obi.created_by=:createdBy AND obi.uuid=:invoiceUuid';
        $result = $this->executeQuerywithBindParameters($select, [
            "createdBy"=> $userId,
            "invoiceUuid" => $invoiceUuid
        ])->toArray();

        if(count($result)==0)
        {
            throw new ServiceException("Unauthorized to access invoice","invoice.auth.error");
        }

        $appId = $result[0]['uuid'];
        $previousCustomerId = $result[0]['customer_id'];

        $data['appId'] = $appId;
        $customerData = $this->createOrGetCustomer($data);
        $customerId = $customerData['customerId'];


        $data['appName'] = $customerData['appName'];
        $data['customerName'] = $customerData['customerName'];

        $data['appId'] = $appId;
        $data['invoiceUuid'] = $invoiceUuid;
        
        $invoiceAmount = $data['total'];
        $amountPaid = isset($data['amountPaid'])?$data['amountPaid']:0.0;
        $amountDue = $invoiceAmount - $amountPaid;
        if($amountDue <=0) {
            throw new ServiceException("Amount paid must be lesser than total","invalid.amount");
        }



        $data = $this->formatInvoiceData($data);

        $pdfGenerationDelegatePath = $this->config['DELEGATE_FOLDER'].$data['appId']."/CreateInvoicePDF.php";
        if(file_exists($pdfGenerationDelegatePath))
        {
            $this->appDelegateService->execute($data['appId'],'CreateInvoicePDF',$data);
        }
        else{
            
            $pdfTemplatePath = $this->config['TEMPLATE_FOLDER']."Invoice.tpl";
            $pdfTemplatePathForAccount = $this->config['TEMPLATE_FOLDER'].$data['accountId']."/".$data['appId']."/";
            FileUtils::copy($pdfTemplatePath,"Invoice.tpl",$pdfTemplatePathForAccount);
            $this->generatePDF($data);
            FileUtils::deleteFile("Invoice.tpl",$pdfTemplatePathForAccount);
        }
        
        $fileId = isset($data['fileId']) ? $data['fileId'] : (isset($data['uuid']) ? $data['uuid'] : null);

        $invoiceData = [
            "amountPaid" => $data['amountPaid'],
            "ledgerData" => $data['ledgerData'],
            "subtotal" => $data['subtotal'],
            "total" => $data['total'],
            "tax" => $data['tax'],
            "invoiceNumber" =>$data['invoiceNumber'],
            "invoiceDate" =>$data['invoiceDate'],
            "invoiceDueDate" =>$data['invoiceDueDate'],
            "appId" => $data['appId'],
            "accountId" => $data['accountId'],
            "invoiceUuid" => $data['invoiceUuid'],
            "accountNumber" => $data['accountNumber'],
            "fileId" => $fileId
        ];

        $update = "UPDATE ox_billing_invoice SET `amount`=:totalAmount, `customer_id`=:customerId,`date_created`=:invoiceDate,`data`=:data WHERE customer_id=:previousCustomerId AND uuid=:invoiceUuid";
        $this->executeQueryWithBindParameters($update,[
            "totalAmount"=> $invoiceAmount,
            "invoiceDate" => $data['invoiceDate'],
            "data" => json_encode($invoiceData),
            "customerId" => $customerId,
            "previousCustomerId" =>$previousCustomerId,
            "invoiceUuid" => $invoiceUuid
        ]);


        if(isset($fileId))
        {
            $data['paymentStatus'] = "pending";
            $data['invoicePDFPath'] = $data['accountId']."/invoice/".$data['appId']."/".$invoiceUuid.".pdf";
            $this->fileService->updateFile($data, $fileId);
        }

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


    public function getInvoicesForAccounts($filterParams)
    {
        $accountId = AuthContext::get(AuthConstants::ACCOUNT_ID);
        $countQuery = "SELECT count(obi.id) as count FROM ox_billing_invoice as obi INNER JOIN ox_billing_customer as obc on obi.customer_id=obc.id WHERE obc.account_id=:accountId";
        $result = $this->executeQueryWithBindParameters($countQuery,[
            "accountId" => $accountId
        ])->toArray();

        $total = $result[0]['count'];
        if($total == 0)
        {
            return array('data' => [], 'total' => 0);
        }

        $pageSize = 20;
        $offset = 0;
        if(count($filterParams) > 0 || sizeof($filterParams) > 0)
        {
            if(isset($filterParams['filter']))
            {
                $filterArray = json_decode($filterParams['filter'], true);
                $pageSize = isset($filterArray[0]['take']) ? $filterArray[0]['take'] : $pageSize;
                $offset = isset($filterArray[0]['skip']) ? $filterArray[0]['skip'] : $offset;
            }

        }
        $select =  "SELECT obi.uuid,obi.amount,obi.data,obi.date_created,oa.name,obc.uuid as customer_id,oa.uuid as app_id,oac.uuid as account_id,obi.is_settled FROM ox_billing_invoice as obi ";
        $innerJoin1 = "INNER JOIN ox_billing_customer as obc on obi.customer_id=obc.id ";
        $innerJoin2 = "INNER JOIN ox_app as oa on obc.app_id=oa.id ";
        $innerJoin3 = "INNER JOIN ox_account as oac on obc.account_id = oac.id ";
        $where ="WHERE obc.account_id=:accountId LIMIT ".$pageSize." OFFSET ".$offset;
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

    public function getCreatedInvoices($filterParams)
    {
        $userId = AuthContext::get(AuthConstants::USER_ID);
        $countQuery = "SELECT count(obi.id) as count FROM ox_billing_invoice as obi  WHERE  obi.is_settled=0 AND obi.created_by=:createdBy";
        $result = $this->executeQueryWithBindParameters($countQuery,[
            "createdBy" => $userId
        ])->toArray();

        $total = $result[0]['count'];
        if($total == 0)
        {
            return array('data' => [], 'total' => 0);
        }

        $pageSize = 20;
        $offset = 0;
        if(count($filterParams) > 0 || sizeof($filterParams) > 0)
        {
            if(isset($filterParams['filter']))
            {
                $filterArray = json_decode($filterParams['filter'], true);
                $pageSize = isset($filterArray[0]['take']) ? $filterArray[0]['take'] : $pageSize;
                $offset = isset($filterArray[0]['skip']) ? $filterArray[0]['skip'] : $offset;
            }

        }
        $select =  "SELECT obi.uuid,obi.amount,obi.data,obi.date_created,oa.name,obc.uuid as customer_id,oa.uuid as app_id,oac.uuid as account_id FROM ox_billing_invoice as obi ";
        $innerJoin1 = "INNER JOIN ox_billing_customer as obc on obi.customer_id=obc.id ";
        $innerJoin2 = "INNER JOIN ox_app as oa on obc.app_id=oa.id ";
        $innerJoin3 = "INNER JOIN ox_account as oac on obc.account_id = oac.id ";
        $where ="WHERE obi.is_settled=0 AND obi.created_by=:createdBy LIMIT ".$pageSize." OFFSET ".$offset;
        $query = $select.$innerJoin1.$innerJoin2.$innerJoin3.$where;


        $result = $this->executeQueryWithBindParameters($query,[
            "createdBy" => $userId
        ])->toArray();
        
        


        foreach($result as $key => $invoice)
        {
            $result[$key]['data'] = json_decode($result[$key]['data'],true);
        }
        return array('data' => $result, 'total' => $total);
    }


    public function getCustomerForAccount($appId,$accountId)
    {
        $select = "SELECT obc.id as customer_id,oa.name as app_name, oac.name as customer_name 
        FROM ox_billing_customer as obc INNER JOIN ox_app as oa on obc.app_id=oa.id 
        INNER JOIN ox_account as oac on obc.account_id=oac.id WHERE obc.app_id=:appId 
        AND obc.account_id=:accountId";

        $result = $this->executeQueryWithBindParameters($select,[
            "appId"=>$appId,
            "accountId" => $accountId
        ])->toArray();

        if(count($result) == 0)
        {
            return null;
        }

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
        $data['invoiceData'] = json_decode($invoiceData,true);
        
        $data['amount'] = $data['amount'] - $data['invoiceData']['amountPaid'];

        $data['invoiceId'] = $invoiceId;
        $data['appId'] = $appId;
        $this->paymentService->initiatePaymentProcess($data['appId'],$data);
        $transactionData = array("transactionId" => $data['token'],"data"=>[
            "amount" => $data['amount'],
            "invoiceId" => $data['invoiceId'],
            "invoiceData" => $data['invoiceData'],
            "config" => $data['config'],
            "transaction" => $data['transaction']
        ]);
        $this->paymentService->processPayment($data['appId'],$data['transaction']['id'],$transactionData);
        
        unset($data['transaction']['data']);
        unset($data['transaction']['token']);
        
        return $transactionData;
    
    }

    public function formatInvoiceDate($date)
    {
        if(strpos($date,"T") !== false)
        {
            return explode("T",$date)[0];
        }
        return explode(" ",$date)[0];
    }

    public function formatInvoiceData($data)
    {
        $invoiceAmount = $data['total'];
        $amountPaid = isset($data['amountPaid'])?$data['amountPaid']:0.0;
        $amountDue = $invoiceAmount - $amountPaid;

        $data['amountDue'] = $amountDue;

        $data['invoiceDate'] = isset($data['invoiceDate'])?$this->formatInvoiceDate($data['invoiceDate']):date('d-m-Y');
        $data['invoiceDueDate'] = isset($data['invoiceDueDate'])?$this->formatInvoiceDate($data['invoiceDueDate']):date('d-m-Y');
        
        foreach($data['ledgerData'] as $key=> $lineItem)
        {
            if(isset($lineItem['transactionEffectiveDate']))
            {
                $data['ledgerData'][$key]['transactionEffectiveDate'] = isset($lineItem['transactionEffectiveDate'])?$this->formatInvoiceDate($lineItem['transactionEffectiveDate']):date('d-m-Y');
            }
            if(isset($lineItem['transactionDueDate']))
            {
                $data['ledgerData'][$key]['transactionDueDate'] = isset($lineItem['transactionDueDate'])?$this->formatInvoiceDate($lineItem['transactionDueDate']):date('d-m-Y');
            }
        } 

        
        return $data;
    }

    public function getBillingCustomers()
    {
        $accountId = AuthContext::get(AuthConstants::ACCOUNT_ID);

        $select = "SELECT DISTINCT obr.app_id, oa.name as app_name, oa.uuid as app_uuid, 
        GROUP_CONCAT(oabrb.account_id) as buyer_account_id, GROUP_CONCAT(oac.uuid) as account_uuid,
        GROUP_CONCAT(oac.name) as account_name
        FROM ox_account_business_role as oabr 
        INNER JOIN ox_business_role as obr on oabr.business_role_id=obr.id 
        INNER JOIN ox_business_relationship as obrs on obrs.seller_account_business_role_id=oabr.id 
        INNER JOIN ox_account_business_role as oabrb on oabrb.id=obrs.buyer_account_business_role_id 
        INNER JOIN ox_app as oa on oa.id=obr.app_id
        INNER JOIN ox_account as oac on oac.id=oabrb.account_id
        WHERE oabr.account_id=:accountId GROUP BY obr.app_id";

        $result = $this->executeQueryWithBindParameters($select,[
            "accountId" => $accountId
        ])->toArray();

        foreach($result as $key=>$item)
        {
            $result[$key]['buyer_account_id'] = explode(",",$item['buyer_account_id']);
            $result[$key]['account_uuid'] = explode(",",$item['account_uuid']);
            $result[$key]['account_name'] = explode(",",$item['account_name']);

        }
        return $result;
    }

    public function generatePDF($data)
    {
        $invoicePDFTemplate = "Invoice";
        $invoicePDFName = $data['invoiceUuid'].".pdf";
        $generatedInvoicePDF = array();

        $accountId = $data['accountId'];
        $appId = $data['appId'];

        $data = $this->formatInvoiceDataForPDF($data);
        $folderDestination =  ArtifactUtils::getDocumentFilePath($this->config['APP_DOCUMENT_FOLDER'],"invoice/".$appId, array('accountId' => $accountId));
        $fileDestination = $folderDestination['absolutePath'].$invoicePDFName;
        if(FileUtils::fileExists($fileDestination)) {
            FileUtils::deleteFile($invoicePDFName,$folderDestination['absolutePath']);
        }
        $doc = $this->documentBuilder->generateDocument($invoicePDFTemplate,$data,$fileDestination);
        $documentpdf = $folderDestination['relativePath'] . $invoicePDFName;
    }

    public function formatInvoiceDataForPDF($data)
    {
        $invoiceAmount = $data['total'];
        $amountPaid = isset($data['amountPaid'])?$data['amountPaid']:0.0;
        $amountDue = $invoiceAmount - $amountPaid;

        $data['amountDue'] = number_format($amountDue, 2, '.', ',');
        $data['amountPaid'] = isset($data['amountPaid'])?number_format($data['amountPaid'], 2, '.', ','):'0.0';
        $data['total'] = number_format($data['total'], 2, '.', ',');
        $data['subtotal'] = number_format($data['subtotal'], 2, '.', ',');
        $data['tax'] = number_format($data['tax'], 2, '.', ',');

        
        foreach($data['ledgerData'] as $key=> $lineItem)
        {
            $data['ledgerData'][$key]['amount'] = number_format($lineItem['amount'], 2, '.', ',');
            $data['ledgerData'][$key]['unitCost'] = number_format($lineItem['unitCost'], 2, '.', ',');
        } 

        
        return $data;
    }

}

