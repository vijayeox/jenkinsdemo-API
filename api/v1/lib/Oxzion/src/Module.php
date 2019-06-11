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
                    return new Service\UserService(
                        $container->get('config'),
                        $container->get(AdapterInterface::class),
                        $container->get(Model\UserTable::class),
                        $container->get(Service\EmailService::class),
                        $container->get(Service\EmailTemplateService::class)
                    );
                },
                Model\UserTable::class => function($container) {
                    return new Model\UserTable(
                        $container->get(Model\UserTableGateway::class)
                    );
                },
                Model\UserTableGateway::class => function ($container) {
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\User());
                    return new TableGateway(
                        'ox_user',
                        $container->get(AdapterInterface::class),
                        null,
                        $resultSetPrototype
                    );
                },
                Service\ElasticService::class => function($container) {
                    $config = $container->get('config');
                    return new Service\ElasticService($config);
                },
                \Oxzion\Service\FileService::class => function($container){
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new \Oxzion\Service\FileService($container->get('config'), $dbAdapter, $container->get(\Oxzion\Model\FileTable::class));
                },
                Service\RoleService::class => function($container){
                    return new Service\RoleService(
                        $container->get('config'),
                        $container->get(AdapterInterface::class),
                        $container->get(Model\RoleTable::class),
                        $container->get(Model\PrivilegeTable::class)
                    );
                },
                Model\RoleTable::class => function($container) {
                    return new Model\RoleTable(
                        $container->get(Model\RoleTableGateway::class)
                    );
                },
                Model\RoleTableGateway::class => function ($container) {
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Role());
                    return new TableGateway(
                        'ox_role',
                        $container->get(AdapterInterface::class),
                        null,
                        $resultSetPrototype
                    );
                },
                Service\PrivilegeService::class => function ($container) {
                    return new Service\PrivilegeService(
                        $container->get('config'),
                        $container->get(AdapterInterface::class),
                        $container->get(Model\PrivilegeTable::class),
                        $container->get(Service\RoleService::class)
                    );
                },
                Model\PrivilegeTable::class => function ($container) {
                    return new Model\PrivilegeTable(
                        $container->get(Model\PrivilegeTableGateway::class)
                    );
                },
                Model\PrivilegeTableGateway::class => function ($container) {
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Privilege());
                    return new TableGateway(
                        'ox_privilege',
                        $container->get(AdapterInterface::class),
                        null,
                        $resultSetPrototype
                    );
                },
                \Oxzion\Service\CommentService::class => function($container){
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new \Oxzion\Service\CommentService($container->get('config'), $dbAdapter, $container->get(\Oxzion\Model\CommentTable::class));
                },
                \Oxzion\Service\SubscriberService::class => function($container){
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new \Oxzion\Service\SubscriberService($container->get('config'), $dbAdapter, $container->get(\Oxzion\Model\SubscriberTable::class));
                },
                \Oxzion\Model\FileTable::class => function($container) {
                    $tableGateway = $container->get(\Oxzion\Model\FileTableGateway::class);
                    return new \Oxzion\Model\FileTable($tableGateway);
                },
                \Oxzion\Model\CommentTable::class => function($container) {
                    $tableGateway = $container->get(\Oxzion\Model\CommentTableGateway::class);
                    return new \Oxzion\Model\CommentTable($tableGateway);
                },
                \Oxzion\Model\SubscriberTable::class => function($container) {
                    $tableGateway = $container->get(\Oxzion\Model\SubscriberTableGateway::class);
                    return new \Oxzion\Model\SubscriberTable($tableGateway);
                },
                \Oxzion\Model\FileTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new \Oxzion\Model\File());
                    return new TableGateway('ox_file', $dbAdapter, null, $resultSetPrototype);
                },
                \Oxzion\Model\CommentTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new \Oxzion\Model\Comment());
                    return new TableGateway('ox_comment', $dbAdapter, null, $resultSetPrototype);
                },
                \Oxzion\Model\SubscriberTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new \Oxzion\Model\Subscriber());
                    return new TableGateway('ox_subscriber', $dbAdapter, null, $resultSetPrototype);
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
                    return new Service\OrganizationService(
                        $container->get('config'),
                        $container->get(AdapterInterface::class),
                        $container->get(Model\OrganizationTable::class),
                        $container->get(Service\UserService::class),
                        $container->get(Service\RoleService::class),
                        $container->get(Service\PrivilegeService::class)
                    );
                },
                Model\OrganizationTable::class => function($container) {
                    return new Model\OrganizationTable(
                        $container->get(Model\OrganizationTableGateway::class)
                    );
                },
                Model\OrganizationTableGateway::class => function ($container) {
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Organization());
                    return new TableGateway(
                        'ox_organization',
                        $container->get(AdapterInterface::class),
                        null,
                        $resultSetPrototype
                    );
                },
                Workflow\WorkflowFactory::class => function ($container){
                    return Workflow\WorkflowFactory::getInstance();
                },
                Model\WorkflowTable::class => function($container) {
                    $tableGateway = $container->get(Model\WorkflowTableGateway::class);
                    return new Model\WorkflowTable($tableGateway);
                },
                Model\WorkflowTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Organization());
                    return new TableGateway('ox_workflow', $dbAdapter, null, $resultSetPrototype);
                },
                Service\WorkflowService::class => function($container){
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new Service\WorkflowService($container->get('config'), 
                    $dbAdapter,
                    $container->get(Model\WorkflowTable::class),
                    $container->get(Service\FormService::class),
                    $container->get(Service\FieldService::class),
                    $container->get(\Oxzion\Service\FileService::class),
                    $container->get(Workflow\WorkflowFactory::class));
                },
                Service\UserTokenService::class => function($container) {
                    $config = $container->get('config');
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new Service\UserTokenService($config, $dbAdapter, $container->get(Model\UserTokenTable::class));
                },
                Model\UserTokenTable::class => function($container) {
                    $tableGateway = $container->get(Model\UserTokenTableGateway::class);
                    return new Model\UserTokenTable($tableGateway);
                },
                Model\UserTokenTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\UserToken());
                    return new TableGateway('ox_user_refresh_token', $dbAdapter, null, $resultSetPrototype);
                },
                Analytics\AnalyticsEngine::class => function ($container) {
                    $config = $container->get('config');
                    return new Analytics\Elastic\AnalyticsEngineImpl($config);
                },
                Search\SearchEngine::class => function ($container) {
                    $config = $container->get('config');
                    return new Search\Elastic\SearchEngineImpl($config);
                },
                ProspectResearch\InfoEngine::class => function ($container) {
                    $config = $container->get('config');
                    return new ProspectResearch\Discovery\InfoEngineImpl($config);
                },
                Search\Indexer::class => function ($container) {
                    $config = $container->get('config');
                    return new Search\Elastic\IndexerImpl($config);
                },
                Service\ProfilePictureService::class => function($container) {
                    $config = $container->get('config');
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new Service\ProfilePictureService($config, $dbAdapter);
                },
                Service\UserSessionService::class => function($container) {
                    $config = $container->get('config');
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new Service\UserSessionService($config, $dbAdapter);
                },
                Service\EmailTemplateService::class => function ($container) {
                    return new Service\EmailTemplateService(
                        $container->get('config'),
                        $container->get(AdapterInterface::class)
                    );
                },
                Service\EmailService::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new Service\EmailService($container->get('config'), $dbAdapter, $container->get(Model\EmailTable::class));
                },
                Model\EmailTable::class => function ($container) {
                    $tableGateway = $container->get(Model\EmailTableGateway::class);
                    return new Model\EmailTable($tableGateway);
                },
                Model\EmailTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Email());
                    return new TableGateway('email_setting_user', $dbAdapter, null, $resultSetPrototype);
                },
                NLP\NLPEngine::class => function ($container) {
                    return new NLP\Dialogflow\NLPDialogflowV1();
                },
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