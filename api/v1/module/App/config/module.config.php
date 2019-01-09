<?php

namespace App;

use Zend\Log\Logger;
use Zend\Router\Http\Segment;
use Zend\Log\Formatter\Simple;
use Zend\Log\Filter\Priority;
use Zend\Log\Processor\RequestId;

return [
    'router' => [
        'routes' => [
            'app' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/app[/:appId]',
                    'defaults' => [
                        'controller' => Controller\AppController::class,
                        'access' =>[
                            // SET ACCESS CONTROL
                            'put'=> 'MANAGE_APP_WRITE',
                            'post'=> 'MANAGE_APP_WRITE',
                            'delete'=> 'MANAGE_APP_DELETE',
                            'get'=> 'VIEW_APP_READ',
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
            'appdeployxml' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/app/:appId/appdeployxml',
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
                    'route' => '/app/:appId/appdeployyml',
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
                    'route' => '/app/:appId/appupload',
                    'defaults' => [
                        'controller' => Controller\AppController::class,
                        'action' => 'appUpload',
                        'method' => 'post'
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
