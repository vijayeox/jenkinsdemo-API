<?php

namespace Oxzion\Encryption;

use Zend\Json\Decoder;
use Zend\Json\Encoder;

class Crypto
{
    private static $secret = "arogAegatnaVOfficeBack123";

    public function base_64_decode($value)
    {
        $mod = 4 - strlen($value) % 4;
        $counter = 1;
        while ($counter <= $mod) {
            $value.="=";
            $counter++;
        }
        $value = strtr($value, "-_", "+/");
        return base64_decode($value);
    }

    public function base_64_encode($value)
    {
        $encoded = base64_encode($value);
        $encoded = strtr($encoded, "+/", "-_");
        return($encoded);
    }
    
    public function key_digestion($secret)
    {
        $hashkey = hash("sha256", $secret, $raw_output = true);
        return $hashkey;
    }

    public function decryption($data, $secret=null)
    {
        if ($secret==null) {
            $secret=self::$secret;
        }
        $dat = $this->base_64_decode($data);
        $decrypted = $this->encrypt_decrypt("decrypt",$dat,$secret);
        $dec = Decoder::decode($decrypted);
        return($dec);
    }

    private function encrypt_decrypt($action, $string,$secret) {
        $output = false;
        $encrypt_method = "AES-256-CBC";
        $secret_iv = $secret."-iv";
        // hash
        $key = hash('sha256', $secret);
        
        // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        if ( $action == 'encrypt' ) {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if( $action == 'decrypt' ) {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }
        return $output;
    }
    

    public function encryption($data, $secret=null)
    {
        if ($secret==null) {
            $secret=self::$secret;
        };
        $dataencoded = Encoder::encode($data);
        $encrypeddata = $this->encrypt_decrypt("encrypt",$dataencoded,$secret);
        $dat = $this->base_64_encode($encrypeddata);
        return $dat;
    }
    
    public function split_name($name, $prefix = '')
    {
        $pos = strrpos($name, ' ');

        if ($pos === false) {
            return array(
                $prefix . 'firstname' => $name,
                $prefix . 'surname' => null
            );
        }

        $firstname = substr($name, 0, $pos + 1);
        $surname = substr($name, $pos);

        return array(
            $prefix . 'firstname' => $firstname,
            $prefix . 'surname' => $surname
        );
    }
}
