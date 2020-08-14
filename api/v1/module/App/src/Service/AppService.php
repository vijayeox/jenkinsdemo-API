<?php
namespace App\Service;

use App\Model\App;
use App\Model\AppTable;
use Exception;
use FileSystemIterator;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\Db\Migration\Migration;
use Oxzion\ServiceException;
use Oxzion\Service\AbstractService;
use Oxzion\Service\EntityService;
use Oxzion\Service\JobService;
use Oxzion\Service\FieldService;
use Oxzion\Service\FormService;
use Oxzion\Service\WorkflowService;
use Oxzion\Utils\ExecUtils;
use Oxzion\Utils\FileUtils;
use Oxzion\Utils\FilterUtils;
use Oxzion\Utils\UuidUtil;
use Oxzion\ValidationException;
use Symfony\Component\Yaml\Yaml;
use Oxzion\Document\Parser\Spreadsheet\SpreadsheetParserImpl;
use Oxzion\Document\Parser\Spreadsheet\SpreadsheetFilter;
use Oxzion\Document\Parser\Form\FormRowMapper;

class AppService extends AbstractService
{
    protected $config;
    private $table;
    protected $workflowService;
    protected $fieldService;
    protected $formService;
    protected $organizationService;
    protected $entityService;
    private $privilegeService;
    private $menuItemService;
    private $pageService;
    private $jobService;
    private $appDeployOptions;

