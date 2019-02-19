<?php

namespace Email;

use Zend\Log\Logger;
use Zend\Router\Http\Segment;
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