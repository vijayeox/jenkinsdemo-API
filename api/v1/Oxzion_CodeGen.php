<?php

use Zend\Config\Reader\Ini;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Metadata\Metadata;
use Zend\Db\Metadata\Object\ColumnObject;
use Zend\Db\Metadata\Object\ConstraintObject;
use Zend\Db\Metadata\Object\TableObject;
use Zend\Mvc\Application;

error_reporting(E_ALL & ~E_NOTICE & ~E_USER_NOTICE);

$_SERVER['REQUEST_URI'] = '/';

require 'public/index.php';

$application = Application::init(require 'config/application.config.php');

$Oxzion_CodeGen = new Oxzion_CodeGen($application, __DIR__);
$Oxzion_CodeGen->run();

class Oxzion_CodeGen
{
    /**
     * @var string
     */
    const DEFAULT_DB_ADAPTER_KEY = 'Zend\Db\Adapter\Adapter';
    /**
     *
     * @var array
     */
    protected $configs = array();
    /**
     *
     * @var Adapter
     */
    protected $dbAdapter;
    /**
     *
     * @var string
     */
    protected $moduleName;

    /**
     *
     * @var string
     */
    protected $workingDir;
    /**
     *
     * @param Application $application
     */
    public function __construct(Application $application, $workingDir){
        $this->workingDir = $workingDir;
        $dbAdapterServiceKey = self::DEFAULT_DB_ADAPTER_KEY;
        $this->dbAdapter = $application->getServiceManager()->get($dbAdapterServiceKey);
    }

    public function run(){
        $configFile = $this->workingDir . '/oxziontableconfig.ini';
        $configs = array();

        // read ini configs
        if (is_readable($configFile)) {
            $iniReader = new Ini();
            $configs = $iniReader->fromFile($configFile);
        } else {
            echo "\nNotice: $configFile does not exist\n";
        }
        echo "\n\nWARNING: please backup your existing code!!!\n\n";
        do {
            $moduleName = $this->prompt("Enter module name: ");
        } while ('' === $moduleName);
        $this->moduleName = $moduleName;
        $tableList = isset($configs['tables'])&& isset($configs['tables'][$moduleName])
        && '' !== $configs['tables'][$moduleName]? preg_split('#\s*,\s*#', $configs['tables'][$moduleName]): null;

        if (null === $tableList) {
            $shouldContinue = $this->prompt("Table list of $moduleName module is not set, "
                + "continue with ALL tables in db? y/n [y]: ");
            if ('n' === strtolower($shouldContinue)) {
                echo 'Exiting...', PHP_EOL;
            }
        }
        echo "Please wait...\n";
        $serviceConfigArray = array();
        $controllerConfigArray = array();
        $controllers = array();
        foreach ($this->getTables() as $table) {
            $tableName = $table->getName();
            // check if the table is in the list, can use in_array because
            // performance does not really matter here
            if (null !== $tableList && !in_array($tableName, $tableList)) {
                continue;
            }
            echo $tableName, "\n";
            $this->generateModel($table);
            $this->generateMapper($table);
            $controllers[] = $this->generateController($table);
            $this->generateTestController($table);
            $serviceConfigArray[] = $this->getServiceConfigCode($table,$this->moduleName);
            $controllerConfigArray[] = $this->getControllerConfig($table,$this->moduleName);
        }
        $ModuleCode = $this->getModuleCode(implode('', $serviceConfigArray),implode('', $controllerConfigArray)
    );
        $this->writeFile(sprintf('%s/module/%s/src/Module.php',$this->workingDir,$this->moduleName),$ModuleCode,false,true);
        $configCode = $this->getConfigCode($this->moduleName,$controllers);
        $this->writeFile(sprintf('%s/module/%s/config/module.config.php',$this->workingDir,$this->moduleName),$configCode,false,true);
    }
    protected function generateController(TableObject $table){
        $modelName = $this->toCamelCase($table->getName());
        $controllerCode = $this->getControllerCode($modelName);
        $this->writeFile(sprintf('%s/module/%s/src/Controller/%sController.php',$this->workingDir,$this->moduleName,$modelName),$controllerCode,false,true);
        return $modelName;
    }
    protected function generateTestController(TableObject $table){
        $modelName = $this->toCamelCase($table->getName());
        $controllerCode = $this->getTestControllerCode($modelName);
        $this->writeFile(sprintf('%s/module/%s/test/Controller/%sControllerTest.php',$this->workingDir,$this->moduleName,$modelName),$controllerCode,false,true);
    }
    /**
     * Get code of Module class for each module
     *
     * @param string $factoriesCode
     * @return string
     */
    protected function getModuleCode($serviceConfig,$controllerConfig)
    {
        return

        $moduleCode = <<<MODULE
<?php

namespace $this->moduleName;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\ConfigProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\JsonModel;
use Oxzion\Error\ErrorHandler;

class Module implements ConfigProviderInterface {

    public function getConfig() {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap(MvcEvent \$e) {
        \$eventManager = \$e->getApplication()->getEventManager();
        \$moduleRouteListener = new ModuleRouteListener();
        \$moduleRouteListener->attach(\$eventManager);

        \$eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, array(\$this, 'onDispatchError'), 0);
        \$eventManager->attach(MvcEvent::EVENT_RENDER_ERROR, array(\$this, 'onRenderError'), 0);
    }
    public function getServiceConfig()
    {
        return [
        'factories' => [
        $serviceConfig
        ],
        ];
    }
    public function getControllerConfig()
    {
        return [
        'factories' => [
        $controllerConfig
        ],
        ];
    }
    public function onDispatchError(\$e)
    {
        return ErrorHandler::getJsonModelError(\$e);
    }

    public function onRenderError(\$e)
    {
        return ErrorHandler::getJsonModelError(\$e);
    }
}
MODULE;
    }

