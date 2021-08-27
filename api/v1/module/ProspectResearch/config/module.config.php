<?php
namespace ProspectResearch;

use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'prospectresearch' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/prospectresearch', /* Prospect Research */
                    'defaults' => [
                        'controller' => Controller\ProspectResearchController::class,
                        'access'=>[
                            // SET ACCESS CONTROL
                            'post'=> 'MANAGE_PROSPECTRESEARCH_READ',
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
