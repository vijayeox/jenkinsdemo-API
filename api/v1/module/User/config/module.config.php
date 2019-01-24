<?php

namespace User;

use Zend\Log\Logger;
use Zend\Router\Http\Segment;
use Zend\Log\Formatter\Simple;
use Zend\Log\Filter\Priority;
use Zend\Log\Processor\RequestId;

return [
    'router' => [
        'routes' => [
            'user' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/user[/:userId]',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'access' => [
                            // SET ACCESS CONTROL
                            'put' => 'MANAGE_USER_WRITE',
                            'post' => 'MANAGE_USER_WRITE',
                            'delete' => 'MANAGE_USER_WRITE',
                            'get' => 'MANAGE_USER_READ',
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
                        'action' => 'addusertogroup',
                        'access' => [
                            // SET ACCESS CONTROL
                            'addusertogroup' => 'MANAGE_USER_WRITE',
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
                        'action' => 'removeuserfromgroup',
                        'access' => [
                            // SET ACCESS CONTROL
                            'removeuserfromgroup' => 'MANAGE_USER_WRITE',
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
                        'action' => 'addusertoproject',
                        'access' => [
                            // SET ACCESS CONTROL
                            'addusertoproject' => 'MANAGE_USER_WRITE',
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
                        'action' => 'removeuserfromproject',
                        'access' => [
                            // SET ACCESS CONTROL
                            'removeuserfromproject' => 'MANAGE_USER_WRITE',
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
                        'access' => [
                            // SET ACCESS CONTROL
                            'userLoginToken' => 'MANAGE_USER_READ',
                        ],
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
                        'action' => 'userSearch'
                    ],
                ],
            ],
        ],
    ],
    'log' => [
        'UserLogger' => [
            'writers' => [
                'stream' => [
                    'name' => 'stream',
                    'priority' => \Zend\Log\Logger::ALERT,
                    'options' => [
                        'stream' => __DIR__ . '/../../../logs/user.log',
                        'formatter' => [
                            'name' => \Zend\Log\Formatter\Simple::class,
                            'options' => [
                                'format' => '%timestamp% %priorityName% (%priority%): %message% %extra%', 'dateTimeFormat' => 'c',
                            ],
                        ],
                        'filters' => [
                            'priority' => \Zend\Log\Logger::INFO,],
                    ],
                ],
            ],
            'processors' => [
                'requestid' => [
                    'name' => \Zend\Log\Processor\RequestId::class,],
            ],
        ],
    ],
    'view_manager' => [
        // We need to set this up so that we're allowed to return JSON
        // responses from our controller.
        'strategies' => ['ViewJsonStrategy',],
    ],
];