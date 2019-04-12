<?php
namespace ProspectResearch;

use Zend\Log\Logger;
use Zend\Router\Http\Segment;
use Zend\Log\Formatter\Simple;
use Zend\Log\Filter\Priority;
use Zend\Log\Processor\RequestId;

return [
	'router' => [
        'routes' => [
            'prospectresearch' => [
                'type'    => Segment::class,
                'options' => [      
                	'route'    => '/prospectresearch', /* Prospect Research */
                    'defaults' => [
                        'controller' => Controller\ProspectResearchController::class,
                        'access'=>[
                            // SET ACCESS CONTROL
                            'post'=> 'MANAGE_PROSPECTRESEARCH_READ',
                        ],
                    ],
                ],
            ],            
        ],
    ],
    'log' => [
        'ProspectResearchLogger' => [
            'writers' => [
                'stream' => [
                    'name' => 'stream',
                    'priority' => \Zend\Log\Logger::ALERT,
                    'options' => [
                        'stream' => __DIR__ . '/../../../logs/Search.log',
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