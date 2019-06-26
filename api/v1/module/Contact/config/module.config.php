<?php

namespace Contact;

use Zend\Log\Logger;
use Zend\Router\Http\Segment;
use Zend\Log\Formatter\Simple;
use Zend\Log\Filter\Priority;
use Zend\Log\Processor\RequestId;

return [
    'router' => [
        'routes' => [
            'contacts' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/contact[/:contactId]',
                    'defaults' => [
                        'controller' => Controller\ContactController::class,
                    ],
                ],
            ],
            'getContactListByOrg' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/contact/org',
                    'defaults' => [
                        'controller' => Controller\ContactController::class,
                        'action' => 'getContactListByOrg',
                        'method' => 'get',
                    ],
                ],
            ],
            'contactsForUser' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/contact/search',
                    'defaults' => [
                        'controller' => Controller\ContactController::class,
                        'action' => 'getContacts',
                        'method' => 'get',
                    ],
                ],
            ],
            'contactIcon' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/contact/:contactId/:ownerId',
                    'defaults' => [
                        'controller' => Controller\ContactIconController::class,
                        'action' => 'getIcon',
                        'method' => 'get',
                    ],
                ],
            ],
        ],
    ],
    'log' => [
        'ContactLogger' => [
            'writers' => [
                'stream' => [
                    'name' => 'stream',
                    'priority' => \Zend\Log\Logger::ALERT,
                    'options' => [
                        'stream' => __DIR__ . '/../../../logs/contact.log',
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