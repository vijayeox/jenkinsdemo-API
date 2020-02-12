<?php
namespace Oxzion\AppDelegate;
use Oxzion\Service\CommunicationService;

class CommunicationDelegate
{
    protected $baseUrl;
    protected $communicationService;
	public function __construct($config) {
		// parent::__construct();
        $this->config = $config;
        $this->communicationService = new CommunicationService($config);
	}

	public function sendSms($data, $body) {
        $messageReturn = $this->communicationService->sendSms($data, $body);
        return $messageReturn;
	}
}
