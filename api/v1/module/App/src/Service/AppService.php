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
use Oxzion\Utils\BosUtils;
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
    /**
     * @ignore __construct
     */
    public function __construct($config, $dbAdapter, AppTable $table, WorkflowService $workflowService, FormService $formService, FieldService $fieldService, $organizationService, $entityService)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
        $this->workflowService = $workflowService;
        $this->formService = $formService;
        $this->fieldService = $fieldService;
        $this->organizationService = $organizationService;
        $this->entityService = $entityService;
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
        $data['status'] = App::PUBLISHED;
        $form->exchangeArray($data);
        $form->validate();
        $count = 0;
        $this->beginTransaction();
        try {
            $count = $this->table->save($form);
            if ($count == 0) {
                $this->rollback();
                return 0;
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

    private function updateymlfororg($yamldata, $modifieddata, $path){
        $filename = "application.yml";
        if(!(array_key_exists('uuid',$yamldata['org']))){
            $yamldata['org'][0]['uuid'] = $modifieddata['uuid'];
        }
        $new_yaml = Yaml::dump($yamldata, 2);
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
        $new_yaml = Yaml::dump($yamldata, 2);
        file_put_contents($path.$filename, $new_yaml);
    }

    private function collectappfieldsdata($data){
        if(!(array_key_exists('type',$data[0]))){
            $data[0]['type'] = 2;
        }
        if(!(array_key_exists('category',$data[0]))){
            $data[0]['category'] = "EXAMPLE_CATEGORY";
        }
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
        $this->beginTransaction();
        try{
            $appUuid = $this->checkAppExists($appData);
            $this->updateymlforapp($ymlData, $appData, $path);

            $queryString = "UPDATE ox_app SET status = ".App::IN_DRAFT." WHERE ox_app.uuid = :appUuid";
            $params = array("appUuid" => $appUuid);
            $result = $this->executeQueryWithBindParameters($queryString, $params);

            $orgUuid = null;
            if(isset($ymlData['org'])){
                $data = $this->processOrg($ymlData['org'][0],NULL);
                $orgUuid = $data['uuid'];
                $this->updateymlfororg($ymlData, $data, $path);
                $result = $this->createAppRegistry($appUuid, $ymlData['org'][0]['uuid']);
            }
            if(isset($ymlData['privilege'])){
                $this->createAppPrivileges($appUuid, $ymlData['privilege'], $orgUuid);
            }
            $this->performMigration($path, $ymlData['app'][0]);
            $appName = $ymlData['app'][0]['name'];
            $this->setupLinks($path, $appName, $appUuid, $orgUuid);
            $this->processWorkflow($ymlData, $path, $orgUuid);
            //check if menu exists and add to app menu table
            //check if workflow given if so deployworkflow
            //check form fields if not found add to fields table fpr the app.
            //if job given setup quartz job
            // $this->updateyml($ymldata, $data['form'], $path);
            //Move the app folder from given path to clients folder
        }catch(Exception $e){
            $this->rollback();
            throw $e;
        }
    }

    private function processWorkflow(&$yamlData, $path,  $orgUuid = NULL){
        if(isset($yamlData['workflow'])) {
            $appUuid = $yamlData['app'][0]['uuid'];
            $orgId = $this->getIdFromUuid('ox_organization', $orgUuid);
            $workflowData = $yamlData['workflow'];
            foreach ($workflowData as $value) {
                $this->checkWorkflowData($value);
                $entity = $this->entityService->getEntityByName($yamlData['app'][0]['uuid'], $value['entity']);
                if(!$entity) {
                    $entity = array('name' => $value['entity']);
                    $result = $this->entityService->saveEntity($yamlData['app'][0]['uuid'], $entity);
                }
                if(isset($value['uuid']) && isset($entity['id'])) {
                    $bpmnFilePath = $path."contents/workflows/".$value['bpmn_file'];
                    $result = $this->workflowService->deploy($bpmnFilePath, $appUuid, $value, $entity['id']);
                }
            }
        }
    }

    private function checkWorkflowData(&$data){
        $data['uuid'] = isset($data['uuid'])?$data['uuid']:UuidUtil::uuid();
        if(!(isset($data['bpmn_file']))){
            throw new Exception("Bpmn file does not exist");
        }
        if(!isset($data['name'])){
            $data['name'] = str_replace('.bpmn', '', $data['bpmn_file']); // Replaces all .bpmn with no space.
            $data['name'] = str_replace(' ', '_', $data['bpmn_file']); // Replaces all spaces
            $data['name'] = preg_replace('/[^A-Za-z0-9\_]/', '', $data['name'], -1); // Removes special chars.
        }
        if(!isset($data['entity'])){
            throw new Exception("Entity is not defined in yml.");
        }
    }

    private function setupLinks($path, $appName, $appId, $orgId = null){
        $link = $this->config['DELEGATE_FOLDER'].$appId;
        $target = $path."/data/delegate";
        if(is_link($link)){
            FileUtils::unlink($link);
        }
        if(file_exists($target)){
            $this->setupLink($target, $link);
        }
        if($orgId){
            $link =$this->config['TEMPLATE_FOLDER'].$orgId;
            $target = $path."/data/template";
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
                            $this->executeCommands($link);
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
        $command_two = "npm install";
        $command_three = "npm run build";
        $command_four = "npm run package:discover";
        BosUtils::execCommand($command_one." && ".$command_two." && ".$command_three." && ".$command_four);
    }

    private function executeCommands($link){
        $link = str_replace(' ', '\ ', $link);
        $command_one = "cd ".$link;
        $command_two = "npm install";
        $command_three = "npm run build";
        BosUtils::execCommand($command_one." && ".$command_two." && ".$command_three);
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
        $result = $this->organizationService->saveOrganization($orgData);
        if($result == 0){
            throw new ServiceException("Organization could not be saved");
        }
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

    private function createAppPrivileges($appUuid, &$privilegedata, $orgId = null)
    {
        $privilegearray = array_unique(array_column($privilegedata, 'name'));
        $list = "'" . implode( "', '", $privilegearray) . "'";

        $idresult = $this->getIdFromUuid('ox_app',$appUuid);

        // find the records which are not from the list of privileges for the particular app
        $queryString = "SELECT count(*) FROM ox_privilege as pr WHERE pr.app_id = :appid AND pr.name NOT IN (".$list.")";
        $params = array("appid" => $idresult);
        $result = $this->executeQueryWithBindParameters($queryString, $params)->toArray();

        if(!empty($result[0]['count(*)'])){
            // delete from ox_app_menu
            $queryString = "UPDATE ox_app_menu AS mn INNER JOIN ox_privilege AS pr ON mn.privilege_id = pr.id INNER JOIN ox_app AS ap ON pr.app_id = ap.id SET mn.privilege_id = NULL WHERE mn.app_id = :appid AND pr.name NOT IN (".$list.")";
            $params = array("appid" => $idresult);
            $result = $this->executeQueryWithBindParameters($queryString, $params);

            //delete from ox_role_privilege
            $queryString = "DELETE rp FROM ox_role_privilege as rp INNER JOIN ox_app as ap ON rp.app_id = ap.id WHERE ap.uuid = :appUuid AND rp.privilege_name NOT IN (".$list.")";
            $params = array("appUuid" => $appUuid);
            $result = $this->executeQueryWithBindParameters($queryString, $params);

            //delete from ox_privilege
            $queryString = "DELETE FROM ox_privilege WHERE app_id = :appid AND name NOT IN (".$list.")";
            $params = array("appid" => $idresult);
            $result = $this->executeQueryWithBindParameters($queryString, $params);
        }

        //get difference of the list and table privileges
        $queryString = "SELECT pr.name FROM ox_privilege as pr
        WHERE pr.app_id = :appid AND pr.name IN (".$list.")";
        $params = array("appid" => $idresult);
        $result = $this->executeQueryWithBindParameters($queryString, $params)->toArray();
        $existingprivileges = array_column($result, 'name');
        $privilegesToBeAdded = array_diff($privilegearray, $existingprivileges);
        $sql = $this->getSqlObject();

        //if any new privileges to be added
        if(!(empty($privilegesToBeAdded))){
            foreach ($privilegesToBeAdded as $key => $value) {
                $insert = $sql->insert('ox_privilege');
                $permission = 3;
                $insert_data = array('name' => $value,'app_id' => $idresult,'permission_allowed' => $permission);
                $insert->values($insert_data);
                $result = $this->executeUpdate($insert);

                // $queryString = "SELECT * from ox_privilege where name = '".$value."' and app_id = :appId";
                // $params = array('appId' => $idresult);
                // $result = $this->executeQueryWithBindParameters($queryString, $params)->toArray();

                $query = "INSERT into ox_role_privilege (role_id, privilege_name, permission, org_id, app_id)
                SELECT r.id, '".$value."', ".$permission.", r.org_id, reg.app_id
                FROM ox_role AS r INNER JOIN
                ox_app_registry AS reg ON r.org_id = reg.org_id
                WHERE reg.app_id = :appId and r.name = 'ADMIN'";
                $params = array('appId' => $idresult);
                $result = $this->executeUpdateWithBindParameters($query, $params);
            }
            // $queryString = "SELECT count(*) from ox_role_privilege where app_id = :appId";
            // $params = array('appId' => $idresult);
            // $result = $this->executeQueryWithBindParameters($queryString, $params)->toArray();
            // print_r($result);exit;
        }
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
        $data['status'] = App::PUBLISHED;
        $form->exchangeArray($data);
        $form->validate();
        $count = 0;
        $this->beginTransaction();
        try {
            $count = $this->table->save($form);
            if ($count == 0) {
                $this->rollback();
                return 0;
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
            $insert =  "INSERT into ox_app_registry (app_id, org_id, start_options)
            select ap.id, org.id, ap.start_options from ox_app as ap, ox_organization as org where ap.uuid = :appId and org.uuid = :orgId";
            $params = array("appId" => $appId, "orgId" => $orgId);
            $result = $this->executeUpdateWithBindParameters($insert, $params);
            // $queryString = "SELECT * FROM ox_app_registry AS ar INNER JOIN ox_app as ap on ap.id = ar.app_id INNER JOIN ox_organization as org on org.id = ar.org_id where ap.uuid = :appId and org.uuid = :orgId";
            // $params = array("appId" => $appId, "orgId" => $orgId);
            // $result = $this->executeQueryWithBindParameters($queryString, $params)->toArray();
            // print_r($result);exit;
            return $result->getAffectedRows();
        }
        return 0;
    }

    public function getFields($appId, $workflowId = null)
    {
        $filterArray = array();
        if (isset($workflowId)) {
            $filterArray['workflow_id'] = $workflowId;
        }
        return $this->fieldService->getFields($appId, $filterArray);
    }

    public function getForms($appId, $workflowId = null)
    {
        $filterArray = array();
        if (isset($workflowId)) {
            $filterArray['workflow_id'] = $workflowId;
        }
        return $this->formService->getForms($appId, $filterArray);
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
