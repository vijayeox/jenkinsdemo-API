<?php
namespace Analytics;

use Zend\Log\Logger;
use Zend\Router\Http\Segment;
use Zend\Log\Formatter\Simple;
use Zend\Log\Filter\Priority;
use Zend\Log\Processor\RequestId;

return [
    'router' => [
        'routes' => [
            'dataSource' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/analytics/datasource[/:dataSourceUuid]',
                    'defaults' => [
                        'controller' => Controller\DataSourceController::class,
                        'access'=>[
                            // SET ACCESS CONTROL
                            'put'=> 'MANAGE_DATASOURCE_WRITE',
                            'post'=> 'MANAGE_DATASOURCE_WRITE',
                            'delete'=> 'MANAGE_DATASOURCE_WRITE',
                            'get'=> 'MANAGE_DATASOURCE_READ',
                        ],
                    ],
                ],
            ],
            'query' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/analytics/query[/:queryUuid]',
                    'defaults' => [
                        'controller' => Controller\QueryController::class,
                        'access'=>[
                            // SET ACCESS CONTROL
                            'put'=> 'MANAGE_QUERY_WRITE',
                            'post'=> 'MANAGE_QUERY_WRITE',
                            'delete'=> 'MANAGE_QUERY_WRITE',
                            'get'=> 'MANAGE_QUERY_READ',
                        ],
                    ],
                ],
            ],
            'visualization' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/analytics/visualization[/:visualizationUuid]',
                    'defaults' => [
                        'controller' => Controller\VisualizationController::class,
                        'access'=>[
                            // SET ACCESS CONTROL
                            'put'=> 'MANAGE_VISUALIZATION_WRITE',
                            'post'=> 'MANAGE_VISUALIZATION_WRITE',
                            'delete'=> 'MANAGE_VISUALIZATION_WRITE',
                            'get'=> 'MANAGE_VISUALIZATION_READ',
                        ],
                    ],
                ],
            ],
            'analytics_widget' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/analytics/widget[/:widgetUuid]',
                    'defaults' => [
                        'controller' => Controller\WidgetController::class,
                        'access'=>[
                            // SET ACCESS CONTROL
                            'put'=> 'MANAGE_ANALYTICS_WIDGET_WRITE',
                            'post'=> 'MANAGE_ANALYTICS_WIDGET_WRITE',
                            'delete'=> 'MANAGE_ANALYTICS_WIDGET_WRITE',
                            'get'=> 'MANAGE_ANALYTICS_WIDGET_READ',
                        ],
                    ],
                ],
            ],
            'dashboard' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/analytics/dashboard[/:dashboardUuid]',
                    'defaults' => [
                        'controller' => Controller\DashboardController::class,
                        'access'=>[
                            // SET ACCESS CONTROL
                            'put'=> 'MANAGE_DASHBOARD_WRITE',
                            'post'=> 'MANAGE_DASHBOARD_WRITE',
                            'delete'=> 'MANAGE_DASHBOARD_WRITE',
                            'get'=> 'MANAGE_DASHBOARD_READ',
                        ],
                    ],
                ],
            ],
        ],
    ],
    'log' => [
        'AnalyticsLogger' => [
            'writers' => [
                'stream' => [
                    'name' => 'stream',
                    'priority' => \Zend\Log\Logger::DEBUG,
                    'options' => [
                        'stream' => __DIR__ . '/../../../logs/Analytics.log',
                        'formatter' => [
                            'name' => \Zend\Log\Formatter\Simple::class,
                            'options' => [
                                'format' => '%timestamp% %priorityName% (%priority%): %message% %extra%','dateTimeFormat' => 'c',
                            ],
                        ],
                        'filters' => [
                            'priority' => \Zend\Log\Logger::DEBUG,
                        ],
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
