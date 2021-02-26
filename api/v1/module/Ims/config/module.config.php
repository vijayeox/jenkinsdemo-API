<?php

namespace Ims;

use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'producer' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/ims/producer',
                    'defaults' => [
                        'controller' => Controller\ProducerController::class,
                        'access' => [
                            // SET ACCESS CONTROL
                            'get' => [],
                            'put' => [],
                            'post' => []
                        ],
                    ],
                ],
            ],
            'getFunctionStructure' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/ims/producer/getFunctionStructure[/:operation]',
                    'defaults' => [
                        'controller' => Controller\ProducerController::class,
                        'method' => 'GET',
                        'action' => 'getFunctionStructure',
                    ],
                ],
            ],
            
        ],
    ],
];