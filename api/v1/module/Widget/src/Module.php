<?php

namespace Widget;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\JsonModel;
use Oxzion\Error\ErrorHandler;

class Module implements ConfigProviderInterface {

    public function getConfig() {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e) {
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
                Model\WidgetTable::class => function($container) {
                    $tableGateway = $container->get(Model\WidgetTableGateway::class);
                    return new Model\WidgetTable($tableGateway);
                },
                Model\WidgetTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Widget());
                    return new TableGateway('ox_widget', $dbAdapter, null, $resultSetPrototype);
                },
                Service\WidgetService::class => function($container){
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new Service\WidgetService($container->get('config'), $dbAdapter, $container->get(Model\WidgetTable::class));
                },
            ],
        ];
    }
    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\WidgetController::class => function($container) {
                    return new Controller\WidgetController(
                        $container->get(Model\WidgetTable::class),$container->get(Service\WidgetService::class),$container->get('WidgetLogger'));
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