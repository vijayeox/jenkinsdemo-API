<?php

namespace Oxzion\Security;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\Error\ErrorHandler;

class SecurityManager{
	private static $instance;
	private function __construct(){
	}
	public static function getInstance(){
		if(!isset(self::$instance)){
			self::$instance = new SecurityManager();
		}
		return self::$instance;
	}
	public function checkAccess($e){
		$accessName = $e->getRouteMatch()->getParam('access', null);
		$actionName = $e->getRouteMatch()->getParam('action', null);
		if(isset($actionName)){
			$api_permission = $accessName[$actionName];
		} else {
        	$api_permission = $accessName[strtolower($e->getRequest()->getMethod())];
		}
		if(isset($accessName)){
			if(!$this->isGranted($api_permission)){
				$response = $e->getResponse();
				$response->setStatusCode(401);
				$jsonModel = ErrorHandler::buildErrorJson("You have no Access to this API");
				$response->getHeaders()->addHeaderLine('Content-Type', 'application/json');
				$response->setContent($jsonModel->serialize());
				return $response;
			} else {
				return;
			}
		}
		return;
	}
	/**
	* @param: privelege Array|String
	* Return : Boolean 0|1 for Granted
	*/
	public function isGranted($privelege){
		$roles = AuthContext::get(AuthConstants::ROLES);
		if (is_string($privelege) && in_array($privelege, $roles)) {
			return 1;
		} else if(is_array($privelege)){
			foreach ($privelege as $value) {
				if(in_array($value, $roles)){
					return 1;
				}
			}
		}
		return 0;
	}
}
?>