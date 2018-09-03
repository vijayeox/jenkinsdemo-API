<?php
namespace Mailbox;

use Zend\Log\Logger;
use Zend\Router\Http\Segment;
use Zend\Log\Formatter\Simple;
use Zend\Log\Filter\Priority;
use Zend\Log\Processor\RequestId;

return [
    'router' => [
        'routes' => [
            'messages' => [
                'type'    => Segment::class,
                'options' => [
                'route'    => '/messages[/:messagesId]',
                'defaults' => [
                        'controller' => Controller\MessagesController::class
                    ],
                ],
            ],
        ],
    ],
    'log' => [
        'MailboxLogger' => [
            'writers' => [
                'stream' => [
                    'name' => 'stream',
                    'priority' => \Zend\Log\Logger::ALERT,
                    'options' => [
                        'stream' => __DIR__ . '/../../../logs/mailbox.log',
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