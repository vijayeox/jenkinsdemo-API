<?php
namespace Oxzion\AppDelegate;
use Logger;


abstract class AbstractDocumentAppDelegate implements DocumentAppDelegate
{
	protected $logger;
	
	public function __construct(){
		$this->logger = Logger::getLogger(__CLASS__);
	}
}
