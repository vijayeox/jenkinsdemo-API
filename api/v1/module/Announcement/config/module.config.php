<?php

namespace Announcement;

use Zend\Log\Logger;
use Zend\Router\Http\Segment;
use Zend\Log\Formatter\Simple;
use Zend\Log\Filter\Priority;
use Zend\Log\Processor\RequestId;

return [
    'router' => [
        'routes' => [
            'announcement' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/announcement[/:announcementId]',
                    'defaults' => [
                        'controller' => Controller\AnnouncementController::class,
                        'access'=>[
                            // SET ACCESS CONTROL
                            'put'=> 'MANAGE_ANNOUNCEMENT_WRITE',
                            'post'=> 'MANAGE_ANNOUNCEMENT_WRITE',
                            'delete'=> 'MANAGE_ANNOUNCEMENT_WRITE',
                        ],
                    ],
                ],
            ],
            'announcementList' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/announcement/a',
                    'defaults' => [
                        'controller' => Controller\AnnouncementController::class,
                        'method' => 'GET',
                        'action' => 'announcementList'
                    ],
                ],
            ],
            'announcementToGroup' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/announcement/:announcementId/save',
                    'defaults' => [
                        'controller' => Controller\AnnouncementController::class,
                        'method' => 'POST',
                        'action' => 'announcementToGroup'
                    ],
                ],
            ],
            'announcementGroups' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/announcement/:announcementId/groups',
                    'defaults' => [
                        'controller' => Controller\AnnouncementController::class,
                        'method' => 'GET',
                        'action' => 'announcementGroups'
                    ],
                ],
            ],
        ],
    ],
    'log' => [
        'AnnouncementLogger' => [
            'writers' => [
                'stream' => [
                    'name' => 'stream',
                    'priority' => \Zend\Log\Logger::ALERT,
                    'options' => [
                        'stream' => __DIR__ . '/../../../logs/announcement.log',
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
