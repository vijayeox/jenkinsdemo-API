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
		if(isset($actionName) && !empty($accessName) ){
			$api_permission = $accessName[$actionName];
		} else {
			if(!empty($accessName)){
        		$api_permission = isset($accessName[strtolower($e->getRequest()->getMethod())]) ? $accessName[strtolower($e->getRequest()->getMethod())] : NULL;
			} else {
				$api_permission = NULL;
			}
		}
		if(AuthContext::get(AuthConstants::API_KEY)) {
			return;
		}
		// print_r($accessName);exit;
		if(isset($accessName) && $api_permission){
			if($accessName && !$this->isGranted($api_permission)){
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
	* @param: privilege Array|String
	* Return : Boolean 0|1 for Granted
	*/
	public function isGranted($privilege){
		$roles = AuthContext::get(AuthConstants::PRIVILEGES);
		
		if (is_string($privilege) && $roles[$privilege]) {
			return 1;
		} else if(is_array($privilege)){
			foreach ($privilege as $value) {
				if($roles[$value]){
					return 1;
				}
			}
		}
		return 0;
	}
}
?>