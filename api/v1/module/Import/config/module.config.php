<?php

namespace Import;

use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'import' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/import[/:importId]',
                    'defaults' => [
                        'controller' => Controller\ImportController::class,
                        'access' => [
                            // SET ACCESS CONTROL
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
