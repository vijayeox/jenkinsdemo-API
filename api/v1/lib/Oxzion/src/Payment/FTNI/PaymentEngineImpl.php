<?php
namespace Oxzion\Payment\FTNI;
use Oxzion\Utils\RestClient;
use DOMDocument;
use Oxzion\Payment\PaymentEngine;

class PaymentEngineImpl implements PaymentEngine
{
    public function __construct($paymentConfig)
    {
        $this->paymentConfig = $paymentConfig;
    }

    public function initiatePaymentProcess(&$data)
    {
        if(isset($data['amount']) && $data['amount'] !=0)
        {
            $paymentConfigInfo = json_decode($data['config']['payment_config'],true);
            $restClient = new RestClient($paymentConfigInfo['sessionUrl']);
            
            $username = $paymentConfigInfo['username'];
            $password = $paymentConfigInfo['password'];
            $customerId = $paymentConfigInfo['customerId'];
    
            $sessionUrl = "?Username=".$username."&Password=".$password."&CustomerID=".$customerId."&AutoAdd=true";
            $response = $restClient->get($sessionUrl);
            $session = json_decode($response,true);
            $sessionId = $session["SessionID"];


            $data['customerId'] = $customerId;
            return $sessionId;
        }
        else {
            throw new ServiceException("Amount invalid or not specified");
        }

    }
    public function handleTransaction(&$data)
    {

        $apiUrl = $this->paymentConfig['appConfig']['apiUrl'];
        $applicationUrl = $this->paymentConfig['appConfig']['applicationUrl'];

        $callbackUrl = (substr($apiUrl, -1) == "/")?$apiUrl."user/ftni/callback":$apiUrl."/user/ftni/callback";
   
        $result = array();
        $result['transaction_id'] = isset($data['transactionId'])?$data['transactionId']:null;
        $result['transaction_status'] = isset($data['transactionStatus'])?$data['transactionStatus']:null;
        
        $data = $data['data'];
        $result['data'] = $data['transaction']['data'];


        $amount = number_format($data['amount'], 2,".","");



        $restClient = new RestClient($data['config']['api_url']);

        $formParams = [
            "SessionID" => $result['transaction_id'],
            "Amount" => $amount,
            "FieldDelimiter" => ",",
            "RowDelimiter" => "/",
            "ProcessingInstruction" => 0,
            "Callback" => $callbackUrl,
            "CancelUrl" => $applicationUrl
        ];

        if(isset($data['invoiceId']))
        {
            $invoiceNumber = $data['invoiceData']['invoiceNumber'];
            $invoiceDate = $data['invoiceData']['invoiceDate'];
            $invoiceDescription = "Invoice Payment";
            $paymentConfigInfo = json_decode($data['config']['payment_config'],true);
            $customerId = $paymentConfigInfo['customerId'];

            $formParams['LedgerData'] = $customerId.",1,".$invoiceNumber.",".$invoiceDate.",".$amount.",".$invoiceDescription;
        }

        $response = $restClient->postMultiPart("",$formParams);

        $dom = new DOMDocument("1.0");
        $dom->loadHTML($response);
        $base = $dom->createElement('base');
        $baseAttr = $dom->createAttribute('href');
        $baseAttr->value = $data['config']['api_url'];

        $base->appendChild($baseAttr);

        $baseAttr = $dom->createAttribute('target');
        $baseAttr->value = "_blank";
        $base->appendChild($baseAttr);

        $head = $dom->getElementsByTagName('head')->item(0);

        if ($head->hasChildNodes()) {
            $head->insertBefore($base,$head->firstChild);
        } else {
            $head->appendChild($base);
        }

        $pageDownEventTrigger = "    
        document.querySelector('#cancelPaymentDialog_YesButton').addEventListener('click',()=>{
            window.parent.postMessage('STEP_DOWN_PAGE', '$applicationUrl');
        });
        setInterval(()=>{
            if(document.getElementById('submitPaymentDialog').style.display == 'block'){
                window.parent.postMessage('TRANSACTION_COMPLETE','$applicationUrl');
            }
        }, 1000)
        ";

        $script = $dom->createElement('script',$pageDownEventTrigger);
        $head = $dom->getElementsByTagName('body')->item(0);
        $head->appendChild($script);

        $data['directFormHtml'] = $dom->saveHTML();

        unset($data['config']);
        return $result;
    }


    public function processCallback($transactionId,$transactionData,$callbackData)
    {
        $transactionAmount = number_format($transactionData['amount'], 2,".","");
        $callbackAmount = number_format($callbackData['Amount'], 2,".","");
        if($transactionAmount == $callbackAmount)
        {
            return [
                "transactionStatus" => "settled"
            ];

        }

        return [
            "transactionStatus" => "pending"
        ];



    }
}
