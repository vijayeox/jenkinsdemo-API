<?php

namespace PaymentGateway;

use Oxzion\Utils\UuidUtil;
use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'paymentgateway' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/app/:appId/paymentgateway[/:paymentId]',
                    'constraints' => [
                        // 'appId' => UuidUtil::UUID_PATTERN,
                    ],
                    'defaults' => [
                        'controller' => Controller\PaymentGatewayController::class,
                        'access' => [
                            // SET ACCESS CONTROL
                            // 'put'=> 'MANAGE_ALERT_WRITE',
                            // 'post'=> 'MANAGE_ALERT_WRITE',
                            // 'delete'=> 'MANAGE_ALERT_WRITE',
                            // 'get'=> 'MANAGE_ALERT_READ',
                            // 'decline'=>'MANAGE_ALERT_READ',
                        ],
                    ],
                ],
            ],
            'initiatepaymentprocess' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/app/:appId/paymentgateway/initiate',
                    'constraints' => [
                        'appId' => UuidUtil::UUID_PATTERN,
                    ],
                    'defaults' => [
                        'controller' => Controller\PaymentGatewayController::class,
                        'method' => 'POST',
                        'action' => 'initiatePaymentProcess',
                    ],
                ],
            ],
            'updatetransactionstatus' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/app/:appId/transaction/:transactionId/status',
                    'constraints' => [
                        'appId' => UuidUtil::UUID_PATTERN,
                    ],
                    'defaults' => [
                        'controller' => Controller\PaymentGatewayController::class,
                        'method' => 'POST',
                        'action' => 'updateTransactionStatus',
                    ],
                ],
            ],
            'paymentcallback' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/user/fortewebhook/callback',
                    'defaults' => [
                        'controller' => Controller\PaymentCallbackController::class,
                        'method' => 'POST',
                        'action' => 'forteWebhookCallback'
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
