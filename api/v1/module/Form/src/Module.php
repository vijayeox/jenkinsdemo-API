<?php

namespace Form;

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
use Oxzion\Service\FieldService;
use Oxzion\Service\FormService;

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
                Controller\FormController::class => function($container) {
                    return new Controller\FormController(
                        $container->get(FormTable::class),$container->get(FormService::class),
                        $container->get('FormLogger'),
                        $container->get(AdapterInterface::class)
                    );
                },
                Controller\FieldController::class => function($container) {
                    return new Controller\FieldController(
                        $container->get(FieldTable::class),$container->get(FieldService::class),
                        $container->get('FormLogger'),
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
