<?php

namespace Alert;

use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'alert' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/alert[/:alertId]',
                    'defaults' => [
                        'controller' => Controller\AlertController::class,
                        'access' => [
                            // SET ACCESS CONTROL
                            'put' => 'MANAGE_ALERT_WRITE',
                            'post' => 'MANAGE_ALERT_WRITE',
                            'delete' => 'MANAGE_ALERT_WRITE',
                            'get' => 'MANAGE_ALERT_READ',
                            'decline' => 'MANAGE_ALERT_READ',
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
                        'method' => 'post',
                        'access' => [
                            // SET ACCESS CONTROL
                            'accept' => 'MANAGE_ALERT_READ',
                        ],
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
                        'method' => 'post',
                        'access' => [
                            // SET ACCESS CONTROL
                            'decline' => 'MANAGE_ALERT_READ',
                        ],
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        // We need to set this up so that we're allowed to return JSON
        // responses from our controller.
        'strategies' => ['ViewJsonStrategy'],
    ],
];
