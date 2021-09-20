<?php

namespace Prehire;

use Oxzion\Error\ErrorHandler;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Prehire\Service\PrehireService;
use Prehire\Service\FoleyService;

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
                PrehireService::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new PrehireService($container->get('config'), $dbAdapter, $container->get(Model\PrehireTable::class));
                },
                FoleyService::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new FoleyService($container->get('config'), $dbAdapter, $container->get(Model\PrehireTable::class));
                },
                Model\PrehireTable::class => function ($container) {
                    $tableGateway = $container->get(Model\PrehireTableGateway::class);
                    return new Model\PrehireTable($tableGateway);
                },
                Model\PrehireTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Prehire());
                    return new TableGateway('ox_prehire', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\PrehireController::class => function ($container) {
                    return new Controller\PrehireController(
                        $container->get(Model\PrehireTable::class),
                        $container->get(PrehireService::class),
                        $container->get(AdapterInterface::class)
                    );
                },
                Controller\FoleyController::class => function ($container) {
                    return new Controller\FoleyController(
                        $container->get(Model\PrehireTable::class),
                        $container->get(FoleyService::class),
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
