<?php
namespace App\Controller;

use Zend\Log\Logger;
use Oxzion\Model\Entity\Form;
use Oxzion\Controller\AbstractApiController;

class FileController extends AbstractApiController {

    public function __construct(Logger $log){
        parent::__construct($log, __CLASS__, new Form(),'moduleid');
        $this->setIdentifierName('appId');
    }
    //GET /{controller}
    public function getList(){
        print_r('test');exit;
    	$this->log->info($this->logClass . ": getList");
        $filter = $this->getParentFilter();
        $filter['orgid'] = $this->currentAvatarObj->orgid;
        $result = $this->table->select($filter);
    	$data = array();
    	while ($result->valid()) {
            $value = $result->current();
    		$data[] = $value->toArray();
            $result->next();
    	}
    	return $this->getSuccessResponseWithData($data);
    }
}