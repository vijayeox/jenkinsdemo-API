<?php

namespace Oxzion\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;
use Oxzion\Jwt\JwtHelper;
use Oxzion\Error\ErrorHandler;

abstract class AbstractApiControllerHelper extends AbstractRestfulController{

    private $config;
    protected function getBaseUrl() {
        return $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'];
    }
    /**
     * Check Request object have Authorization token or not 
     * @param type $request
     * @return type String
     */


    public function findJwtToken($request)
    {
        $jwtToken = $request->getHeaders("Authorization") ? $request->getHeaders("Authorization")->getFieldValue() : '';
        if ($jwtToken) {
            $jwtToken = trim(trim($jwtToken, "Bearer"), " ");
            return $jwtToken;
        }
        if ($request->isGet()) {
            $jwtToken = $request->getQuery('token');
        }
        if ($request->isPost()) {
            $jwtToken = $request->getPost('token');
        }
        return $jwtToken;
    }
    protected function convertParams(){
        $params = json_decode(file_get_contents("php://input"),true);
        if(!isset($params)){
            $params = $this->params()->fromPost();          
            if(!is_object($params)){
                if(key($params)){
                    $params = json_decode(key($params),true);
                }
            }
        }
        return $params;
    }
    /**
     * contain encoded token for user.
     */
    protected function decodeJwtToken($token)
    {
        $config = $this->getConfig();
        $tokenPayload = $config['authRequiredText'];
        if (!$token) {
            return $tokenPayload;
        }
        $jwtKey = $config['jwtKey'];
        $jwtAlgo = $config['jwtAlgo'];
        $decodeToken = JwtHelper::decodeJwtToken($token, $jwtKey, $jwtAlgo);
        return $decodeToken;
    }

    protected function getTokenPayload($responseData){
        return JwtHelper::getTokenPayload($responseData);
    }

    protected function getRefreshTokenPayload(){
        return JwtHelper::getRefreshTokenPayload();
    }

    protected function generateJwtToken($payload){
        $config = $this->getConfig();
        $jwtKey = $config['jwtKey'];
        $jwtAlgo = $config['jwtAlgo'];      
        return JwtHelper::generateJwtToken($payload, $jwtKey, $jwtAlgo);
    }

    protected function getSuccessResponseWithData(array $data, $code = 200){
        return $this->getSuccessResponse(null, $code, $data);
    }
    protected function getSuccessResponse($message = null, $code = 200, array $data = null,$total = null){
        $this->response->setStatusCode($code);
        $payload = ['status' => 'success'];
        if(! is_null($message)){
            $payload['message'] = $message;
        }
        if(! is_null($data)){
            $payload['data'] = (array) $data;
        }
        if(! is_null($total)){
            $payload['total'] = $total;
        }
        return new JsonModel($payload);
    }


    protected function getSuccessResponseDataWithPagination(array $data,$total,$code = 200){
        return $this->getSuccessResponse(null, $code, $data,$total);
    }

    

    protected function getFailureResponse($message, array $data = null){
        return $this->getErrorResponse($message, 200, $data);
    }
    protected function getErrorResponse($message, $code = 200, array $data = null){
        $this->response->setStatusCode($code);
        return ErrorHandler::buildErrorJson($message,$data);
    }
    protected function getInvalidMethod(){
        return $this->getErrorResponse("Method Not Found",405);
    }

    protected function getConfig(){
        if(! isset($this->config)){
            $this->config = $this->getEvent()->getApplication()->getServiceManager()->get('Config');
        }

        return $this->config;
        
    }
}
