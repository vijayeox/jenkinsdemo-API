<?php
namespace Avatar\Controller;

use Zend\Log\Logger;
use Oxzion\Model\Entity\Avatar;
use Oxzion\Model\Table\AvatarTable;
use Oxzion\Model\Table\GenericTable;
use Oxzion\Model\Entity\Group;
use Oxzion\Model\Table\GroupTable;
use Zend\View\Model\JsonModel;
use Oxzion\Controller\AbstractApiController;

class AvatarController extends AbstractApiController {

    public function __construct(Logger $log){
        parent::__construct($log, __CLASS__, new Avatar());
        $this->setIdentifierName('avatarId');
    }
}