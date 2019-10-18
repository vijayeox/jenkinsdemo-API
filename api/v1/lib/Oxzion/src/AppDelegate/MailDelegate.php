<?php
namespace Oxzion\AppDelegate;
use Oxzion\Document\DocumentBuilder;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Service\TemplateService;
use Logger;


abstract class MailDelegate implements AppDelegate
{

	protected $logger;
	private $messageProducer;
	private $templateService;

	public function setLogger(Logger $logger){
		 $this->logger = $logger;
	}

	public function setMessageProducer(MessageProducer $messageProducer){
		$this->messageProducer = $messageProducer;
	}

	public function setTemplateService(TemplateService $templateService){
		$this->templateService = $templateService;
	}

	protected function sendMail(array $data,string $template,array $mailOptions)
    {
        $mailOptions['body'] = $this->templateService->getContent($template,$data);
        $userMail = $this->messageProducer->sendTopic(json_encode($mailOptions), 'mail');
    }
}
