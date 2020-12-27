<?php

namespace Esign;

use Oxzion\Error\ErrorHandler;
use Oxzion\Service\EsignService;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Oxzion\Messaging\MessageProducer;

class Module implements ConfigProviderInterface
{
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'onDispatchError'), 0);
        $eventManager->attach(MvcEvent::EVENT_RENDER_ERROR, array($this, 'onRenderError'), 0);
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                EsignService::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new EsignService($container->get('config'), $dbAdapter, $container->get(\Oxzion\Model\Esign\EsignDocumentTable::class), $container->get(\Oxzion\Model\Esign\EsignDocumentSignerTable::class),$container->get(\Oxzion\Messaging\MessageProducer::class));
                },
                \Oxzion\Model\Esign\EsignDocumentTable::class => function ($container) {
                    $tableGateway = $container->get(\Oxzion\Model\Esign\EsignDocumentGateway::class);
                    return new \Oxzion\Model\Esign\EsignDocumentTable($tableGateway);
                },
                \Oxzion\Model\Esign\EsignDocumentGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new \Oxzion\Model\Esign\EsignDocument());
                    return new TableGateway('ox_esign_document', $dbAdapter, null, $resultSetPrototype);
                },
                \Oxzion\Model\Esign\EsignDocumentSignerTable::class => function ($container) {
                    $tableGateway = $container->get(\Oxzion\Model\Esign\EsignDocumentSignerTableGateway::class);
                    return new \Oxzion\Model\Esign\EsignDocumentSignerTable($tableGateway);
                },
                \Oxzion\Model\Esign\EsignDocumentSignerTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new \Oxzion\Model\Esign\EsignDocumentSigner());
                    return new TableGateway('ox_esign_document_signer', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\EsignController::class => function ($container) {
                    return new Controller\EsignController(
                        $container->get(EsignService::class)                    );
                },
                
            ],
        ];
    }

    public function onDispatchError($e)
    {
        return ErrorHandler::getJsonModelError($e);
    }

    public function onRenderError($e)
    {
        return ErrorHandler::getJsonModelError($e);
    }
}
