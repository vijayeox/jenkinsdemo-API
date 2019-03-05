<?php

namespace Auth\Controller;

use Zend\Log\Logger;
use Oxzion\Controller\AbstractApiControllerHelper;
use Oxzion\Encryption\Crypto;
use Zend\View\Model\JsonModel;
use Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as AuthAdapter;
use Firebase\JWT\JWT;
use Oxzion\Service\UserService;
use Oxzion\Service\UserTokenService;

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
     * @ignore userService
     */
    private $userService;
    private $userTokenService;

    /**
     * @ignore __construct
     */
    public function __construct(AuthAdapter $authAdapter, UserService $userService, Logger $log, UserTokenService $userTokenService)
    {
        $this->authAdapter = $authAdapter;
        $this->log = $log;
        $this->userService = $userService;
        $this->userTokenService = $userTokenService;
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
     *    string jwt
     * },
     * }
     * </code>
     */
    public function authAction()
    {
        $data = $this->request->getPost()->toArray();
        $crypto = new Crypto();
        $this->authAdapter->setIdentity($data['username']);
        $this->authAdapter->setCredential($data['password']);
        $result = $this->authAdapter->authenticate();
        if ($result->isValid()) {
            if (isset($data['org_id'])) {
                return $this->getJwt($data['username'], $data['org_id']);
            } else {
                return $this->getJwt($data['username'], $this->userService->getUserOrg($data['username']));
            }
        } else {
            return $this->getFailureResponse("Authentication Failure - Invalid username or password");
        }
    }

    public function refreshtokenAction(){
        $data = $this->request->getPost()->toArray();
        $tokenPayload = $this->decodeJwtToken($data['jwt']);
        // print_r($data['jwt']);
        if (is_array($tokenPayload)) {
            $userDetail = $this->userService->getUserDetailsbyUserName($tokenPayload['username']);
            $userTokenInfo = $this->userTokenService->checkExpiredTokenInfo($data['refresh_token']);
            // print_r($data); 
            // print_r($userTokenInfo);
            if (!empty($userTokenInfo)) {
                $dataJwt = $this->getTokenPayload($tokenPayload['username'], $tokenPayload['orgId']);
                $jwt = $this->generateJwtToken($data);
                $refreshToken = $this->userTokenService->generateRefreshToken($userDetail, $this->getRefreshTokenPayload());
                return $this->getSuccessResponseWithData(['jwt' => $jwt,'refresh_token'=>$refreshToken]);
                
            }else{
                return $this->getErrorResponse("Refresh Token Expired", 404, array());
            }
        }else{
                return $this->getErrorResponse("Invalid JWT Token", 404, array());
        }

    }

    /**
     * @ignore getJwt
     */
    private function getJwt($userName, $orgId)
    {
        $dataJwt = $this->getTokenPayload($userName, $orgId);
        $userDetail = $this->userService->getUserDetailsbyUserName($userName);
        $refreshToken = $this->userTokenService->generateRefreshToken($userDetail, $this->getRefreshTokenPayload());
        $jwt = $this->generateJwtToken($dataJwt);
        if($refreshToken != 0){
            return $this->getSuccessResponseWithData(['jwt' => $jwt,'refresh_token'=>$refreshToken]);
        } else {
            return $this->getErrorResponse("Login Error", 405, array());
        }
    }
}
