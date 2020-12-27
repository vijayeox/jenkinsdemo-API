<?php

namespace Esign;

use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'esignStatus' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/status/:docId',
                    'defaults' => [
                        'controller' => Controller\EsignController::class,
                        'action' => 'getStatus',
                        'method' => 'GET'
                        ],
                    ],
                ],
            ],
        ],
    ];
