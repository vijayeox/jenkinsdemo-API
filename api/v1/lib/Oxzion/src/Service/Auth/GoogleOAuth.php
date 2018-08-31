<?php
namespace Auth;
include __DIR__.'/../autoload.php';
include __DIR__.'/../Common/Config.php';
include __DIR__.'/../../../vendor/autoload.php';
use Google_Client;
use Google_Service_Calendar;
use Google_Service_Oauth2;

class GoogleOAuth extends OAuth{
	
	private $client;
	
	public function __construct($accessToken){
		$this->client = $this->_getClient($accessToken);
	}
	private function _getClient($accessToken){
		$client = self::getClient();
		// if(is_string($accessToken)){
		// 	$accessToken = (array) json_decode($accessToken);
		// }

		$client->setAccessToken($accessToken);

		return $client;
	}

	static function getClient(){
		$data = array();
		$values = array();
		$uris = array();
		$juris = array();
		$javascript_origins = array();
		$uri = 'http://'.$_SERVER['SERVER_NAME'].$_SERVER['SCRIPT_NAME'].'employee/avatar/googlepluscallback';
		$uri = str_replace('xo.php', '', $uri);
		array_push($uris, $uri);
		$values['auth_uri'] = "https://accounts.google.com/o/oauth2/auth";
		$values['client_secret'] = "S7fHeI3rdwZJzZEmA2SWHWVs";
		$values['token_uri'] = "https://accounts.google.com/o/oauth2/token";
		$values['redirect_uris'] = $uris;
		$values['client_x509_cert_url'] =$uri;
		$values['client_id'] = "1010936018966-b1tpugctn07n3js2tct3rehvequb8dog.apps.googleusercontent.com";
		$values['auth_provider_x509_cert_url'] = "https://www.googleapis.com/oauth2/v1/certs";
		array_push($juris, $uri);
		$values['javascript_origins'] = $juris;
		$data['web'] = $values;
		try {
			$client = new Google_Client();
			$client->setApplicationName(APPLICATION_NAME);
			$client->addScope("https://www.googleapis.com/auth/userinfo.email");
			$client->addScope("https://www.googleapis.com/auth/userinfo.profile");
			$client->addScope("https://mail.google.com/");
			$client->addScope(Google_Service_Calendar::CALENDAR);
			$client->addScope("https://www.googleapis.com/auth/drive");
			$client->addScope("https://www.googleapis.com/auth/blogger");
			$client->addScope("https://www.google.com/m8/feeds/");
			$client->addScope("https://www.googleapis.com/auth/mapsengine");
			$client->setApprovalPrompt('force');
			$client->setAuthConfig($data);
			$client->setAccessType('offline');
		} catch(Exception $e){
			return null;
		}
		return $client;
	}

	function isAccessTokenExpired(){
		return $this->client->isAccessTokenExpired();
	}

	function fetchAccessTokenWithRefreshToken(){
		$this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
		return json_encode($this->client->getAccessToken());
	}
	function getGoogleEmailid(){
	 		$objOAuthService = new Google_Service_Oauth2($this->client);
	 		$userInfoArray = $objOAuthService->userinfo->get();
	 		return $userInfoArray['email'];
	}
}
?>