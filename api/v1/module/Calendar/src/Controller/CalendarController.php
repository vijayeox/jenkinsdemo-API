<?php
namespace Calendar\Controller;

use Zend\Log\Logger;
use Oxzion\Model\Table\CalendarTable;
use Oxzion\Model\Entity\Calendar;
use Oxzion\Controller\AbstractApiController;

class CalendarController extends AbstractApiController {

	public function __construct(Logger $log){
		parent::__construct($log, __CLASS__, Calendar::class);
		$this->setIdentifierName('operatingrhythmId');
	}
}