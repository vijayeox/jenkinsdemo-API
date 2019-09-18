<?php

namespace Workflow;

use Zend\Router\Http\Segment;
use Oxzion\Utils\UuidUtil;
return [
    'router' => [
        'routes' => [
            'workflowInstance' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/workflow/:workflowId[/activity/:activityId][/instance/:instanceId]',
                    'constraints' => [
                        'workflowId' => UuidUtil::UUID_PATTERN,
                        'activityId' => '[0-9]*',                    
                    ],
                    'defaults' => [
                        'controller' => Controller\WorkflowInstanceController::class,
                        'action' => 'activity',
                        'access'=>[
                        ],
                    ],
                ],
            ],
            'workflowActivityInstance' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/workflowinstance/:workflowInstanceId/activity/:activityId/submit',
                    'constraints' => [
                        'activityId' => UuidUtil::UUID_PATTERN,                        
                    ],
                    'defaults' => [
                        'controller' => Controller\WorkflowInstanceController::class,
                        'action' => 'activity',
                        'access'=>[
                        ],
                    ],
                ],
            ],
            'addActivityInstance' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/callback/workflow/activityinstance',
                    'defaults' => [
                        'controller' => Controller\ActivityInstanceController::class,
                        'method' => 'POST',
                        'action' => 'addActivityInstance',
                        'access' => [
                            // SET ACCESS CONTROL
                        ],
                    ],
                ],
            ],
            'completeActivityInstance' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/callback/workflow/activitycomplete',
                    'defaults' => [
                        'controller' => Controller\ActivityInstanceController::class,
                        'method' => 'POST',
                        'action' => 'completeActivityInstance',
                        'access' => [
                            // SET ACCESS CONTROL
                        ],
                    ],
                ],
            ],
            'serviceTaskExecution' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/callback/workflow/servicetask',
                    'defaults' => [
                        'controller' => Controller\ServiceTaskController::class,
                        'method' => 'POST',
                        'action' => 'execute',
                        'access' => [
                            // SET ACCESS CONTROL
                        ],
                    ],
                ],
            ],
            'workflowIndividualInstance' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/workflowinstance/:workflowInstanceId/activity/:activityId[/instance/:instanceId]',
                    'constraints' => [
                        'activityId' => UuidUtil::UUID_PATTERN,                       
                    ],
                    'defaults' => [
                        'controller' => Controller\WorkflowInstanceController::class,
                        'action' => 'workflowInstance',
                        'access'=>[
                        ],
                    ],
                ],
            ],
            'completeWorkflowInstance' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/callback/workflowinstance/complete',
                    'defaults' => [
                        'controller' => Controller\WorkflowInstanceController::class,
                        'method' => 'POST',
                        'action' => 'completeWorkflow',
                        'access' => [
                        ],
                    ],
                ],
            ],
            'claimActivityInstance' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/workflowinstance/:workflowInstanceId/activity/:activityInstanceId/claim',
                    'defaults' => [
                        'controller' => Controller\WorkflowInstanceController::class,
                        'method' => 'POST',
                        'action' => 'claimActivityInstance',
                        'access' => [
                        ],
                    ],
                ],
            ],
            'activityInstanceForm' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/activity/:activityInstanceId/form',
                    'defaults' => [
                        'controller' => Controller\WorkflowInstanceController::class,
                        'method' => 'POST',
                        'action' => 'activityInstanceForm',
                        'access' => [
                        ],
                    ],
                ],
            ],
            'filelisting' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/app/:appId/[workflow/:workflowId/][:userId/]file',
                    'constraints' => [
                        'appId' => UuidUtil::UUID_PATTERN,
                        'workflowId' => UuidUtil::UUID_PATTERN,
                        'userId' => UuidUtil::UUID_PATTERN,
                    ],
                    'defaults' => [
                        'controller' => Controller\WorkflowInstanceController::class,
                        'method' => 'GET',
                        'action' => 'getFileList',
                        'access' => [
                            // SET ACCESS CONTROL
                        ],
                    ],
                ],
            ],
            'filedocumentlisting' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/app/:appId/file/:fileId/document',
                    'constraints' => [
                        'appId' => UuidUtil::UUID_PATTERN,
                        'fileId' => UuidUtil::UUID_PATTERN,
                    ],
                    'defaults' => [
                        'controller' => Controller\WorkflowInstanceController::class,
                        'method' => 'GET',
                        'action' => 'getFileDocumentList',
                        'access' => [
                            // SET ACCESS CONTROL
                        ],
                    ],
                ],
            ],
        ],
    ],
    'log' => [
        'ActivityInstanceLogger' => [
            'writers' => [
                'stream' => [
                    'name' => 'stream',
                    'priority' => \Zend\Log\Logger::ALERT,
                    'options' => [
                        'stream' => __DIR__ . '/../../../logs/activity.log',
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
        'ServiceTaskLogger' => [
            'writers' => [
                'stream' => [
                    'name' => 'stream',
                    'priority' => \Zend\Log\Logger::ALERT,
                    'options' => [
                        'stream' => __DIR__ . '/../../../logs/servicetask.log',
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
        'WorkflowInstanceLogger' => [
            'writers' => [
                'stream' => [
                    'name' => 'stream',
                    'priority' => \Zend\Log\Logger::ALERT,
                    'options' => [
                        'stream' => __DIR__ . '/../../../logs/workflowinstance.log',
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
