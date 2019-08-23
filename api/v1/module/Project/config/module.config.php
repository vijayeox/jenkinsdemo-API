<?php

namespace Project;

use Zend\Log\Logger;
use Zend\Router\Http\Segment;
use Zend\Log\Formatter\Simple;
use Zend\Log\Filter\Priority;
use Zend\Log\Processor\RequestId;

return [
    'router' => [
        'routes' => [
            'project' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/[organization/:orgId/]project[/:projectUuid]',
                    'defaults' => [
                        'controller' => Controller\ProjectController::class,
                        'access'=>[
                            // SET ACCESS CONTROL
                            'put'=> 'MANAGE_PROJECT_WRITE',
                            'post'=> 'MANAGE_PROJECT_WRITE',
                            'delete'=> 'MANAGE_PROJECT_WRITE',
                            'get'=> 'MANAGE_PROJECT_READ',
                        ],
                    ],
                ],
            ],
            'projectusersave' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/[organization/:orgId/]project/:projectUuid/save',
                    'defaults' => [
                        'controller' => Controller\ProjectController::class,
                        'method' => 'POST',
                        'action' => 'saveUser',
                        'access' => [
                            'saveUser'=>'MANAGE_PROJECT_WRITE'
                        ],
                    ],
                ],
            ],
            'projectuser' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/project/:projectUuid/users',
                    'defaults' => [
                        'controller' => Controller\ProjectController::class,
                        'method' => 'GET',
                        'action' => 'getListOfUsers',
                        'access' => [
                            'getListOfUsers'=>'MANAGE_PROJECT_READ'
                        ],
                    ],
                ],
            ],
            'myproject' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/project/myproject',
                    'defaults' => [
                        'controller' => Controller\ProjectController::class,
                        'method' => 'GET',
                        'action' => 'getListOfMyProject',
                        'access' => [
                            'getListOfMyProject'=>'MANAGE_PROJECT_READ'
                        ],
                    ],
                ],
            ],
        ],
    ],
    'log' => [
        'ProjectLogger' => [
            'writers' => [
                'stream' => [
                    'name' => 'stream',
                    'priority' => \Zend\Log\Logger::ALERT,
                    'options' => [
                        'stream' => __DIR__ . '/../../../logs/Project.log',
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
