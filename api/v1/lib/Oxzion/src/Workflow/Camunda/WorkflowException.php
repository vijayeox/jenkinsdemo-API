<?php

namespace Oxzion\Workflow\Camunda;
use Exception;

class WorkflowException extends Exception{
    private $reason;

    public function __construct($message, $reason, $code = 0){
        $this->reason = $reason;
        parent::__construct($message, $code);
    }

    public function getReason(){
        return $this->reason;
    }
}

?>