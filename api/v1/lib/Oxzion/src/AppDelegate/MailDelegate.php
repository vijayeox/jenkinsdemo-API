<?php
namespace Oxzion\AppDelegate;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Service\TemplateService;
use Oxzion\Auth\AuthConstants;
use Oxion\Auth\AuthContext;


abstract class MailDelegate extends AbstractAppDelegate
{
	use UserContextTrait;
	private $messageProducer;
	private $templateService;
	public $mailConfig = Array();

	public function __construct() {
		parent::__construct();
		$this->mailConfig = ["host" => "http://localhost:8081/"];
	}

	public function setMessageProducer(MessageProducer $messageProducer) {
		$this->messageProducer = $messageProducer;
	}

	public function setTemplateService(TemplateService $templateService){
		$this->templateService = $templateService;
	}

	protected function sendMail(array $data,string $template,array $mailOptions)
    {
    	$this->logger->info("SEND MAIL ----".print_r($data,true));
    	$orgUuid = isset($data['orgUuid']) ? $data['orgUuid'] : ( isset($data['orgId']) ? $data['orgId'] : AuthContext::get(AuthConstants::ORG_UUID));
    	$data['orgUuid'] = $orgUuid;

        $mailOptions['body'] = $this->templateService->getContent($template,$data);
        $userMail = $this->messageProducer->sendQueue(json_encode($mailOptions), 'mail');
    }
}
