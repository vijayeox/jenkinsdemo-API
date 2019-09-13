<?php

namespace Oxzion\Encryption;

use Zend\Json\Decoder;
use Zend\Json\Encoder;

class Crypto {
    
    private static $secret = "arogAegatnaVOfficeBack123";

    public function base_64_decode($value) {
        $mod = 4 - strlen($value) % 4;
        $counter = 1;
        while ($counter <= $mod) {
            $value.="=";
            $counter++;
        }
        $value = strtr($value,"-_", "+/" );
        return base64_decode($value);
    }

    public function base_64_encode($value) {
        $encoded = base64_encode($value);        
        $encoded = strtr($encoded,"+/", "-_" );
        return($encoded);
    }
    
    public function key_digestion($secret) {
        $hashkey = hash("sha256", $secret, $raw_output = TRUE);
        return $hashkey;
    }

    public function decryption($data,$secret=null) {
           if ($secret==null) {
               $secret=self::$secret;                        
            }
            $encrypted_key = hash("sha256", $secret, $raw_output = TRUE);
            $dat = $this->base_64_decode($data);
            $decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $encrypted_key, $dat, MCRYPT_MODE_ECB);
            $dec = Decoder::decode($decrypted);
            return($dec);
    }

    public function encryption($data,$secret=null) {
            if ($secret==null) {
                    $secret=self::$secret;                        
                };
            $dataencoded = Encoder::encode($data);
            $encrypted_key = hash("sha256", $secret, $raw_output = TRUE);
            $encrypeddata = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $encrypted_key, $dataencoded, MCRYPT_MODE_ECB);            
            $dat = $this->base_64_encode($encrypeddata);
            return $dat;
    }
    
    public function split_name($name, $prefix = '') {
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