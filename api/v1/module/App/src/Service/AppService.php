<?php
namespace App\Service;

use App\Model\App;
use App\Model\AppTable;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\Service\AbstractService;
use Oxzion\ValidationException;
use Exception;
use Oxzion\Utils\UuidUtil;
use Oxzion\Utils\FileUtils;
use Oxzion\Utils\YMLUtils;
use Oxzion\Utils\ZipUtils;
use Oxzion\Service\WorkflowService;
use Oxzion\Service\FormService;
use Oxzion\Service\FieldService;
use Oxzion\Service\EntityService;
use Oxzion\Utils\FilterUtils;
use Oxzion\Utils\ExecUtils;
use Oxzion\ServiceException;
use Symfony\Component\Yaml\Yaml;
use Zend\Db\Sql\Expression;
use Oxzion\Db\Migration\Migration;
use FileSystemIterator;

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
    /**
     * @ignore __construct
     */
    public function __construct($config, $dbAdapter, AppTable $table, WorkflowService $workflowService, FormService $formService, FieldService $fieldService, $organizationService, $entityService, $privilegeService, $roleService, $menuItemService, $pageService)
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
        try{
            $queryString = "Select ap.name,ap.uuid,ap.description,ap.type,ap.logo,ap.category,ap.date_created,ap.date_modified,ap.created_by,ap.modified_by,ap.status,ar.org_id,ar.start_options from ox_app as ap
            left join ox_app_registry as ar on ap.id = ar.app_id where ar.org_id=? and ap.status!=?";
            $queryParams = array(AuthContext::get(AuthConstants::ORG_ID),1);
            $resultSet = $this->executeQueryWithBindParameters($queryString, $queryParams)->toArray();
            return $resultSet;
        }catch(Exception $e){
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
    }

    public function getApp($id)
    {
        try{
            $queryString = "Select ap.name,ap.uuid,ap.description,ap.type,ap.logo,ap.category,ap.date_created,ap.date_modified,ap.created_by,ap.modified_by,ap.status,ar.org_id,ar.start_options from ox_app as ap
            left join ox_app_registry as ar on ap.id = ar.app_id where ar.org_id=? and ap.status!=? and ap.uuid =?";
            $queryParams = array(AuthContext::get(AuthConstants::ORG_ID),1,$id);
            $resultSet = $this->executeQueryWithBindParameters($queryString, $queryParams)->toArray();
            return $resultSet;
        }catch(Exception $e){
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
    }
    public function createApp($data,$returnForm = false){
        $form = new App();
        $data['uuid'] = isset($data['uuid'])?$data['uuid']:UuidUtil::uuid();
        $data['date_created'] = date('Y-m-d H:i:s');
        $data['created_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['status'] =  isset($data['status']) ? $data['status'] : App::IN_DRAFT;
        $form->exchangeArray($data);
        $form->validate();
        $count = 0;

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
        if($returnForm === true)
            return array('form' => $form->toArray(),'count' => $count);
        else
            return $count;
    }

    private function updateyml($yamldata, $path){
        $filename = "application.yml";
        $new_yaml = Yaml::dump($yamldata, 10);
        file_put_contents($path.$filename, $new_yaml);
    }

    private function updateymlforapp(&$yamldata, $modifieddata, $path){
        $filename = "application.yml";
        if(!(array_key_exists('uuid',$yamldata['app'][0]))){
            $yamldata['app'][0]['uuid'] = $modifieddata['uuid'];
        }
        if(!(array_key_exists('category',$yamldata['app'][0]))){
            $yamldata['app'][0]['category'] = $modifieddata['category'];
        }
        $new_yaml = Yaml::dump($yamldata, 10);
        file_put_contents($path.$filename, $new_yaml);
    }

    private function collectappfieldsdata(&$data){
        if(!(array_key_exists('type',$data[0]))){
            $data[0]['type'] = 2;
        }
        if(!(array_key_exists('category',$data[0]))){
            $data[0]['category'] = "OFFICE";
        }
        if(!(array_key_exists('autostart',$data[0]))){
            $data[0]['autostart'] = "true";
        }
        $data[0]['name'] = str_replace(" ", "", $data[0]['name']);
        return $data;
    }

    private function loadAppDescriptor($path){
        //check if directory exists
        $filename = "application.yml";
        if(!(file_exists($path))){
            throw new ServiceException("Directory not found","directory.required");
        }
        //check if filename exists
        else{
            if (!(file_exists($path.$filename))){
                throw new ServiceException("File not found","file.required");
            }
            else{
                $yaml = Yaml::parse(file_get_contents($path.$filename));
                if (empty($yaml)) {
                    throw new ServiceException("File is empty","file.data.required");
                }
                else{
                    if(!(isset($yaml['app']))){
                        throw new ServiceException("App details does not exist in yaml", "app.required");
                    }else {
                        return $yaml;
                    }
                }
            }
        }
    }

    public function deployApp($path){
        $ymlData = $this->loadAppDescriptor($path);
        $appData = $this->collectappfieldsdata($ymlData['app'])[0];
        try{
            $appUuid = $this->checkAppExists($ymlData['app'][0]);
            $appData['uuid'] = $ymlData['app'][0]['uuid'];
            $this->updateymlforapp($ymlData, $appData, $path);
            $orgUuid = null;
            if(isset($ymlData['org'])){
                $data = $this->processOrg($ymlData['org'][0]);
                $orgUuid = $data['uuid'];
                $result = $this->createAppRegistry($appUuid, $ymlData['org'][0]['uuid']);
            }
            if(isset($ymlData['privilege'])){
                $this->createAppPrivileges($appUuid, $ymlData['privilege'], $orgUuid);
            }
            $returnData = $this->createRole($ymlData);
            $this->performMigration($path, $ymlData['app'][0]);
            $appName = $ymlData['app'][0]['name'];
            $this->setupAppView($path, $ymlData);
            $this->setupLinks($path, $appName, $appUuid, $orgUuid);
            $this->processWorkflow($ymlData, $path, $orgUuid);
            $this->processForm($path, $ymlData);
            $this->processMenu($ymlData, $path);
            $this->processPage($ymlData, $path);
            //if job given setup quartz job
            $this->updateyml($ymlData, $path);
            //Move the app folder from given path to clients folder
            $appData['status'] = App::PUBLISHED;
            $this->updateApp($appData['uuid'], $appData);        
        }catch(Exception $e){
            throw $e;
        }
    }

    private function processMenu(&$yamlData, $path){
        if(isset($yamlData['menu'])){
            $appId = $yamlData['app'][0]['uuid'];
            $sequence = 0;
            foreach ($yamlData['menu'] as &$menuData) {
                $menu = $menuData;
                $menu['sequence'] = $sequence++;
                $menu['privilege_name'] = isset($menu['privilege']) ? $menu['privilege'] : null;
                $menu['uuid'] = isset($menu['uuid'])?$menu['uuid']:UuidUtil::uuid();
                $menuUpdated = $this->menuItemService->updateMenuItem($menu['uuid'], $menu);
                if($menuUpdated == 0){
                    $count = $this->menuItemService->saveMenuItem($appId, $menu);
                                       
                }
                $page = $menu['page'];
                if(isset($menu['page_id'])){
                    $pageId = $menu['page_id'];
                }else{
                    $pageId = null;
                    $page['uuid'] = UuidUtil::uuid();
                }
                $routedata = array("appId" => $appId, "orgId" => $yamlData['org'][0]['uuid']);
                $result = $this->pageService->savePage($routedata, $page, $pageId);
                $menu['page_id'] = isset($menu['page_id']) ? $menu['page_id'] : $page['id'];
                $count = $this->menuItemService->updateMenuItem($menu['uuid'], $menu);
                $menuData['uuid'] = isset($menuData['uuid']) ? $menuData['uuid'] : $menu['uuid'];
            }
        }
    }
    private function processPage(&$yamlData, $path){
        if(isset($yamlData['pages'])){
            $appId = $yamlData['app'][0]['uuid'];
            $sequence = 0;
            foreach ($yamlData['pages'] as &$pageData) {
                $page = $page['page'];
                if(isset($page['page_id'])){
                    $pageId = $page['page_id'];
                }else{
                    $pageId = null;
                    $page['uuid'] = UuidUtil::uuid();
                }
                $routedata = array("appId" => $appId, "orgId" => $yamlData['org'][0]['uuid']);
                $result = $this->pageService->savePage($routedata, $page, $pageId);
                $page['page_id'] = isset($page['page_id']) ? $page['page_id'] : $page['id'];
                $pageData['uuid'] = isset($pageData['uuid']) ? $pageData['uuid'] : $page['uuid'];
            }
        }
    }

    private function processForm($path, &$yamlData){
        if(isset($yamlData['form'])){
            $appUuid = $yamlData['app'][0]['uuid'];
            foreach ($yamlData['form'] as &$form) {
                $form['uuid'] = isset($form['uuid'])?$form['uuid']:UuidUtil::uuid();
                $data = $form;
                $entity = $this->entityService->getEntityByName($appUuid, $data['entity']);
                if(!$entity) {
                    $entity = array('name' => $data['entity']);
                    $result = $this->entityService->saveEntity($appUuid, $entity);
                }
                $data['entity_id'] = $entity['id'];
                if(isset($data['template'])){
                    $data['template'] = file_get_contents($path.'content/forms/'.$data['template']);
                }
                $count = $this->formService->updateForm($appUuid, $data['uuid'], $data);
                if ($count == 0) {
                    $this->formService->createForm($appUuid, $data);
                }
            }
        }
    }

    private function setupAppView($path, $yamlData){
        if(!is_dir($path.'view')){
            FileUtils::createDirectory($path.'view');
        }
        if(!is_dir($path.'view/apps/')){
            FileUtils::createDirectory($path.'view/apps/');
        }
        $appName = $path.'view/apps/'.$yamlData['app'][0]['name'];
        $metadataPath = $appName.'/metadata.json';
        if(FileUtils::fileExists($appName) && FileUtils::fileExists($metadataPath)){
            return;
        }
        $eoxapp = $this->config['DATA_FOLDER'].'eoxapps';
        FileUtils::copyDir($eoxapp, $appName);
        $jsonData = json_decode(file_get_contents($metadataPath), true);
        $jsonData['name']=$yamlData['app'][0]['name'];
        $jsonData['appId'] = $yamlData['app'][0]['uuid'];
        $jsonData['title']['en_EN']= $yamlData['app'][0]['name'];
        if(isset($yamlData['app'][0]['description'])){
            $jsonData['description']['en_EN'] = $yamlData['app'][0]['description'];
        }
        file_put_contents($appName.'/metadata.json', json_encode($jsonData));
    }

    private function processWorkflow(&$yamlData, $path,  $orgUuid = NULL){
        if(isset($yamlData['workflow'])) {
            $appUuid = $yamlData['app'][0]['uuid'];
            $orgId = $this->getIdFromUuid('ox_organization', $orgUuid);
            $workflowData = $yamlData['workflow'];
            foreach ($workflowData as $value) {
                $result = 0;
                $result = $this->checkWorkflowData($value);
                if($result == 0){
                    $entity = $this->entityService->getEntityByName($yamlData['app'][0]['uuid'], $value['entity']);
                    if(!$entity) {
                        $entity = array('name' => $value['entity']);
                        $result = $this->entityService->saveEntity($yamlData['app'][0]['uuid'], $entity);
                    }
                    if(isset($value['uuid']) && isset($entity['id'])) {
                        $bpmnFilePath = $path."content/workflows/".$value['bpmn_file'];
                        $result = $this->workflowService->deploy($bpmnFilePath, $appUuid, $value, $entity['id']);
                    }
                }
            }
        }
    }

    private function checkWorkflowData(&$data){
        $data['uuid'] = isset($data['uuid'])?$data['uuid']:UuidUtil::uuid();
        if(!(isset($data['bpmn_file']))){
            $this->logger->warn("BPMN file not specified, hence deploy failed! ");
            return 1;
        }
        if(!isset($data['name'])){
            $data['name'] = str_replace('.bpmn', '', $data['bpmn_file']); // Replaces all .bpmn with no space.
            $data['name'] = str_replace(' ', '_', $data['bpmn_file']); // Replaces all spaces
            $data['name'] = preg_replace('/[^A-Za-z0-9\_]/', '', $data['name'], -1); // Removes special chars.
        }
        if(!isset($data['entity'])){
            $this->logger->warn("Entity not given, deploy failed ! ");
            return 1;
        }
    }

    private function setupLinks($path, $appName, $appId, $orgId = null){
        $link = $this->config['DELEGATE_FOLDER'].$appId;
        $target = $path."data/delegate";
        if(is_link($link)){
            FileUtils::unlink($link);
        }
        if(file_exists($target)){
            $this->setupLink($target, $link);
        }
        if($orgId){
            $link =$this->config['TEMPLATE_FOLDER'].$orgId;
            $target = $path."data/template";
            if(is_link($link)){
                FileUtils::unlink($link);
            }
            if(file_exists($target)){
                $this->setupLink($target, $link);
            }
        }
        $apps = $path."view/apps/";
        $flag = 0;
        $folderCount = 0;
        if(file_exists($apps) && is_dir($apps)){
            $files = new FileSystemIterator($apps);
            foreach ($files as $file) {
                if($file->isDir()){
                    $folderCount += 1;
                }
            }
            if ($folderCount == 1){
                foreach ($files as $file) {
                    if($file->isDir()){
                        $target = $file->getPathName();
                        $link = $this->config['APPS_FOLDER'].$file->getFilename();
                        if(is_link($link)){
                            unlink($link);
                        }
                        if(file_exists($target)){
                            $this->setupLink($target, $link);
                            $this->executeCommands($target);
                            $flag = 1;
                        }
                    }
                }
            }
            else if ($folderCount > 1){
                throw new Exception("Cannot setup symlink as more than one app exists");
            }
            if($flag == 1){
                $runDiscover = $this->executePackageDiscover();
            }
        }
    }

    private function executePackageDiscover(){
        $app = $this->config['APPS_FOLDER'];
        $command_one = "cd ".$app."../bos/";
        $command = $app."../bos/";
        if(!file_exists($command."src/client/local.js")){
            copy($command.'src/client/local.js.example', $command.'src/client/local.js');
        }
        $command_two = "npm run package:discover";
        $output = ExecUtils::execCommand($command_one." && ".$command_two);
        $this->logger->info("PAckage Discover .. \n". print_r($output, true));
           
    }

    private function executeCommands($link){
        $link = str_replace(' ', '\ ', $link);
        $command_one = "cd ".$link;
        $command_two = "npm install";
        $command_three = "npm run build";
        $command = $command_one." && ".$command_two;
        $output = ExecUtils::execCommand($command);
        $this->logger->info("Executing command $command .. \n". print_r($output, true));
        $command = $command_one." && ".$command_three;
        $output = ExecUtils::execCommand($command);
        $this->logger->info("Executing command $command .. \n". print_r($output, true));
    }

    private function setupLink($target, $link){
        if(file_exists($link)){
            throw new Exception("Cannot setup $target, as folder $link already exists");
        }
        if(!is_link($link)){
            $this->logger->info("setting up link $link with $target");
            FileUtils::symlink($target, $link);
        }
    }
    private function performMigration($appPath, $data){
        $appName = $data['name'];
        $appId = $data['uuid'];
        $description = isset($data['description']) ? $data['description'] : $appName;
        $migration = new Migration($this->config, $appName, $appId, $description);
        if(file_exists($appPath."/data/migrations/")){
            $migrationFolder = $appPath."/data/migrations/";
            $fileList = array_diff(scandir($migrationFolder), array(".", ".."));
            if(count($fileList)>0){
                $migration->migrate(realpath($migrationFolder));
            }
        }
    }

    private function createRole(&$yamlData){
        if (isset($yamlData['role'])){
            if (!(isset($yamlData['org'][0]['uuid']))){
                $this->logger->warn("Organization not provided not processing roles!");
                return;
            }
            $appUuid = $yamlData['app'][0]['uuid'];
            $appId = $this->getIdFromUuid('ox_app', $appUuid);
            $params['orgId'] = $yamlData['org'][0]['uuid'];
            foreach($yamlData['role'] as &$roleData){
                $role = $roleData;
                if(!isset($role['name'])){
                    $this->logger->warn("Role name not provided continuing!");
                    continue;
                }
                $role['uuid'] = isset($role['uuid'])?$role['uuid']:UuidUtil::uuid();
                $result = $this->roleService->saveRole($params, $role, $role['uuid']);
                $roleData['uuid'] = $role['uuid'];
            }
        }
    }

    private function processOrg(&$orgData){
        if(!isset($orgData['uuid'])){
            $orgData['uuid'] = UuidUtil::uuid();
        }
        if(!isset($orgData['contact'])){
            $orgData['contact']=array();
            $orgData['contact']['username'] = str_replace('@', '.', $orgData['email']);
            $orgData['contact']['firstname'] = 'Admin';
            $orgData['contact']['lastname'] = 'User';
            $orgData['contact']['email'] = $orgData['email'];
        }
        if(!isset($orgData['preferences'])){
            $orgData['preferences']= '{}';
        }
        $orgdata = $orgData;
        $result = $this->organizationService->saveOrganization($orgdata);
        if($result == 0){
            throw new ServiceException("Organization could not be saved", 'org.not.saved');
        }
        $orgData['uuid'] = $orgdata['uuid'];
        return $orgData;
    }
    private function checkAppExists(&$appdata){
        try{
            $queryString = "Select ap.uuid,ap.name as name from ox_app as ap where ap.name = :appName";
            $params = array("appName" => $appdata['name']);

            if(isset($appdata['uuid'])){
                $queryString .= " OR ap.uuid = :appId";
                $params['appId'] = $appdata['uuid'];
            }

            $result = $this->executeQueryWithBindParameters($queryString, $params)->toArray();
            if(count($result) == 0){
                $data = $this->createApp($appdata, true);
                $appdata['uuid'] = $data['form']['uuid'];
            }
            else
            {
                if(isset($appdata['uuid'])){
                    if($appdata['uuid'] == $result[0]['uuid']){
                        if($appdata['name'] != $result[0]['name']){
                            $this->updateApp($appdata['uuid'], $appdata);
                        }
                    }else{
                        throw new ServiceException("App Already Exists", 'duplicate.app');
                    }
                }else{
                    if($appdata['name'] == $result[0]['name']){
                        $appdata['uuid'] = $result[0]['uuid'];
                    }
                }
            }
            return $appdata['uuid'];
        }catch(Exception $e){
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
    }

    private function createAppPrivileges($appUuid, $privilegedata, $orgId = null)
    {
        $privilegearray = array_unique(array_column($privilegedata, 'name'));
        $list = "'" . implode( "', '", $privilegearray) . "'";

        $appId = $this->getIdFromUuid('ox_app',$appUuid);
        $this->privilegeService->saveAppPrivileges($appId, $privilegedata);
    }

    public function getAppList($filterParams = null)
    {
        $pageSize = 20;
        $offset = 0;
        $where = "";
        $sort = "name";
        $cntQuery ="SELECT count(id) FROM `ox_app`";
        if (count($filterParams) > 0 || sizeof($filterParams) > 0) {
            $filterArray = json_decode($filterParams['filter'], true);
            if (isset($filterArray[0]['filter'])) {
                $filterlogic = isset($filterArray[0]['filter']['logic']) ? $filterArray[0]['filter']['logic'] : "AND" ;
                $filterList = $filterArray[0]['filter']['filters'];
                $where = " WHERE ".FilterUtils::filterArray($filterList, $filterlogic);
            }
            if (isset($filterArray[0]['sort']) && count($filterArray[0]['sort']) > 0) {
                $sort = $filterArray[0]['sort'];
                $sort = FilterUtils::sortArray($sort);
            }
            $pageSize = $filterArray[0]['take'];
            $offset = $filterArray[0]['skip'];
        }
        $where .= strlen($where) > 0 ? " AND status!=1" : "WHERE status!=1";
        $sort = " ORDER BY ".$sort;
        $limit = " LIMIT ".$pageSize." offset ".$offset;
        $resultSet = $this->executeQuerywithParams($cntQuery.$where);
        $count=$resultSet->toArray()[0]['count(id)'];
        $query ="SELECT * FROM `ox_app` ".$where." ".$sort." ".$limit;
        $resultSet = $this->executeQuerywithParams($query);
        $result = $resultSet->toArray();
        for ($x=0;$x<sizeof($result);$x++) {
            $result[$x]['start_options'] = json_decode($result[$x]['start_options'], true);
        }
        return array('data' => $result,
           'total' => $count);
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
        $form->exchangeArray($data);
        $form->validate();
        $count = 0;
        $this->beginTransaction();
        try {
            $count = $this->table->save($form);
            if ($count == 0) {
                $this->rollback();
                throw new ServiceException("App could not be saved", "app.save.failed" );
            }
            $this->commit();
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
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
            try{
                $this->beginTransaction();
                $insert =  "INSERT into ox_app_registry (app_id, org_id, start_options)
                select ap.id, org.id, ap.start_options from ox_app as ap, ox_organization as org where ap.uuid = :appId and org.uuid = :orgId";
                $params = array("appId" => $appId, "orgId" => $orgId);
                $result = $this->executeUpdateWithBindParameters($insert, $params);
                $this->commit();
                return $result->getAffectedRows();
            }catch(Exception $e){
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
                    $data['name'] = isset($apps[$x]['name']) ? $apps[$x]['name'] : null ;
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
                    $data['uuid'] = isset($apps[$x]['uuid'])? $apps[$x]['uuid'] : UuidUtil::uuid();
                    $form->exchangeArray($data);
                    $form->validate();
                    $count += $this->table->save($form);
                } else {
                    $start_options = isset($apps[$x]['options']) ? json_encode($apps[$x]['options']) : null;
                    $category = isset($apps[$x]['category']) ? $apps[$x]['category'] : null;
                    $isdefault = isset($apps[$x]['isdefault']) ? $apps[$x]['isdefault'] : 0;
                    $modified_by = 1;
                    $update = "UPDATE ox_app SET `start_options` = '".$start_options."', `category` = '".$category."',`isdefault` = ".$isdefault.", `date_modified` = '".date('Y-m-d H:i:s')."',`modified_by` = ".$modified_by." WHERE name = '".$apps[$x]['name']."'";
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
        } catch(Exception $e) {
            $this->rollback();
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }
        return 1;
    }

    public function addToAppRegistry($data)
    {
        $this->logger->debug("Adding App to registry");
        try{
            $data['orgId'] = isset($data['orgId']) ? $data['orgId'] : AuthContext::get(AuthConstants::ORG_UUID) ;
            $app = $this->table->getByName($data['app_name']);
            return $this->createAppRegistry($app->uuid, $data['orgId']);
        }catch(Exception $e){
            $this->logger->error($e->getMessage(), $e);
            throw $e;
        }

    }
}
