<?php

namespace User;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\JsonModel;
use Oxzion\Error\ErrorHandler;
use Oxzion\Model\UserTable;
use Oxzion\Model\RoleTable;
use Oxzion\Service\UserService;
use Oxzion\Service\RoleService;
use Oxzion\Service\ProfilePictureService;
use Oxzion\Service\EmailService;
use Oxzion\Service\UserSessionService;
use Project\Service\ProjectService;
use Oxzion\Service\EmployeeService;

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
                Controller\UserController::class => function ($container) {
                    return new Controller\UserController(
                        $container->get(UserTable::class),
                        $container->get(UserService::class),
                        $container->get(AdapterInterface::class),
                        $container->get(EmailService::class),
                        $container->get(ProjectService::class),
                        $container->get(EmployeeService::class)
                    );
                },
                Controller\ProfilePictureController::class => function ($container) {
                    return new Controller\ProfilePictureController(
                        $container->get(ProfilePictureService::class),
                        $container->get(AdapterInterface::class)
                    );
                },
                 Controller\ProfilePictureDownloadController::class => function ($container) {
                     return new Controller\ProfilePictureDownloadController(
                        $container->get(ProfilePictureService::class),
                        $container->get(AdapterInterface::class),
                        $container->get(UserService::class)
                    );
                 },
                Controller\UserSessionController::class => function ($container) {
                    return new Controller\UserSessionController(
                        $container->get(UserSessionService::class),
                        $container->get(AdapterInterface::class)
                    );
                },
                Controller\ForgotPasswordController::class => function($container) {
                    return new Controller\ForgotPasswordController(
                        $container->get(UserService::class)
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
