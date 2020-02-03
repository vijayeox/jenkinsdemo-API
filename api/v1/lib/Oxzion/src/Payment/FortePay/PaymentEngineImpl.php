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
        $this->org_id = $this->paymentConfigInfo->org_id;
        $this->method = $this->paymentConfigInfo->method;
        $this->js_url = $this->paymentConfig['js_url'];
    }
    public function initiatePaymentProcess(&$data)
    {
        $this->logger->info("Entered ");
        //unset config 
        unset($data['config']);
        $total_amount = "500.00";
        $hash_method = 'md5';
        $version = '2.0';
        $order_number = 'A1234';
		date_default_timezone_set("America/Chicago");
		$unixtime = strtotime(gmdate('Y-m-d H:i:s'));
		$millitime = microtime(true) * 1000;
        $utc = number_format(($millitime * 10000) + 621355968000000000 , 0, '.', '');
        //register customer
        // $customer_token = $this->registerCustomer($data);
        //
		$signaturedata = "$this->api_access_id|$this->method|$version|$total_amount|$utc|$order_number||";
        $signature = hash_hmac($hash_method,$signaturedata,$this->api_secure_key);
        $returnArray = array("api_access_id" => $this->api_access_id,"total_amount" => $total_amount, "version" => $version,
        "method" => $this->method, "location_id" => $this->location_id, "utc_time" => $utc, "hash_method" => $hash_method,
        "signature" => $signature, "order_number" => $order_number, "js_url" => $this->js_url);
        // return $returnArray;
        $this->logger->info("Exit ");
        // $returnArray['customer_token'] = $customer_token;
        return $returnArray;
    }
    public function handleTransaction(&$data){
        if(isset($data['ssl_token_response'])){
            $return['transaction_id'] = $data['ssl_txn_id'];
            $return['transaction_status'] = $data['ssl_token_response']; 
            $return['data'] = json_encode($data);
        } else {
            if(isset($data['errorCode'])){
                $return['transaction_id'] = null;
                $return['transaction_status'] = $data['errorName']; 
                $return['data'] = json_encode($data);
            }  
        }
        return $return;
    }

    private function registerCustomer(&$data) {
        try {
            $url = $this->api_url."/organizations/org_".$this->org_id."/locations/loc_".$this->location_id."/customers";
            $curl = curl_init();
            $payloadarr = array(
                'first_name' => $data['firstname'],
                'last_name' => $data['lastname'],
                'company_name' => 'Bridgemed',
                'customer_id' => '1234'
            );
             
            $payload = json_encode($payloadarr);
            curl_setopt_array($curl, array(
              CURLOPT_URL => $url,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_POSTFIELDS => $payload,
              CURLOPT_HTTPHEADER => array(
                "Content-Type: application/json",
                "X-Forte-Auth-Organization-Id: org_".$this->org_id,
                "Authorization: Basic ".base64_encode($this->api_access_id.":".$this->api_secure_key)
              ),
            ));
            
            $response = json_decode(curl_exec($curl));  
            curl_close($curl);
            return (explode('cst_',$response->customer_token))[1];
        } catch(Exception $e){
            throw new ServiceException($e->getMessage(), "could.not.register.to.forte");
        } 
    }
}