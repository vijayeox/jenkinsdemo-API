<?php

namespace Mlet;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\JsonModel;
use Oxzion\Error\ErrorHandler;

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
                Model\MletTable::class => function ($container) {
                    $tableGateway = $container->get(Model\MletTableGateway::class);
                    return new Model\MletTable($tableGateway);
                },
                Model\MletTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Mlet());
                    return new TableGateway('ox_mlet', $dbAdapter, null, $resultSetPrototype);
                },
                Service\MletService::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new Service\MletService($container->get('config'), $dbAdapter, $container->get(Model\MletTable::class), $container->get(\Oxzion\Analytics\AnalyticsEngine::class));
                },
                
            ],
        ];
    }
    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\MletController::class => function ($container) {
                    return new Controller\MletController(
                        $container->get(Model\MletTable::class),
                        $container->get(Service\MletService::class)
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
