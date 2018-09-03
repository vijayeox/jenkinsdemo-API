<?php
namespace File;

use Zend\Log\Logger;
use Zend\Router\Http\Segment;
use Zend\Log\Formatter\Simple;
use Zend\Log\Filter\Priority;
use Zend\Log\Processor\RequestId;

return [
    'router' => [
        'routes' => [
            'file' => [
                'type'    => Segment::class,
                'options' => [
                'route'    => '/file[/:fileId]',
                'defaults' => [
                        'controller' => Controller\FileController::class
                    ],
                ],
            ],
        ],
    ],
    'log' => [
        'FileLogger' => [
            'writers' => [
                'stream' => [
                    'name' => 'stream',
                    'priority' => \Zend\Log\Logger::ALERT,
                    'options' => [
                        'stream' => __DIR__ . '/../../../logs/file.log',
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