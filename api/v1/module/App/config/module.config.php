<?php

namespace App;

use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'app' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/app[/:appId]',
                    'defaults' => [
                        'controller' => Controller\AppController::class,
                        'access' => [
                            // SET ACCESS CONTROL
                            'put' => 'MANAGE_APP_WRITE',
                            'post' => 'MANAGE_APP_WRITE',
                            'delete' => 'MANAGE_APP_DELETE',
                            'get' => 'MANAGE_APP_READ',
                        ],
                    ],
                ],
            ],
            'appinstall' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/app/:appId/appinstall',
                    'defaults' => [
                        'controller' => Controller\AppController::class,
                        'action' => 'installAppForOrg',
                        'method' => 'post'
                        // 'access' => [
                        //     // SET ACCESS CONTROL
                        //     'put'=> 'MANAGE_APP_WRITE',
                        //     'post'=> 'MANAGE_APP_WRITE',
                        //     'delete'=> 'MANAGE_APP_DELETE',
                        //     'get'=> 'VIEW_APP_READ',
                        // ],
                    ],
                ],
            ],
            'applist' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/app/a',
                    'defaults' => [
                        'controller' => Controller\AppController::class,
                        'action' => 'applist',
                        'method' => 'GET'
                    ],
                ],
            ],
            'appdeployxml' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/app/appdeployxml',
                    'defaults' => [
                        'controller' => Controller\AppController::class,
                        'action' => 'getDataFromDeploymentDescriptorUsingXML',
                        'method' => 'get'
                    ],
                ],
            ],
            'appdeployyml' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/app/appdeployyml',
                    'defaults' => [
                        'controller' => Controller\AppController::class,
                        'action' => 'getDataFromDeploymentDescriptorUsingYML',
                        'method' => 'get'
                    ],
                ],
            ],
            'appupload' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/app/appupload',
                    'defaults' => [
                        'controller' => Controller\AppController::class,
                        'action' => 'appUpload',
                        'method' => 'post'
                    ],
                ],
            ],
            'appregister' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/app/register',
                    'defaults' => [
                        'controller' => Controller\AppRegisterController::class,
                        'action' => 'appregister',
                        'method' => 'POST'
                    ],
                ],
            ],
        ],
    ],
    'log' => [
        'AppLogger' => [
            'writers' => [
                'stream' => [
                    'name' => 'stream',
                    'priority' => \Zend\Log\Logger::ALERT,
                    'options' => [
                        'stream' => __DIR__ . '/../../../logs/app.log',
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
