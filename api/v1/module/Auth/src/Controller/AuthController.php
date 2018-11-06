<?php

namespace Auth\Controller;

use Zend\Log\Logger;
use Oxzion\Controller\AbstractApiControllerHelper;
use Oxzion\Encryption\Crypto;
use Zend\View\Model\JsonModel;
use Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as AuthAdapter;
use Firebase\JWT\JWT;
use Auth\Service\AuthService;

class AuthController extends AbstractApiControllerHelper
{
	private $authAdapter;
	private $log;
	private $authService;
	
	public function __construct(AuthAdapter $authAdapter,AuthService $authService, Logger $log){
		$this->authAdapter = $authAdapter;
		$this->log = $log;
		$this->authService = $authService;
    }

    //POST 
	public function authAction(){
		$data = $this->request->getPost()->toArray();
		//TODO validate apikey
		$crypto = new Crypto();
		$this->authAdapter->setIdentity($data['username']);
		$this->authAdapter->setCredential($data['password']);
		$result = $this->authAdapter->authenticate();
		if($result->isValid()){
			if(isset($data['org_id'])){
				return $this->getJwt($data['username'],$data['org_id']);
			} else {
				return $this->getJwt($data['username'],$this->authService->getUserOrg($data['username']));
			}
		}else{
			return $this->getFailureResponse("Authentication Failure - Invalid username or password");
		}
	}
	
	private function getJwt($userName,$orgId){
		$data = $this->getTokenPayload($userName,$orgId);
		$jwt = $this->generateJwtToken($data);
	    return $this->getSuccessResponseWithData(['jwt' => $jwt]);
	}
}
