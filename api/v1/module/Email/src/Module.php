<?php

namespace Email;

use Oxzion\Error\ErrorHandler;
use Oxzion\Model\EmailTable;
use Oxzion\Service\EmailService;
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
                Service\DomainService::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new Service\DomainService($container->get('config'), $dbAdapter, $container->get(Model\DomainTable::class));
                },
                Model\DomainTable::class => function ($container) {
                    $tableGateway = $container->get(Model\DomainTableGateway::class);
                    return new Model\DomainTable($tableGateway);
                },
                Model\DomainTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Domain());
                    return new TableGateway('ox_email_domain', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\EmailController::class => function ($container) {
                    return new Controller\EmailController(
                        $container->get(EmailTable::class),
                        $container->get(EmailService::class),
                        $container->get(AdapterInterface::class)
                    );
                },
                Controller\DomainController::class => function ($container) {
                    return new Controller\DomainController(
                        $container->get(Model\DomainTable::class),
                        $container->get(Service\DomainService::class),
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
