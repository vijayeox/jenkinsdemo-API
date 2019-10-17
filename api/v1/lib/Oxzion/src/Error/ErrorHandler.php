<?php

namespace Oxzion\Error;

use Zend\View\Model\JsonModel;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;

class ErrorHandler
{
    private static $logger;
    
    public static function init()
    {
        self::$logger = new Logger();
        $writer = new Stream(__DIR__ . '/../../../../logs/Error.log');
        self::$logger->addWriter($writer);
    }

    public static function onDispatchError($e)
    {
        return self::getJsonModelError($e);
    }

    public static function onRenderError($e)
    {
        return self::getJsonModelError($e);
    }
    public static function buildErrorJson($message, array $data = null, $errorCode = 0)
    {
        $payload = ['status' => 'error'];
        if ($errorCode != 0) {
            $payload['errorCode'] = $errorCode;
        }
        if (! is_null($message)) {
            $payload['message'] = $message;
        }
        if (! is_null($data)) {
            $payload['data'] = (array) $data;
        }
        return new JsonModel($payload);
    }

    public static function getJsonModelError($e)
    {
        $error = $e->getError();
        if (!$error) {
            return;
        }
        $response = $e->getResponse();
        $exception = $e->getParam('exception');
        $exceptionJson = array();
        if ($exception) {
            $exceptionJson = array(
                'class' => get_class($exception),
                'file' => $exception->getFile(),
                'line' => $exception->getLine(),
                'message' => $exception->getMessage(),
                'stacktrace' => $exception->getTraceAsString()
            );
        }
        self::$logger->log(Logger::ERR, print_r($exceptionJson, true));
        
        $errorJson = array(
            'message'   => 'An error occurred during execution; please try again later.',
        );
        
        // if(isset($_ENV['ENV']) && strtolower($_ENV['ENV']) != 'production'){
        $errorJson['error'] = $error;
        $errorJson['exception'] = $exceptionJson;
        // }
        
        if ($error == 'error-router-no-match') {
            $errorJson['message'] = 'Resource not found.';
        }

        $model = new JsonModel(array('status' => 'error', 'errors' => array($errorJson)));

        $e->setResult($model);

        return $model;
    }
}
ErrorHandler::init();
