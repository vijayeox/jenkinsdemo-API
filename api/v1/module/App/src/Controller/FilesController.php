<?php
namespace App\Controller;

use Zend\Log\Logger;
use Oxzion\Model\Entity\Form;
use Oxzion\Controller\AbstractApiController;
use Oxzion\Service\ElasticSearch;

class FilesController extends AbstractApiController {

    public function __construct(Logger $log){
        parent::__construct($log, __CLASS__, new Form(),'moduleid');
        $this->setIdentifierName('formId');
    }
    //GET /{controller}
    public function getList(){
        print_r('test');exit;
    }
    public function get($id){
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
        return $this->getSuccessResponseWithData($result);
    }
}