<?php
namespace Announcement\Controller;

use Zend\Log\Logger;
use Oxzion\Model\Entity\Alerts;
use Oxzion\Model\Table\AlertTable;
use Oxzion\Controller\AbstractApiController;

class AlertController extends AbstractApiController {

    public function __construct(Logger $log){
        parent::__construct($log, __CLASS__, Alert::class);
        $this->setIdentifierName('alertsId');
    }
}