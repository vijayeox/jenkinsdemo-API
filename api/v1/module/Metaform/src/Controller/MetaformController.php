<?php
namespace Metaform\Controller;

use Zend\Log\Logger;
use Metaform\Model\Metaform;
use Metaform\Model\MetaformTable;
use Oxzion\Controller\AbstractApiController;

class MetaformController extends AbstractApiController
{

	public function __construct(Logger $log){
		parent::__construct($log, __CLASS__,new Metaform);
		$this->setIdentifierName('formId');
	}

}
