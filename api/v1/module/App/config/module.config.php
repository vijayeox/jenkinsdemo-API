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
            'applisttype' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/app/type/:typeId',
                    'defaults' => [
                        'controller' => Controller\AppController::class,
                        'action' => 'appListByType',
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
            'appupload' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/app/:appId/deployworkflow[/:workflowId]',
                    'defaults' => [
                        'controller' => Controller\AppController::class,
                        'action' => 'workflowDeploy',
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
            'appform' => [
                'type'    => Segment::class,
                'options' => [
                	'route'    => '/app/:appId/form[/:id]',
                    'defaults' => [
                        'controller' => Controller\FormController::class,
                        'access'=>[
                            // SET ACCESS CONTROL
                            'put'=> 'MANAGE_FORM_WRITE',
                            'post'=> 'MANAGE_FORM_WRITE',
                            'delete'=> 'MANAGE_FORM_WRITE',
                            'get'=> 'MANAGE_FORM_READ',
                        ],
                    ],
                ],
            ],
            'appfield' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/app/:appId/field[/:id]',
                    'defaults' => [
                        'controller' => Controller\FieldController::class,
                        'access'=>[
                            // SET ACCESS CONTROL
                            'put'=> 'MANAGE_FIELD_WRITE',
                            'post'=> 'MANAGE_FIELD_WRITE',
                            'delete'=> 'MANAGE_FIELD_WRITE',
                            'get'=> 'MANAGE_FIELD_READ',
                        ],
                    ],
                ],
            ],
            'appworkflow' => [
                'type'    => Segment::class,
                'options' => [
                	'route'    => '/app/:appId/workflow[/:workflowId]',
                    'defaults' => [
                        'controller' => Controller\WorkflowController::class,
                        'access'=>[
                            // SET ACCESS CONTROL
                            // 'put'=> 'MANAGE_FORM_WRITE',
                            // 'post'=> 'MANAGE_FORM_WRITE',
                            // 'delete'=> 'MANAGE_FORM_WRITE',
                            // 'get'=> 'MANAGE_FORM_READ',
                        ],
                    ],
                ],
            ],
            'appmenu' => [
                'type'    => Segment::class,
                'options' => [
                	'route'    => '/app/:appId/menu[/:menuId]',
                    'defaults' => [
                        'controller' => Controller\MenuItemController::class,
                        'access'=>[
                            // SET ACCESS CONTROL
                            // 'put'=> 'MANAGE_MENU_WRITE',
                            // 'post'=> 'MANAGE_MENU_WRITE',
                            // 'delete'=> 'MANAGE_MENU_WRITE',
                            // 'get'=> 'MANAGE_MENU_READ',
                        ],
                    ],
                ],
            ],
            'apppage' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/app/:appId/page[/:pageId]',
                    'defaults' => [
                        'controller' => Controller\PageController::class,
                        'access' =>[
                            // SET ACCESS CONTROL
                            // 'put'=> 'MANAGE_PAGE_WRITE',
                            // 'post'=> 'MANAGE_PAGE_WRITE',
                            // 'delete'=> 'MANAGE_PAGE_WRITE',
                            // 'get'=> 'MANAGE_PAGE_READ',
                        ]
                    ]
                ]
            ],
            'workflowfields' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/app/:appId/workflow/:workflowId/fields',
                    'defaults' => [
                        'controller' => Controller\WorkflowController::class,
                        'action' => 'workflowFields',
                        'method' => 'GET',
                        'access'=>[
                        ],
                    ],
                ],
            ],
            'workflowform' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/app/:appId/workflow/:workflowId/forms',
                    'defaults' => [
                        'controller' => Controller\WorkflowController::class,
                        'action' => 'workflowForms',
                        'method' => 'GET',
                        'access'=>[
                        ],
                    ],
                ],
            ],
            'assignments' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/app/:appId/assignments',
                    'defaults' => [
                        'controller' => Controller\AppController::class,
                        'action' => 'assignments',
                        'access'=>[
                        ],
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
