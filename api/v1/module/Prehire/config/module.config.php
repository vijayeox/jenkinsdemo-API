<?php

namespace Prehire;

use Oxzion\Utils\UuidUtil;
use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'prehire' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/prehire/:implementation/:referenceId[/:prehireId]',
                    'constraints' => [
                        'referenceId' => UuidUtil::UUID_PATTERN,
                        'prehireId' => UuidUtil::UUID_PATTERN,
                    ],
                    'defaults' => [
                        'controller' => Controller\PrehireController::class,
                        'access' => [
                            'put'=> 'MANAGE_PREHIRE_WRITE',
                            'post'=> 'MANAGE_PREHIRE_WRITE',
                            'delete'=> 'MANAGE_PREHIRE_WRITE',
                            'get'=> 'MANAGE_PREHIRE_READ',
                        ],
                    ],
                ],
            ],
            'foleyEndpoint' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/foley/endpoint/:type',
                    'defaults' => [
                        'controller' => Controller\FoleyController::class,
                        'method' => 'POST',
                        'action' => 'foleyEndpoint',
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
