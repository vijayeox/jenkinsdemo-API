<?php
namespace Mailbox\Controller;

use Zend\Log\Logger;
use Mailbox\Model\Messages;
use Mailbox\Model\MessagesTable;
use Oxzion\Controller\AbstractApiController;

class MessagesController extends AbstractApiController {

    public function __construct(Logger $log){
        parent::__construct($log, __CLASS__, Messages::class);
        $this->setIdentifierName('messagesId');
    }
}