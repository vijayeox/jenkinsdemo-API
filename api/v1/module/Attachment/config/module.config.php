<?php

namespace Attachment;

use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'attachment' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/attachment[/:attachmentId]',
                    'defaults' => [
                        'controller' => Controller\AttachmentController::class,
                        'access' => [
                            // SET ACCESS CONTROL
                            'put' => ['MANAGE_ATTACHMENT_WRITE', 'MANAGE_ANNOUNCEMENT_WRITE'],
                            'post' => ['MANAGE_ATTACHMENT_WRITE', 'MANAGE_ANNOUNCEMENT_WRITE'],
                            'delete' => ['MANAGE_ATTACHMENT_WRITE', 'MANAGE_ANNOUNCEMENT_WRITE'],
                            'get' => ['MANAGE_ATTACHMENT_READ', 'MANAGE_ANNOUNCEMENT_READ'],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        // We need to set this up so that we're allowed to return JSON
        // responses from our controller.
        'strategies' => ['ViewJsonStrategy'],
    ],
];
