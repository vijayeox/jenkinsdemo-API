<?php
namespace App\Controller;

use Zend\Log\Logger;
use Oxzion\Model\Entity\App;
use Oxzion\Controller\AbstractApiController;
use Oxzion\Service\ElasticSearch;

class AppController extends AbstractApiController {
    public $params;
    public $queryParams;

    public function __construct(Logger $log){
        parent::__construct($log, __CLASS__, new App());
        $this->setIdentifierName('moduleid');
    }
     //GET /{controller}
    public function getList(){
    	$modules = $this->currentAvatarObj->getModules();
    	if(is_null($modules)){
            return $this->getErrorResponse("Entity not found for id - $id", 404);
        }
        return $this->getSuccessResponseWithData($modules);
    }
    public function getByModuleAction(){
        $this->params = $this->params()->fromRoute();
        $this->queryParams = $this->params()->fromQuery();
        $searchObj = new ElasticSearch($this->currentAvatarObj);
        $params = array();
        if(isset($this->queryParams['start'])){
        	$params['start'] = $this->queryParams['start'];
        } else {
        	$params['start'] = 0;
        }
        if(isset($params['pagesize'])){
        	$params['pagesize'] = $this->queryParams['pagesize'];
        } else {
        	$params['pagesize'] = 25;
        }
        $params['moduleid'] = $this->params['moduleid'];
        $result = $searchObj->FilterWithParams($params);
        return $this->getSuccessResponseWithData($result);
    }
}