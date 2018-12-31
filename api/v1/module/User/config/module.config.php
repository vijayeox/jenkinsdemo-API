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
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/user[/:userId]',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'access'=> [
                            // SET ACCESS CONTROL
                            'put'=> 'MANAGE_USER_WRITE',
                            'post'=> 'MANAGE_USER_WRITE',
                            'delete'=> 'MANAGE_USER_WRITE',
                            'get'=> 'MANAGE_USER_READ',
                        ],
                    ],
                ],
            ],
            'assignUserManager' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/user/:userId/assign/:managerId',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'method' => 'GET',
                        'action' => 'assignManagerToUser',
                        'access'=>[
                            // SET ACCESS CONTROL
                            'assignManagerToUser' => 'MANAGE_USER_WRITE',
                        ],
                    ],
                ],
            ],
            'removeUserManager' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/user/:userId/remove/:managerId',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'method' => 'DELETE',
                        'action' => 'removeManagerForUser',
                        'access'=>[
                            // SET ACCESS CONTROL
                            'removeManagerForUser' => 'MANAGE_USER_WRITE',
                        ],
                    ],
                ],
            ],
            'addUserToAGroup' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/user/:userId/addusertogroup/:groupId',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'method' => 'POST',
                        'action' => 'addusertogroup',
                        'access'=>[
                            // SET ACCESS CONTROL
                            'addusertogroup' => 'MANAGE_USER_WRITE',
                        ],
                    ],
                ],
            ],
            'removeUserFromGroup' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/user/:userId/removeuserfromgroup',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'method' => 'DELETE',
                        'action' => 'removeUserFromGroup',
                        'access'=>[
                            // SET ACCESS CONTROL
                            'removeUserFromGroup' => 'MANAGE_USER_WRITE',
                        ],
                    ],
                ],
            ],
            'addUserToProject' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/user/:userId/addusertoproject/:projectId',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'method' => 'POST',
                        'action' => 'addUserToProject',
                        'access'=>[
                            // SET ACCESS CONTROL
                            'addUserToProject' => 'MANAGE_USER_WRITE',
                        ],
                    ],
                ],
            ],
            'removeUserFromProject' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/user/:userId/removeuserfromproject/:projectId',
                    'defaults' => [
                        'controller' => Controller\UserController::class,
                        'method' => 'DELETE',
                        'action' => 'removeUserFromProject',
                        'access'=>[
                            // SET ACCESS CONTROL
                            'removeUserFromProject' => 'MANAGE_USER_WRITE',
                        ],
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
                                    'format' => '%timestamp% %priorityName% (%priority%): %message% %extra%','dateTimeFormat' => 'c',
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