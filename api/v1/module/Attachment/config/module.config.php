<?php

namespace Attachment;

use Zend\Log\Logger;
use Zend\Router\Http\Segment;
use Zend\Log\Formatter\Simple;
use Zend\Log\Filter\Priority;
use Zend\Log\Processor\RequestId;

return [
    'router' => [
        'routes' => [
            'attachment' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/attachment[/:attachmentId]',
                    'defaults' => [
                        'controller' => Controller\AttachmentController::class,
                        'access'=>[
                            // SET ACCESS CONTROL
                            'put'=> ['MANAGE_ATTACHMENT_WRITE','MANAGE_ANNOUNCEMENT_WRITE'],
                            'post'=> ['MANAGE_ATTACHMENT_WRITE','MANAGE_ANNOUNCEMENT_WRITE'],
                            'delete'=> ['MANAGE_ATTACHMENT_WRITE','MANAGE_ANNOUNCEMENT_WRITE'],
                            'get'=> ['MANAGE_ATTACHMENT_READ','MANAGE_ANNOUNCEMENT_READ']
                        ],
                    ],
                ],
            ],
        ],
    ],
    'log' => [
        'AttachmentLogger' => [
            'writers' => [
                'stream' => [
                    'name' => 'stream',
                    'priority' => \Zend\Log\Logger::ALERT,
                    'options' => [
                        'stream' => __DIR__ . '/../../../logs/attachment.log',
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
