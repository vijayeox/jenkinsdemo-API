<?php
namespace Role;

use Zend\Log\Logger;
use Zend\Router\Http\Segment;
use Zend\Log\Formatter\Simple;
use Zend\Log\Filter\Priority;
use Zend\Log\Processor\RequestId;

return [
    'router' => [
        'routes' => [
            'role' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/role[/:roleId]',
                    'defaults' => [
                        'controller' => Controller\RoleController::class,
                        'access' => [
                            // SET ACCESS CONTROL
                            'put' => 'MANAGE_ROLE_WRITE',
                            'post' => 'MANAGE_ROLE_WRITE',
                            'delete' => 'MANAGE_ROLE_WRITE',
                            'get' => 'MANAGE_ROLE_READ',
                        ],
                    ],
                ],
            ],
            'roleprivilege' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/role/:roleId/privilege',
                    'defaults' => [
                        'controller' => Controller\RoleController::class,
                        'method' => 'GET',
                        'action' => 'roleprivilege',
                        'access' =>  [
                            'roleprivilege'=>'MANAGE_ROLE_READ'
                        ],
                    ],
                ],
            ],
        ],
    ],
    'log' => [
        'RoleLogger' => [
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