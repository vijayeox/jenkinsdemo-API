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
use Oxzion\Model\FormTable;
use Oxzion\Model\FieldTable;
use Oxzion\Model\FileTable;
use Oxzion\Model\WorkflowTable;
use Oxzion\Service\FieldService;
use Oxzion\Service\FileService;
use Oxzion\Service\FormService;
use Oxzion\Service\WorkflowService;
use Group\Service\GroupService;

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
                Service\AppService::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new Service\AppService($container->get('config'), $dbAdapter, $container->get(Model\AppTable::class), $container->get(\Oxzion\Service\WorkflowService::class), $container->get(\Oxzion\Service\FormService::class), $container->get(\Oxzion\Service\FieldService::class));
                },
                Model\AppTable::class => function ($container) {
                    $tableGateway = $container->get(Model\AppTableGateway::class);
                    return new Model\AppTable($tableGateway);
                },
                Model\AppTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\App());
                    return new TableGateway('ox_app', $dbAdapter, null, $resultSetPrototype);
                },
                Service\MenuItemService::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new Service\MenuItemService($container->get('config'), $container->get(GroupService::class), $dbAdapter, $container->get(Model\MenuItemTable::class));
                },
                Model\MenuItemTable::class => function ($container) {
                    $tableGateway = $container->get(Model\MenuItemTableGateway::class);
                    return new Model\MenuItemTable($tableGateway);
                },
                Model\MenuItemTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\MenuItem());
                    return new TableGateway('ox_app_menu', $dbAdapter, null, $resultSetPrototype);
                },
                Service\PageService::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new Service\PageService($container->get('config'), $dbAdapter, $container->get(Model\PageTable::class));
                },
                Model\PageTable::class => function ($container) {
                    $tableGateway = $container->get(Model\PageTableGateway::class);
                    return new Model\PageTable($tableGateway);
                },
                Model\PageTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Page());
                    return new TableGateway('ox_app_page', $dbAdapter, null, $resultSetPrototype);
                },
                Service\PageContentService::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new Service\PageContentService($container->get('config'), $dbAdapter, $container->get(Model\PageContentTable::class));
                },
                Model\PageContentTable::class => function ($container) {
                    $tableGateway = $container->get(Model\PageContentTableGateway::class);
                    return new Model\PageContentTable($tableGateway);
                },
                Model\PageContentTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\PageContent());
                    return new TableGateway('ox_page_content', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\AppController::class => function ($container) {
                    return new Controller\AppController(
                        $container->get(Model\AppTable::class),
                        $container->get(Service\AppService::class),
                        $container->get('AppLogger'),
                        $container->get(AdapterInterface::class)
                    );
                },
                Controller\AppRegisterController::class => function ($container) {
                    return new Controller\AppRegisterController(
                        $container->get(Model\AppTable::class),
                        $container->get(Service\AppService::class),
                        $container->get('AppLogger'),
                        $container->get(AdapterInterface::class)
                    );
                },
                Controller\MenuItemController::class => function ($container) {
                    return new Controller\MenuItemController(
                        $container->get(Model\MenuItemTable::class),
                        $container->get(Service\MenuItemService::class),
                        $container->get('AppLogger'),
                        $container->get(AdapterInterface::class)
                    );
                },
                Controller\PageController::class => function ($container) {
                    return new Controller\PageController(
                        $container->get(Model\PageTable::class),
                        $container->get(Service\PageService::class),
                        $container->get(Service\PageContentService::class),
                        $container->get('AppLogger'),
                        $container->get(AdapterInterface::class)
                    );
                },
                Controller\PageContentController::class => function ($container) {
                    return new Controller\PageContentController(
                        $container->get(Model\PageContentTable::class),
                        $container->get(Service\PageContentService::class),
                        $container->get('AppLogger'),
                        $container->get(AdapterInterface::class)
                    );
                },
                Controller\FormController::class => function ($container) {
                    return new Controller\FormController(
                        $container->get(FormTable::class),
                        $container->get(FormService::class),
                        $container->get('AppLogger'),
                        $container->get(AdapterInterface::class)
                    );
                },
                Controller\FileController::class => function ($container) {
                    return new Controller\FileController(
                        $container->get(FileTable::class),
                        $container->get(FileService::class),
                        $container->get('AppLogger'),
                        $container->get(AdapterInterface::class)
                    );
                },
                Controller\FieldController::class => function ($container) {
                    return new Controller\FieldController(
                        $container->get(FieldTable::class),
                        $container->get(FieldService::class),
                        $container->get('AppLogger'),
                        $container->get(AdapterInterface::class)
                    );
                },
                Controller\WorkflowController::class => function ($container) {
                    return new Controller\WorkflowController(
                        $container->get(WorkflowTable::class),
                        $container->get(WorkflowService::class),
                        $container->get('AppLogger'),
                        $container->get(AdapterInterface::class)
                    );
                },
                Controller\ImportController::class => function ($container) {
                    return new Controller\ImportController(
                        $container->get(Service\ImportService::class),
                        $container->get('AppLogger'),
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