        /**
     * Get code of Controller class for each module
     *
     * @param string $modulename
     * @return string
     */
        protected function getControllerCode($modulename)
        {
            $controllerName = $modulename.'Controller';
            $routerName = strtolower($modulename);
            $identifier = strtolower($modulename)."Id";
            $tableName = $modulename."Table";
            $route = '/'.$routerName.'[/:'.$identifier.']';
            return
            $controllerCode = <<<CONTROLLER
<?php
namespace $this->moduleName\Controller;

use Logger;
use $this->moduleName\Model\\$modulename;
use $this->moduleName\Model\\$tableName;
use Oxzion\Controller\AbstractApiController;

class $controllerName extends AbstractApiController {

    public function __construct($tableName \$table, Logger \$log){
        parent::__construct(\$table, \$log, __CLASS__, $modulename::class);
        \$this->setIdentifierName('$identifier');
    }
}
CONTROLLER;
        }

        /**
     * Get code of Test Controller class for each module
     *
     * @param string $modulename
     * @return string
     */
        protected function getTestControllerCode($modulename)
        {
            $controllerName = $modulename.'Controller';
            $controllerTestName = $modulename.'ControllerTest';
            $routerName = strtolower($modulename);
            return
            $controllerCode = <<<TESTCONTROLLER
<?php
namespace $modulename;

use $modulename\Controller\\$controllerName;
use Zend\Stdlib\ArrayUtils;
use $modulename\Model;
use Oxzion\Test\ControllerTest;

class $controllerTestName extends ControllerTest{
    public function setUp(){
        \$configOverrides = [include __DIR__ . '/../../../../config/autoload/global.php'];
        \$this->setApplicationConfig(ArrayUtils::merge(include __DIR__ . '/../../../../config/application.config.php',\$configOverrides));
        parent::setUp();
        \$this->initAuthToken('testUser');
    }
    public function testGetList(){
                    //TODO CREATE TEST CASE FOR testGetList
    }
    public function testGet(){
                    //TODO CREATE TEST CASE FOR testGet
    }
    public function testGetNotFound(){
                    //TODO CREATE TEST CASE FOR testGetNotFound
    }
    public function testCreate(){
                    //TODO CREATE TEST CASE FOR testCreate
    }
    public function testCreateFailure(){
                    //TODO CREATE TEST CASE FOR testCreateFailure
    }
    public function testUpdate(){
                    //TODO CREATE TEST CASE FOR testUpdate
    }
    public function testUpdateNotFound(){
                    //TODO CREATE TEST CASE FOR testUpdateNotFound
    }
    public function testUpdateFailure(){
                    //TODO CREATE TEST CASE FOR testUpdateFailure
    }
    public function testDelete(){
                    //TODO CREATE TEST CASE FOR testDelete
    }
    public function testDeleteNotFound(){
                    //TODO CREATE TEST CASE FOR testDeleteNotFound
    }
}
TESTCONTROLLER;
        }

