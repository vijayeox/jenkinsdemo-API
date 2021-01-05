<?php

namespace Group;

use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'groups' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/[account/:accountId/]group[/:groupId]',
                    'defaults' => [
                        'controller' => Controller\GroupController::class,
                        'access' => [
                            // SET ACCESS CONTROL
                            'put' => 'MANAGE_GROUP_WRITE',
                            'post' => 'MANAGE_GROUP_WRITE',
                            'delete' => 'MANAGE_GROUP_WRITE'
                        ],
                    ],
                ],
            ],
            'groupsUser' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/group/user/:userId',
                    'defaults' => [
                        'controller' => Controller\GroupController::class,
                        'method' => 'GET',
                        'action' => 'getGroupsforUser',
                        'access' => [
                            'getGroupsforUser' => 'MANAGE_GROUP_WRITE',
                        ],
                    ],
                ],
            ],
            'getusers' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/[account/:accountId/]group/:groupId/users',
                    'defaults' => [
                        'controller' => Controller\GroupController::class,
                        'method' => 'GET',
                        'action' => 'getuserlist',
                        'access' => [
                            'getuserlist' => 'MANAGE_GROUP_WRITE',
                        ],
                    ],
                ],
            ],
            'saveusers' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/[account/:accountId/]group/:groupId/save',
                    'defaults' => [
                        'controller' => Controller\GroupController::class,
                        'method' => 'POST',
                        'action' => 'saveUser',
                        'access' => [
                            'saveUser' => 'MANAGE_GROUP_WRITE',
                        ],
                    ],
                ],
            ],
            'groupsList' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/[account/:accountId/]groups/list',
                    'defaults' => [
                        'controller' => Controller\GroupController::class,
                        'method' => 'POST',
                        'action' => 'groupsList',
                    ],
                ],
            ],
            'groupLogo' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/group/:accountId/logo/:groupId',
                    'defaults' => [
                        'controller' => Controller\GroupLogoController::class,
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
