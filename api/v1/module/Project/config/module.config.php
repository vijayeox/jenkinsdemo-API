<?php

namespace Project;

use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'project' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/[organization/:orgId/]project[/:projectUuid][/:force_flag]',
                    'defaults' => [
                        'controller' => Controller\ProjectController::class,
                        'access' => [
                            // SET ACCESS CONTROL
                            'put' => 'MANAGE_PROJECT_WRITE',
                            'post' => 'MANAGE_PROJECT_WRITE',
                            'delete' => 'MANAGE_PROJECT_WRITE',
                            'get' => 'MANAGE_PROJECT_READ',
                        ],
                    ],
                ],
            ],
            'projectusersave' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/[organization/:orgId/]project/:projectUuid/save',
                    'defaults' => [
                        'controller' => Controller\ProjectController::class,
                        'method' => 'POST',
                        'action' => 'saveUser',
                        'access' => [
                            'saveUser' => 'MANAGE_PROJECT_WRITE',
                        ],
                    ],
                ],
            ],
            'projectuser' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/[organization/:orgId/]project/:projectUuid/users',
                    'defaults' => [
                        'controller' => Controller\ProjectController::class,
                        'method' => 'GET',
                        'action' => 'getListOfUsers',
                        'access' => [
                            'getListOfUsers' => 'MANAGE_PROJECT_READ',
                        ],
                    ],
                ],
            ],
            'myproject' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/[organization/:orgId/]project/myproject',
                    'defaults' => [
                        'controller' => Controller\ProjectController::class,
                        'method' => 'GET',
                        'action' => 'getListOfMyProject',
                        'access' => [
                            'getListOfMyProject' => 'MANAGE_PROJECT_READ',
                        ],
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
