<?php

namespace Esign;

use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'esign' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/getStatus/:docID',
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
