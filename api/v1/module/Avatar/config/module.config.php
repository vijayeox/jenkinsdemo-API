<?php
namespace Avatar;

use Zend\Log\Logger;
use Zend\Router\Http\Segment;
use Zend\Log\Formatter\Simple;
use Zend\Log\Filter\Priority;
use Zend\Log\Processor\RequestId;

return [
    'router' => [
        'routes' => [
            'avatars' => [
                'type'    => Segment::class,
                'options' => [
                'route'    => '/avatar[/:avatarId]',
                'defaults' => [
                        'controller' => Controller\AvatarController::class
                    ],
                ],
            ],
            'avatargroups' => [
                'type'    => Segment::class,
                'options' => [
                'route'    => '/avatar[/:avatarId]/groups',
                'defaults' => [
                        'controller' => Controller\GroupController::class
                    ],
                ],
            ],
        ],
    ],
    'log' => [
        'AvatarLogger' => [
            'writers' => [
                'stream' => [
                    'name' => 'stream',
                    'priority' => \Zend\Log\Logger::ALERT,
                    'options' => [
                        'stream' => __DIR__ . '/../../../logs/avatar.log',
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