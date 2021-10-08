<?php

namespace Billing;

use Oxzion\Utils\UuidUtil;
use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'billingInvoice' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/billing/invoice[/:invoiceId]',
                    'constraints' => [
                        'invoiceId' => UuidUtil::UUID_PATTERN,
                        
                    ],
                    'defaults' => [
                        'controller' => Controller\InvoiceController::class,
                        'access' => [
                            // SET ACCESS CONTROL
                            // 'put' => 'MANAGE_APPLICATION_WRITE',
                            // 'post' => 'MANAGE_APPLICATION_WRITE',
                            // 'delete' => 'MANAGE_APPLICATION_WRITE',
                            // 'get' => 'MANAGE_APPLICATION_READ',
                        ],
                    ],
                ],
            ],
            'invoicePayment' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/billing/payment',
                    'constraints' => [
                        'appId' => UuidUtil::UUID_PATTERN
                        
                    ],
                    'defaults' => [
                        'controller' => Controller\InvoiceController::class,
                        'action' => 'invoicePayment',
                        'method' => 'POST',
                        'access' => [
                            // SET ACCESS CONTROL
                            // 'put' => 'MANAGE_APPLICATION_WRITE',
                            // 'post' => 'MANAGE_APPLICATION_WRITE',
                            // 'delete' => 'MANAGE_APPLICATION_WRITE',
                            // 'get' => 'MANAGE_APPLICATION_READ',
                        ],
                    ],
                ],
            ]

    ],
    'view_manager' => [
        // We need to set this up so that we're allowed to return JSON
        // responses from our controller.
        'strategies' => ['ViewJsonStrategy'],
        ],
    ]   
];
