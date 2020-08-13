<?php

namespace File;

use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'comment' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/file/:fileId/comment[/:id]',
                    'defaults' => [
                        'controller' => Controller\CommentController::class,
                        'access'=>[
                            // SET ACCESS CONTROL
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
                        ],
                    ],
                ],
            ],
            'subscriber' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/file/:fileId/subscriber[/:id]',
                    'defaults' => [
                        'controller' => Controller\SubscriberController::class,
                        'access'=>[
                            // SET ACCESS CONTROL
                        ],
                    ],
                ],
            ],
            'fileCallbackUpdate' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/callback/file/update',
                    'defaults' => [
                        'controller' => Controller\FileCallbackController::class,
                        'method' => 'POST',
                        'action' => 'updateFile',
                        'access'=>[
                            // SET ACCESS CONTROL
                        ],
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        // We need to set this up so that we're allowed to return JSON
        // responses from our controller.
        'strategies' => ['ViewJsonStrategy',],
    ],
];