        protected function getRoutes($routes){
            $routerCode = "";
            foreach ($routes as $key => $routename) {
                $id = strtolower($routename)."Id";
                $routerCode .= "'".strtolower($routename)."' => [
                'type'    => Segment::class,
                'options' => [
                'route'    => '/".strtolower($routename)."[/:".$id."]',
                'defaults' => [
                        'controller' => Controller\\".$routename."Controller::class
                    ],
                ],
            ],";
        }
        return $routerCode;
    }
/**
     * Get code of Config class for each module
     *
     * @param string $factoriesCode
     * @return string
     */
protected function getConfigCode($modulename,$routers) {
    $controllerName = $modulename.'Controller';
    $routerName = strtolower($modulename);
    $identifier = strtolower($modulename).'Id';
    $route = '/'.$routerName.'[/:'.$identifier.']';
    $logger = $this->moduleName.'Logger';
$routerCode = $this->getRoutes($routers);

    return
    $configCode = <<<CONFIG
<?php
namespace $modulename;

use Logger;
use Zend\Router\Http\Segment;

return [
    'router' => [
        'routes' => [
            $routerCode
        ],
    ],
    'view_manager' => [
    // We need to set this up so that we're allowed to return JSON
    // responses from our controller.
        'strategies' => ['ViewJsonStrategy',],
    ],
];
CONFIG;
}
    /**
     *
     * @param TableObject $table
     * @return string
     */
    protected function getServiceConfigCode(TableObject $table,$moduleName)
    {
        $tableName = $table->getName();
        $serviceName = $moduleName."Service";
        $modelName = $this->toCamelCase($tableName);
        $gateWay = ucwords($tableName).'TableGateway';
        $table = ucwords($modelName).'Table';
        return <<<CODE
        Model\\$table::class => function(\$container) {
            \$tableGateway = \$container->get(Model\\$gateWay::class);
            return new Model\\$table(\$tableGateway);
        },
        Service\\$serviceName::class => function(\$container){
            \$dbAdapter = \$container->get(AdapterInterface::class);
            return new Service\\$serviceName(\$container->get('config'), \$dbAdapter, \$container->get(Model\\$table::class));
        },
        Model\\$gateWay::class => function (\$container) {
            \$dbAdapter = \$container->get(AdapterInterface::class);
            \$resultSetPrototype = new ResultSet();
            \$resultSetPrototype->setArrayObjectPrototype(new Model\\$modelName());
            return new TableGateway('$tableName', \$dbAdapter, null, \$resultSetPrototype);
        },
CODE;
    }
    /**
     *
     * @param TableObject $table
     * @return string
     */
    protected function getControllerConfig(TableObject $table,$modulename)
    {
        $tableName = $table->getName();
        $modelName = $this->toCamelCase($tableName);
        $controller = $modelName.'Controller';
        $table = $modelName.'Table';
        $logger = $this->moduleName.'Logger';
return <<<CODE
    Controller\\$controller::class => function(\$container) {
        return new Controller\\$controller(\$container->get(Model\\$table::class),
        \$container->get('$logger'));
    },
CODE;
        }

    /**
     *
     * @param TableObject $table
     */
    protected function generateService(TableObject $table){
        $modelName = $this->toCamelCase($table->getName());
        $modelTableName = $modelName."Table";
        $code = <<<SERVICE
<?php
namespace $this->moduleName\Service;

use Oxzion\Service\AbstractService;
use $this->moduleName\Model\\$modelTableName;
use $this->moduleName\Model\\$this->moduleName;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\ValidationException;
use Exception;

class $modelTableName extends AbstractService {
     private \$table;

    public function __construct(\$config, \$dbAdapter, $modelTableName \$table){
        parent::__construct(\$config, \$dbAdapter);
        \$this->table = \$table;
    }
}
SERVICE;
        $filename = sprintf(
            '%s/module/%s/src/Service/%s.php',
            $this->workingDir,
            $this->moduleName,
            $modelTableName
        );
        $this->writeFile($filename, $code, false, true);
    }
    /**
     *
     * @param TableObject $table
     */
    protected function generateMapper(TableObject $table){
        $modelName = $this->toCamelCase($table->getName());
        $primaryKey = array();
        $indexes = array();
        $mappingCode = '';
        $indexCode = '';
        foreach ($table->getConstraints() as $constraint){
            /* @var $constraint ConstraintObject */

            $constraintType = $constraint->getType();
            if ('PRIMARY KEY' === $constraintType) {
                $primaryKey = $constraint->getColumns();
            }

            $indexes[] = $constraint->getColumns();
        }

        if (isset($indexes[0])) {
            $indexCodeArray = array();

            foreach ($indexes as $index)
            {
                $singleIndexCode = $this->getMethodsOfIndex($index, $modelName);
                if (!is_string($singleIndexCode)) {
                    continue;
                }
                $indexCodeArray[] = $singleIndexCode;
            }
            $indexCode = implode('', $indexCodeArray);
        }
        if (count($primaryKey) === 1) {
            $mappingCode = $this->getPrimaryKeyCode($primaryKey, $modelName);
        }
        $modelTableName = $modelName."Table";
        $code = <<<TABLE
<?php
namespace $this->moduleName\Model;

use Oxzion\Db\ModelTable;
use Zend\Db\TableGateway\TableGatewayInterface;
use Oxzion\Model\Model;

class $modelTableName extends ModelTable {
    public function __construct(TableGatewayInterface \$tableGateway) {
        parent::__construct(\$tableGateway);
    }
    public function save(Model \$data){
        return \$this->internalSave(\$data->toArray());
    }
}
TABLE;
        $filename = sprintf(
            '%s/module/%s/src/Model/%s.php',
            $this->workingDir,
            $this->moduleName,
            $modelTableName
        );

        $this->writeFile($filename, $code, false, true);
    }
 /**
     *
     * @param TableObject $table
     */
 protected function generateFactories(array $tableList)
 {

    foreach ($tableList as $table) {
        $tableName = str_replace($table, "Table", "");
        $code.= "\nModel\\".$table."::class => function(\$container) {
            \$tableGateway = \$container->get(Model\\".$table."Gateway::class);
            return new Model\\".$table."(\$tableGateway);
            },
            Model\\".$table."Gateway::class => function (\$container) {
                \$dbAdapter = \$container->get(AdapterInterface::class);
                \$resultSetPrototype = new ResultSet();
                \$resultSetPrototype->setArrayObjectPrototype(new Model\\".$tableName."());
                return new TableGateway('".strtolower($tableName)."', \$dbAdapter, null, \$resultSetPrototype);
            },";
        }
        return $code;
    }

    /**
     *
     * @param array $primaryKey
     * @param string $modelName
     * @return string
     */
    protected function getPrimaryKeyCode($primaryKey, $modelName)
    {
        $primaryKeyCamelCase = $this->toCamelCase($primaryKey[0]);

        return <<<CODE
    /**
     * @param int \$id
     * @return {$modelName}Model
     */
        public function get{$modelName}Model(\$id)
        {
            return \$this->tableGateway->select(array('$primaryKey[0]' => \$id))->current();
        }

        /**
     * @param {$modelName}Model \$model
     */
        public function save{$modelName}Model({$modelName}Model \$model){
            \$id = \$model->get$primaryKeyCamelCase();

            if (!\$id) {
                \$this->tableGateway->insert(\$model->toArray());
                } else {
                    \$this->tableGateway->update(\$model->toArray(), array('$primaryKey[0]' => \$id));
                }
            }

            /**
            *
            * @param {$modelName}Model|int \$model
            */
            public function delete{$modelName}Model(\$model)
            {
                if (\$model instanceof {$modelName}Model) {
                    \$id = \$model->get$primaryKeyCamelCase();
                    } else {
                        \$id = \$model;
                    }

                    \$this->tableGateway->delete(array('$primaryKey[0]' => \$id));
                }
CODE;
            }

    /**
     *
     * @param type $index
     * @param string $modelName
     * @return string|boolean
     */
    protected function getMethodsOfIndex($index, $modelName)
    {
        $camelCaseColumns = $index;
        $functionNames = array();

        foreach ($camelCaseColumns as &$camelCaseColumn)
        {
            $camelCaseColumn = $this->toCamelCase($camelCaseColumn);
        }

        $vars = array();

        foreach ($camelCaseColumns as $var)
        {
            $var[0] = strtolower($var[0]);
            $vars[] = $var;
        }

        $functionNameResultSet = "get{$modelName}ModelSetBy" . implode('And', $camelCaseColumns);
        $functionNameResult = "get{$modelName}ModelBy" . implode('And', $camelCaseColumns);

        if (isset($functionNames[$functionNameResult]) || isset($functionNames[$functionNameResultSet])) {
            return false;
        }

        $functionNames[$functionNameResult] = 1;
        $functionNames[$functionNameResultSet] = 1;

        $argListArray = array();
        $varCommentsArray = array();

        foreach ($vars as $var)
        {
            $argListArray[] = '$' . $var;
            $varCommentsArray[] = "     * @param mixed $$var";
        }
        $argList = implode(', ', $argListArray);
        $varComments = implode("\n", $varCommentsArray);

        $whereArray = array();

        foreach ($index as $offset => $indexColumn)
        {
            $whereArray[] = "'$indexColumn' => $$vars[$offset]";
        }

        $where = implode(",\n            ", $whereArray);

        return <<<CODE
    /**
     *
        $varComments
     * @return {$modelName}Model
     */
        public function $functionNameResult($argList){
            return \$this->tableGateway->select(array($where))->current();
        }


    /**
     *
        $varComments
     * @return ResultSet
     */
        public function $functionNameResultSet($argList) {
            return \$this->tableGateway->select(array($where));
        }
CODE;
    }

    protected function generateModel(TableObject $table)
    {
        $modelName = $this->toCamelCase($table->getName());

        $fieldsCode = array();
        $getterSetters = array();

        foreach ($table->getColumns() as $column)
        {
            /* @var $column ColumnObject */
            $fieldName = $column->getName();
            $fieldNameCamelCase = $varName = $this->toCamelCase($fieldName);
            $varName[0] = strtolower($varName[0]);
            $defaultValue = var_export($column->getColumnDefault(), true);

            $getterSetters[] = "
            public function get$fieldNameCamelCase() {
                return \$this->data['$fieldName'];
            }

            public function set$fieldNameCamelCase(\$$varName) {
                \$this->data['$fieldName'] = \$$varName;
            }";

            $fieldsCode[] = "
            '$fieldName' => $defaultValue,";
        }

        $fieldsCode = "array(" . implode('', $fieldsCode) . '
    )';
    $getterSettersCode = implode('', $getterSetters);

    $code = <<<MODEL
