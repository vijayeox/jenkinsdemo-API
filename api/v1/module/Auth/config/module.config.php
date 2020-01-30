<?php
namespace Auth;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'refreshtoken' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/refreshtoken',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action' => 'refreshtoken',
                        'method' => 'post'
                    ],
                ],
            ],
            'auth' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/auth',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action' => 'auth',
                        'method' => 'post'
                    ],
                ],
            ],
            'register' => [
                'type'    => Literal::class,
                'options' => [
                    'route'    => '/register',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action' => 'register',
                        'method' => 'post'
                    ],
                ],
            ],
            'validatetoken' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/validatetoken',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action' => 'validatetoken',
                        'method' => 'post'
                    ],
                ],
            ],
            'userprof' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/userprof',
                    'defaults' => [
                        'controller' => Controller\AuthController::class,
                        'action' => 'userprof',
                        'method' => 'post'
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
