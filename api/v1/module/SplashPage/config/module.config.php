<?php

namespace SplashPage;

use Zend\Log\Logger;
use Zend\Router\Http\Segment;
use Zend\Log\Formatter\Simple;
use Zend\Log\Filter\Priority;
use Zend\Log\Processor\RequestId;

return [
    'router' => [
        'routes' => [
            'splashpage' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/splashpage[/:splashpageId]',
                    'defaults' => [
                        'controller' => Controller\SplashPageController::class,
                        'access'=>[
                            // SET ACCESS CONTROL
                            'put'=> 'MANAGE_SPLASHPAGE_WRITE',
                            'post'=> 'MANAGE_SPLASHPAGE_WRITE',
                            'delete'=> 'MANAGE_SPLASHPAGE_WRITE',
                            'get'=> 'MANAGE_SPLASHPAGE_READ',
                        ],
                    ],
                ],
            ],
            'splashpageOrg' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/splashpage/organization/:organizaionId',
                    'defaults' => [
                        'controller' => Controller\SplashPageController::class,
                        'method' => 'GET',
                        'action' => 'getSplashpageforOrganization',
                        'access' => [
                            'getSplashpageforOrganization'=>'MANAGE_SPLASHPAGE_WRITE'
                       ],
                   ],
               ],
           ],
            // 'UpdateSplashPage' => [
            //     'type' => Segment::class,
            //     'options' => [
            //         'route' => '/splashpage/update',
            //         'defaults' => [
            //             'controller' => Controller\SplashPageController::class,
            //             'method' => 'PUT',
            //             'action' => 'UpdateSplashPage',
            //             'access'=>[
            //                 'UpdateSplashPage'=>'MANAGE_SPLASHPAGE_WRITE'
            //             ]
            //         ],
            //     ],
            // ],
            // 'announcementToGroup' => [
            //     'type' => Segment::class,
            //     'options' => [
            //         'route' => '/announcement/:announcementId/group',
            //         'defaults' => [
            //             'controller' => Controller\AnnouncementController::class,
            //             'method' => 'POST',
            //             'action' => 'announcementToGroup'
            //         ],
            //     ],
            // ],
        ],
    ],
    'log' => [
        'SplashPageLogger' => [
            'writers' => [
                'stream' => [
                    'name' => 'stream',
                    'priority' => \Zend\Log\Logger::ALERT,
                    'options' => [
                        'stream' => __DIR__ . '/../../../logs/splashpage.log',
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
