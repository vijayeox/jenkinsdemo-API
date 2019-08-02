<?php
namespace Callback\Controller;

use Zend\Log\Logger;
use Oxzion\Controller\AbstractApiControllerHelper;
use Oxzion\ValidationException;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Service\TemplateService;

class OXCallbackController extends AbstractApiControllerHelper {

	private $messageProducer;
	private $templateService;
	private $config;
    // /**
    // * @ignore __construct
    // */
	public function __construct(TemplateService $templateService,$config,Logger $log) {
		$this->templateService = $templateService;
		$this->messageProducer = MessageProducer::getInstance();
		$this->config = $config;
	}

	public function userCreatedAction(){
		$params = $this->extractPostData();
		$params['baseurl'] = $this->config['baseUrl'];
		$this->messageProducer->sendTopic(json_encode(array(
			'To' => $params['email'],
			'Subject' => 'Your login details for OX Zion!!',
			'body' => $this->templateService->getContent('newUser', $params)
		)),'mail');
		return $this->getSuccessResponse();
	}
}