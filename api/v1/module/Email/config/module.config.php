<?php

namespace Email;

use Zend\Log\Logger;
use Zend\Router\Http\Segment;
use Zend\Router\Http\Method;
use Zend\Log\Formatter\Simple;
use Zend\Log\Filter\Priority;
use Zend\Log\Processor\RequestId;

return [
    'router' => [
        'routes' => [
            'Email' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/email[/:emailId]',
                    'defaults' => [
                        'controller' => Controller\EmailController::class,
                        'access' => [
                            // SET ACCESS CONTROL
                            'put' => 'MANAGE_EMAIL_WRITE',
                            'post' => 'MANAGE_EMAIL_CREATE',
                            'delete' => 'MANAGE_EMAIL_DELETE',
                            'get' => 'MANAGE_EMAIL_READ',
                        ],
                    ],
                ],
            ],
            'emailDefault' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/email/:emailId/default',
                    'defaults' => [
                        'controller' => Controller\EmailController::class,
                        'method' => 'GET',
                        'action' => 'emailDefault',
                        'access' => [
                            'emailDefault'=>'MANAGE_PROJECT_WRITE'
                        ],
                    ],
                ],
            ],
            'deleteEmail' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/email/deletemail',
                    'defaults' => [
                        'controller' => Controller\EmailController::class,
                        'method' => 'POST',
                        'action' => 'deleteEmail',
                        'access' => [
                            'deleteEmail'=>'MANAGE_PROJECT_WRITE'
                        ],
                    ],
                ],
            ],
            'updateEmail' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/email/update/:address',
                    'defaults' => [
                        'controller' => Controller\EmailController::class,
                        'method' => 'PUT',
                        'action' => 'updateEmail',
                        'access' => [
                            'updateEmail'=>'MANAGE_PROJECT_WRITE'
                        ],
                    ],
                ],
            ],
        ],
    ],
    'log' => [
        'EmailLogger' => [
            'writers' => [
                'stream' => [
                    'name' => 'stream',
                    'priority' => \Zend\Log\Logger::ALERT,
                    'options' => [
                        'stream' => __DIR__ . '/../../../logs/email.log',
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