<?php

namespace Oxzion\Jwt;

use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

class JwtHelper
{
    public static function getTokenPayload($responseData)
    {
        $tokenId = base64_encode(openssl_random_pseudo_bytes(32));
        $issuedAt = time();
        $notBefore = $issuedAt;
        $expire = $notBefore + 72000; // Adding 3600 seconds
        $data = ['iat' => $issuedAt, 'jti' => $tokenId, 'nbf' => $notBefore, 'exp' => $expire, 'data' => $responseData];
        return $data;
    }


    public static function generateJwtToken($payload, $jwtKey, $jwtAlgo)
    {
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

    public static function decodeJwtToken($token, $jwtKey, $jwtAlgo)
    {
        if (!$token) {
            return false;
        }
        try {
            $secretKey = base64_decode($jwtKey);
            $decodedToken = JWT::decode($token, $secretKey, [$jwtAlgo]);
        } catch (ExpiredException $e) {
            $tks = explode('.', $token);
            list($headb64, $bodyb64, $cryptob64) = $tks;
            $payload = json_decode(JWT::urlsafeB64Decode($bodyb64), false);
            return array('username'=>$payload->data->username, 'accountId'=> $payload->data->accountId, 'Error'=>$e->getMessage());
        }
        return $decodedToken;
    }
}
