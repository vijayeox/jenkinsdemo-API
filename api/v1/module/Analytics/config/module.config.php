<?php
namespace Analytics;

use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'dataSource' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/analytics/datasource[/:dataSourceUuid]',
                    'defaults' => [
                        'controller' => Controller\DataSourceController::class,
                        'access' => [
                            // SET ACCESS CONTROL
                            'put' => 'MANAGE_DATASOURCE_WRITE',
                            'post' => 'MANAGE_DATASOURCE_WRITE',
                            'delete' => 'MANAGE_DATASOURCE_WRITE',
                            'get' => 'MANAGE_DATASOURCE_READ',
                        ],
                    ],
                ],
            ],
            'query' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/analytics/query[/:queryUuid]',
                    'defaults' => [
                        'controller' => Controller\QueryController::class,
                        'access' => [
                            // SET ACCESS CONTROL
                            'put' => 'MANAGE_QUERY_WRITE',
                            'post' => 'MANAGE_QUERY_WRITE',
                            'delete' => 'MANAGE_QUERY_WRITE',
                            'get' => 'MANAGE_QUERY_READ',
                        ],
                    ],
                ],
            ],
            'previewQuery' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/analytics/query/preview',
                    'defaults' => [
                        'controller' => Controller\QueryController::class,
                        'method' => 'POST',
                        'action' => 'previewQuery',
                        'access' => [
                            'previewQuery' => 'MANAGE_QUERY_WRITE',
                        ],
                    ],
                ],
            ],
            'queryData' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/analytics/query/data',
                    'defaults' => [
                        'controller' => Controller\QueryController::class,
                        'method' => 'POST',
                        'action' => 'queryData',
                        'access' => [
                            'queryData' => 'MANAGE_QUERY_WRITE',
                        ],
                    ],
                ],
            ],
            'visualization' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/analytics/visualization[/:visualizationUuid]',
                    'defaults' => [
                        'controller' => Controller\VisualizationController::class,
                        'access' => [
                            // SET ACCESS CONTROL
                            'put' => 'MANAGE_VISUALIZATION_WRITE',
                            'post' => 'MANAGE_VISUALIZATION_WRITE',
                            'delete' => 'MANAGE_VISUALIZATION_WRITE',
                            'get' => 'MANAGE_VISUALIZATION_READ',
                        ],
                    ],
                ],
            ],
            'analytics_widget' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/analytics/widget[/:widgetUuid]',
                    'defaults' => [
                        'controller' => Controller\WidgetController::class,
                        'access' => [
                            'put' => 'MANAGE_ANALYTICS_WIDGET_WRITE',
                            'post' => 'MANAGE_ANALYTICS_WIDGET_WRITE',
                            'delete' => 'MANAGE_ANALYTICS_WIDGET_WRITE',
                        ],
                    ],
                ],
            ],
            'copyWidget' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/analytics/widget[/:widgetUuid]/copy',
                    'defaults' => [
                        'controller' => Controller\WidgetController::class,
                        'method' => 'POST',
                        'action' => 'copyWidget',
                        'access' => [
                            'copyWidget' => 'MANAGE_ANALYTICS_WIDGET_WRITE',
                        ],
                    ],
                ],
            ],
            'dashboard' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/analytics/dashboard[/:dashboardUuid]',
                    'defaults' => [
                        'controller' => Controller\DashboardController::class,
                        'access' => [
                            // SET ACCESS CONTROL
                            'put' => 'MANAGE_DASHBOARD_WRITE',
                            'post' => 'MANAGE_DASHBOARD_WRITE',
                            'delete' => 'MANAGE_DASHBOARD_WRITE',
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
