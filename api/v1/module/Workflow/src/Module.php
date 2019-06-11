<?php

namespace Workflow;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\JsonModel;
use Oxzion\Error\ErrorHandler;
use Oxzion\Service\WorkflowService;

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
                Service\WorkflowInstanceService::class => function($container){
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new Service\WorkflowInstanceService($container->get('config'), $dbAdapter, 
                    $container->get(Model\WorkflowInstanceTable::class),
                    $container->get(\Oxzion\Service\FileService::class),
                    $container->get(\Oxzion\Service\WorkflowService::class),
                    $container->get(\Oxzion\Workflow\WorkflowFactory::class));
                },
                Model\WorkflowInstanceTable::class => function($container) {
                    $tableGateway = $container->get(Model\WorkflowInstanceTableGateway::class);
                    return new Model\WorkflowInstanceTable($tableGateway);
                },
                Model\WorkflowInstanceTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\WorkflowInstance());
                    return new TableGateway('ox_workflow_instance', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }

    public function getControllerConfig() {
        return [
            'factories' => [
                Controller\WorkflowInstanceController::class => function($container) {
                    return new Controller\WorkflowInstanceController(
                        $container->get(Model\WorkflowInstanceTable::class),$container->get(Service\WorkflowInstanceService::class),$container->get(WorkflowService::class),
                        $container->get('AppLogger'),
                        $container->get(AdapterInterface::class)
                    );
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
