<?php
namespace Organization;

use Zend\Log\Logger;
use Zend\Router\Http\Segment;
use Zend\Log\Formatter\Simple;
use Zend\Log\Filter\Priority;
use Zend\Log\Processor\RequestId;

return [
    'router' => [
        'routes' => [
            'organization' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/organization[/:orgId]',
                    'defaults' => [
                        'controller' => Controller\OrganizationController::class,
                        'access'=>[
                            // SET ACCESS CONTROL
                            'put'=> 'MANAGE_ORGANIZATION_WRITE',
                            'post'=> 'MANAGE_ORGANIZATION_WRITE',
                            'delete'=> 'MANAGE_ORGANIZATION_WRITE',
                            'get'=> 'MANAGE_ORGANIZATION_READ',
                        ],
                    ],
                ],
            ],
            'addUserToOrganization' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/organization/:orgId/users/save',
                    'defaults' => [
                        'controller' => Controller\OrganizationController::class,
                        'method' => 'POST',
                        'action' => 'addUserToOrganization',
                        'access' => [
                            // SET ACCESS CONTROL
                            'addUserToOrganization' => 'MANAGE_USER_WRITE',
                        ],
                    ],
                ],
            ],
            'organizationLogo' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/organization/logo/:orgId',
                    'defaults' => [
                        'controller' => Controller\OrganizationLogoController::class
                    ],
                ],
            ],
        ],
    ],
    'log' => [
        'OrganizationLogger' => [
            'writers' => [
                'stream' => [
                    'name' => 'stream',
                    'priority' => \Zend\Log\Logger::ALERT,
                    'options' => [
                        'stream' => __DIR__ . '/../../../logs/organization.log',
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