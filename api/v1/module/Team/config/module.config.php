<?php

namespace Team;

use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'teams' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/[account/:accountId/]team[/:teamId]',
                    'defaults' => [
                        'controller' => Controller\TeamController::class,
                        'access' => [
                            // SET ACCESS CONTROL
                            'put' => 'MANAGE_TEAM_WRITE',
                            'post' => 'MANAGE_TEAM_WRITE',
                            'delete' => 'MANAGE_TEAM_WRITE'
                        ],
                    ],
                ],
            ],
            'teamsUser' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/team/user/:userId',
                    'defaults' => [
                        'controller' => Controller\TeamController::class,
                        'method' => 'GET',
                        'action' => 'getTeamsforUser',
                        'access' => [
                            'getTeamsforUser' => 'MANAGE_TEAM_WRITE',
                        ],
                    ],
                ],
            ],
            'getusers' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/[account/:accountId/]team/:teamId/users',
                    'defaults' => [
                        'controller' => Controller\TeamController::class,
                        'method' => 'GET',
                        'action' => 'getuserlist',
                        'access' => [
                            'getuserlist' => 'MANAGE_TEAM_WRITE',
                        ],
                    ],
                ],
            ],
            'saveusers' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/[account/:accountId/]team/:teamId/save',
                    'defaults' => [
                        'controller' => Controller\TeamController::class,
                        'method' => 'POST',
                        'action' => 'saveUser',
                        'access' => [
                            'saveUser' => 'MANAGE_TEAM_WRITE',
                        ],
                    ],
                ],
            ],
            'teamsList' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/[account/:accountId/]teams/list',
                    'defaults' => [
                        'controller' => Controller\TeamController::class,
                        'method' => 'POST',
                        'action' => 'teamsList',
                    ],
                ],
            ],
            'getsubteam' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/team/:teamId/subteam',
                    'defaults' => [
                        'controller' => Controller\TeamController::class,
                        'method' => 'GET',
                        'action' => 'getSubteams',
                        'access' => [
                            'getSubteams' => 'MANAGE_TEAM_WRITE',
                        ],
                    ],
                ],
            ],
            'teamLogo' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/team/:accountId/logo/:teamId',
                    'defaults' => [
                        'controller' => Controller\TeamLogoController::class,
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
