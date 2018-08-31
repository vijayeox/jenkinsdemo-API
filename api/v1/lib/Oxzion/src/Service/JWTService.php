<?php
require __DIR__ .'/autoload.php';

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\ValidationData;
use Lcobucci\JWT\Parser;
class VA_ExternalLogic_JWTService {


	public function __construct(){
		
	}

	public function createToken($username,$password){
		$result = VA_Logic_Session::processLogin($username, $password);
		if ($result==VA_Logic_Session::VALIDLOGIN) {
			$avatar = VA_Logic_Session::getAvatar();
			$crypto = new VA_Logic_Crypto();
			$userdata = array("username"=>$avatar->username,"password"=>$avatar->password);
			$encrypteduserdata = $crypto->encryption($userdata);
            	$token = (new Builder())->setIssuer(VA_Logic_Session::getFullBaseUrl()) // Configures the issuer (iss claim)
                        ->setAudience(VA_Logic_Session::getFullBaseUrl()) // Configures the audience (aud claim)
                        ->setId($encrypteduserdata, true) // Configures the id (jti claim), replicating as a header item
                        ->setIssuedAt(time()) // Configures the time that the token was issue (iat claim)
                        ->setNotBefore(time() + 60) // Configures the time that the token can be used (nbf claim)
                        ->setExpiration(time() + 3600) // Configures the expiration time of the token (exp claim)
                        ->set('uid', $avatar->id) // Configures a new claim, called "uid"
                        ->getToken(); // Retrieves the generated token
                        $token->getHeaders(); // Retrieves the token headers
						$token->getClaims(); // Retrieves the token claims
			return $token;
		}
	}
	public function validateToken($token){
		$token = (new Parser())->parse((string) $token);
		$data = new ValidationData();
		$data->setIssuer($token->getClaim('iss'));
		$data->setId($token->getHeader('jti'));
		$crypto = new VA_Logic_Crypto();
		$data->setCurrentTime(time() + 60);
		if($token->validate($data)){
			return $crypto->decryption($token->getHeader('jti'));
		} else {
			return;
		}
	}
}