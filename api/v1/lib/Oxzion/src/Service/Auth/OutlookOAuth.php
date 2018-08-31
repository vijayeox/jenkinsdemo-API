<?php
namespace Auth;
use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Token\AccessToken;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
class OutlookOAuth extends OAuth{
	
	private $accessToken;
	
	public function __construct($accessToken){
		if(is_string($accessToken)){
			$accessToken = json_decode($accessToken);
		}
		$this->accessToken = new AccessToken($accessToken);
		
	}

	function isAccessTokenExpired(){
		return $this->accessToken->hasExpired();
	}
	function getAccessToken(){
		return $this->accessToken->getToken();
	}
	
	function fetchAccessTokenWithRefreshToken(){
		$oauthClient = self::getClient();
		$this->accessToken = $oauthClient->getAccessToken('refresh_token', ['refresh_token' => $this->accessToken->getRefreshToken()])->jsonSerialize();
		return $this->accessToken;
	}

	static function getClient($prompt = False){
		$config = ['clientId'=> '538dc896-bac6-4471-af48-056ac372192c','clientSecret'=> 'wNu1CWJdcaNw9fvJf2euCLu','redirectUri'=> 'http://localhost/club2.6/public/employee/avatar/outlookcallback','urlAuthorize'=> "https://login.microsoftonline.com/common/oauth2/v2.0/authorize",'urlAccessToken'=> "https://login.microsoftonline.com/common/oauth2/v2.0/token",'urlResourceOwnerDetails' => '','scopes'=> "email Calendars.ReadWrite User.Read Mail.Read Mail.ReadWrite Mail.Send Mail.Send.Shared MailboxSettings.ReadWrite MailboxSettings.Read User.Read.All User.ReadWrite User.ReadBasic.All User.ReadWrite.All People.Read People.Read.All Mail.Read.Shared openid email offline_access  https://graph.microsoft.com/User.Read offline_access profile"];
		$oauthClient = new GenericProvider($config);
		return $oauthClient;
	}
	function getEmailid(){
		$graph = new Graph();
		$graph->setAccessToken($this->getAccessToken());
		$user = $graph->createRequest('GET', '/me')->setReturnType(Model\User::class)->execute();
		if(!$user->getMail()){
			$emailid = $user->getUserPrincipalName();
		} else {
			$emailid = $user->getMail();
		}
		return $emailid;
	}
}
?>