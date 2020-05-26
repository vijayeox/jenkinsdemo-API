<?php
namespace Oxzion\AppDelegate;
use Logger;


abstract class TemplateAppDelegate extends AbstractAppDelegate
{
	use UserContextTrait;
	protected $logger;
	protected $destination;

	public function __construct(){
		$this->logger = Logger::getLogger(__CLASS__);
	}

    public function setTemplatePath($destination){
    	$this->destination = $destination;
    }
}
