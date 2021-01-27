<?php
namespace Role;

use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'role' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/[account/:accountId/]role[/:roleId]',
                    'defaults' => [
                        'controller' => Controller\RoleController::class,
                        'access' => [
                            // SET ACCESS CONTROL
                            'put' => ['MANAGE_ROLE_WRITE', 'MANAGE_USER_WRITE'],
                            'post' => ['MANAGE_ROLE_WRITE', 'MANAGE_USER_WRITE'],
                            'delete' => ['MANAGE_ROLE_WRITE', 'MANAGE_USER_WRITE'],
                            'get' => ['MANAGE_ROLE_READ', 'MANAGE_USER_READ'],
                        ],
                    ],
                ],
            ],
            'roleprivilege' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/[account/:accountId/]role/:roleId/privilege',
                    'defaults' => [
                        'controller' => Controller\RoleController::class,
                        'method' => 'GET',
                        'action' => 'roleprivilege',
                        'access' => [
                            'roleprivilege' => 'MANAGE_ROLE_READ',
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
