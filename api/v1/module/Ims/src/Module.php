<?php

namespace Ims;

use Oxzion\Error\ErrorHandler;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Insurance\Ims\Service as ImsService;
use Zend\Db\Adapter\AdapterInterface;
use Zend\ModuleManager\Feature\ConfigProviderInterface;

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
                ImsService::class => function ($container) {
                    return new ImsService(
                        $container->get('config'),
                        $container->get(AdapterInterface::class),
                        $container->get(\Oxzion\Messaging\MessageProducer::class)
                    );
                },

            ],
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\ProducerController::class => function ($container) {
                    return new Controller\ProducerController(
                        $container->get(ImsService::class)
                    );
                },
                Controller\InsuredController::class => function ($container) {
                    return new Controller\InsuredController(
                        $container->get(ImsService::class)
                    );
                },
                Controller\QuoteController::class => function ($container) {
                    return new Controller\QuoteController(
                        $container->get(ImsService::class)
                    );
                },
                Controller\DocumentController::class => function ($container) {
                    return new Controller\DocumentController(
                        $container->get(ImsService::class)
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
