<?php

namespace Analytics;

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

    public function getServiceConfig() {
        return [
            'factories' => [
                Service\DataSourceService::class => function($container){
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new Service\DataSourceService($container->get('config'), $dbAdapter, $container->get(Model\DataSourceTable::class));
                },
                Model\DataSourceTable::class => function($container) {
                    $tableGateway = $container->get(Model\DataSourceTableGateway::class);
                    return new Model\DataSourceTable($tableGateway);
                },
                Model\DataSourceTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\DataSource());
                    return new TableGateway('datasource', $dbAdapter, null, $resultSetPrototype);
                },
                Service\QueryService::class => function($container){
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new Service\QueryService($container->get('config'), $dbAdapter, $container->get(Model\QueryTable::class));
                },
                Model\QueryTable::class => function($container) {
                    $tableGateway = $container->get(Model\QueryTableGateway::class);
                    return new Model\QueryTable($tableGateway);
                },
                Model\QueryTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Query());
                    return new TableGateway('query', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }

    public function getControllerConfig() {
        return [
            'factories' => [
                Controller\DataSourceController::class => function($container) {
                    return new Controller\DataSourceController(
                            $container->get(Model\DataSourceTable::class), $container->get(Service\DataSourceService::class), $container->get('AnalyticsLogger'),
                        $container->get(AdapterInterface::class));
                },
                Controller\QueryController::class => function($container) {
                    return new Controller\QueryController(
                            $container->get(Model\QueryTable::class), $container->get(Service\QueryService::class), $container->get('AnalyticsLogger'),
                        $container->get(AdapterInterface::class));
                },
            ],
        ];
    }

    public function onDispatchError($e) {
        return ErrorHandler::getJsonModelError($e);
    }

    public function onRenderError($e) {
        return ErrorHandler::getJsonModelError($e);
    }

}
