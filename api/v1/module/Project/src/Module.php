<?php

namespace Project;

use Oxzion\Error\ErrorHandler;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Service\AccountService;
use Oxzion\Service\UserService;
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
                Service\ProjectService::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $acctService = $container->get(AccountService::class);
                    $userService = $container->get(UserService::class);
                    return new Service\ProjectService($container->get('config'), 
                                                      $dbAdapter, 
                                                      $container->get(Model\ProjectTable::class), 
                                                      $acctService, 
                                                      $userService,
                                                      $container->get(MessageProducer::class));
                },
                Model\ProjectTable::class => function ($container) {
                    $tableGateway = $container->get(Model\ProjectTableGateway::class);
                    return new Model\ProjectTable($tableGateway);
                },
                Model\ProjectTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Project());
                    return new TableGateway('ox_project', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\ProjectController::class => function ($container) {
                    return new Controller\ProjectController(
                        $container->get(Model\ProjectTable::class),
                        $container->get(Service\ProjectService::class),
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
