<?php

namespace Oxzion\Error;

use Zend\View\Model\JsonModel;

class ErrorHandler {
	
	public static function onDispatchError($e)
    {
        return self::getJsonModelError($e);
    }

    public static function onRenderError($e)
    {
        return self::getJsonModelError($e);
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

        $errorJson = array(
            'message'   => 'An error occurred during execution; please try again later.',
            'error'     => $error,
            'exception' => $exceptionJson,
        );
        if ($error == 'error-router-no-match') {
            $errorJson['message'] = 'Resource not found.';
        }

        $model = new JsonModel(array('status' => 'error', 'errors' => array($errorJson)));

        $e->setResult($model);

        return $model;
    }
}