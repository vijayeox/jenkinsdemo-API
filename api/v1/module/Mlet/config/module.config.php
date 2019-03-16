<?php
namespace Mlet;

use Zend\Log\Logger;
use Zend\Router\Http\Segment;
use Zend\Log\Formatter\Simple;
use Zend\Log\Filter\Priority;
use Zend\Log\Processor\RequestId;

return [
	'router' => [
        'routes' => [
            'mlet' => [
                'type'    => Segment::class,
                'options' => [
                	'route'    => '/mlet[/:mletId]',
                    'defaults' => [
                        'controller' => Controller\MletController::class,
                        'access'=>[
                            // SET ACCESS CONTROL
                            'put'=> 'MANAGE_MLET_WRITE',
                            'post'=> 'MANAGE_MLET_WRITE',
                            'delete'=> 'MANAGE_MLET_WRITE',
                            'get'=> 'MANAGE_MLET_READ',
                        ],
                    ],
                ],
            ], 
            'mletResult' => [
                'type'    => Segment::class,
                'options' => [
                	'route'    => '/mlet/:mletId/result',
                    'defaults' => [
                        'controller' => Controller\MletController::class,
                        'method' => 'POST',
                        'action' => 'getResult',
                        'access' => [
                            'getResult'=>'MANAGE_MLET_READ'
                       ],
                    ],
                ],
            ],             
        ],
    ],
    'log' => [
        'MletLogger' => [
            'writers' => [
                'stream' => [
                    'name' => 'stream',
                    'priority' => \Zend\Log\Logger::ALERT,
                    'options' => [
                        'stream' => __DIR__ . '/../../../logs/Mlet.log',
                        'formatter' => [
                            'name' => \Zend\Log\Formatter\Simple::class,
                            'options' => [
                                'format' => '%timestamp% %priorityName% (%priority%): %message% %extra%',
                                'dateTimeFormat' => 'c',
                            ],
                        ],
                        'filters' => [
                            'priority' => \Zend\Log\Logger::INFO,
                        ],
                    ],
                ],
            ],
            'processors' => [
                'requestid' => [
                    'name' => \Zend\Log\Processor\RequestId::class,
                ],
            ],
        ],
    ],
    'view_manager' => [
        // We need to set this up so that we're allowed to return JSON
        // responses from our controller.
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
    
];