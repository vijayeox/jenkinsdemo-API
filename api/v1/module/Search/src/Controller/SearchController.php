<?php
namespace Search\Controller;

use Zend\Log\Logger;
use Oxzion\Controller\AbstractApiController;
use Oxzion\Service\ElasticSearch;

class SearchController extends AbstractApiController {

    public function __construct(Logger $log){
        $this->setIdentifierName('entity');
        $this->setIdentifierName('searchKey');
    }
    public function get($id){
    	$params = $this->params()->fromRoute();
    	$searchObj = new ElasticSearch($this->currentAvatarObj);
        $params['toarray'] = 1;
        $params['pagesize'] = 25;
        $params['searchval'] = $params['searchKey'];
    	$searchObj->FilterWithParams($params);
    	print_r($params);exit;
    }
}