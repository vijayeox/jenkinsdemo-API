<?php
namespace Screen;

use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'screen' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/screen[/:id]',
                    'defaults' => [
                        'controller' => Controller\ScreenController::class,
                        'access'=>[
                            // SET ACCESS CONTROL
                            'put'=> 'MANAGE_SCREEN_WRITE',
                            'post'=> 'MANAGE_SCREEN_WRITE',
                            'delete'=> 'MANAGE_SCREEN_WRITE',
                            'get'=> 'MANAGE_SCREEN_READ',
                        ],
                    ],
                ],
            ],
            'widgetlist' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/screen/:screenId/widget',
                    'defaults' => [
                        'controller' => Controller\ScreenwidgetController::class,
                        'action' => 'getWidgets',
                        'access'=>[
                            // SET ACCESS CONTROL
                            'getWidgets'=> 'MANAGE_SCREENWIDGET_READ',
                        ],
                    ],
                ],
            ],

            'screenwidget' => [
                'type'    => Segment::class,
                'options' => [
                    'route' => '/screenwidget[/:id]',
                    'defaults' => [
                        'controller' => Controller\ScreenwidgetController::class,
                        'access'=>[
                            // SET ACCESS CONTROL
                            'put'=> 'MANAGE_SCREENWIDGET_WRITE',
                            'post'=> 'MANAGE_SCREENWIDGET_WRITE',
                            'delete'=> 'MANAGE_SCREENWIDGET_WRITE',
                            'get'=> 'MANAGE_SCREENWIDGET_READ',
                        ],
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        // We need to set this up so that we're allowed to return JSON
        // responses from our controller.
        'strategies' => [
            'ViewJsonStrategy',
        ],
    ],
    
];
