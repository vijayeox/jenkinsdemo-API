<?php
namespace Auth;

abstract class OAuth{
	const GOOGLE = "GOOGLE";
	const OUTLOOK = "OUTLOOK";

	abstract function isAccessTokenExpired();
	abstract function fetchAccessTokenWithRefreshToken();

	public static function getOAuthClient($provider, $accessToken){
		$client;
		switch ($provider) {
			case self::GOOGLE:
				$client = new GoogleOAuth($accessToken);
				break;
			case self::OUTLOOK:
				$client = new OutlookOAuth($accessToken);
				break;
			
			
		}

		return $client;
	}

	public static function getAccessTokenWithAuthCode($provider, $authCode){
		$accessToken;
		switch ($provider) {
			case self::GOOGLE:
				$client = GoogleOAuth::getClient();
				$accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
				break;
			case self::OUTLOOK:
				$client = OutlookOAuth::getClient();
				$accessToken = $client->getAccessToken('authorization_code', ['code' => $authCode]);
				$accessToken = $accessToken->jsonSerialize();
				break;
			
			
		}

		return $accessToken;

	}

	public static function getAuthenticationUrl($provider){
		$url;
		switch ($provider) {
			case self::GOOGLE:
				$client = GoogleOAuth::getClient();
				$client->setApprovalPrompt('force');
				$url = $client->createAuthUrl();
				break;
			case self::OUTLOOK:
				$client = OutlookOAuth::getClient();
				$url = $client->getAuthorizationUrl(array('prompt'=>'consent'));
				break;
		}

		return $url;
	}
}
?>