    /**
     * @ignore __construct
     */
    public function __construct($config, $dbAdapter, AppTable $table, WorkflowService $workflowService, FormService $formService, FieldService $fieldService, JobService $jobService, $organizationService, $entityService, $privilegeService, $roleService, $menuItemService, $pageService)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
        $this->workflowService = $workflowService;
        $this->formService = $formService;
        $this->fieldService = $fieldService;
        $this->organizationService = $organizationService;
        $this->entityService = $entityService;
        $this->privilegeService = $privilegeService;
        $this->roleService = $roleService;
        $this->menuItemService = $menuItemService;
        $this->pageService = $pageService;
        $this->jobService = $jobService;
        $this->appDeployOptions = array("initialize", "symlink", "entity", "workflow", "form", "page", "menu", "job");
    }

    /**
     * GET List App Service
     * @method getApps
     * @return array $data get list of Apps by User
     * <code>
     * {
     * }
     * </code>
     */
    public function getApps()
    {
        try {
            $queryString = "Select ap.name,ap.uuid,ap.description,ap.type,ap.logo,ap.category,ap.date_created,ap.date_modified,ap.created_by,ap.modified_by,ap.status,ar.org_id,ar.start_options from ox_app as ap
            left join ox_app_registry as ar on ap.id = ar.app_id where ar.org_id=? and ap.status!=?";
            $queryParams = array(AuthContext::get(AuthConstants::ORG_ID), 1);
            $resultSet = $this->executeQueryWithBindParameters($queryString, $queryParams)->toArray();
            return $resultSet;
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
    }

    public function getApp($id)
    {
        try {
            $queryString = "Select ap.name,ap.uuid,ap.description,ap.type,ap.logo,ap.category,ap.date_created,ap.date_modified,ap.created_by,ap.modified_by,ap.status,ar.org_id,ar.start_options from ox_app as ap
            left join ox_app_registry as ar on ap.id = ar.app_id where ar.org_id=? and ap.status!=? and ap.uuid =?";
            $queryParams = array(AuthContext::get(AuthConstants::ORG_ID), 1, $id);
            $resultSet = $this->executeQueryWithBindParameters($queryString, $queryParams)->toArray();
            return $resultSet;
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
    }

    public function createApp($data, $returnForm = false)
    {
        $form = new App();
        $data['uuid'] = isset($data['uuid']) ? $data['uuid'] : UuidUtil::uuid();
        $data['date_created'] = date('Y-m-d H:i:s');
        $data['created_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['status'] = isset($data['status']) ? $data['status'] : App::IN_DRAFT;
        $form->exchangeArray($data);
        $form->validate();
        $count = 0;
        $this->logger->info("Data modified before the transaction", $data);
        $this->beginTransaction();
        try {
            $count = $this->table->save($form);
            if ($count == 0) {
                $this->rollback();
                throw new ServiceException("App Save Failed", "app.save.failed");
            }
            if (!isset($data['id'])) {
                $id = $this->table->getLastInsertValue();
                $data['id'] = $id;
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
        if ($returnForm === true) {
            return array('form' => $form->toArray(), 'count' => $count);
        } else {
            return $count;
        }

    }

    private function updateyml($yamldata, $path)
    {
        $filename = "application.yml";
        $new_yaml = Yaml::dump($yamldata, 20);
        file_put_contents($path . $filename, $new_yaml);
    }

    private function updateymlforapp(&$yamldata, $modifieddata, $path)
    {
        $filename = "application.yml";
        try {
            if (!(array_key_exists('uuid', $yamldata['app'][0]))) {
                $yamldata['app'][0]['uuid'] = $modifieddata['uuid'];
            }
            if (!(array_key_exists('category', $yamldata['app'][0]))) {
                $yamldata['app'][0]['category'] = $modifieddata['category'];
            }
            $new_yaml = Yaml::dump($yamldata, 20);
            file_put_contents($path . $filename, $new_yaml);
        } catch (ServiceException $e) {
            throw $e;
        }
    }

    private function collectappfieldsdata(&$data)
    {
        if (!(array_key_exists('type', $data[0]))) {
            $data[0]['type'] = 2;
        }
        if (!(array_key_exists('category', $data[0]))) {
            $data[0]['category'] = "OFFICE";
        }
        if (!(array_key_exists('autostart', $data[0]))) {
            $data[0]['autostart'] = "true";
        }
        $data[0]['name'] = str_replace(" ", "", $data[0]['name']);
        return $data;
    }

    private function loadAppDescriptor($path)
    {
        //check if directory exists
        $filename = "application.yml";
        if (!(file_exists($path))) {
            throw new ServiceException("Directory not found", "directory.required");
        } else { //check if filename exists
            if (!(file_exists($path . $filename))) {
                throw new ServiceException("File not found", "file.required");
            } else {
                $yaml = Yaml::parse(file_get_contents($path . $filename));
                if (empty($yaml)) {
                    throw new ServiceException("File is empty", "file.data.required");
                } else {
                    if (!(isset($yaml['app']))) {
                        throw new ServiceException("App details does not exist in yaml", "app.required");
                    } else {
                        return $yaml;
                    }
                }
            }
        }
    }

    public function deployApp($path, $params = null)
    {      
        try {
            $ymlData = $this->loadAppDescriptor($path);
            if(!isset($params)){
                $params = $this->appDeployOptions;
            }
            foreach ($this->appDeployOptions as $key => $value) {
                if(!in_array($value, $params)){
                    continue;
                }
                switch ($value) {
                    case 'initialize':  $this->processApp($ymlData, $path);
                                        $this->createOrg($ymlData);
                                        $this->createAppPrivileges($ymlData);
                                        $this->createRole($ymlData);
                                        $this->performMigration($ymlData, $path);
                                        $this->setupAppView($ymlData, $path);
                                        break;
                    case 'entity': $this->processEntity($ymlData);
                        break;
                    case 'workflow': $this->processWorkflow($ymlData, $path);
                        break;
                    case 'form': $this->processForm($ymlData, $path);
                        break;
                    case 'page': $this->processPage($ymlData, $path);
                        break;
                    case 'menu': $this->processMenu($ymlData, $path);
                        break;
                    case 'job': $this->processJob($ymlData);
                        break;
                    case 'symlink': $this->processSymlinks($ymlData, $path);
                    break;
                   default: $this->logger->info("no matching parameter found");
                       break;
               }
            }
            $this->updateyml($ymlData, $path);
            $appData = $ymlData['app'][0];
            $appData['status'] = App::PUBLISHED;
            $this->logger->info("\n App Data before app update - ", print_r($appData, true));
            $this->updateApp($appData['uuid'], $appData);
        } catch (Exception $e) {
            throw $e;
        }
    }

    private function processApp(&$yamlData, $path){
        $appData = $this->collectappfieldsdata($yamlData['app']);
        $appUuid = $this->checkAppExists($yamlData['app'][0]);
        $appData['uuid'] = $yamlData['app'][0]['uuid'];
        $this->updateymlforapp($yamlData, $appData, $path);
    }

    public function processSymlinks($yamlData, $path){
        $appUuid = $yamlData['app'][0]['uuid'];
        $appName = $yamlData['app'][0]['name'];
        $orgUuid = isset($yamlData['org'][0]['uuid']) ? $yamlData['org'][0]['uuid'] : null;
        $this->setupLinks($path, $appName, $appUuid, $orgUuid);
    }

    public function createOrg(&$yamlData){
        if (isset($yamlData['org'])) {
            $data = $this->processOrg($yamlData['org'][0]);
            $orgUuid = $data['uuid'];
            $appUuid = $yamlData['app'][0]['uuid'];
            $result = $this->createAppRegistry($appUuid, $yamlData['org'][0]['uuid']);
            
        }
    }

    public function processJob(&$yamlData) {
        $this->logger->info("Deploy App - Process Job with YamlData ");
        if(isset($yamlData['job'])){
            $appUuid = $yamlData['app'][0]['uuid'];
            foreach ($yamlData['job'] as $data) {
                try {
                    if(!isset($data['name']) || !isset($data['url']) || !isset($data['uuid']) || !isset($data['cron']) || !isset($data['data']))
                    {
                        throw new ServiceException('Job Name/url/uuid/cron/data not specified', 'job.details.not.specified');                    
                    }
                    $jobName = $data['uuid'];
                    $jobGroup = $data['name'];
                    $jobPayload = array("job" => array("url" => $this->config['internalBaseUrl'] . $data['url'], "data" => $data['data']), "schedule" => array("cron" => $data['cron']));
                    $cron = $data['cron'];
                    $appId = isset($data['appId']) ? $data['appId'] : $appUuid;
                    $appId = $this->getIdFromUuid('ox_app', $appId);
                    $query = "SELECT id from ox_job where name = :jobName and group_name = :groupName and app_id = :appId";
                    $params = array('jobName' => $jobName, 'groupName' => $jobGroup, 'appId' => $appId);
                    $result = $this->executeQueryWithBindParameters($query, $params)->toArray();
                    if(isset($result) && !empty($result))
                    {
                        $cancel = $this->jobService->cancelJob($jobName, $jobGroup, $appUuid);
                    }
                    $this->logger->info("executing schedule job ");
                    $response = $this->jobService->scheduleNewJob($jobName, $jobGroup, $jobPayload, $cron, $appUuid);
                }
                catch (Exception $e) {
                    $this->logger->info("there is an exception: ");
                    $response = json_decode($e->getCode());
                    if($response == 404){
                        $this->logger->info("deleting from db ");
                        $query = "DELETE from ox_job where name = :jobName and group_name = :groupName and app_id = :appId";
                        $params = array('jobName' => $jobName, 'groupName' => $jobGroup, 'appId' => $appId);
                        $result = $this->executeQueryWithBindParameters($query, $params);
                        $this->logger->info("executing schedule job - ");
                        $response = $this->jobService->scheduleNewJob($jobName, $jobGroup, $jobPayload, $cron, $appUuid);
                    }
                    else
                    {
                        $this->logger->info("Process Job ---- Exception" . print_r($e->getMessage(), true));
                        throw $e;
                    }
                }
            }
        }     
    }

    public function processMenu(&$yamlData, $path)
    {
        $this->logger->info("Deploy App - Process Menu with YamlData ");
        if (isset($yamlData['menu'])) {
            $appUuid = $yamlData['app'][0]['uuid'];
            $sequence = 0;
            foreach ($yamlData['menu'] as &$menuData) {
                $menu = $menuData;
                $menu['sequence'] = $sequence++;
                $menu['privilege_name'] = isset($menu['privilege']) ? $menu['privilege'] : null;
                $menu['uuid'] = isset($menu['uuid']) ? $menu['uuid'] : UuidUtil::uuid();
                $menuUpdated = $this->menuItemService->updateMenuItem($menu['uuid'], $menu);
                if ($menuUpdated == 0) {
                    $count = $this->menuItemService->saveMenuItem($appUuid, $menu);
                }
                if(isset($menu['page_uuid'])){
                    $menu['page_id'] = $this->getIdFromUuid('ox_app_page', $menu['page_uuid']);
                }
                $count = $this->menuItemService->updateMenuItem($menu['uuid'], $menu);
                $menuData['uuid'] = isset($menuData['uuid']) ? $menuData['uuid'] : $menu['uuid'];
            }
        }
    }

    public function processPage(&$yamlData, $path)
    {
        $this->logger->info("Deploy App - Process Page with YamlData ");
        if (isset($yamlData['pages'])) {
            $appUuid = $yamlData['app'][0]['uuid'];
            $sequence = 0;
            foreach ($yamlData['pages'] as &$pageData) {
                if (isset($pageData['page_name']) && !empty($pageData['page_name'])) {
                    $page = Yaml::parse(file_get_contents($path . 'content/pages/' . $pageData['page_name']));
                }
                $page['page_id'] = $pageData['uuid'];
                $pageId = $page['page_id'];
                $this->logger->info('the page data is: '.print_r($page, true));
                $routedata = array("appId" => $appUuid, "orgId" => $yamlData['org'][0]['uuid']);
                $result = $this->pageService->savePage($routedata, $page, $pageId);
            }
        }
    }

    public function processForm(&$yamlData, $path)
    {
        if (isset($yamlData['form'])) {
            $appUuid = $yamlData['app'][0]['uuid'];
            $entityReferences = $this->getEntityFieldReferences($yamlData, $path);
            foreach ($yamlData['form'] as &$form) {
                $form['uuid'] = isset($form['uuid']) ? $form['uuid'] : UuidUtil::uuid();
                $data = $form;
                $entity = $this->entityService->getEntityByName($appUuid, $data['entity']);
                if (!$entity) {
                    $entity = array('name' => $data['entity']);
                    $result = $this->entityService->saveEntity($appUuid, $entity);
                }
                $data['entity_id'] = $entity['id'];
                if (isset($data['template_file'])) {
                    $data['template'] = file_get_contents($path . 'content/forms/' . $data['template_file']);
                }
                $fieldReference = NULL;
                if($entityReferences && isset($entityReferences[$data['entity']])){
                    $fieldReference = $this->getFieldReference($entityReferences[$data['entity']]);
                }

                $count = $this->formService->updateForm($appUuid, $data['uuid'], $data, $fieldReference);
                if ($count == 0) {
                    $this->formService->createForm($appUuid, $data, $fieldReference);
                }
                $form['uuid'] = isset($form['uuid']) ? $form['uuid'] : $data['uuid'];
            }
        }
    }
    private function getFieldReference($path){
        $parser = new SpreadsheetParserImpl();
        $parser->init($path);
        $sheetNames = $parser->getSheetNames();
        $rowMapper = new FormRowMapper();
        $filter = new SpreadsheetFilter();
        $filter->setRows(2);
        $fieldReference = $parser->parseDocument(array('rowMapper' => $rowMapper,
                                                       'filter' => $filter));
        
        $fieldReference = $fieldReference[$sheetNames[0]];
        return $fieldReference;
    }
    private function getEntityFieldReferences($yamlData, $path){
        $entityReferences = array();
        if(isset($yamlData['entity']) && !empty($yamlData['entity'])){
            foreach ($yamlData['entity'] as $entity) {
                if(isset($entity['fieldReference'])){
                    $fileRefPath = $path . 'content/entity/'.$entity['fieldReference'];
                    if (FileUtils::fileExists($fileRefPath)) {
                        $entityReferences[$entity['name']] = $fileRefPath;
                    }
                }
            }
        }
        return count($entityReferences) > 0 ? $entityReferences : NULL;
    }

    public function setupAppView($yamlData, $path)
    {
        if (!is_dir($path . 'view')) {
            FileUtils::createDirectory($path . 'view');
        }
        if (!is_dir($path . 'view/apps/')) {
            FileUtils::createDirectory($path . 'view/apps/');
        }
        $appName = $path . 'view/apps/' . $yamlData['app'][0]['name'];
        $metadataPath = $appName . '/metadata.json';
        if (FileUtils::fileExists($appName) && FileUtils::fileExists($metadataPath)) {
            return;
        }
        $eoxapp = $this->config['DATA_FOLDER'] . 'eoxapps';
        FileUtils::copyDir($eoxapp, $path);
        FileUtils::renameFile($path . 'view/apps/eoxapps' ,$path . 'view/apps/' . $yamlData['app'][0]['name']);
        $jsonData = json_decode(file_get_contents($metadataPath), true);
        $jsonData['name'] = $yamlData['app'][0]['name'];
        $jsonData['appId'] = $yamlData['app'][0]['uuid'];
        $jsonData['title']['en_EN'] = $yamlData['app'][0]['name'];
        if (isset($yamlData['app'][0]['description'])) {
            $jsonData['description']['en_EN'] = $yamlData['app'][0]['description'];
        }
        if (isset($yamlData['app'][0]['autostart'])) {
            $jsonData['autostart'] = $yamlData['app'][0]['autostart'];
        }
        file_put_contents($appName . '/metadata.json', json_encode($jsonData));
        $packagePath = $appName . '/package.json';
        $jsonData = json_decode(file_get_contents($packagePath), true);
        $jsonData['name'] = $yamlData['app'][0]['name'];
        file_put_contents($appName . '/package.json', json_encode($jsonData));
        $indexScssPath = $appName . '/index.scss';
        $indexfileData = file_get_contents($indexScssPath);
        $indexfileData2 = str_replace('{AppName}', $yamlData['app'][0]['name'], $indexfileData);
        file_put_contents($appName . '/index.scss', $indexfileData2);
    }

    public function processWorkflow(&$yamlData, $path)
    {
        if (isset($yamlData['workflow'])) {
            $appUuid = $yamlData['app'][0]['uuid'];
            $workflowData = $yamlData['workflow'];
            foreach ($workflowData as $value) {
                $entityName = null;
                if(isset($value['entity'])) {
                    $value['entity'] = is_array($value['entity']) ? $value['entity'] : array($value['entity']);
                    foreach ($value['entity'] as $entityName) {
                        $result = 0;
                        $result = $this->checkWorkflowData($value,$appUuid);
                        if ($result == 0) {
                            $entity = $this->entityService->getEntityByName($yamlData['app'][0]['uuid'], $entityName);
                            if (!$entity) {
                                $entity = array('name' => $entityName);
                                $result = $this->entityService->saveEntity($yamlData['app'][0]['uuid'], $entity);
                            }
                            if (isset($value['uuid']) && isset($entity['id'])) {
                                $bpmnFilePath = $path . "content/workflows/" . $value['bpmn_file'];
                                $result = $this->workflowService->deploy($bpmnFilePath, $appUuid, $value, $entity['id']);
                            }
                        }
                    }
                }
            }
        }
    }

    private function checkWorkflowData(&$data,$appUuid)
    {
        $data['uuid'] = isset($data['uuid']) ? $data['uuid'] : UuidUtil::uuid();
        if (!(isset($data['bpmn_file']))) {
            $this->logger->warn("BPMN file not specified, hence deploy failed! ");
            return 1;
        }
        if (!isset($data['name'])) {
            $data['name'] = str_replace('.bpmn', '', $data['bpmn_file']); // Replaces all .bpmn with no space.
            $data['name'] = str_replace(' ', '_', $data['bpmn_file']); // Replaces all spaces
            $data['name'] = preg_replace('/[^A-Za-z0-9\_]/', '', $data['name'], -1); // Removes special chars.
        }
        if (isset($data['entity'])) {
            $this->assignWorkflowToEntityMapping($data['entity'], $data['uuid'],$appUuid);
        } else {
            $this->logger->warn("Entity not given, deploy failed ! ");
            return 1;
        }
    }

    private function setLinkAndRunBuild($target,$link){
        $flag = 0;
        $folderCount = 0;
        if (file_exists($target) && is_dir($target)) {
            $files = new FileSystemIterator($target);
            foreach ($files as $file) {
                if ($file->isDir()) {
                    $folderCount += 1;
                }
            }
            foreach ($files as $file) {
                $this->logger->info("\n Symlinking files" . print_r($file, true));
                if ($file->isDir()) {
                    $targetName = $file->getPathName();
                    $linkName = $link . $file->getFilename();
                    if (is_link($linkName)) {
                        unlink($linkName);
                    }
                    if (file_exists($targetName)) {
                        $this->setupLink($targetName, $linkName);
                        $this->executeCommands($targetName);
                        $flag = 1;
                    }
                }
            }
            if ($flag == 1) {
                $runDiscover = $this->executePackageDiscover();
            }
        }
    }

    private function setupLinks($path, $appName, $appId, $orgId = null)
    {
        $link = $this->config['DELEGATE_FOLDER'] . $appId;
        $target = $path . "data/delegate";
        if (is_link($link)) {
            FileUtils::unlink($link);
        }
        if (file_exists($target)) {
            $this->setupLink($target, $link);
        }
        $formlink = $this->config['FORM_FOLDER'] . $appId;
        $formsTarget = $path . "content/forms";
        if (is_link($formlink)) {
            FileUtils::unlink($formlink);
        }
        if (file_exists($formsTarget)) {
            $this->setupLink($formsTarget, $formlink);
        }

        $formlink = $this->config['PAGE_FOLDER'] . $appId;
        $formsTarget = $path . "content/pages";
        if (is_link($formlink)) {
            FileUtils::unlink($formlink);
        }
        if (file_exists($formsTarget)) {
            $this->setupLink($formsTarget, $formlink);
        }
        $formlink = $this->config['ENTITY_FOLDER'] . $appId;
        $formsTarget = $path . "content/entity";
        if (is_link($formlink)) {
            FileUtils::unlink($formlink);
        }
        if (file_exists($formsTarget)) {
            $this->setupLink($formsTarget, $formlink);
        }
        $guilink = $this->config['GUI_FOLDER'] . $appId;
        $guiTarget = $path . "view/gui";
        if (is_link($guilink)) {
            FileUtils::unlink($guilink);
        }
        if (file_exists($guiTarget)) {
            $this->setupLink($guiTarget, $guilink);
            $this->executeCommands($guilink);
        }

        if ($orgId) {
            $link = $this->config['TEMPLATE_FOLDER'] . $orgId;
            $target = $path . "data/template";
            if (is_link($link)) {
                FileUtils::unlink($link);
            }
            if (file_exists($target)) {
                $this->setupLink($target, $link);
            }
        }
        $appTarget = $path . "view/apps/";
        $themeTarget = $path."view/themes";
        $themeLink = $this->config['THEME_FOLDER'];
        $appLink = $this->config['APPS_FOLDER'];
        $this->setLinkAndRunBuild($themeTarget,$themeLink);
        $this->setLinkAndRunBuild($appTarget,$appLink);
    }

    private function executePackageDiscover()
    {
        $app = $this->config['APPS_FOLDER'];
        $command_one = "cd " . $app . "../bos/";
        $command = $app . "../bos/";
        if (!file_exists($command . "src/client/local.js")) {
            copy($command . 'src/client/local.js.example', $command . 'src/client/local.js');
        }
        $command_two = "npm run package:discover";
        $output = ExecUtils::execCommand($command_one . " && " . $command_two);
        $this->logger->info("PAckage Discover .. \n" . print_r($output, true));

    }

    private function executeCommands($link)
    {
        $link = str_replace(' ', '\ ', $link);
        $command_one = "cd " . $link;
        $command_two = "npm install";
        $command_three = "npm run build";
        $command = $command_one . " && " . $command_two;
        $output = ExecUtils::execCommand($command);
        $this->logger->info("Executing command $command .. \n" . print_r($output, true));
        $command = $command_one . " && " . $command_three;
        $output = ExecUtils::execCommand($command);
        $this->logger->info("Executing command $command .. \n" . print_r($output, true));
    }

    private function setupLink($target, $link)
    {
        if (file_exists($link)) {
            throw new Exception("Cannot setup $target, as folder $link already exists");
        }
        if (!is_link($link)) {
            $this->logger->info("setting up link $link with $target");
            FileUtils::symlink($target, $link);
        }
    }

    public function performMigration($yamlData, $path)
    {
        $data = $yamlData['app'][0];
        $appName = $data['name'];
        $appId = $data['uuid'];
        $description = isset($data['description']) ? $data['description'] : $appName;
        $migration = new Migration($this->config, $appName, $appId, $description);
        if (file_exists($path . "/data/migrations/")) {
            $migrationFolder = $path . "/data/migrations/";
            $fileList = array_diff(scandir($migrationFolder), array(".", ".."));
            if (count($fileList) > 0) {
                $migration->migrate(realpath($migrationFolder));
            }
        }
    }

    public function createRole(&$yamlData)
    {
        if (isset($yamlData['role'])) {
            if (!(isset($yamlData['org'][0]['uuid']))) {
                $this->logger->warn("Organization not provided not processing roles!");
                return;
            }
            $appUuid = $yamlData['app'][0]['uuid'];
            $appId = $this->getIdFromUuid('ox_app', $appUuid);
            $params['orgId'] = $yamlData['org'][0]['uuid'];
            foreach ($yamlData['role'] as &$roleData) {
                $role = $roleData;
                if (!isset($role['name'])) {
                    $this->logger->warn("Role name not provided continuing!");
                    continue;
                }
                $role['uuid'] = isset($role['uuid']) ? $role['uuid'] : UuidUtil::uuid();
                $result = $this->roleService->saveRole($params, $role, $role['uuid']);
                $roleData['uuid'] = $role['uuid'];
            }
        }
    }

    private function processOrg(&$orgData)
    {
        if (!isset($orgData['uuid'])) {
            $orgData['uuid'] = UuidUtil::uuid();
        }
        if (!isset($orgData['contact'])) {
            $orgData['contact'] = array();
            $orgData['contact']['username'] = str_replace('@', '.', $orgData['email']);
            $orgData['contact']['firstname'] = 'Admin';
            $orgData['contact']['lastname'] = 'User';
            $orgData['contact']['email'] = $orgData['email'];
        }
        if (!isset($orgData['preferences'])) {
            $orgData['preferences'] = '{}';
        }
        $orgdata = $orgData;
        $result = $this->organizationService->saveOrganization($orgdata);
        if ($result == 0) {
            throw new ServiceException("Organization could not be saved", 'org.not.saved');
        }
        $orgData['uuid'] = $orgdata['uuid'];
        return $orgData;
    }

    private function checkAppExists(&$appdata)
    {
        try {
            $queryString = "Select ap.uuid,ap.name as name from ox_app as ap where ap.name = :appName";
            $params = array("appName" => $appdata['name']);
            if (isset($appdata['uuid'])) {
                $queryString .= " OR ap.uuid = :appId";
                $params['appId'] = $appdata['uuid'];
            }
            $result = $this->executeQueryWithBindParameters($queryString, $params)->toArray();
            if (count($result) == 0) {
                $data = $this->createApp($appdata, true);
                $appdata['uuid'] = $data['form']['uuid'];
            } else {
                if (isset($appdata['uuid'])) {
                    if ($appdata['uuid'] == $result[0]['uuid']) {
                        if ($appdata['name'] != $result[0]['name']) {
                            $this->updateApp($appdata['uuid'], $appdata);
                        }
                    } else {
                        throw new ServiceException("App Already Exists", 'duplicate.app');
                    }
                } else {
                    if ($appdata['name'] == $result[0]['name']) {
                        $appdata['uuid'] = $result[0]['uuid'];
                    }
                }
            }
            return $appdata['uuid'];
        } catch (Exception $e) {
            throw $e;
        }
    }

    public function createAppPrivileges($yamlData)
    {        
        if (isset($yamlData['privilege'])) {
            $appUuid = $yamlData['app'][0]['uuid'];
            $privilegedata = $yamlData['privilege'];
            $privilegearray = array_unique(array_column($privilegedata, 'name'));
            $list = "'" . implode("', '", $privilegearray) . "'";
            $appId = $this->getIdFromUuid('ox_app', $appUuid);
            $this->privilegeService->saveAppPrivileges($appId, $privilegedata);
        }
    }

    public function getAppList($filterParams = null)
    {
        $pageSize = 20;
        $offset = 0;
        $where = "";
        $sort = "name";
        try {
            $cntQuery = "SELECT count(id) FROM `ox_app`";
            if (count($filterParams) > 0 || sizeof($filterParams) > 0) {
                $filterArray = json_decode($filterParams['filter'], true);
                if (isset($filterArray[0]['filter'])) {
                    $filterlogic = isset($filterArray[0]['filter']['logic']) ? $filterArray[0]['filter']['logic'] : "AND";
                    $filterList = $filterArray[0]['filter']['filters'];
                    $where = " WHERE " . FilterUtils::filterArray($filterList, $filterlogic);
                }
                if (isset($filterArray[0]['sort']) && count($filterArray[0]['sort']) > 0) {
                    $sort = $filterArray[0]['sort'];
                    $sort = FilterUtils::sortArray($sort);
                }
                $pageSize = $filterArray[0]['take'];
                $offset = $filterArray[0]['skip'];
            }
            $where .= strlen($where) > 0 ? " AND status!=1" : "WHERE status!=1";
            $sort = " ORDER BY " . $sort;
            $limit = " LIMIT " . $pageSize . " offset " . $offset;
            $resultSet = $this->executeQuerywithParams($cntQuery . $where);
            $count = $resultSet->toArray()[0]['count(id)'];
            $query = "SELECT * FROM `ox_app` " . $where . " " . $sort . " " . $limit;
            $resultSet = $this->executeQuerywithParams($query);
            $result = $resultSet->toArray();
            for ($x = 0; $x < sizeof($result); $x++) {
                $result[$x]['start_options'] = json_decode($result[$x]['start_options'], true);
            }
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
        return array('data' => $result, 'total' => $count);
    }

    public function updateApp($id, &$data)
    {
        $obj = $this->table->getByUuid($id);
        if (is_null($obj)) {
            return 0;
        }
        $form = new App();
        $data = array_merge($obj->toArray(), $data); //Merging the data from the db for the ID
        $data['id'] = $this->getIdFromUuid('ox_app', $id);
        $data['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_modified'] = date('Y-m-d H:i:s');
        $data['status'] = isset($data['status']) ? $data['status'] : App::IN_DRAFT;
        $this->logger->info("Modified data before the transaction - " . print_r($data, true));
        $form->exchangeArray($data);
        $form->validate();
        $count = 0;
        $this->beginTransaction();
        try {
            $count = $this->table->save($form);
            if ($count == 0) {
                $this->rollback();
                throw new ServiceException("App could not be saved", "app.save.failed");
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
        return $count;
    }

    public function deleteApp($id)
    {
        $obj = $this->table->getByUuid($id);
        if (is_null($obj)) {
            return 0;
        }
        $form = new App();
        $data = $obj->toArray();
        $data['id'] = $this->getIdFromUuid('ox_app', $id);
        $data['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_modified'] = date('Y-m-d H:i:s');
        $data['status'] = App::DELETED;
        $this->logger->info("Modified data before the transaction - " . print_r($data, true));
        $form->exchangeArray($data);
        $count = 0;
        $this->beginTransaction();
        try {
            $count = $this->table->save($form);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            $this->rollback();
            throw $e;
        }
        return $count;
    }

    /**
     * @return mixed
     */
    public function getAppUploadFolder()
    {
        return $upload = $this->config["APP_UPLOAD_FOLDER"];
    }

    /**
     * @param $appData
     * @return array|int
     */
    public function insertAppDetail($appData)
    {
        if (!empty($appData['name'])) {
            $formData['name'] = $appData['name'];
            $formData['description'] = $appData['description'];
            $formData['logo'] = $appData['logo'];
            $formData['app_id'] = $appData['app-id'];
            $formData['type'] = $appData['type'];
        }
        try {
            $id = $this->deployAppForOrg($formData);
        } catch (ValidationException $e) {
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
        return $id;
    }

    /**
     * @param $formArray
     * @return int|string
     * @throws Exception
     */
    public function getFormInsertFormat($formArray)
    {
        if (!empty($formArray['app-form-assign'])) {
            $formData['app_id'] = $formArray['app-id'];
            $formData['name'] = $formArray['app-form-assign']['form-name'];
            $formData['description'] = $formArray['app-form-assign']['form-description'];
            if (!empty($formArray['app-form-assign']['form-statuslist'])) {
                foreach ($formArray['app-form-assign']['form-statuslist'] as $statusArray) {
                    $stsData[$statusArray['status-value']] = $statusArray['status-text'];
                    $sts['data'] = $stsData;
                }
            }
            $formData['statuslist'] = json_encode($sts);
        }
        try {
            $count = $this->formService->createForm($formData);
        } catch (ValidationException $e) {
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
        return $count;
    }

    private function createAppRegistry($appId, $orgId)
    {
        $sql = $this->getSqlObject();
        //Code to check if the app is already registered for the organization
        $queryString = "select count(ar.id) as count
        from ox_app_registry as ar
        inner join ox_app ap on ap.id = ar.app_id
        inner join ox_organization org on org.id = ar.org_id
        where ap.uuid = :appId and org.uuid = :orgId";
        $params = array("appId" => $appId, "orgId" => $orgId);
        $resultSet = $this->executeQueryWithBindParameters($queryString, $params)->toArray();
        if ($resultSet[0]['count'] == 0) {
            try {
                $this->beginTransaction();
                $insert = "INSERT into ox_app_registry (app_id, org_id, start_options)
                select ap.id, org.id, ap.start_options from ox_app as ap, ox_organization as org where ap.uuid = :appId and org.uuid = :orgId";
                $params = array("appId" => $appId, "orgId" => $orgId);
                $result = $this->executeUpdateWithBindParameters($insert, $params);
                $this->commit();
                return $result->getAffectedRows();
            } catch (Exception $e) {
                $this->rollback();
                throw $e;
            }
        }
        return 0;
    }

    public function registerApps($data)
    {
        $apps = json_decode($data['applist'], true);
        unset($data);
        $form = new App();
        $list = array();
        for ($x = 0; $x < sizeof($apps); $x++) {
            $data['name'] = isset($apps[$x]['name']) ? $apps[$x]['name'] : null;
            array_push($list, $data);
        }
        $this->logger->info("Data modified before the transaction", $data);
        $this->beginTransaction();
        try {
            $appSingleArray = array_unique(array_map('current', $list));
            $update = "UPDATE ox_app SET status = " . App::DELETED . " where ox_app.name NOT IN ('" . implode("','", $appSingleArray) . "')";
            $result = $this->runGenericQuery($update);
            $select = "SELECT name FROM ox_app where name in ('" . implode("','", $appSingleArray) . "')";
            $result = $this->executeQuerywithParams($select)->toArray();
            $result = array_unique(array_map('current', $result));
            $count = 0;
            for ($x = 0; $x < sizeof($apps); $x++) {
                if (!in_array($apps[$x]['name'], $result)) {
                    $data['name'] = isset($apps[$x]['name']) ? $apps[$x]['name'] : null;
                    $data['category'] = isset($apps[$x]['category']) ? $apps[$x]['category'] : null;
                    $data['isdefault'] = isset($apps[$x]['isdefault']) ? $apps[$x]['isdefault'] : 0;
                    $data['start_options'] = json_encode($apps[$x]['options']);
                    //this API call is done by the server hence hardcoding the created by value
                    $data['created_by'] = 1;
                    $data['date_created'] = date('Y-m-d H:i:s');
                    $data['status'] = App::PUBLISHED;
                    $data['type'] = App::PRE_BUILT;
                    if (isset($apps[$x]['uuid']) && $apps[$x]['uuid'] == "NULL") {
                        $apps[$x]['uuid'] = null;
                    }
                    $data['uuid'] = isset($apps[$x]['uuid']) ? $apps[$x]['uuid'] : UuidUtil::uuid();
                    $this->logger->info("Data modified inside the foreach before it is saved", $data);
                    $form->exchangeArray($data);
                    $form->validate();

                    $count += $this->table->save($form);
                } else {
                    $start_options = isset($apps[$x]['options']) ? json_encode($apps[$x]['options']) : null;
                    $category = isset($apps[$x]['category']) ? $apps[$x]['category'] : null;
                    $isdefault = isset($apps[$x]['isdefault']) ? $apps[$x]['isdefault'] : 0;
                    $modified_by = 1;
                    $update = "UPDATE ox_app SET `start_options` = '" . $start_options . "', `category` = '" . $category . "',`isdefault` = " . $isdefault . ", `date_modified` = '" . date('Y-m-d H:i:s') . "',`modified_by` = " . $modified_by . " WHERE name = '" . $apps[$x]['name'] . "'";
                    $updatequery = $this->executeQuerywithParams($update);
                }
            }
            $query = "SELECT id from `ox_app` WHERE isdefault = 1";
            $selectquery = $this->executeQuerywithParams($query)->toArray();
            $idList = array_unique(array_map('current', $selectquery));
            for ($i = 0; $i < sizeof($idList); $i++) {
                $insert = "INSERT INTO `ox_app_registry` (`org_id`,`app_id`,`date_created`)
                SELECT org.id, '" . $idList[$i] . "', now() from ox_organization as org
                where org.id not in(SELECT org_id FROM ox_app_registry WHERE app_id ='" . $idList[$i] . "')";
                $result = $this->runGenericQuery($insert);
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
        return 1;
    }

    public function addToAppRegistry($data)
    {
        $this->logger->debug("Adding App to registry");
        try {
            $data['orgId'] = isset($data['orgId']) ? $data['orgId'] : AuthContext::get(AuthConstants::ORG_UUID);
            $app = $this->table->getByName($data['app_name']);
            return $this->createAppRegistry($app->uuid, $data['orgId']);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }

    }

    public function processEntity(&$yamlData, $assoc_id = null)
    {
        if (isset($yamlData['entity']) && !empty($yamlData['entity'])) {
            $appId = $yamlData['app'][0]['uuid'];
            $sequence = 0;
            foreach ($yamlData['entity'] as &$entityData) {
                $entity = $entityData;
                $entity['assoc_id'] = $assoc_id;
                $result = $this->entityService->saveEntity($appId, $entity);
                $entityData['uuid'] = $entity['uuid'];
                if(isset($entity['identifiers'])){
                    $result = $this->entityService->saveIdentifiers($entity['id'], $entity['identifiers']);
                }
                if(isset($entity['field'])){
                    foreach ($entity['field'] as $field) {
                        $result = $this->fieldService->getFieldByName($entity['uuid'], $field['name']);
                        if ($result == 0) {
                            $field['entity_id'] = $entity['id'];
                            $this->fieldService->saveField($appId, $field);
                        } else {
                            $this->fieldService->updateField($result['uuid'], $field);
                        }
                    }
                }
                if (isset($entity['child']) && $entity['child']) {
                    $childEntityData = ['entity' => $entityData['child'], 'app' => [['uuid' => $appId]]];
                    $this->processEntity($childEntityData, $entity['id']);
                    $entityData['child'] = $childEntityData['entity'];
                }
            }
        }
    }

    private function assignWorkflowToEntityMapping($entityArray ,$workflowUuid, $appUuid) {
        try {
            $data = array();
            $workflowId = $this->getIdFromUuid('ox_workflow',$workflowUuid);
            $entityArray = is_array($entityArray) ? $entityArray : array($entityArray);
            foreach ($entityArray as $entityName) {
                $entityData = $this->entityService->getEntityByName($appUuid,$entityName);
                if($entityData == null)
                    continue;
                $individualEntry = array(
                    'workflow_id' => $workflowId,
                    'entity_id' => $entityData['id']
                );
                array_push($data, $individualEntry);
            }
            if(!empty($data)) {
                $this->multiInsertOrUpdate('ox_workflow_entity_mapper', $data);
            }
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
    }

    /**
     * installAppForOrg
     *
     * ! Deprecated - Method is not from /app/:appId/appinstall api. But not used anywhere in the application
     * ? Need to check if this can be removed
     * @return null
     */
    public function installAppForOrg()
    {
        return null;
    }
}
