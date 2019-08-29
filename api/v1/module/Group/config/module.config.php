<?php

namespace Group;

use Zend\Log\Logger;
use Zend\Router\Http\Segment;
use Zend\Log\Formatter\Simple;
use Zend\Log\Filter\Priority;
use Zend\Log\Processor\RequestId;

return [
    'router' => [
        'routes' => [
            'groups' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/[organization/:orgId/]group[/:groupId]',
                    'defaults' => [
                        'controller' => Controller\GroupController::class,
                        'access'=>[
                            // SET ACCESS CONTROL
                            'put'=> 'MANAGE_GROUP_WRITE',
                            'post'=> 'MANAGE_GROUP_WRITE',
                            'delete'=> 'MANAGE_GROUP_WRITE',
                            'get'=> 'MANAGE_GROUP_READ',
                        ],
                    ],
                ],
            ],
            'groupsUser' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/group/user/:userId',
                    'defaults' => [
                        'controller' => Controller\GroupController::class,
                        'method' => 'GET',
                        'action' => 'getGroupsforUser',
                        'access' => [
                            'getGroupsforUser'=>'MANAGE_GROUP_WRITE'
                       ],
                   ],
               ],
           ],
           'getusers' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/[organization/:orgId/]group/:groupId/users',
                    'defaults' => [
                        'controller' => Controller\GroupController::class,
                        'method' => 'GET',
                        'action' => 'getuserlist',
                        'access' => [
                            'getuserlist'=>'MANAGE_GROUP_WRITE'
                       ],
                   ],
               ],
           ],
           'saveusers' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/[organization/:orgId/]group/:groupId/save',
                    'defaults' => [
                        'controller' => Controller\GroupController::class,
                        'method' => 'POST',
                        'action' => 'saveUser',
                        'access' => [
                            'saveUser'=>'MANAGE_GROUP_WRITE'
                       ],
                   ],
               ],
           ],
           'groupsList' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/[organization/:orgId/]groups/list',
                    'defaults' => [
                        'controller' => Controller\GroupController::class,
                        'method' => 'POST',
                        'action' => 'groupsList'
                    ],
                ],
            ],
           'groupLogo' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/group/:orgId/logo/:groupId',
                    'defaults' => [
                        'controller' => Controller\GroupLogoController::class
                    ],
                ],
            ],
       ],
   ],
   'log' => [
    'GroupLogger' => [
        'writers' => [
            'stream' => [
                'name' => 'stream',
                'priority' => \Zend\Log\Logger::ALERT,
                'options' => [
                    'stream' => __DIR__ . '/../../../logs/group.log',
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
