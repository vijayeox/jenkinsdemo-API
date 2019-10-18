<?php
namespace Mlet;

use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'mlet' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/mlet[/:mletId]',
                    'defaults' => [
                        'controller' => Controller\MletController::class,
                        'access'=>[
                            // SET ACCESS CONTROL
                            'put'=> 'MANAGE_MLET_WRITE',
                            'post'=> 'MANAGE_MLET_WRITE',
                            'delete'=> 'MANAGE_MLET_WRITE',
                            'get'=> 'MANAGE_MLET_READ',
                        ],
                    ],
                ],
            ],
            'mletResult' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/mlet/:mletId/result',
                    'defaults' => [
                        'controller' => Controller\MletController::class,
                        'method' => 'POST',
                        'action' => 'getResult',
                        'access' => [
                            'getResult'=>'MANAGE_MLET_READ'
                       ],
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
