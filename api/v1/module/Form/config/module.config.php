<?php
namespace Form;

use Zend\Log\Logger;
use Zend\Router\Http\Segment;
use Zend\Log\Formatter\Simple;
use Zend\Log\Filter\Priority;
use Zend\Log\Processor\RequestId;

return [
	'router' => [
        'routes' => [
            'form' => [
                'type'    => Segment::class,
                'options' => [
                	'route'    => '/form[/:formId]',
                    'defaults' => [
                        'controller' => Controller\FormController::class,
                        'access'=>[
                            // SET ACCESS CONTROL
                            'put'=> 'MANAGE_FORM_WRITE',
                            'post'=> 'MANAGE_FORM_WRITE',
                            'delete'=> 'MANAGE_FORM_WRITE',
                            'get'=> 'MANAGE_FORM_READ',
                        ],
                    ],
                ],
            ],
            'field' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/form/:formId/field[/:id]',
                    'defaults' => [
                        'controller' => Controller\FieldController::class,
                        'access'=>[
                            // SET ACCESS CONTROL
                            'put'=> 'MANAGE_FIELD_WRITE',
                            'post'=> 'MANAGE_FIELD_WRITE',
                            'delete'=> 'MANAGE_FIELD_WRITE',
                            'get'=> 'MANAGE_FIELD_READ',
                        ],
                    ],
                ],
            ],
        ],
    ],
    'log' => [
        'FormLogger' => [
            'writers' => [
                'stream' => [
                    'name' => 'stream',
                    'priority' => \Zend\Log\Logger::ALERT,
                    'options' => [
                        'stream' => __DIR__ . '/../../../logs/metaform.log',
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