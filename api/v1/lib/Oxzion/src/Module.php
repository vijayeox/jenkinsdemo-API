<?php

namespace Oxzion;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\ModuleEvent;
use Zend\ModuleManager\ModuleManager;
use Logger;

class Module
{
    private static $logInitialized = false;
    public function init(ModuleManager $moduleManager)
    {
        $events = $moduleManager->getEventManager();
        // Registering a listener at default priority, 1, which will trigger
        // after the ConfigListener merges config.
        $events->attach(ModuleEvent::EVENT_MERGE_CONFIG, array($this, 'onMergeConfig'));
    }

    public function onMergeConfig(ModuleEvent $e)
    {
        $configListener = $e->getConfigListener();
        $config         = $configListener->getMergedConfig(false);
        if(!self::$logInitialized){
            self::$logInitialized = true;
            Logger::configure($config['logger']);
        }
    }
    public function getServiceConfig()
    {
        
        return [
            'factories' => [
                Auth\AuthContext::class => function ($container) {
                    return new Auth\AuthContext();
                },
                Auth\AuthSuccessListener::class => function ($container) {
                    return new Auth\AuthSuccessListener($container->get(Service\UserService::class));
                },
                Service\UserService::class => function ($container) {
                    return new Service\UserService(
                        $container->get('config'),
                        $container->get(AdapterInterface::class),
                        $container->get(Model\UserTable::class),
                        $container->get(Service\AddressService::class),
                        $container->get(Service\EmailService::class),
                        $container->get(Service\TemplateService::class),
                        $container->get(Messaging\MessageProducer::class)
                    );
                },
                Model\UserTable::class => function ($container) {
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
                Service\ElasticService::class => function ($container) {
                    $config = $container->get('config');
                    return new Service\ElasticService($config);
                },
                \Oxzion\Service\FileService::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new \Oxzion\Service\FileService($container->get('config'), $dbAdapter, $container->get(\Oxzion\Model\FileTable::class), $container->get(\Oxzion\Service\FormService::class), $container->get(Messaging\MessageProducer::class),$container->get(\Oxzion\Service\FieldService::class));
                },
                Service\RoleService::class => function ($container) {
                    return new Service\RoleService(
                        $container->get('config'),
                        $container->get(AdapterInterface::class),
                        $container->get(Model\RoleTable::class),
                        $container->get(Model\PrivilegeTable::class)
                    );
                },
                Model\RoleTable::class => function ($container) {
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
                        $container->get(Model\PrivilegeTable::class)
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
                \Oxzion\Service\CommentService::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new \Oxzion\Service\CommentService($container->get('config'), $dbAdapter, $container->get(\Oxzion\Model\CommentTable::class));
                },
                \Oxzion\Service\SubscriberService::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new \Oxzion\Service\SubscriberService($container->get('config'), $dbAdapter, $container->get(\Oxzion\Model\SubscriberTable::class));
                },
                \Oxzion\Model\FileTable::class => function ($container) {
                    $tableGateway = $container->get(\Oxzion\Model\FileTableGateway::class);
                    return new \Oxzion\Model\FileTable($tableGateway);
                },
                \Oxzion\Model\CommentTable::class => function ($container) {
                    $tableGateway = $container->get(\Oxzion\Model\CommentTableGateway::class);
                    return new \Oxzion\Model\CommentTable($tableGateway);
                },
                \Oxzion\Model\SubscriberTable::class => function ($container) {
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
                Service\FormService::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new Service\FormService($container->get('config'), $dbAdapter, 
                                                    $container->get(Model\FormTable::class), 
                                                    $container->get(FormEngine\FormFactory::class), 
                                                    $container->get(Service\FieldService::class));
                },
                Service\ActivityService::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new Service\ActivityService($container->get('config'), $dbAdapter, $container->get(Model\ActivityTable::class),$container->get(Service\FormService::class));
                },
                Service\FieldService::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new Service\FieldService($container->get('config'), $dbAdapter, $container->get(Model\FieldTable::class));
                },
                Model\FormTable::class => function ($container) {
                    $tableGateway = $container->get(Model\FormTableGateway::class);
                    return new Model\FormTable($tableGateway);
                },
                Model\FormTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Form());
                    return new TableGateway('ox_form', $dbAdapter, null, $resultSetPrototype);
                },
                Model\ActivityTable::class => function ($container) {
                    $tableGateway = $container->get(Model\ActivityTableGateway::class);
                    return new Model\ActivityTable($tableGateway);
                },
                Model\ActivityTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Activity());
                    return new TableGateway('ox_activity', $dbAdapter, null, $resultSetPrototype);
                },
                Model\FieldTable::class => function ($container) {
                    $tableGateway = $container->get(Model\FieldTableGateway::class);
                    return new Model\FieldTable($tableGateway);
                },
                Model\FieldTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Field());
                    return new TableGateway('ox_field', $dbAdapter, null, $resultSetPrototype);
                },
                Service\OrganizationService::class => function ($container) {
                    return new Service\OrganizationService(
                        $container->get('config'),
                        $container->get(AdapterInterface::class),
                        $container->get(Model\OrganizationTable::class),
                        $container->get(Service\UserService::class),
                        $container->get(Service\AddressService::class),
                        $container->get(Service\RoleService::class),
                        $container->get(Service\PrivilegeService::class),
                        $container->get(Messaging\MessageProducer::class)
                    );
                },
                Model\OrganizationTable::class => function ($container) {
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
                Service\AddressService::class => function ($container) {
                    return new Service\AddressService(
                        $container->get('config'),
                        $container->get(AdapterInterface::class),
                        $container->get(Model\AddressTable::class)
                    );
                },
                Model\AddressTable::class => function ($container) {
                    return new Model\AddressTable(
                        $container->get(Model\AddressTableGateway::class)
                    );
                },
                Model\AddressTableGateway::class => function ($container) {
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Address());
                    return new TableGateway(
                        'ox_address',
                        $container->get(AdapterInterface::class),
                        null,
                        $resultSetPrototype
                    );
                },
                Workflow\WorkflowFactory::class => function ($container) {
                    return Workflow\WorkflowFactory::getInstance($container->get('config'));
                },
                FormEngine\FormFactory::class => function ($container) {
                    return FormEngine\FormFactory::getInstance();
                },
                Model\WorkflowTable::class => function ($container) {
                    $tableGateway = $container->get(Model\WorkflowTableGateway::class);
                    return new Model\WorkflowTable($tableGateway);
                },
                Model\WorkflowTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Workflow());
                    return new TableGateway('ox_workflow', $dbAdapter, null, $resultSetPrototype);
                },
                Model\WorkflowDeploymentTable::class => function ($container) {
                    $tableGateway = $container->get(Model\WorkflowDeploymentTableGateway::class);
                    return new Model\WorkflowDeploymentTable($tableGateway);
                },
                Model\WorkflowDeploymentTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\WorkflowDeployment());
                    return new TableGateway('ox_workflow_deployment', $dbAdapter, null, $resultSetPrototype);
                },
                Service\WorkflowService::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new Service\WorkflowService(
                        $container->get('config'),
                        $dbAdapter,
                        $container->get(Model\WorkflowTable::class),
                        $container->get(Service\FormService::class),
                        $container->get(Service\FieldService::class),
                        $container->get(\Oxzion\Service\FileService::class),
                        $container->get(Workflow\WorkflowFactory::class),
                        $container->get(Service\ActivityService::class),
                        $container->get(Model\WorkflowDeploymentTable::class)
                    );
                },
                Service\UserTokenService::class => function ($container) {
                    $config = $container->get('config');
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new Service\UserTokenService($config, $dbAdapter, $container->get(Model\UserTokenTable::class));
                },
                Model\UserTokenTable::class => function ($container) {
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
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new Analytics\Elastic\AnalyticsEngineImpl($config,$dbAdapter,$config);
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
                Service\ProfilePictureService::class => function ($container) {
                    $config = $container->get('config');
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new Service\ProfilePictureService($config, $dbAdapter);
                },
                Service\UserSessionService::class => function ($container) {
                    $config = $container->get('config');
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new Service\UserSessionService($config, $dbAdapter);
                },
                Service\TemplateService::class => function ($container) {
                    return new Service\TemplateService(
                        $container->get('config'),
                        $container->get(AdapterInterface::class)
                    );
                },
                AppDelegate\AppDelegateService::class => function ($container) {

                    return new AppDelegate\AppDelegateService(
                        $container->get('config'),
                        $container->get(AdapterInterface::class),
                        $container->get(Document\DocumentBuilder::class),
                        $container->get(Service\TemplateService::class),
                        $container->get(Messaging\MessageProducer::class),
                        $container->get(Service\FileService::class),
                        $container->get(Service\WorkflowInstanceService::class),
                        $container->get(Service\ActivityInstanceService::class)
                    );
                },
                Document\DocumentBuilder::class => function ($container) {

                    return new Document\DocumentBuilder(
                        $container->get('config'),
                        $container->get(Service\TemplateService::class),
                        new Document\DocumentGeneratorImpl()
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
                Service\UserCacheService::class => function ($container) {
                    return new Service\UserCacheService(
                        $container->get('config'),
                        $container->get(AdapterInterface::class),
                        $container->get(Model\UserCacheTable::class)
                    );
                },
                Model\UserCacheTable::class => function ($container) {
                    return new Model\UserCacheTable(
                        $container->get(Model\UserCacheTableGateway::class)
                    );
                },
                Model\UserCacheTableGateway::class => function ($container) {
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\UserCache());
                    return new TableGateway(
                        'ox_user_cache',
                        $container->get(AdapterInterface::class),
                        null,
                        $resultSetPrototype
                    );
                },
                Model\ErrorLogTable::class => function ($container) {
                    $tableGateway = $container->get(Model\ErrorLogTableGateway::class);
                    return new Model\ErrorLogTable($tableGateway);
                },
                Model\ErrorLogTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\ErrorLog());
                    return new TableGateway('ox_error_log', $dbAdapter, null, $resultSetPrototype);
                },
                Messaging\MessageProducer::class => function ($container) {
                    $config = $container->get('config');
                    return new Messaging\MessageProducer($config,$container->get(Service\ErrorLogService::class));
                },
                Service\ErrorLogService::class => function ($container) {
                    return new Service\ErrorLogService(
                        $container->get('config'),
                        $container->get(AdapterInterface::class),
                        $container->get(Model\ErrorLogTable::class),
                        $container->get(Service\UserCacheService::class),
                        $container->get(Workflow\WorkflowFactory::class)
                    );
                },
                Model\WorkflowInstanceTable::class => function ($container) {
                    $tableGateway = $container->get(Model\WorkflowInstanceTableGateway::class);
                    return new Model\WorkflowInstanceTable($tableGateway);
                },
                Model\ActivityInstanceTable::class => function ($container) {
                    $tableGateway = $container->get(Model\ActivityInstanceTableGateway::class);
                    return new Model\ActivityInstanceTable($tableGateway);
                },
                Model\WorkflowInstanceTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\WorkflowInstance());
                    return new TableGateway('ox_workflow_instance', $dbAdapter, null, $resultSetPrototype);
                },
                Model\ActivityInstanceTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\ActivityInstance());
                    return new TableGateway('ox_activity_instance', $dbAdapter, null, $resultSetPrototype);
                },
                Service\WorkflowInstanceService::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new Service\WorkflowInstanceService(
                        $container->get('config'),
                        $dbAdapter,
                        $container->get(Model\WorkflowInstanceTable::class),
                        $container->get(Service\FileService::class),
                        $container->get(Service\UserService::class),
                        $container->get(Service\WorkflowService::class),
                        $container->get(Workflow\WorkflowFactory::class),
                        $container->get(Service\ActivityInstanceService::class)
                    );
                },
                Service\ActivityInstanceService::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new Service\ActivityInstanceService(
                        $container->get('config'),
                        $dbAdapter,
                        $container->get(Model\ActivityInstanceTable::class),
                        $container->get(Workflow\WorkflowFactory::class),
                        $container->get(Service\WorkflowService::class)
                    );
                },
                 Model\JobTable::class => function ($container) {
                    $tableGateway = $container->get(Model\JobTableGateway::class);
                    return new Model\JobTable($tableGateway);
                },
                Model\JobTableGateway::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Model\Job());
                    return new TableGateway('ox_job', $dbAdapter, null, $resultSetPrototype);
                },
                Service\JobService::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new Service\JobService(
                        $container->get('config'),
                        $dbAdapter,
                        $container->get(Messaging\MessageProducer::class),
                        $container->get(Model\JobTable::class)
                    );
                },
                Service\CommandService::class => function ($container) {
                    $dbAdapter = $container->get(AdapterInterface::class);
                    return new Service\CommandService($container->get('config'),
                        $dbAdapter,
                        $container->get(Service\TemplateService::class),
                        $container->get(AppDelegate\AppDelegateService::class),
                        $container->get(Service\FileService::class),
                        $container->get(Service\JobService::class),
                        $container->get(Messaging\MessageProducer::class),
                        $container->get(Service\WorkflowInstanceService::class),
                        $container->get(Service\WorkflowService::class),
                        $container->get(Service\UserService::class));
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
