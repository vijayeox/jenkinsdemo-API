<?php
namespace Oxzion;

use Auth\OAuth;

require_once __DIR__.'/../Common/Config.php';
include __DIR__.'/../autoload.php';
include __DIR__.'/../../../vendor/autoload.php';

class OAuthService {

	public function __construct(){
		$this->dao = new Dao();
	}

	function getCredentials($userid, $email, $provider){
		$sql = "select credentials, refresh_token from oauth2_setting WHERE userid = ".$userid." AND provider = '".$provider."' AND email = '".$email."'" ;

		if(!$result = $this->dao->execQuery($sql)){ //a RUNNING job exists for the userid
			return;
		}

		$row = $result->fetch_assoc();
		$credentials = $row['credentials'];
		if(isset($credentials)){
			$credentials = (array)json_decode($credentials);
			$credentials['refresh_token'] = $row['refresh_token'];
			$credentials = $this->refreshAccessToken($userid, $email, $provider, $credentials);
			if(!is_array($credentials)){
				$credentials = (array)json_decode($credentials);
			}
		}
		
		return $credentials;
	}

	function refreshAccessToken($userid, $email, $provider, $accessToken){
		$oAuth = OAuth::getOAuthClient($provider, $accessToken);
		if($oAuth->isAccessTokenExpired()){
			$accessToken = $oAuth->fetchAccessTokenWithRefreshToken();
			$this->saveCredentials($userid, $email, $provider, $accessToken);
		}

		return $accessToken;
	}

	function saveCredentials($userid, $email, $provider, $credentials){
		$rToken;
		if(is_array($credentials)){
			if(isset($credentials['refresh_token'])){
				$rToken = $credentials['refresh_token'];
			}
			$credentials = json_encode($credentials);
		}else{
			$rToken = (array)json_decode($credentials);
			if(isset($rToken['refresh_token'])){
				$rToken = $rToken['refresh_token'];
			}
		}
		$sql = "select count(id) cnt from oauth2_setting where userid = ".$userid." AND email = '".$email."' AND provider = '".$provider."'";
 		if(!$result = $this->dao->execQuery($sql)){ //a RUNNING job exists for the userid
 			return false;
 		}
 		$row = $result->fetch_assoc();
 		$val = $row['cnt'] > 0;
 		$result->free();
 		$refreshToken = "";
 		
 		if($val){
 			if(isset($rToken)){
 				$refreshToken = ", refresh_token = '".$rToken."' ";
 			}
 			$sql = "update oauth2_setting set credentials = '".$credentials."'".$refreshToken." where userid = ".$userid." AND provider = '".$provider."' AND email = '".$email."'";
 		}else{
 			if(!isset($rToken)){
 				throw new Exception("No refresh token please set approval prompt to force and perform the authentication");
 			}
 			$sql = "INSERT INTO oauth2_setting (userid, email, provider, credentials, refresh_token,calendarflag) VALUES (".$userid.", '".$email."', '".$provider."', '".$credentials."', '".$rToken."',1)";
 		}
 		return $this->dao->execUpdate($sql);
 	}
 }
 ?>