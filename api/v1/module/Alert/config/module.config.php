<?php

namespace Alert;

use Zend\Log\Logger;
use Zend\Router\Http\Segment;
use Zend\Log\Formatter\Simple;
use Zend\Log\Filter\Priority;
use Zend\Log\Processor\RequestId;

return [
    'router' => [
        'routes' => [
            'alert' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/alert[/:alertId]',
                    'defaults' => [
                        'controller' => Controller\AlertController::class,
                        'access'=>[
                            // SET ACCESS CONTROL
                            'put'=> 'MANAGE_ANNOUNCEMENT_WRITE',
                            'post'=> 'MANAGE_ANNOUNCEMENT_WRITE',
                            'delete'=> 'MANAGE_ANNOUNCEMENT_WRITE',
                            'get'=> 'MANAGE_ANNOUNCEMENT_READ',
                            'getList'=> 'MANAGE_ANNOUNCEMENT_READ',
                        ],
                    ],
                ],
            ],
            'alertaccept' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/alert/:alertId/accept',
                    'defaults' => [
                        'controller' => Controller\AlertController::class,
                        'action' => 'accept',
                        'method'=>'post'
                    ],
                ],
            ],
            'alertdecline' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/alert/:alertId/decline',
                    'defaults' => [
                        'controller' => Controller\AlertController::class,
                        'action' => 'decline',
                        'method'=>'post'
                    ],
                ],
            ],
        ],
    ],
    'log' => [
        'AlertLogger' => [
            'writers' => [
                'stream' => [
                    'name' => 'stream',
                    'priority' => \Zend\Log\Logger::ALERT,
                    'options' => [
                        'stream' => __DIR__ . '/../../../logs/alert.log',
                        'formatter' => [
                            'name' => \Zend\Log\Formatter\Simple::class,
                            'options' => [
                                'format' => '%timestamp% %priorityName% (%priority%): %message% %extra%', 'dateTimeFormat' => 'c',
                            ],
                        ],
                        'filters' => [
                            'priority' => \Zend\Log\Logger::INFO,],
                    ],
                ],
            ],
            'processors' => [
                'requestid' => [
                    'name' => \Zend\Log\Processor\RequestId::class,],
            ],
        ],
    ],
    'view_manager' => [
        // We need to set this up so that we're allowed to return JSON
        // responses from our controller.
        'strategies' => ['ViewJsonStrategy',],
    ],
];
