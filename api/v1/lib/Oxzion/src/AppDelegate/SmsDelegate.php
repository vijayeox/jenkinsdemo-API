<?php
namespace Oxzion\AppDelegate;

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
		$response = $this->sendMessage($smsOptions, 'twillio_sms');
		return $response;
    }
}
