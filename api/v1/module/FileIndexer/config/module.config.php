<?php
namespace FileIndexer;

use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            'index' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/fileindexer',
                    'defaults' => [
                        'controller' => Controller\FileIndexerController::class,
                        'action' => 'index',
                    ],
                ],
            ],
            'indexFile' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/fileindexer/file',
                    'defaults' => [
                        'controller' => Controller\FileIndexerController::class,
                        'action' => 'indexFile',
                    ],
                ],
            ],
            'batchIndex' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/fileindexer/batch',
                    'defaults' => [
                        'controller' => Controller\FileIndexerController::class,
                        'action' => 'batchIndex',
                    ],
                ],
            ],
            'deleteIndex' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/fileindexer/remove',
                    'defaults' => [
                        'controller' => Controller\FileIndexerController::class,
                        'action' => 'deleteIndex',
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
                // We need to set this up so that we're allowed to return JSON
                // responses from our controller.
        'strategies' => ['ViewJsonStrategy',],
    ],
];
