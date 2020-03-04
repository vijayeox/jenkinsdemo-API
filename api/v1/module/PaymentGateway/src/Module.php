<?php

namespace PaymentGateway;

use Oxzion\Error\ErrorHandler;
use Oxzion\Service\PaymentService;
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
                PaymentService::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new PaymentService($container->get('config'), $dbAdapter, $container->get(Model\PaymentTable::class), $container->get(Model\PaymentTransactionTable::class));
                },
                Model\PaymentTable::class => function ($container) {
                    $tableGateway = $container->get(Model\PaymentTableGateway::class);
                    return new Model\PaymentTable($tableGateway);
                },
                Model\PaymentTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Payment());
                    return new TableGateway('ox_payment', $dbAdapter, null, $resultSetPrototype);
                },
                Model\PaymentTransactionTable::class => function ($container) {
                    $tableGateway = $container->get(Model\PaymentTransactionTableGateway::class);
                    return new Model\PaymentTransactionTable($tableGateway);
                },
                Model\PaymentTransactionTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Payment());
                    return new TableGateway('ox_payment_transaction', $dbAdapter, null, $resultSetPrototype);
                },
            ],
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\PaymentGatewayController::class => function ($container) {
                    return new Controller\PaymentGatewayController(
                        $container->get(Model\PaymentTable::class),
                        $container->get(PaymentService::class),
                        $container->get(AdapterInterface::class)
                    );
                },
                Controller\PaymentCallbackController::class => function ($container) {
                    return new Controller\PaymentCallbackController(
                        $container->get(PaymentService::class)
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
