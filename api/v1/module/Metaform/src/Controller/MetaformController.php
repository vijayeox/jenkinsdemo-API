<?php
namespace Metaform\Controller;

use Zend\Log\Logger;
use Metaform\Model\Metaform;
use Metaform\Model\MetaformTable;
use Oxzion\Controller\AbstractApiController;

class MetaformController extends AbstractApiController
{

	public function __construct(MetaformTable $table, Logger $log){
		parent::__construct($table, $log, __CLASS__, Metaform::class);
		$this->setIdentifierName('formId');
	}

}
