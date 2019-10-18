<?php
namespace Messaging;

use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'messaging' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/messaging',
                    'defaults' => [
                        'controller' => Controller\MessagingController::class
                    ],
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
