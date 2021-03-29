<?php

namespace Ims;

use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'insuredFunctionStructure' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/ims/insured/getFunctionStructure[/:operation]',
                    'defaults' => [
                        'controller' => Controller\InsuredController::class,
                        'method' => 'GET',
                        'action' => 'getFunctionStructure',
                    ],
                ],
            ],
            'producerFunctionStructure' => [
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
            'insured' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/ims/insured',
                    'defaults' => [
                        'controller' => Controller\InsuredController::class
                    ],
                ],
            ],
            'producer' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/ims/producer',
                    'defaults' => [
                        'controller' => Controller\ProducerController::class
                    ],
                ],
            ],

        ],
    ],
];
