<?php

namespace Workflow;

use Oxzion\Error\ErrorHandler;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Service\FileService;
use Oxzion\Service\TemplateService;
use Oxzion\Service\UserService;
use Oxzion\Service\WorkflowInstanceService;
use Oxzion\Service\WorkflowService;
use Oxzion\Service\CommandService;
use Oxzion\Service\ServiceTaskService;
use Oxzion\Service\ActivityInstanceService;
use Oxzion\Model\WorkflowInstanceTable;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

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
            ],
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\WorkflowInstanceController::class => function ($container) {
                    return new Controller\WorkflowInstanceController(
                        $container->get(WorkflowInstanceTable::class),
                        $container->get(WorkflowInstanceService::class),
                        $container->get(WorkflowService::class),
                        $container->get(ActivityInstanceService::class),
                        $container->get(AdapterInterface::class)
                    );
                },
                Controller\WorkflowInstanceCallbackController::class => function ($container) {
                    return new Controller\WorkflowInstanceCallbackController(
                        $container->get(WorkflowInstanceTable::class),
                        $container->get(WorkflowInstanceService::class),
                        $container->get(AdapterInterface::class)
                    );
                },
                Controller\ActivityInstanceController::class => function ($container) {
                    return new Controller\ActivityInstanceController(
                        $container->get(ActivityInstanceService::class),
                        $container->get(WorkflowInstanceService::class),
                        $container->get(CommandService::class)
                    );
                },
                Controller\ServiceTaskController::class => function ($container) {
                    return new Controller\ServiceTaskController(
                        $container->get(ServiceTaskService::class),
                        $container->get(WorkflowInstanceService::class)
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
