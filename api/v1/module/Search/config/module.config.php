<?php
namespace Search;

use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'search' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/search',
                    'defaults' => [
                        'controller' => Controller\SearchController::class,
                        'access'=>[
                            // SET ACCESS CONTROL
                            'put'=> 'MANAGE_SEARCH_WRITE',
                            'post'=> 'MANAGE_SEARCH_READ',
                            'delete'=> 'MANAGE_SEARCH_WRITE',
                            'get'=> 'MANAGE_SEARCH_READ',
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
