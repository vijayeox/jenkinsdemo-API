<?php
namespace Field\Controller;

use Zend\Log\Logger;
use Oxzion\Controller\AbstractApiController;
use Oxzion\Model\Entity\Field;
use Oxzion\Model\Table\FieldTable;

class FieldController extends AbstractApiController
{
	public function __construct(Logger $log){
		parent::__construct($log, __CLASS__, Field::class);
	}

}
