<?php
namespace Oxzion\Payment\ConvergePay;

use Oxzion\Payment\PaymentEngine;

class PaymentEngineImpl implements PaymentEngine
{
    public function __construct($paymentConfig){
        $this->paymentConfig = $paymentConfig;
    }
    public function initiatePaymentProcess(&$data)
    {
        $paymentConfigInfo = json_decode($this->paymentConfig['payment_config']);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->paymentConfig['api_url']);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_HTTPHEADER , array(
            "Accept: */*",
            "Content-Type: application/x-www-form-urlencoded",
            "cache-control: no-cache"
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS,
            "ssl_merchant_id=$paymentConfigInfo->merchant_id".
            "&ssl_user_id=$paymentConfigInfo->user_id".
            "&ssl_pin=$paymentConfigInfo->pincode".
            "&ssl_transaction_type=ccsale".
            "&ssl_first_name=".$data['firstname'].
            "&ssl_last_name=".$data['lastname'].
            "&ssl_get_token=Y".
            "&ssl_add_token=Y".
            "&ssl_amount=".$data['amount']
        );
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        $result = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if($httpcode == 200){
            return $result;
        }
        return false;
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
}