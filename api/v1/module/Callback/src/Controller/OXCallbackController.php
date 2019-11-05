<?php
namespace Callback\Controller;

use Oxzion\Controller\AbstractApiControllerHelper;
use Oxzion\ValidationException;
use Zend\Db\Adapter\AdapterInterface;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Service\TemplateService;

class OXCallbackController extends AbstractApiControllerHelper
{
    private $messageProducer;
    private $templateService;
    private $config;
    private $log;
    // /**
    // * @ignore __construct
    // */
    public function __construct(TemplateService $templateService, $config,MessageProducer $messageProducer)
    {
        $this->templateService = $templateService;
        $this->messageProducer = $messageProducer;
        $this->config = $config;
        $this->log = $this->getLogger();
    }

    public function userCreatedAction()
    {
        $params = $this->extractPostData();
        $params['baseurl'] = $this->config['baseUrl'];
        $this->messageProducer->sendQueue(json_encode(array(
            'to' => $params['email'],
            'subject' => 'Your login details for OX Zion!!',
            'body' => $this->templateService->getContent('newUser', $params)
        )), 'mail');
        return $this->getSuccessResponse();
    }
}
