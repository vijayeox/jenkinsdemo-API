<?php

namespace Auth\Controller;

use Auth\Service\AuthService;
use Zend\Log\Logger;
use Oxzion\Controller\AbstractApiControllerHelper;
use Oxzion\Encryption\Crypto;
use Zend\View\Model\JsonModel;
use Auth\Adapter\LoginAdapter as AuthAdapter;
use Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as ApiAdapter;
use Firebase\JWT\JWT;
use Oxzion\Service\UserService;
use Oxzion\Service\UserTokenService;
use Exception;

class AuthController extends AbstractApiControllerHelper
{
    /**
     * @ignore authAdapter
     */
    private $authAdapter;
    private $apiAdapter;
    /**
     * @ignore log
     */
    private $log;
    /**
     * @ignore userService
     */
    private $userService;
    private $userTokenService;
    private $authService;

    /**
     * @ignore __construct
     */
    public function __construct(AuthAdapter $authAdapter, ApiAdapter $apiAdapter, UserService $userService, Logger $log, UserTokenService $userTokenService, AuthService $authService)
    {
        $this->authAdapter = $authAdapter;
        $this->apiAdapter = $apiAdapter;
        $this->log = $log;
        $this->userService = $userService;
        $this->userTokenService = $userTokenService;
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
     *    string jwt
     * },
     * }
     * </code>
     */
    public function authAction()
    {
        $data = $this->request->getPost()->toArray();
        $crypto = new Crypto();
        if (isset($data['username'])&&isset($data['password'])) {
            $data['username']= trim($data['username']);
            $this->authAdapter->setIdentity($data['username']);
            $this->authAdapter->setCredential($data['password']);
            $result = $this->authAdapter->authenticate();
        } elseif (isset($data['apikey'])) {
            $apiSecret = array_column($this->getApiSecret($data['apikey']), 'secret');
            if (!empty($apiSecret)) {
                $this->apiAdapter->setIdentity($data['apikey']);
                $this->apiAdapter->setCredential($apiSecret[0]);
                $result = $this->apiAdapter->authenticate();
            } else {
                return $this->getErrorResponse("Authentication Failure - Incorrect Api Key", 404);
            }
        }
        if (isset($result)) {
            if ($result->isValid()) {
                if (isset($data['username'])&&isset($data['password'])) {
                    return $this->getJwt($data['username'], $this->userService->getUserOrg($data['username']),0);
                } elseif (isset($data['apikey'])) {
                    return $this->getApiJwt($data['apikey']);
                }
            }
        }
        return $this->getErrorResponse("Authentication Failure - Incorrect data specified", 404);
    }

    public function refreshtokenAction()
    {
        $data = $this->request->getPost()->toArray();
        try {
            if (isset($data['jwt'])) {
                $tokenPayload = $this->decodeJwtToken($data['jwt']);
                if (is_array($tokenPayload) || is_object($tokenPayload)) {
                    $uname = isset($tokenPayload->data->username)? $tokenPayload->data->username:$tokenPayload['username'] ;
                    $orgId = isset($tokenPayload->data->orgid)? $tokenPayload->data->orgid: $tokenPayload['orgid'];
                    $userDetail = $this->userService->getUserDetailsbyUserName($uname);
                    $userDetail['id'] = isset($userDetail['id']) ? $userDetail['id'] : NULL;
                    $userTokenInfo = $this->userTokenService->checkExpiredTokenInfo($userDetail['id']);
                    if (!empty($userTokenInfo)) {
                        $data = ['username' => $uname, 'orgid' => $orgId];
                        $dataJwt = $this->getTokenPayload($data);
                        $jwt = $this->generateJwtToken($dataJwt);
                        $refreshToken = $this->userTokenService->generateRefreshToken($userDetail);
                        return $this->getSuccessResponseWithData(['jwt' => $jwt,'refresh_token'=>$refreshToken]);
                    } else {
                        return $this->getErrorResponse("Refresh Token Expired", 404);
                    }
                } else {
                    return $this->getErrorResponse("JWT Token Error", 404);
                }
            } else {
                return $this->getErrorResponse("JWT Token Not Found", 404);
            }
        } catch (Exception $e) {
            return $this->getErrorResponse("Invalid JWT Token", 404);
        }
    }

    public function registerAction()
    {
        $data = $this->extractPostData();
        try {
            $result = $this->authService->executeActions($data);
            if($result ==0){
                return $this->getErrorResponse("There was an error while executing", 404);
            }
        } catch (Exception $e){
            return $this->getErrorResponse($e->getMessage(), 404);
        }
        if (isset($result['auto_login'])) {
            $result['credentials'] = $this->getJwt($result['user']['username'], $this->userService->getUserOrg($result['user']['username']),1);
        } 
        return $this->getSuccessResponseWithData($result['credentials']);
    }

    /**
     * @ignore getJwt
     */
    private function getJwt($userName, $orgId,$raw=0)
    {
        $data = ['username' => $userName, 'orgid' => $orgId];
        $dataJwt = $this->getTokenPayload($data);
        $userDetail = $this->userService->getUserDetailsbyUserName($userName);
        $refreshToken = $this->userTokenService->generateRefreshToken($userDetail);
        $jwt = $this->generateJwtToken($dataJwt);
        if ($refreshToken != 0) {
            if($raw){
                return ['jwt' => $jwt,'refresh_token'=>$refreshToken,'username'=>$userName];
            }
            return $this->getSuccessResponseWithData(['jwt' => $jwt,'refresh_token'=>$refreshToken,'username'=>$userName]);
        } else {
            if($raw){
                return array();
            }
            return $this->getErrorResponse("Login Error", 405, array());
        }
    }

    public function validatetokenAction()
    {
        $data = $this->request->getPost()->toArray();
        try {
            if (isset($data['jwt'])) {
                $tokenPayload = $this->decodeJwtToken($data['jwt']);
                if (is_array($tokenPayload) && !is_object($tokenPayload)) {
                    if ($tokenPayload['Error'] == 'Expired token') {
                        return $this->getErrorResponse("Token Expired");
                    } else {
                        return $this->getErrorResponse("Token Invalid");
                    }
                } else {
                    return $this->getSuccessResponse("Token Valid");
                }
            } else {
                return $this->getErrorResponse("JWT Token Not Found", 404);
            }
        } catch (Exception $e) {
            return $this->getErrorResponse("Invalid JWT Token", 404);
        }
    }
    private function getApiJwt($apiKey)
    {
        $data = ['apikey' => $apiKey];
        $dataJwt = $this->getTokenPayload($data);
        $jwt = $this->generateJwtToken($dataJwt);
        if ($jwt) {
            return $this->getSuccessResponseWithData(['jwt'=>$jwt]);
        } else {
            return $this->getErrorResponse("Invalid JWT Token", 404, array());
        }
    }

    public function getApiSecret($apiKey)
    {
        $apiSecret = $this->authService->getApiSecret($apiKey);
        return $apiSecret;
    }

    public function userprofAction()
    {
        $data = $this->request->getPost()->toArray();
        try {
            if (isset($data["username"])) {
                $username = $data["username"];
                $res =$this->userService->getUserBaseProfile($username);

                if ($res == 0) {
                    return $this->getErrorResponse("Invalid User", 404);
                } else {
                    $profilePicUrl = $this->getBaseUrl() . "/user/profile/" . $res["uuid"];
                    return $this->getSuccessResponseWithData(['username'=> $res["name"],'profileUrl'=>$profilePicUrl]);
                }
            } else {
                return $this->getErrorResponse("Invalid Request", 404);
            }
        } catch (Exception $e) {
            return $this->getErrorResponse("Something went wrong", 404);
        }
    }
}
