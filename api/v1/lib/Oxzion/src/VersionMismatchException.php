<?php

namespace Oxzion;

class VersionMismatchException extends \Oxzion\ServiceException {

	private $obj;

    public function __construct($returnObject){
        parent::__construct('Entity version sent by client does not match the version on server.', 'VERSION_MISMATCH');
        $this->obj = $returnObject;
    }

    public function getReturnObject(){
    	return $this->obj;
    }
}

?>

