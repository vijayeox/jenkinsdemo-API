<?php

namespace Oxzion\Controller;

use Logger;
use Oxzion\Error\ErrorHandler;
use Oxzion\Jwt\JwtHelper;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\Stdlib\RequestInterface as Request;
use Zend\View\Model\JsonModel;
use Exception;
use Oxzion\OxServiceException;
use Oxzion\InsertFailedException;
use Oxzion\UpdateFailedException;
use Oxzion\ValidationException;
use Oxzion\VersionMismatchException;
use Oxzion\EntityNotFoundException;
use Oxzion\DuplicateEntityException;
use Oxzion\FileNotFoundException;
use Oxzion\FileContentException;

abstract class AbstractApiControllerHelper extends AbstractRestfulController
{

    private $config;
    protected function getBaseUrl()
    {
        return $_SERVER['REQUEST_SCHEME'] . "://" . $_SERVER['SERVER_NAME'] . ":" . $_SERVER['SERVER_PORT'];
    }

    protected function getLogger()
    {
        return Logger::getLogger('Controller');
    }

    /**
     * Check Request object have Authorization token or not
     * @param type $request
     * @return type String
     */
    protected function extractPostData()
    {
        $params = json_decode(file_get_contents("php://input"), true);
        if (!isset($params)) {
            $params = $this->params()->fromPost();
        }
        return $params;
    }

    /**
     * Process post data and call create
     *
     * @param Request $request
     * @return mixed
     * @throws Exception\DomainException If a JSON request was made, but no
     *    method for parsing JSON is available.
     */
    public function processPostData(Request $request)
    {
        if ($this->requestHasContentType($request, AbstractRestfulController::CONTENT_TYPE_JSON)) {
            return $this->create($this->jsonDecode($request->getContent()));
        }
        $data = $request->getPost()->toArray();
        if (sizeof($data) == 0) {
            $data = json_decode(file_get_contents("php://input"), true);
        }
        return $this->create($data);
    }

    protected function processBodyContent($request)
    {
        $content = $request->getContent();
        // JSON content? decode and return it.
        if ($this->requestHasContentType($request, AbstractRestfulController::CONTENT_TYPE_JSON)) {
            return $this->jsonDecode($request->getContent());
        }

        parse_str($content, $parsedParams);
        // If parse_str fails to decode, or we have a single element with empty value
        if (!is_array($parsedParams) || empty($parsedParams)
            || (1 == count($parsedParams) && '' === reset($parsedParams))
        ) {
            if (!empty($content)) {
                return $content;
            } else {
                return json_decode(file_get_contents("php://input"), true);
            }
        }

        return $parsedParams;
    }

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

    protected function getTokenPayload($responseData)
    {
        return JwtHelper::getTokenPayload($responseData);
    }

    protected function getRefreshTokenPayload()
    {
        return JwtHelper::getRefreshTokenPayload();
    }

    protected function generateJwtToken($payload)
    {
        $config = $this->getConfig();
        $jwtKey = $config['jwtKey'];
        $jwtAlgo = $config['jwtAlgo'];
        return JwtHelper::generateJwtToken($payload, $jwtKey, $jwtAlgo);
    }

    protected function getSuccessResponseWithData(array $data, $code = 200)
    {
        return $this->getSuccessResponse(null, $code, $data);
    }

    protected function getSuccessResponseWithParams(array $data = null, array $paramData = null, $code = 200, $param = null)
    {
        $this->response->setStatusCode($code);
        $payload = ['status' => 'success'];
        if (!is_null($data)) {
            $payload['data'] = (array) $data;
        }
        if (!is_null($param)) {
            if (!is_null($paramData)) {
                $payload[$param] = $paramData;
            }
        }
        return new JsonModel($payload);
    }

    protected function getSuccessResponse($message = null, $code = 200, array $data = null, $total = null, $role = null)
    {
        $this->response->setStatusCode($code);
        $payload = ['status' => 'success'];
        if (!is_null($message)) {
            $payload['message'] = $message;
        }
        if (!is_null($data)) {
            $payload['data'] = (array) $data;
        }
        if (!is_null($total)) {
            $payload['total'] = $total;
        }
        return new JsonModel($payload);
    }

    protected function getSuccessResponseDataWithPagination(array $data = null, $total, $code = 200)
    {
        return $this->getSuccessResponse(null, $code, $data, $total);
    }

    protected function getSuccessStringResponse($message = null, $code = 200, $data = null)
    {
        $this->response->setStatusCode($code);
        $payload = ['status' => 'success'];
        if (!is_null($message)) {
            $payload['message'] = $message;
        }
        if (!is_null($data)) {
            $payload['data'] = (array) $data;
        }
        return new JsonModel($payload);
    }

    protected function getFailureResponse($message, array $data = null)
    {
        return $this->getErrorResponse($message, 200, $data);
    }

    protected function getErrorResponse($message, $code = 200, array $data = null)
    {
        $this->response->setStatusCode($code);
        return ErrorHandler::buildErrorJson($message, $data, $code);
    }

    protected function getInvalidMethod()
    {
        return $this->getErrorResponse("Method Not Found", 405);
    }

    protected function getConfig()
    {
        if (!isset($this->config)) {
            $this->config = $this->getEvent()->getApplication()->getServiceManager()->get('Config');
        }

        return $this->config;
    }

    protected function exceptionToResponse(Exception $e) {
	$errorType = OxServiceException::ERR_TYPE_ERROR;
    $errorCode = OxServiceException::ERR_CODE_INTERNAL_SERVER_ERROR;
	$context = NULL;
        $message = NULL;
        if ($e instanceof OxServiceException) {
            $errorType = $e->getErrorType();
            $errorCode = $e->getErrorCode();
            $message = $e->getMessage();
            $context = $e->getContextData();
        } 
        else if ($e instanceof ValidationException) {
            $errorType = OxServiceException::ERR_TYPE_ERROR;
            $errorCode = OxServiceException::ERR_CODE_NOT_ACCEPTABLE; //Input data is not acceptable.
            $message = 'Validation error(s).';
            $context = ['errors' => $e->getErrors()];
        }
        else if ($e instanceof VersionMismatchException) {
            $errorType = OxServiceException::ERR_TYPE_ERROR;
            $errorCode = OxServiceException::ERR_CODE_PRECONDITION_FAILED; //Version mismatch is precondition failure.
            $message = 'Entity version sent by client does not match the version on server.';
        }
        else {
            $errorType = OxServiceException::ERR_TYPE_ERROR;
            $errorCode = OxServiceException::ERR_CODE_INTERNAL_SERVER_ERROR; //Unexpected error is always HTTP 500.
            $message = 'Unexpected error.';
        }

        if (OxServiceException::ERR_TYPE_FAILURE == $errorType) {
            $errorCode = OxServiceException::ERR_CODE_OK;
        }

        $this->response->setStatusCode($errorCode);
        $returnObj = [
            'status' => $errorType,
            'errorCode' => $errorCode,
            'message' => $message
        ];
        if (!empty($context)) {
            $returnObj['data'] = $context;
        }
        return new JsonModel($returnObj);
    }

    protected function getVersionFromQueryOrPost($throwIfNotFound = true) {
        $params = $this->params()->fromQuery();
        if (!empty($params) && array_key_exists('version', $params)) {
            return $params['version'];
        }
        $params = $this->params()->fromPost();
	if (!empty($params) && array_key_exists('version', $params)) {
            return $params['version'];
        }
        if ($throwIfNotFound) {
            throw new VersionRequiredException('Version is required.');
        }
        return NULL;
    }
}

