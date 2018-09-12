<?php

namespace Metaform;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\JsonModel;
use Oxzion\Error\ErrorHandler;
use Oxzion\Service\UserService;

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
                UserService::class => function($container) {
                    $config = $container->get('config');
                    return new UserService($config);
                },
                Model\MetaformTable::class => function($container) {
                    $tableGateway = $container->get(Model\MetaformTableGateway::class);
                    return new Model\MetaformTable($tableGateway);
                },
                Model\MetaformTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Metaform());
                    return new TableGateway('metaforms', $dbAdapter, null, $resultSetPrototype);
                },
                Model\MetafieldTable::class => function($container) {
                    $tableGateway = $container->get(Model\MetafieldTableGateway::class);
                    return new Model\MetafieldTable($tableGateway);
                },
                Model\MetafieldTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Metafield());
                    return new TableGateway('metafields', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\MetaformController::class => function($container) {
                    return new Controller\MetaformController(
                        $container->get(Model\MetaformTable::class),
                        $container->get('MetaformLogger')
                    );
                },
                Controller\MetafieldController::class => function($container) {
                    return new Controller\MetafieldController(
                        $container->get(Model\MetafieldTable::class),
                        $container->get('MetaformLogger'),
                        $container->get(AdapterInterface::class)
                    );
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
