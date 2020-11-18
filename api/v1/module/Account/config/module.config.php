<?php
namespace Account;

use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'account' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/account[/:accountId]',
                    'defaults' => [
                        'controller' => Controller\AccountController::class,
                        'access' => [
                            // SET ACCESS CONTROL
                            'put' => 'MANAGE_ACCOUNT_WRITE',
                            'post' => 'MANAGE_ACCOUNT_WRITE',
                            'delete' => 'MANAGE_ACCOUNT_WRITE',
                            'get' => ['MANAGE_ACCOUNT_READ','MANAGE_INSTALL_APP_READ'],
                        ],
                    ],
                ],
            ],
            'addUsersToAccount' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/account/:accountId/save',
                    'defaults' => [
                        'controller' => Controller\AccountController::class,
                        'method' => 'POST',
                        'action' => 'addUsersToAccount',
                        'access' => [
                            // SET ACCESS CONTROL
                            'addUsersToAccount' => 'MANAGE_ACCOUNT_WRITE',
                        ],
                    ],
                ],
            ],
            'accountLogo' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/account/logo/:accountId',
                    'defaults' => [
                        'controller' => Controller\AccountLogoController::class,
                    ],
                ],
            ],
            'accountuser' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/account/:accountId/users',
                    'defaults' => [
                        'controller' => Controller\AccountController::class,
                        'method' => 'GET',
                        'action' => 'getListOfAccountUsers',
                        'access' => [
                            'getListOfAccountUsers' => ['MANAGE_USER_READ'],
                        ],
                    ],
                ],
            ],
            'getListofAdminUsers' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/account[/:accountId]/adminusers',
                    'defaults' => [
                        'controller' => Controller\AccountController::class,
                        'method' => 'GET',
                        'action' => 'getListofAdminUsers',
                        'access' => [
                            'getListofAdminUsers' => ['MANAGE_ACCOUNT_READ', 'MANAGE_MYACCOUNT_WRITE'],
                        ],
                    ],
                ],
            ],
            'getListofAccountGroups' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/account/:accountId/groups',
                    'defaults' => [
                        'controller' => Controller\AccountController::class,
                        'method' => 'GET',
                        'action' => 'getListofAccountGroups',
                        'access' => [
                            'getListofAccountGroups' => ['MANAGE_GROUP_READ'],
                        ],
                    ],
                ],
            ],
            'getListofAccountProjects' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/account/:accountId/projects',
                    'defaults' => [
                        'controller' => Controller\AccountController::class,
                        'method' => 'GET',
                        'action' => 'getListofAccountProjects',
                        'access' => [
                            'getListofAccountProjects' => ['MANAGE_PROJECT_READ'],
                        ],
                    ],
                ],
            ],
            'getListofAccountAnnouncements' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/account/:accountId/announcements',
                    'defaults' => [
                        'controller' => Controller\AccountController::class,
                        'method' => 'GET',
                        'action' => 'getListofAccountAnnouncements',
                        'access' => [
                            'getListofAccountAnnouncements' => ['MANAGE_ANNOUNCEMENT_READ'],
                        ],
                    ],
                ],
            ],
            'getListofAccountRoles' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/account/:accountId/roles',
                    'defaults' => [
                        'controller' => Controller\AccountController::class,
                        'method' => 'GET',
                        'action' => 'getListofAccountRoles',
                        'access' => [
                            'getListofAccountRoles' => ['MANAGE_ROLE_READ'],
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
