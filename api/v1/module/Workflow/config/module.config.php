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
            'unclaimActivityInstance' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/app/:appId/workflowinstance/:workflowInstanceId/activityinstance/:activityInstanceId/unclaim',
                    'defaults' => [
                        'controller' => Controller\WorkflowInstanceController::class,
                        'method' => 'POST',
                        'action' => 'unclaimActivityInstance',
                        'access' => [
                        ],
                    ],
                ],
            ],
            'reclaimActivityInstance' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/app/:appId/workflowinstance/:workflowInstanceId/activityinstance/:activityInstanceId/reclaim',
                    'defaults' => [
                        'controller' => Controller\WorkflowInstanceController::class,
                        'method' => 'POST',
                        'action' => 'reclaimActivityInstance',
                        'access' => [
                        ],
                    ],
                ],
            ],
            
            'activitylog' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/app/:appId/file/:fileId/activitylog',
                    'defaults' => [
                        'controller' => Controller\WorkflowInstanceController::class,
                        'action' => 'getActivityLog',
                        'method' => 'GET',
                    ],
                ],
            ],
            'fielddiff' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/app/:appId/activity/:activityInstanceId',
                    'defaults' => [
                        'controller' => Controller\WorkflowInstanceController::class,
                        'action' => 'getFieldDiff',
                        'method' => 'GET',
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
        ],
    ],
    'view_manager' => [
        // We need to set this up so that we're allowed to return JSON
        // responses from our controller.
        'strategies' => ['ViewJsonStrategy'],
    ],
];
