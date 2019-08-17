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
                            'put' => '',
                            'post' => '',
                            'delete' => '',
                            'get' => '',
                        ],
                    ],
                ],
            ],
            'emailDefault' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/email/:emailId/default',
                    'defaults' => [
                        'controller' => Controller\EmailController::class,
                        'method' => 'GET',
                        'action' => 'emailDefault',
                        'access' => [
                            'emailDefault' => 'MANAGE_PROJECT_WRITE'
                        ],
                    ],
                ],
            ],
            'deleteEmail' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/email/delete/:address',
                    'defaults' => [
                        'controller' => Controller\EmailController::class,
                        'method' => 'DELETE',
                        'action' => 'deleteEmail',
                        'access' => [
                            'deleteEmail' => 'MANAGE_PROJECT_WRITE'
                        ],
                    ],
                ],
            ],
            'updateEmail' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/email/update/:address',
                    'defaults' => [
                        'controller' => Controller\EmailController::class,
                        'method' => 'PUT',
                        'action' => 'updateEmail',
                        'access' => [
                            'updateEmail' => 'MANAGE_PROJECT_WRITE'
                        ],
                    ],
                ],
            ],
            'Domain' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/domain[/:domainId]',
                    'defaults' => [
                        'controller' => Controller\DomainController::class,
                        'access' => [
                            // SET ACCESS CONTROL
                            'put' => 'MANAGE_DOMAIN_WRITE',
                            'post' => 'MANAGE_DOMAIN_CREATE',
                            'delete' => 'MANAGE_DOMAIN_DELETE',
                            'get' => 'MANAGE_DOMAIN_READ',
                        ],
                    ],
                ],
            ],
            'deleteDomain' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/domain/delete/:name',
                    'defaults' => [
                        'controller' => Controller\DomainController::class,
                        'method' => 'DELETE',
                        'action' => 'deleteDomain',
//                        'access' => [
//                            'deleteEmail' => 'MANAGE_DOMAIN_CREATE'
//                        ],
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
        'DomainLogger' => [
            'writers' => [
                'stream' => [
                    'name' => 'stream',
                    'priority' => \Zend\Log\Logger::ALERT,
                    'options' => [
                        'stream' => __DIR__ . '/../../../logs/domain.log',
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
