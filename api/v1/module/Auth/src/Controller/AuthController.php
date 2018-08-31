<?php

namespace Auth\Controller;

use Zend\Log\Logger;
use Oxzion\Controller\AbstractApiControllerHelper;
use Oxzion\Encryption\Crypto;
use Zend\View\Model\JsonModel;
use Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as AuthAdapter;
use Firebase\JWT\JWT;

class AuthController extends AbstractApiControllerHelper
{
	private $authAdapter;
	private $log;
	
	public function __construct(AuthAdapter $authAdapter, Logger $log){
		$this->authAdapter = $authAdapter;
		$this->log = $log;
		
    }

    //POST 
	public function authAction(){
		$data = $this->request->getPost()->toArray();
		if(! is_array($data)){

		}
		$key = $this->request->getHeaders()->get('x-apikey');
		//TODO validate apikey
		$crypto = new Crypto();
		$this->authAdapter->setIdentity($data['username']);
		$this->authAdapter->setCredential($data['password']);
		$result = $this->authAdapter->authenticate();
		if($result->isValid()){
			return $this->getJwt($data['username']);
		}else{
			return $this->getFailureResponse("Authentication Failure - Invalid username or password");
		}
	}
	
	private function getJwt($username){
		$data = $this->getTokenPayload($username);
		$jwt = $this->generateJwtToken($data);
	    return $this->getSuccessResponseWithData(['jwt' => $jwt]);
	}
}
