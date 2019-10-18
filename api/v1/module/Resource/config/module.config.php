<?php

namespace Resource;

use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'resource' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/resource[/:resourceId]',
                    'defaults' => [
                        'controller' => Controller\ResourceController::class
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
