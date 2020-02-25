<?php
namespace Oxzion\AppDelegate;

use Oxzion\Messaging\MessageProducer;
use Oxzion\Service\TemplateService;

abstract class CommunicationDelegate extends AbstractAppDelegate
{
    use UserContextTrait;
	private $messageProducer;
	protected $templateService;
	protected $baseUrl;

	public function __construct() {
		parent::__construct();
	}

	public function setMessageProducer(MessageProducer $messageProducer) {
		$this->messageProducer = $messageProducer;
	}

	public function setTemplateService(TemplateService $templateService){
		$this->templateService = $templateService;
	}

    public function setBaseUrl($baseUrl){
        $this->baseUrl = $baseUrl;
    }
    
    protected function sendMessage(array $options, $queue){
        $response = $this->messageProducer->sendQueue(json_encode($options), $queue);
		return $response;
    }
}
