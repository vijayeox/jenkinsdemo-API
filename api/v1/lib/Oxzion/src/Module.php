<?php

namespace Oxzion;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module {

    public function getServiceConfig(){
        return [
            'factories' => [
                Auth\AuthContext::class => function($container) {
                    return new Auth\AuthContext();
                },
                Auth\AuthSuccessListener::class => function($container){
                    return new Auth\AuthSuccessListener($container->get(Service\UserService::class));
                },
                Service\UserService::class => function($container) {
                    $config = $container->get('config');
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new Service\UserService($config, $dbAdapter, $container->get(Model\UserTable::class));
                },
                Service\ElasticService::class => function($container) {
                    $config = $container->get('config');
                    return new Service\ElasticService($config);
                },
                \Bos\Service\FileService::class => function($container){
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new \Bos\Service\FileService($container->get('config'), $dbAdapter, $container->get(\Bos\Model\FileTable::class));
                },
                \Bos\Service\CommentService::class => function($container){
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new \Bos\Service\CommentService($container->get('config'), $dbAdapter, $container->get(\Bos\Model\CommentTable::class));
                },
                \Bos\Model\FileTable::class => function($container) {
                    $tableGateway = $container->get(\Bos\Model\FileTableGateway::class);
                    return new \Bos\Model\FileTable($tableGateway);
                },
                \Bos\Model\CommentTable::class => function($container) {
                    $tableGateway = $container->get(\Bos\Model\CommentTableGateway::class);
                    return new \Bos\Model\CommentTable($tableGateway);
                },
                \Bos\Model\FileTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new \Bos\Model\File());
                    return new TableGateway('ox_file', $dbAdapter, null, $resultSetPrototype);
                },
                \Bos\Model\CommentTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new \Bos\Model\Comment());
                    return new TableGateway('ox_comment', $dbAdapter, null, $resultSetPrototype);
                },
                Service\FormService::class => function($container){
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new Service\FormService($container->get('config'), $dbAdapter, $container->get(Model\FormTable::class));
                },
                Service\FieldService::class => function($container){
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new Service\FieldService($container->get('config'), $dbAdapter, $container->get(Model\FieldTable::class));
                },
                Model\FormTable::class => function($container) {
                    $tableGateway = $container->get(Model\FormTableGateway::class);
                    return new Model\FormTable($tableGateway);
                },
                Model\FormTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Form());
                    return new TableGateway('ox_form', $dbAdapter, null, $resultSetPrototype);
                },
                Model\FieldTable::class => function($container) {
                    $tableGateway = $container->get(Model\FieldTableGateway::class);
                    return new Model\FieldTable($tableGateway);
                },
                Model\FieldTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Field());
                    return new TableGateway('ox_field', $dbAdapter, null, $resultSetPrototype);
                },
                Service\OrganizationService::class => function($container){
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new Service\OrganizationService($container->get('config'), $dbAdapter, $container->get(Model\OrganizationTable::class));
                },
                Model\OrganizationTable::class => function($container) {
                    $tableGateway = $container->get(Model\OrganizationTableGateway::class);
                    return new Model\OrganizationTable($tableGateway);
                },
                Model\OrganizationTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Organization());
                    return new TableGateway('ox_organization', $dbAdapter, null, $resultSetPrototype);
                },
                Model\UserTable::class => function($container) {
                    $tableGateway = $container->get(Model\UserTableGateway::class);
                    return new Model\UserTable($tableGateway);
                },
                Model\UserTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\User());
                    return new TableGateway('avatars', $dbAdapter, null, $resultSetPrototype);
                },
                Workflow\WorkflowFactory::class => function ($container){
                    return Workflow\WorkflowFactory::getInstance(); 
                }
            ],
        ];
    }
    /**
     * Retrieve default zend-db configuration for zend-mvc context.
     *
     * @return array
     */
    public function getConfig()
    {
        return [
        ];
    }

}