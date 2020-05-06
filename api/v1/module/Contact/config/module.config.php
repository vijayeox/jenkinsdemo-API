<?php

namespace Contact;

use Zend\Router\Http\Segment;

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
                    'route' => '/contact/:ownerId/:contactId',
                    'defaults' => [
                        'controller' => Controller\ContactIconController::class,
                        'action' => 'getIcon',
                        'method' => 'get',
                    ],
                ],
            ],
            'contactImport' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/contact/import',
                    'defaults' => [
                        'controller' => Controller\ContactController::class,
                        'action' => 'contactImport',
                        'method' => 'post',
                    ],
                ],
            ],
            'contactExport' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/contact/export[/:contactUuid]',
                    'defaults' => [
                        'controller' => Controller\ContactController::class,
                        'action' => 'contactExport',
                        'method' => 'POST',
                    ],
                ],
            ],
            'contactsDelete' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/contacts/delete',
                    'defaults' => [
                        'controller' => Controller\ContactController::class,
                        'action' => 'contactsDelete',
                        'method' => 'POST',
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
