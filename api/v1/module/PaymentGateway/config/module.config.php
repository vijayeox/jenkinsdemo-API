<?php

namespace PaymentGateway;

use Zend\Log\Formatter\Simple;
use Zend\Log\Logger;
use Zend\Log\Processor\RequestId;
use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'paymentgateway' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/paymentgateway[/:paymentId]/app/:appId',
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
            'initiatepayment' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/paymentgateway/app/:appId/initiate',
                    'defaults' => [
                        'controller' => Controller\PaymentGatewayController::class,
                        'method' => 'GET',
                        'action' => 'initiatePayment',
                    //     'access' => [
                    //         'getuserlist'=>'MANAGE_GROUP_WRITE'
                    //    ],
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
