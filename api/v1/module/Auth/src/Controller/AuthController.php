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
    /**
    * @ignore authAdapter
    */
	private $authAdapter;
    /**
    * @ignore log
    */
	private $log;
    /**
    * @ignore authService
    */
	private $authService;
	
    /**
    * @ignore __construct
    */
	public function __construct(AuthAdapter $authAdapter,AuthService $authService, Logger $log){
		$this->authAdapter = $authAdapter;
		$this->log = $log;
		$this->authService = $authService;
    }

    /**
    * Login Auth API
    * @api
    * @method POST
    * @param string $username Username of user to Login
    * @param string $password password of user to Login
    * @return array Returns a JSON Response with Status Code and Created Announcement.
    * <code>
    * {
    *  string success|error,
    *  array data{
	*	string jwt
    * },
    * }
    * </code>
    */
	public function authAction(){
		$data = $this->request->getPost()->toArray();
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
    /**
    * @ignore getJwt
    */
	private function getJwt($userName,$orgId){
		$data = $this->getTokenPayload($userName,$orgId);
		$jwt = $this->generateJwtToken($data);
	    return $this->getSuccessResponseWithData(['jwt' => $jwt]);
	}
}
