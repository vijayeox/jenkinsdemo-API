<?php
namespace Oxzion\AppDelegate;
use Oxzion\Document\DocumentBuilder;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Service\TemplateService;
use Logger;


abstract class MailDelegate extends AbstractAppDelegate
{

	private $messageProducer;
	private $templateService;

	public function __construct(){
		parent::__construct();
	}
	
	public function setMessageProducer(MessageProducer $messageProducer){
		$this->messageProducer = $messageProducer;
	}

	public function setTemplateService(TemplateService $templateService){
		$this->templateService = $templateService;
	}

	protected function sendMail(array $data,string $template,array $mailOptions)
    {
    	$this->logger->info("SEND MAIL ----".print_r($data,true));
    	$orgUuid = isset($data['orgUuid']) ? $data['orgUuid'] : ( isset($data['orgId']) ? $data['orgId'] :AuthContext::get(AuthConstants::ORG_UUID));
    	$data['orgUuid'] = $orgUuid;

        $mailOptions['body'] = $this->templateService->getContent($template,$data);
        $userMail = $this->messageProducer->sendTopic(json_encode($mailOptions), 'mail');
    }
}
