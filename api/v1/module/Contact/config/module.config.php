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
                        'access' => [
                            // SET ACCESS CONTROL
                            'put' => 'MANAGE_CONTACT_WRITE',
                            'post' => 'MANAGE_CONTACT_CREATE',
                            'delete' => 'MANAGE_CONTACT_DELETE',
                            'get' => 'MANAGE_CONTACT_READ',
                        ],
                    ],
                ],
            ],
            'contactsForOrg' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/contact/org[/:orgId]',
                    'defaults' => [
                        'controller' => Controller\ContactController::class,
                        'action' => 'getContactListByOrg',
                        'method' => 'get',
                        'access'=>[
                            // SET ACCESS CONTROL
                            'getContactListByOrg'=> 'MANAGE_CONTACT_READ',
                        ],
                    ],
                ],
            ],
            'contactList' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/contact/list',
                    'defaults' => [
                        'controller' => Controller\ContactController::class,
                        'action' => 'getContactListWithLimit',
                        'method' => 'post',
                        'access'=>[
                            // SET ACCESS CONTROL
                            'getContactListWithLimit'=> 'MANAGE_CONTACT_READ',
                        ],
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