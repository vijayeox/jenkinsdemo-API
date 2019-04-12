<?php

namespace Callback;

use Oxzion\Error\ErrorHandler;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Oxzion\Service\UserService;

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
                Service\ChatService::class => function($container){
                    return new Service\ChatService($container->get('config'),$container->get('CallbackLogger'));
                },
                Service\CRMService::class => function($container){
                    return new Service\CRMService($container->get('config'),$container->get('CallbackLogger'));
                },

                \Contact\Service\ContactService::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new \Contact\Service\ContactService($container->get('config'), $dbAdapter, $container->get(\Contact\Model\ContactTable::class));
                },
                \Contact\Model\ContactTable::class => function ($container) {
                    $tableGateway = $container->get(\Contact\Model\ContactTableGateway::class);
                    return new \Contact\Model\ContactTable($tableGateway);
                },
                \Contact\Model\ContactTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new \Contact\Model\Contact());
                    return new TableGateway('ox_contact', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\ChatCallbackController::class => function ($container) {
                    return new Controller\ChatCallbackController($container->get(Service\ChatService::class),$container->get('CallbackLogger'));
                },
                Controller\CRMCallbackController::class => function ($container) {
                    return new Controller\CRMCallbackController($container->get(Service\CRMService::class),$container->get(\Contact\Service\ContactService::class),$container->get(UserService::class),$container->get('CallbackLogger'));
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