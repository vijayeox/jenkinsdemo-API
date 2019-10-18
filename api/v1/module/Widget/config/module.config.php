<?php
namespace Widget;

use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'widget' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/widget[/:widgetId]',
                    'defaults' => [
                        'controller' => Controller\WidgetController::class,
                        'access'=>[
                            // SET ACCESS CONTROL
                            'put'=> 'MANAGE_WIDGET_WRITE',
                            'post'=> 'MANAGE_WIDGET_WRITE',
                            'delete'=> 'MANAGE_WIDGET_WRITE',
                            'get'=> 'MANAGE_WIDGET_READ',
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