<?php

namespace $this->moduleName\Model;

class {$modelName}{

    protected \$data = $fieldsCode;
    $getterSettersCode

    public function exchangeArray(\$data) {
        foreach (\$data as \$key => \$value)
        {
            if (!array_key_exists(\$key, \$this->data)) {
                continue;//throw new \Exception("\$key field does not exist in " . __CLASS__);
            }
            \$this->data[\$key] = \$value;
        }
    }

    public function toArray() {
        return \$this->data;
    }
}
MODEL;
    $this->writeFile(sprintf('%s/module/%s/src/Model/%s.php',$this->workingDir,$this->moduleName,$modelName),$code,false,true);
}

    /**
     *
     * @return TableObject[]
     */
    protected function getTables(){
        $metadata = new Metadata($this->dbAdapter);
        return $metadata->getTables();
    }

    /**
     *
     * @param string $filename
     * @param string $contents
     * @param boolean $generatePatchIfExists
     * @param boolean $overwrite
     */
    protected function writeFile($filename, $contents, $generatePatchIfExists = true, $overwrite = false){
        $dir = dirname($filename);

        if (!file_exists($dir)) {
            mkdir($dir, 0777, true);
        }

        if ($overwrite) {
            file_put_contents($filename, $contents);
        } elseif ("service.config.zk" !== substr($filename, -17) && file_exists($filename)) {
            $oxzion_CodeGenFile = $filename . '.zk';
            file_put_contents($oxzion_CodeGenFile, $contents);

            if ($generatePatchIfExists && 'Linux' === PHP_OS) {
                `diff -u $filename $oxzion_CodeGenFile > $filename.patch`;
            }
        } else {
            file_put_contents($filename, $contents);
        }
    }

    /**
     *
     * @param string $name
     * @return string
     */
    protected function toCamelCase($name)
    {
        return implode('', array_map('ucfirst', explode('_', $name)));
    }

    /**
     *
     * @param string $message
     * @return string
     */
    protected function prompt($message){
        echo $message;
        return trim(fgets(STDIN));
    }

}

