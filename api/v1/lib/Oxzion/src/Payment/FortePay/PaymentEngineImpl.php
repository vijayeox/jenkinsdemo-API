<?php
namespace Oxzion\Payment\FortePay;

use Oxzion\Payment\PaymentEngine;
use Logger;
use Oxzion\ServiceException;
use Exception;
class PaymentEngineImpl implements PaymentEngine
{
    protected $logger;

    public function __construct($paymentConfig){
        $this->logger = Logger::getLogger(__CLASS__);
        $this->paymentConfig = $paymentConfig;
        $this->paymentConfigInfo = json_decode($this->paymentConfig['payment_config']);
        $this->api_url = $this->paymentConfig['api_url'];
        $this->api_access_id  = $this->paymentConfigInfo->api_access_id;
        $this->api_secure_key = $this->paymentConfigInfo->api_secure_key;
        $this->location_id = $this->paymentConfigInfo->location_id;
        $this->account_id = $this->paymentConfigInfo->account_id;
        $this->js_url = $this->paymentConfig['js_url'];
    }
    public function initiatePaymentProcess(&$data)
    {
        $this->logger->info("Entered ");
        try {
            //validate expected input
            $this->validateParameters($data);
            //unset config 
            unset($data['config']);
            $total_amount = $data['amount'];
            $method = $data['method'];
            $hash_method = 'md5';
            $version = '2.0';
            $order_number = $data['order_number'];
            date_default_timezone_set("America/Chicago");
            $unixtime = strtotime(gmdate('Y-m-d H:i:s'));
            $millitime = microtime(true) * 1000;
            $utc = number_format(($millitime * 10000) + 621355968000000000 , 0, '.', '');
            $returnArray = array();
            if(isset($data['customer_token']) && isset($data['paymethod_token']) && $data['method']  == 'schedule') {
                $signaturedata = "$this->api_access_id|$method|$version|$total_amount|$utc|$order_number|{$data['customer_token']}|{$data['paymethod_token']}";
                $returnArray = array_merge($returnArray, array("customer_token" => $data['customer_token'],"paymethod_token" => $data['paymethod_token']));
            } else {
                $signaturedata = "$this->api_access_id|$method|$version|$total_amount|$utc|$order_number||";
            }
            $signature = hash_hmac($hash_method,$signaturedata,$this->api_secure_key);
            $returnArray = array_merge($returnArray,array("api_access_id" => $this->api_access_id,"amount" => $total_amount, "version" => $version,
            "method" => $method, "location_id" => $this->location_id, "utc_time" => $utc, "hash_method" => $hash_method,
            "signature" => $signature, "order_number" => $order_number, "js_url" => $this->js_url));
            $this->logger->info("Exit ");
        } catch(Exception $e){
            throw new ServiceException($e->getMessage(), "could.not.register.to.forte");
        } 
        return $returnArray;
    }
    public function handleTransaction(&$data){
        $this->logger->info("Entered ");
        $return = array();
        if(!isset($data['event'])) {
            $this->logger->info("Exit, event field is missing");
            throw new ServiceException("event field is required", "event.required");
        }
        $return['transaction_status'] = $data['event'];
        $return['data'] = json_encode($data);
        if($data['event'] == "success" || $data['event'] == "failure"){
            $return['transaction_id'] = $data['trace_number']; 
        } else {
            $return['data'] = json_encode($data);
        }
        $this->logger->info("Exit ");
        return $return;
    }
    
    private function validateParameters($data) {
        $this->logger->info("Entered ");
        if(!(isset($data['amount'], $data['method'] ,$data['order_number']))) {
            $messsage = "";
            $messsage .= isset($data['amount']) ? '':'amount, ';
            $messsage .= isset($data['method']) ? '' : 'method, ';
            $messsage .= isset($data['order_number']) ? '' : 'order_number ';
            $messsage .= "are required for payment";
            $this->logger->info("Exit with validation failed");
            throw new ServiceException($messsage, "fortepay.validation.failed");
        }
        $this->logger->info("Exit ");
        return;
    }
}