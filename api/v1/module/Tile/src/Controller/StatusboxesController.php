<?php
namespace Tile\Controller;

use Zend\Log\Logger;
use Tile\Model\Statusboxes;
use Tile\Model\StatusboxesTable;
use Oxzion\Controller\AbstractApiController;

class StatusboxesController extends AbstractApiController {

    public function __construct(Logger $log){
        parent::__construct($log, __CLASS__, Statusboxes::class);
        $this->setIdentifierName('statusboxesId');
    }
}