<?php
namespace Chat\Controller;

use Zend\Log\Logger;
use Oxzion\Model\Table\ChatTable;
use Oxzion\Model\Entity\Chat;
use Oxzion\Controller\AbstractApiController;

class ChatController extends AbstractApiController {

    public function __construct(Logger $log){
        parent::__construct($log, __CLASS__, Chat::class);
        $this->setIdentifierName('cometchatId');
    }
}