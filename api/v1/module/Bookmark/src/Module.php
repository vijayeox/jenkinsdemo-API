<?php

namespace Bookmark;

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
                Service\BookmarkService::class => function($container){
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new Service\BookmarkService($container->get('config'), $dbAdapter, $container->get(Model\BookmarkTable::class));
                },
                Model\BookmarkTable::class => function($container) {
                    $tableGateway = $container->get(Model\BookmarkTableGateway::class);
                    return new Model\BookmarkTable($tableGateway);
                },
                Model\BookmarkTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Bookmark());
                    return new TableGateway('links', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }

    public function getControllerConfig() {
        return [
            'factories' => [
                Controller\BookmarkController::class => function($container) {
                    return new Controller\BookmarkController(
                            $container->get(Model\BookmarkTable::class), $container->get(Service\BookmarkService::class), $container->get('BookmarkLogger'),
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
