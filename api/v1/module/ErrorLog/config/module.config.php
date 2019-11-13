<?php

namespace ErrorLog;

use Zend\Router\Http\Segment;
use Zend\Router\Http\Method;

return [
    'router' => [
        'routes' => [
            'ox_error_log' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/errorlog[/:errorId]',
                    'defaults' => [
                        'controller' => Controller\ErrorController::class,
                        'access' => [
                            // SET ACCESS CONTROL
                            'put' => '',
                            'post' => '',
                            'delete' => '',
                            'get' => '',
                        ],
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        // We need to set this up so that we're allowed to return JSON
        // responses from our controller.
        'strategies' => ['ViewJsonStrategy',],
    ],
];
