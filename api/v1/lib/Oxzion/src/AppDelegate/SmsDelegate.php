<?php
namespace Oxzion\AppDelegate;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Service\TemplateService;
use Oxzion\Auth\AuthConstants;
use Oxion\Auth\AuthContext;


abstract class SmsDelegate extends CommunicationDelegate
{
	
	public function __construct() {
		parent::__construct();
	}

	protected function sendSms(array $data,string $template,array $smsOptions)
    {
    	$this->logger->info("SEND Sms ----".print_r($data,true));
    	$orgUuid = isset($data['orgUuid']) ? $data['orgUuid'] : ( isset($data['orgId']) ? $data['orgId'] : AuthContext::get(AuthConstants::ORG_UUID));
    	$data['orgUuid'] = $orgUuid;

        $smsOptions['body'] = $this->templateService->getContent($template,$data);
		$response = $this->sendMessage($smsOptions, 'SEND_SMS');
		return $response;
    }
}
