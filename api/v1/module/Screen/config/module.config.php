<?php
namespace Screen;

use Zend\Log\Logger;
use Zend\Router\Http\Segment;
use Zend\Log\Formatter\Simple;
use Zend\Log\Filter\Priority;
use Zend\Log\Processor\RequestId;

return [
    'router' => [
        'routes' => [
            'screen' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/screen[/:screenId]',
                    'defaults' => [
                        'controller' => Controller\ScreenController::class
                    ],
                ],
            ],
            'screenwidget' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/screen/:screenId/widget[/:id]',
                    'defaults' => [
                        'controller' => Controller\ScreenwidgetController::class
                    ],
                ],
            ],
        ],
    ],
    'log' => [
        'ScreenLogger' => [
            'writers' => [
                'stream' => [
                    'name' => 'stream',
                    'priority' => \Zend\Log\Logger::ALERT,
                    'options' => [
                        'stream' => __DIR__ . '/../../../logs/screen.log',
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
