<?php
namespace Oxzion\AppDelegate;

use Exception;
use Oxzion\AppDelegate\DocumentAppDelegate;
use Oxzion\AppDelegate\MailDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\Document\DocumentBuilder;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Service\AbstractService;
use Oxzion\Service\TemplateService;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;
use Oxzion\Utils\FileUtils;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;


class FileDelegateService implements AppDelegate
{

    protected $logger;

    public function setLogger(Logger $logger){
         $this->logger = $logger;
    }

}
