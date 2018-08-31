<?php
namespace Group\Controller;

use Zend\Log\Logger;
use Oxzion\Controller\AbstractApiController;
use Oxzion\Model\Entity\Group;

class GroupController extends AbstractApiController {

    public function __construct(Logger $log){
        parent::__construct($log, __CLASS__, new Group());
        $this->setIdentifierName('groupId');
    }
}