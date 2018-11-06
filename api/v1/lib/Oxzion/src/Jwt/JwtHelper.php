<?php

namespace Oxzion\Jwt;

use Firebase\JWT\JWT;

class JwtHelper {
	public static function getTokenPayload($username,$orgId){
    	$tokenId = base64_encode(openssl_random_pseudo_bytes(32));
    	$issuedAt   = time();
	    $notBefore  = $issuedAt; 
	    $expire     = $notBefore + 72000; // Adding 3600 seconds
	    $data = ['iat'  => $issuedAt,'jti'  => $tokenId,'nbf'  => $notBefore,'exp'  => $expire,'data' => ['username' => $username,'orgId' => $orgId]];
		return $data;	    
    }

    public static function generateJwtToken($payload, $jwtKey, $jwtAlgo){
    	if (!is_array($payload) && !is_object($payload)) {
            return false;
        }
        $secretKey = base64_decode($jwtKey);
	    $jwt = JWT::encode(
			        $payload,      //Data to be encoded in the JWT
			        $secretKey, // The signing key
			        $jwtAlgo     // Algorithm used to sign the token
			        );
	    return $jwt;
	}

	public static function decodeJwtToken($token, $jwtKey, $jwtAlgo){
		if(! $token){
			return false;
		}
		$secretKey = base64_decode($jwtKey);
		return JWT::decode($token, $secretKey, [$jwtAlgo]);
	}
}