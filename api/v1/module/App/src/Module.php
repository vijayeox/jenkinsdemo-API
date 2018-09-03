<?php

namespace App;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\JsonModel;
use Oxzion\Error\ErrorHandler;
use Oxzion\Model\Table\ModuleTable;
use Oxzion\Model\Entity\App;
use Oxzion\Model\Table\AppCategoryTable;
use Oxzion\Model\Entity\ModuleCategory;

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
            ],
        ];
    }
    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\AppCategoryController::class => function($container) {
                    return new Controller\AppCategoryController($container->get('AppLogger'));
                },
                Controller\AppController::class => function($container) {
                    return new Controller\AppController($container->get('AppLogger'));
                },
                Controller\FilesController::class => function($container) {
                    return new Controller\FilesController($container->get('AppLogger'));
                },
                Controller\FormController::class => function($container) {
                    return new Controller\FormController($container->get('AppLogger'));
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