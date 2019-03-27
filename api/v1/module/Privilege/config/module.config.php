<?php
namespace Privilege;

use Zend\Log\Logger;
use Zend\Router\Http\Segment;
use Zend\Log\Formatter\Simple;
use Zend\Log\Filter\Priority;
use Zend\Log\Processor\RequestId;

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
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/privilege/getappid',
                    'defaults' => [
                        'controller' => Controller\PrivilegeController::class,
                        'method' => 'GET',
                        'action' => 'getAppId',
                        'access' => [
                            'getAppId'=>'MANAGE_PRIVILEGE_WRITE'
                        ],
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
//                        'access'=>[
//                            // SET ACCESS CONTROL
//                            'getUserPrivileges'=> 'MANAGE_PRIVILEGE_READ',
//                        ],
                    ],
                ],
            ],
        ],
    ],
    'log' => [
        'PrivilegeLogger' => [
            'writers' => [
                'stream' => [
                    'name' => 'stream',
                    'priority' => \Zend\Log\Logger::ALERT,
                    'options' => [
                        'stream' => __DIR__ . '/../../../logs/privilege.log',
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