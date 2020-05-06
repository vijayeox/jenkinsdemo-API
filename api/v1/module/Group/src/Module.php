<?php

namespace Group;

use Oxzion\Error\ErrorHandler;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Service\OrganizationService;
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
                Service\GroupService::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $orgService = $container->get(OrganizationService::class);
                    $userService = $container->get(UserService::class);
                    return new Service\GroupService($container->get('config'), $dbAdapter, $container->get(Model\GroupTable::class), $orgService, $container->get(MessageProducer::class));
                },
                Model\GroupTable::class => function ($container) {
                    $tableGateway = $container->get(Model\GroupTableGateway::class);
                    return new Model\GroupTable($tableGateway);
                },
                Model\GroupTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Group());
                    return new TableGateway('ox_group', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\GroupController::class => function ($container) {
                    return new Controller\GroupController(
                        $container->get(Model\GroupTable::class),
                        $container->get(Service\GroupService::class),
                        $container->get(AdapterInterface::class),
                        $container->get(OrganizationService::class)
                    );
                },
                Controller\GroupLogoController::class => function ($container) {
                    return new Controller\GroupLogoController(
                        $container->get(Service\GroupService::class),
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
