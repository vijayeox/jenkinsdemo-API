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
            'subscriber' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/file/:fileId/subscriber[/:id]',
                    'defaults' => [
                        'controller' => Controller\SubscriberController::class,
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
        ],
    ],
    'view_manager' => [
        // We need to set this up so that we're allowed to return JSON
        // responses from our controller.
        'strategies' => ['ViewJsonStrategy',],
    ],
];
