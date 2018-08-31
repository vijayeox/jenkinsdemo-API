<?php
namespace App;

use Zend\Log\Logger;
use Zend\Router\Http\Segment;
use Zend\Log\Formatter\Simple;
use Zend\Log\Filter\Priority;
use Zend\Log\Processor\RequestId;

return [
    'router' => [
        'routes' => [
            'modulecategories' => [
                'type'    => Segment::class,
                'options' => [
                'route'    => '/modulecategories[/:modulecategoriesId]',
                'defaults' => [
                        'controller' => Controller\ModulecategoriesController::class
                    ],
                ],
            ],
            'apps' => [
                'type'    => Segment::class,
                'options' => [
                'route'    => '/app[/:appId]',
                'defaults' => [
                        'controller' => Controller\AppController::class
                    ],
                ],
            ],
            'forms' => [
                'type'    => Segment::class,
                'options' => [
                'route'    => '/app/:moduleid/form[/:formId]',
                'defaults' => [
                        'controller' => Controller\FormController::class
                    ],
                ],
            ],
            'instanceforms' => [
                'type'    => Segment::class,
                'options' => [
                'route'    => '/app/:moduleid/files',
                'defaults' => [
                        'controller' => Controller\AppController::class,
                        'action' =>'getByModule'
                    ],
                ],
            ],
        ],
    ],
    'log' => [
        'AppLogger' => [
            'writers' => [
                'stream' => [
                    'name' => 'stream',
                    'priority' => \Zend\Log\Logger::ALERT,
                    'options' => [
                        'stream' => __DIR__ . '/../../../logs/app.log',
                            'formatter' => [
                                'name' => \Zend\Log\Formatter\Simple::class,
                                'options' => [
                                    'format' => '%timestamp% %priorityName% (%priority%): %message% %extra%','dateTimeFormat' => 'c',
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