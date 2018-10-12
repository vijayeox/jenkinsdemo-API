<?php

namespace Bookmark;

use Zend\Log\Logger;
use Zend\Router\Http\Segment;
use Zend\Log\Formatter\Simple;
use Zend\Log\Filter\Priority;
use Zend\Log\Processor\RequestId;

return [
    'router' => [
        'routes' => [
            'bookmark' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/bookmark[/:bookmarkId]',
                    'defaults' => [
                        'controller' => Controller\BookmarkController::class,
                        'access'=>[
                            // SET ACCESS CONTROL
                            'put'=> 'MANAGE_BOOKMARK_WRITE',
                            'post'=> 'MANAGE_BOOKMARK_WRITE',
                            'delete'=> 'MANAGE_BOOKMARK_WRITE',
                            'get'=> 'MANAGE_BOOKMARK_READ',
                        ],
                    ],
                ],
            ],
        ],
    ],
    'log' => [
        'BookmarkLogger' => [
            'writers' => [
                'stream' => [
                    'name' => 'stream',
                    'priority' => \Zend\Log\Logger::ALERT,
                    'options' => [
                        'stream' => __DIR__ . '/../../../logs/bookmark.log',
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
