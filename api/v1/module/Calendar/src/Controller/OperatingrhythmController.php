<?php
namespace Calendar\Controller;

use Zend\Log\Logger;
use Calendar\Model\Operatingrhythm;
use Calendar\Model\OperatingrhythmTable;
use Oxzion\Controller\AbstractApiController;

class OperatingrhythmController extends AbstractApiController {

    public function __construct(OperatingrhythmTable $table, Logger $log){
        parent::__construct($table, $log, __CLASS__, Operatingrhythm::class);
        $this->setIdentifierName('operatingrhythmId');
    }
}