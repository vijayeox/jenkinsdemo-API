<?php
namespace Widget;

use Zend\Log\Logger;
use Zend\Router\Http\Segment;
use Zend\Log\Formatter\Simple;
use Zend\Log\Filter\Priority;
use Zend\Log\Processor\RequestId;

return [
	'router' => [
        'routes' => [
            'widget' => [
                'type'    => Segment::class,
                'options' => [
                	'route'    => '/widget[/:widgetId]',
                    'defaults' => [
                        'controller' => Controller\WidgetController::class,
                        'access'=>[
                            // SET ACCESS CONTROL
                            'put'=> 'MANAGE_WIDGET_WRITE',
                            'post'=> 'MANAGE_WIDGET_WRITE',
                            'delete'=> 'MANAGE_WIDGET_WRITE',
                            'get'=> 'MANAGE_WIDGET_READ',
                        ],
                    ],
                ],
            ],            
        ],
    ],
    'log' => [
        'WidgetLogger' => [
            'writers' => [
                'stream' => [
                    'name' => 'stream',
                    'priority' => \Zend\Log\Logger::ALERT,
                    'options' => [
                        'stream' => __DIR__ . '/../../../logs/Widget.log',
                        'formatter' => [
                            'name' => \Zend\Log\Formatter\Simple::class,
                            'options' => [
                                'format' => '%timestamp% %priorityName% (%priority%): %message% %extra%',
                                'dateTimeFormat' => 'c',
                            ],
                        ],
                        'filters' => [
                            'priority' => \Zend\Log\Logger::INFO,
                        ],
                    ],
                ],
            ],
            'processors' => [
                'requestid' => [
                    'name' => \Zend\Log\Processor\RequestId::class,
                ],
            ],
        ],
    ],
    'view_manager' => [
        // We need to set this up so that we're allowed to return JSON
        // responses from our controller.
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
    
];