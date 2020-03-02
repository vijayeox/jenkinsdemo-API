<?php

namespace User;

use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'user' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/[organization/:orgId/]user[/:userId][/:type]',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'access' => [
                            // SET ACCESS CONTROL
                            'put' => ['MANAGE_USER_WRITE', 'MANAGE_ORGANIZATION_WRITE', 'MANAGE_GROUP_WRITE'],
                            'post' => ['MANAGE_USER_WRITE', 'MANAGE_ORGANIZATION_WRITE', 'MANAGE_GROUP_WRITE'],
                            'delete' => ['MANAGE_USER_WRITE', 'MANAGE_ORGANIZATION_WRITE', 'MANAGE_GROUP_WRITE'],
                            'get' => ['MANAGE_USER_READ', 'MANAGE_ORGANIZATION_READ', 'MANAGE_GROUP_READ'],
                        ],
                    ],
                ],
            ],
            'loggedInUser' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/user/me[/:type]',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'method' => 'GET',
                        'action' => 'getUserDetail',
                        'access' => [
                            // SET ACCESS CONTROL
                        ],
                    ],
                ],
            ],
            'saveMe' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/user/me/save',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'method' => 'PUT',
                        'action' => 'saveMe',
                    ],
                ],
            ],
            'userInfoById' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/user/:userId/detail/:type',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'method' => 'GET',
                        'action' => 'getUserInfoById',
                        'access' => [
                            // SET ACCESS CONTROL
                        ],
                    ],
                ],
            ],
            'assignUserManager' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/user/:userId/assign/:managerId',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'method' => 'GET',
                        'action' => 'assignManagerToUser',
                        'access' => [
                            // SET ACCESS CONTROL
                            'assignManagerToUser' => 'MANAGE_USER_WRITE',
                        ],
                    ],
                ],
            ],
            'getUserAppsAndPrivileges' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/user/me/access',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'method' => 'GET',
                        'action' => 'getUserAppsAndPrivileges',
                    ],
                ],
            ],
            'removeUserManager' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/user/:userId/remove/:managerId',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'method' => 'DELETE',
                        'action' => 'removeManagerForUser',
                        'access' => [
                            // SET ACCESS CONTROL
                            'removeManagerForUser' => 'MANAGE_USER_WRITE',
                        ],
                    ],
                ],
            ],
            'addUserToGroup' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/user/:userId/addusertogroup/:groupId',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'method' => 'POST',
                        'action' => 'addUserToGroup',
                        'access' => [
                            // SET ACCESS CONTROL
                            'addUserToGroup' => 'MANAGE_USER_WRITE',
                        ],
                    ],
                ],
            ],
            'removeUserFromGroup' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/user/:userId/removeuserfromgroup',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'method' => 'DELETE',
                        'action' => 'removeUserFromGroup',
                        'access' => [
                            // SET ACCESS CONTROL
                            'removeUserFromGroup' => 'MANAGE_USER_WRITE',
                        ],
                    ],
                ],
            ],
            'addUserToProject' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/user/:userId/addusertoproject/:projectId',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'method' => 'POST',
                        'action' => 'addUserToProject',
                        'access' => [
                            // SET ACCESS CONTROL
                            'addUserToProject' => 'MANAGE_USER_WRITE',
                        ],
                    ],
                ],
            ],
            'removeUserFromProject' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/user/:userId/removeuserfromproject/:projectId',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'method' => 'DELETE',
                        'action' => 'removeUserFromProject',
                        'access' => [
                            // SET ACCESS CONTROL
                            'removeUserFromProject' => 'MANAGE_USER_WRITE',
                        ],
                    ],
                ],
            ],
            'userToken' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/user/usertoken',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'method' => 'get',
                        'action' => 'userLoginToken',
                    ],
                ],
            ],
            'userSearch' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/user/usersearch',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'method' => 'POST',
                        'action' => 'userSearch',
                    ],
                ],
            ],
            'usersList' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/[organization/:orgId/]users/list',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'method' => 'POST',
                        'action' => 'usersList',
                    ],
                ],
            ],
            'changePassword' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/user/me/changepassword',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'method' => 'POST',
                        'action' => 'changePassword',
                    ],
                ],
            ],
            'profilePicture' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/user/profile/:profileId',
                    'defaults' => [
                        'controller' => Controller\ProfilePictureDownloadController::class,
                    ],
                ],
            ],
            'profilePictureByUsername' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/user/profile/username/:username',
                    'defaults' => [
                        'controller' => Controller\ProfilePictureDownloadController::class,
                        'action' => 'profilePictureByUsername',
                    ],
                ],
            ],
            'updateProfile' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/user/profile',
                    'defaults' => [
                        'controller' => Controller\ProfilePictureController::class,
                        'method' => 'POST',
                        'action' => 'updateProfile',
                    ],
                ],
            ],
            'forgotPassword' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/user/me/forgotpassword',
                    'defaults' => [
                        'controller' => Controller\ForgotPasswordController::class,
                        'method' => 'POST',
                        'action' => 'forgotPassword',
                    ],
                ],
            ],
            'resetPassword' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/user/me/resetpassword',
                    'defaults' => [
                        'controller' => Controller\ForgotPasswordController::class,
                        'method' => 'POST',
                        'action' => 'resetPassword',
                    ],
                ],
            ],
            'getSession' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/user/me/getsession',
                    'defaults' => [
                        'controller' => Controller\UserSessionController::class,
                        'method' => 'GET',
                        'action' => 'getSession',
                    ],
                ],
            ],
            'getUserDetailList' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/organization/:orgId/user/:userId/profile',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'method' => 'GET',
                        'action' => 'getUserDetailList',
                        'access' => [
                            'getUserDetailList' => ['MANAGE_USER_READ'],
                        ],
                    ],
                ],
            ],
            'updateSession' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/user/me/updatesession',
                    'defaults' => [
                        'controller' => Controller\UserSessionController::class,
                        'method' => 'POST',
                        'action' => 'updateSession',
                    ],
                ],
            ],
            'getuserproject' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/user/:userId/project',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'method' => 'GET',
                        'action' => 'getuserproject',
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
