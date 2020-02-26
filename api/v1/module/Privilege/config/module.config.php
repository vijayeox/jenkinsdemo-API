<?php
namespace Privilege;

use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'privilege' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/privilege[/:privilegeId]',
                    'defaults' => [
                        'controller' => Controller\PrivilegeController::class,
                        'access' => [
                            // SET ACCESS CONTROL
                            'put' => 'MANAGE_PRIVILEGE_WRITE',
                            'post' => 'MANAGE_PRIVILEGE_WRITE',
                            'delete' => 'MANAGE_PRIVILEGE_WRITE',
                            'get' => 'MANAGE_PRIVILEGE_READ',
                        ],
                    ],
                ],
            ],
            'getAppId' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/privilege/getappid',
                    'defaults' => [
                        'controller' => Controller\PrivilegeController::class,
                        'method' => 'GET',
                        'action' => 'getAppId',
                        'access' => [
                            'getAppId' => 'MANAGE_PRIVILEGE_WRITE',
                        ],
                    ],
                ],
            ],
            'getmasterprivilege' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/organization[/:orgId]/masterprivilege[/:roleId]',
                    'defaults' => [
                        'controller' => Controller\PrivilegeController::class,
                        'action' => 'getMasterPrivilege',
                    ],
                ],
            ],
            'userPrivileges' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/privilege/app/:appId',
                    'defaults' => [
                        'controller' => Controller\PrivilegeController::class,
                        'action' => 'getUserPrivileges',
                        'method' => 'get',
                        'access' => [
                            // SET ACCESS CONTROL
                            // 'getUserPrivileges' => 'MANAGE_PRIVILEGE_READ',
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
