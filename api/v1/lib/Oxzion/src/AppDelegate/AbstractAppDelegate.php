<?php
namespace Oxzion\AppDelegate;
use Oxzion\Document\DocumentBuilder;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Service\TemplateService;
use Logger;


abstract class AbstractAppDelegate implements AppDelegate
{
	protected $logger;
	
	public function __construct(){
		$this->logger = Logger::getLogger(__CLASS__);
	}
}
