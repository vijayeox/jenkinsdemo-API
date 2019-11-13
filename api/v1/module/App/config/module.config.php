<?php

namespace App;

use Zend\Router\Http\Segment;
use Oxzion\Utils\UuidUtil;

return [
    'router' => [
        'routes' => [
            'app' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/app[/:appId]',
                    'constraints' => [
                        'appId' => UuidUtil::UUID_PATTERN,                   
                    ],
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
            'deployapp' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/app/deployapp',
                    'defaults' => [
                        'controller' => Controller\AppController::class,
                        'action' => 'deployApp',
                        'method' => 'post'
                    ],
                ],
            ],
            'appinstall' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/app/:appId/appinstall',
                    'constraints' => [
                        'appId' => UuidUtil::UUID_PATTERN,                    
                    ],
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
                    'route' => '/app/:appId/entity/:entityId/deployworkflow[/:workflowId]',
                    'constraints' => [
                        'workflowId' => UuidUtil::UUID_PATTERN,                    
                    ],
                    'defaults' => [
                        'controller' => Controller\EntityController::class,
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
            'addtoappregistry' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/app/org/:orgId/addtoappregistry',
                    'constraints' => [
                        'orgId' => UuidUtil::UUID_PATTERN,                   
                    ],
                    'defaults' => [
                        'controller' => Controller\AppRegisterController::class,
                        'action' => 'addToAppregistry',
                        'method' => 'POST'
                    ],
                ],
            ],
            'appform' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/app/:appId/form[/:id]',
                    'constraints' => [
                        'appId' => UuidUtil::UUID_PATTERN,
                        'id' => UuidUtil::UUID_PATTERN,                    
                    ],
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
            'appfile' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/app/:appId/form/:formId/file[/:id]',
                    'constraints' => [
                        'appId' => UuidUtil::UUID_PATTERN,
                        'formId' => UuidUtil::UUID_PATTERN, 
                        'id' => UuidUtil::UUID_PATTERN,                   
                    ],
                    'defaults' => [
                        'controller' => Controller\FileController::class,
                        'access'=>[
                            // SET ACCESS CONTROL
                            'put'=> 'MANAGE_FILE_WRITE',
                            'post'=> 'MANAGE_FILE_WRITE',
                            'delete'=> 'MANAGE_FILE_WRITE',
                            'get'=> 'MANAGE_FILE_READ',
                        ],
                    ],
                ],
            ],
            'appfield' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/app/:appId/field[/:id]',
                    'constraints' => [
                        'appId' => UuidUtil::UUID_PATTERN,
                        'id' => UuidUtil::UUID_PATTERN,                    
                    ],
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
            'appDelegate' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/app/:appId/delegate/:delegate',
                    'constraints' => [
                        'appId' => UuidUtil::UUID_PATTERN,
                        'delegate' => '[A-Za-z0-9]*',                    
                    ],
                    'defaults' => [
                        'controller' => Controller\AppDelegateController::class,
                        'action' => 'delegate',
                        'method' => 'POST'
                    ],
                ],
            ],
            'appworkflow' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/app/:appId/workflow[/:workflowId]',
                    'constraints' => [
                        'workflowId' => UuidUtil::UUID_PATTERN,
                        'appId' => UuidUtil::UUID_PATTERN,                    
                    ],
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
                    'constraints' => [
                        'appId' => UuidUtil::UUID_PATTERN,
                        'menuId' => UuidUtil::UUID_PATTERN,                    
                    ],
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
                    'route' => '/app/:appId[/org/:orgId]/page[/:pageId]',
                    'constraints' => [
                        'appId' => UuidUtil::UUID_PATTERN,
                        'orgId' => UuidUtil::UUID_PATTERN,                    
                        'pageId' => UuidUtil::UUID_PATTERN,
                    ],
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
            'apppagecontent' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/app/:appId/pagecontent[/:pageContentId]',
                    'constraints' => [
                        'appId' => UuidUtil::UUID_PATTERN,
                    ],
                    'defaults' => [
                        'controller' => Controller\PageContentController::class,
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
                    'constraints' => [
                        'appId' => UuidUtil::UUID_PATTERN,
                        'workflowId' => UuidUtil::UUID_PATTERN,                    
                    ],
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
                    'constraints' => [
                        'appId' => UuidUtil::UUID_PATTERN,
                        'workflowId' => UuidUtil::UUID_PATTERN,                    
                    ],
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
                    'constraints' => [
                        'appId' => UuidUtil::UUID_PATTERN,                   
                    ],
                    'defaults' => [
                        'controller' => Controller\AppController::class,
                        'action' => 'assignments',
                        'access'=>[
                        ],
                    ],
                ],
            ],
            'form_workflow' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/app/:appId/form/:formId/workflow',
                    'constraints' => [
                        'appId' => UuidUtil::UUID_PATTERN,
                        'formId' => UuidUtil::UUID_PATTERN
                    ],
                    'defaults' => [
                        'controller' => Controller\FormController::class,
                        'action' => 'getWorkflow',
                        'method' => 'GET'
                    ],
                ],
            ],
            'importcsv' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/app/:appId/importcsv',
                    'constraints' => [
                        'appId' => UuidUtil::UUID_PATTERN,                  
                    ],
                    'defaults' => [
                        'controller' => Controller\ImportController::class,
                        'action' => 'importCSV',
                        'method' => 'POST'
                    ],
                ],
            ],
            'startform' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/app/:appId/workflow/:workflowId/startform',
                    'constraints' => [
                        'appId' => UuidUtil::UUID_PATTERN,
                        'workflowId' => UuidUtil::UUID_PATTERN,                    
                    ],
                    'defaults' => [
                        'controller' => Controller\WorkflowController::class,
                        'action' => 'startform',
                        'method' => 'POST'
                    ],
                ],
            ],
            'storecache' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/app/:appId/storecache[/:cacheId]',
                    'constraints' => [
                        'appId' => UuidUtil::UUID_PATTERN,                  
                    ],
                    'defaults' => [
                        'controller' => Controller\CacheController::class,
                        'action' => 'store'
                    ],
                ],
            ],
            'app_cache' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/app/:appId/cache',
                    'constraints' => [
                        'appId' => UuidUtil::UUID_PATTERN,                  
                    ],
                    'defaults' => [
                        'controller' => Controller\CacheController::class,
                        'action' => 'cache'
                    ],
                ],
            ],
            'remove_app_cache' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/app/:appId/deletecache[/:cacheId]',
                    'constraints' => [
                        'appId' => UuidUtil::UUID_PATTERN,                  
                    ],
                    'defaults' => [
                        'controller' => Controller\CacheController::class,
                        'action' => 'cacheDelete',
                        'method' => 'DELETE'
                    ],
                ],
            ],
            'getdocument' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/app/:appId/file/:fileId/document/:documentName',
                    'constraints' => [
                        'appId' => UuidUtil::UUID_PATTERN,
                        'fileId' => UuidUtil::UUID_PATTERN,                    
                    ],
                    'defaults' => [
                        'controller' => Controller\FileController::class,
                        'action' => 'getDocument',
                        'method' => 'GET'
                    ],
                ],
            ],
            'fileData' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/app/:appId/workflowInstance/:workflowInstanceId',
                    'constraints' => [
                        'appId' => UuidUtil::UUID_PATTERN,
                        'workflowId' => UuidUtil::UUID_PATTERN,                    
                    ],
                    'defaults' => [
                        'controller' => Controller\FileController::class,
                        'action' => 'getFileData',
                        'method' => 'GET'
                    ],
                ],
            ],
            'appentity' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/app/:appId/entity[/:entityId]',
                    'defaults' => [
                        'controller' => Controller\EntityController::class,
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
            'fileremainder' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/app/:appId/remainder',
                    'defaults' => [
                        'controller' => Controller\FileController::class,
                        'action' => 'sendReminder',
                        'method' => 'POST',
                        'access'=>[
                        ],
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        // We need to set this up so that we're allowed to return JSON
        // responses from our controller.
        'strategies' => ['ViewJsonStrategy',],
    ],
];
