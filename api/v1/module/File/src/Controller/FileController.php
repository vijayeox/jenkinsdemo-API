<?php
namespace File\Controller;

use Zend\Log\Logger;
use Oxzion\Model\Entity\File;
use Oxzion\Controller\AbstractApiController;

class FileController extends AbstractApiController {

    public function __construct(Logger $log){
        parent::__construct($log, __CLASS__, new File());
        $this->setIdentifierName('fileId');
    }
    public function get($id){
    	$file = new File($id);
    	$data = $file->checkAccess($this->currentAvatarObj);
    	if(is_null($data)){
            return $this->getErrorResponse("Entity not found for id - $id", 404);
        }
        if(is_array($data) && isset($data['response'])){
            return $this->getErrorResponse("You do not have Permission to Access this File - $id", 200);
        }
        return $this->getSuccessResponseWithData($data);
    }
}