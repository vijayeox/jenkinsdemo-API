<?php

namespace Auth;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Auth\Service\AuthService;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\JsonModel;
use Oxzion\Error\ErrorHandler;
use Oxzion\Service\UserService;
use Oxzion\Service\UserCacheService;
use Oxzion\Service\UserTokenService;
use Auth\Adapter\LoginAdapter as AuthAdapter;
use Zend\Authentication\Adapter\DbTable\CredentialTreatmentAdapter as ApiAdapter;

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
                Adapter\LoginAdapter::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new Adapter\LoginAdapter($dbAdapter, 'ox_user', 'username', 'password', 'MD5(SHA1(?))');
                },
                ApiAdapter::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new ApiAdapter($dbAdapter, 'ox_api_key', 'api_key', 'secret');
                },
                Service\AuthService::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new Service\AuthService($container->get('config'), $dbAdapter,$container->get(UserService::class),$container->get(UserCacheService::class));
                },
            ],
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\AuthController::class => function ($container) {
                    return new Controller\AuthController(
                        $container->get(Adapter\LoginAdapter::class),
                        $container->get(ApiAdapter::class),
                        $container->get(UserService::class),
                        $container->get('AuthLogger'),
                        $container->get(UserTokenService::class),
                        $container->get(Service\AuthService::class)
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
