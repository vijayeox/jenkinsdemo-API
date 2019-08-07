<?php
namespace Auth;

use Zend\Log\Logger;
use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\Log\Formatter\Simple;
use Zend\Log\Filter\Priority;
use Zend\Log\Processor\RequestId;

return [
    'router' => [
        'routes' => [
            'refreshtoken' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/refreshtoken',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action' => 'refreshtoken',
                        'method' => 'post'
                    ],
                ],
            ],
            'auth' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/auth',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action' => 'auth',
                        'method' => 'post'
                    ],
                ],
            ],
            'validatetoken' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/validatetoken',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action' => 'validatetoken',
                        'method' => 'post'
                    ],
                ],
            ],
            'userprof' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/userprof',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action' => 'userprof',
                        'method' => 'post'
                    ],
                ],
            ],
        ],
    ],
    'log' => [
        'AuthLogger' => [
            'writers' => [
                'stream' => [
                    'name' => 'stream',
                    'priority' => \Zend\Log\Logger::ALERT,
                    'options' => [
                        'stream' => __DIR__ . '/../../../logs/auth.log',
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
