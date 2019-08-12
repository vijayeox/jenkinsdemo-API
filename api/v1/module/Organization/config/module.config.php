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
                    'route' => '/organization/:orgId/save',
                    'defaults' => [
                        'controller' => Controller\OrganizationController::class,
                        'method' => 'POST',
                        'action' => 'addUserToOrganization',
                        'access' => [
                            // SET ACCESS CONTROL
                            'addUserToOrganization' => 'MANAGE_ORGANIZATION_WRITE',
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
            'organizationuser' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/organization/:orgId/users',
                    'defaults' => [
                        'controller' => Controller\OrganizationController::class,
                        'method' => 'GET',
                        'action' => 'getListOfOrgUsers',
                        'access' => [
                            'getListOfOrgUsers'=> ['MANAGE_ORGANIZATION_READ','MANAGE_GROUP_READ']
                        ],
                    ],
                ],
            ],
            'getListofAdminUsers' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/organization[/:orgId]/adminusers',
                    'defaults' => [
                        'controller' => Controller\OrganizationController::class,
                        'method' => 'GET',
                        'action' => 'getListofAdminUsers',
                        'access' => [
                            'getListofAdminUsers'=> ['MANAGE_ORGANIZATION_READ', 'MANAGE_MYORG_WRITE']
                        ],
                    ],
                ],
            ],
            'getListofOrgGroups' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/organization/:orgId/groups',
                    'defaults' => [
                        'controller' => Controller\OrganizationController::class,
                        'method' => 'GET',
                        'action' => 'getListofOrgGroups',
                        'access' => [
                            'getListofOrgGroups'=> ['MANAGE_GROUP_READ']
                        ],
                    ],
                ],
            ],
            'getListofOrgProjects' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/organization/:orgId/projects',
                    'defaults' => [
                        'controller' => Controller\OrganizationController::class,
                        'method' => 'GET',
                        'action' => 'getListofOrgProjects',
                        'access' => [
                            'getListofOrgProjects'=> ['MANAGE_PROJECT_READ']
                        ],
                    ],
                ],
            ],
            'getListofOrgAnnouncements' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/organization/:orgId/announcements',
                    'defaults' => [
                        'controller' => Controller\OrganizationController::class,
                        'method' => 'GET',
                        'action' => 'getListofOrgAnnouncements',
                        'access' => [
                            'getListofOrgAnnouncements'=> ['MANAGE_ANNOUNCEMENT_READ']
                        ],
                    ],
                ],
            ],
            'getListofOrgRoles' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/organization/:orgId/roles',
                    'defaults' => [
                        'controller' => Controller\OrganizationController::class,
                        'method' => 'GET',
                        'action' => 'getListofOrgRoles',
                        'access' => [
                            'getListofOrgRoles'=> ['MANAGE_ROLE_READ']
                        ],
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