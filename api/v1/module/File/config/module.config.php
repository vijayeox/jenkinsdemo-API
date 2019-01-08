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
                'type' => Segment::class,
                'options' => [
                    'route' => '/file[/:fileId]',
                    'defaults' => [
                        'controller' => Controller\FileController::class,
                        'access'=>[
                            // SET ACCESS CONTROL
                            'put'=> 'MANAGE_FILE_WRITE',
                            'post'=> 'MANAGE_FILE_WRITE',
                            'delete'=> 'MANAGE_FILE_WRITE',
                            'get'=> 'MANAGE_FILE_READ',
                        ],
                    ],
                ],
            ],
            'comment' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/file/:fileId/comment[/:id]',
                    'defaults' => [
                        'controller' => Controller\CommentController::class,
                        'access'=>[
                            // SET ACCESS CONTROL
                            'put'=> 'MANAGE_COMMENT_WRITE',
                            'post'=> 'MANAGE_COMMENT_WRITE',
                            'delete'=> 'MANAGE_COMMENT_WRITE',
                            'get'=> 'MANAGE_COMMENT_READ',
                        ],
                    ],
                ],
            ],
            'commentchild' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/file/:fileId/comment/:id/getchildlist',
                    'defaults' => [
                        'controller' => Controller\CommentController::class,
                        'method' => 'GET',
                        'action' => 'getChildList',
                        'access' => [
                            'getChildList'=>'MANAGE_PROJECT_WRITE'
                        ],
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
                        'stream' => __DIR__ . '/../../../logs/instanceform.log',
                        'formatter' => [
                            'name' => \Zend\Log\Formatter\Simple::class,
                            'options' => [
                                'format' => '%timestamp% %priorityName% (%priority%): %message% %extra%', 'dateTimeFormat' => 'c',
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
