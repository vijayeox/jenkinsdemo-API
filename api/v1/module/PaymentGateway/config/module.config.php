<?php

namespace PaymentGateway;

use Zend\Log\Formatter\Simple;
use Zend\Log\Logger;
use Zend\Log\Processor\RequestId;
use Zend\Router\Http\Segment;
use Oxzion\Utils\UuidUtil;

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
        ],
    ],
    'log' => [
        'PaymentGatewayLogger' => [
            'writers' => [
                'stream' => [
                    'name' => 'stream',
                    'priority' => \Zend\Log\Logger::ALERT,
                    'options' => [
                        'stream' => __DIR__ . '/../../../logs/payment.log',
                        'formatter' => [
                            'name' => \Zend\Log\Formatter\Simple::class,
                            'options' => [
                                'format' => '%timestamp% %priorityName% (%priority%): %message% %extra%', 'dateTimeFormat' => 'c',
                            ],
                        ],
                        'filters' => [
                            'priority' => \Zend\Log\Logger::INFO],
                    ],
                ],
            ],
            'processors' => [
                'requestid' => [
                    'name' => \Zend\Log\Processor\RequestId::class],
            ],
        ],
    ],
    'view_manager' => [
        // We need to set this up so that we're allowed to return JSON
        // responses from our controller.
        'strategies' => ['ViewJsonStrategy'],
    ],
];
