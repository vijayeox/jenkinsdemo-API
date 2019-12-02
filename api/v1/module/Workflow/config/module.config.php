<?php

namespace Workflow;

use Oxzion\Utils\UuidUtil;
use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'workflowInstance' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/workflow/:workflowId[/activity/:activityId]',
                    'constraints' => [
                        'workflowId' => UuidUtil::UUID_PATTERN,
                        'activityId' => '[0-9]*',
                    ],
                    'defaults' => [
                        'controller' => Controller\WorkflowInstanceController::class,
                        'method' => 'POST',
                        'action' => 'startWorkflow',
                        'access' => [
                        ],
                    ],
                ],
            ],
            'workflowActivityInstance' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/workflowinstance/:workflowInstanceId/activity/:activityInstanceId/submit',
                    'constraints' => [
                        'activityInstanceId' => UuidUtil::UUID_PATTERN,
                        'workflowInstanceId' => UuidUtil::UUID_PATTERN,
                    ],
                    'defaults' => [
                        'controller' => Controller\WorkflowInstanceController::class,
                        'method' => 'POST',
                        'action' => 'submit',
                        'access' => [
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
            'initiateWorkflow' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/callback/workflowinstance/start',
                    'defaults' => [
                        'controller' => Controller\WorkflowInstanceCallbackController::class,
                        'method' => 'POST',
                        'action' => 'initiateWorkflow',
                        'access' => [
                        ],
                    ],
                ],
            ],
            'completeWorkflowInstance' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/callback/workflowinstance/complete',
                    'defaults' => [
                        'controller' => Controller\WorkflowInstanceCallbackController::class,
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
                    'route' => '/app/:appId/workflowinstance/:workflowInstanceId/activityinstance/:activityInstanceId/claim',
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
                    'route' => '/app/:appId/workflowinstance/:workflowInstanceId/activityinstance/:activityInstanceId/form',
                    'defaults' => [
                        'controller' => Controller\WorkflowInstanceController::class,
                        'method' => 'GET',
                        'action' => 'activityInstanceForm',
                        'access' => [
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
    'view_manager' => [
        // We need to set this up so that we're allowed to return JSON
        // responses from our controller.
        'strategies' => ['ViewJsonStrategy'],
    ],
];
