<?php
namespace App\Service;

use App\Model\App;
use App\Model\AppTable;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\Service\AbstractService;
use Oxzion\ValidationException;
use Zend\Log\Logger;
use Exception;
use Ramsey\Uuid\Uuid;
use Oxzion\Utils\FileUtils;
use Oxzion\Utils\YMLUtils;
use Oxzion\Utils\ZipUtils;
use Oxzion\Service\WorkflowService;
use Oxzion\Service\FormService;
use Oxzion\Service\FieldService;
use Oxzion\Utils\FilterUtils;
use Oxzion\ServiceException;
use Symfony\Component\Yaml\Yaml;

class AppService extends AbstractService
{
    protected $config;
    private $table;
    protected $workflowService;
    protected $fieldService;
    protected $formService;
    protected $organizationService;
    /**
     * @ignore __construct
     */
    public function __construct($config, $dbAdapter, AppTable $table, WorkflowService $workflowService, FormService $formService, FieldService $fieldService,$organizationService,Logger $log)
    {
        parent::__construct($config, $dbAdapter,$log);
        $this->table = $table;
        $this->workflowService = $workflowService;
        $this->formService = $formService;
        $this->fieldService = $fieldService;
        $this->organizationService = $organizationService;
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
                $this->logger->err($e->getMessage()."-".$e->getTraceAsString());
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
            $this->logger->err($e->getMessage()."-".$e->getTraceAsString());
            throw $e;
        }
    }
    public function createApp($data,$returnForm = false){
        $form = new App();
        $data['uuid'] = isset($data['uuid'])?$data['uuid']:Uuid::uuid4()->toString();
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
            $this->logger->err($e->getMessage()."-".$e->getTraceAsString());
            throw $e;
        }
        if($returnForm === true)
            return array('form' => $form->toArray(),'count' => $count);
        else
            return $count;
    }

    private function updateyml($yamldata, $modifieddata, $path){
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
                        if(!isset($yaml['app'][0]['uuid'])){
                            $yaml['app'][0]['uuid'] = Uuid::uuid4();
                        }
                        return $yaml;
                    }
                }
            }
        }
    }

    public function deployApp($path){
        $ymldata = $this->loadAppDescriptor($path);
        $appdata = $this->collectappfieldsdata($ymldata['app'])[0];
        $this->beginTransaction();
        try{
            $appUuid = $appdata['uuid'];
            if (!$this->checkAppExists($appUuid)) {
                $data = $this->createApp($appdata,true);
            }
            else{
                $this->updateApp($appUuid, $appdata);
            }
            $this->updateyml($ymldata, $data['form'], $path);
            if(isset($ymldata['org'])){
                $this->processOrg($ymldata['org'][0],NULL);
                $this->createAppRegistry($appUuid, $ymldata['org'][0]['uuid']);
            }
            // $this->checkAppPrivileges($appUuid);
            //check if privileges are in db
            //if not add to privileges table
            //and add to role_privileges table for admin role of that org
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
        // print_r($data); exit;
        return $data;
    }

    private function processOrg(&$orgData){
        if(!isset($orgData['uuid'])){
            $orgData['uuid'] = Uuid::uuid4()->toString();
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
    }
    private function checkAppExists($appUuid){
        try{
            $queryString = "Select count(id) as count from ox_app as ap where ap.uuid = :appUuid";
            $params = array("appUuid" => $appUuid);
            $result = $this->executeQueryWithBindParameters($queryString, $params)->toArray();
            return $result[0]['count'] != 0;
        }catch(Exception $e){
            $this->logger->err($e->getMessage()."-".$e->getTraceAsString());
            throw $e;
        }
    }

    //useless
    // private function checkAppPrivileges($appUuid)
    // {
    //     $app_id = $this->getIdFromUuid('ox_app', $appUuid);
    //     //check if app_id exists
    //     $queryString = "select ar.app_id from ox_privileges as ar
    //     inner join ox_app as ap on ap.id = ar.app_id";
    //     $params = array("appid" => $app_id);
    //     $resultSet = $this->executeQueryWithBindParameters($queryString, $params);
    //     $queryResult = $resultSet->toArray();
    //     if(isset($queryResult)){
    //             //check if priviledges exist
    //         $queryString = "select permission_allowed from ox_privileges as pr where pr.app_id = :appid";
    //         $params = array("appid" => $app_id);
    //         $resultSet = $this->executeQueryWithBindParameters($queryString, $params);
    //         $queryResult = $resultSet->toArray();
    //         if (empty($queryResult)) {
    //             // $insert = $sql->insert('ox_privilege');
    //             // $insert->values($data);
    //             // $this->executeUpdate($insert);
    //             return 1;
    //         }
    //         return 0;
    //     }
    // }

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
            $this->logger->err($e->getMessage()."-".$e->getTraceAsString());
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
            $this->logger->err($e->getMessage()."-".$e->getTraceAsString());
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

    // I am not doing anything here because we dont know how the app installation process will be when we do that, so I am creating a place holder to use for the future.
    // The purpose of this function is to give permission and privileges to the app that is getting istalled in the OS

    /**
     * Deploy App API using YAML File
     * @param $appFolder </br>
     * <code>
     * </code>
     * @return array Returns a JSON Response with Status Code.</br>
     * <code> status : "success|error"
     * </code>
     */
    public function getDataFromDeploymentDescriptorUsingYML($appFolder)
    {
        $appUploadFolder = $appFolder;
        try {
            $appUploadedZipFile = $appUploadFolder . "/uploads/App.zip";
            $destinationFolder = $appUploadFolder . "/temp";
            ZipUtils::extract($appUploadedZipFile, $destinationFolder);
            $fileName = file_get_contents($appUploadFolder . "/temp/App/web.yml");
        } catch (Exception $e) {
            $this->logger->err($e->getMessage()."-".$e->getTraceAsString());
            throw $e;
        }
        $ymlArray = YMLUtils::ymlToArray($fileName);
        //Code to insert the details of the app to the app table. Returns 1 or 0 for success or failure
        $app = $this->insertAppDetail($ymlArray['config']);
        if ($app === 0) {
            return 0;
        }
        $appPrivileges = $this->applyAppPrivilege($ymlArray['config'], $app);

        if ($appPrivileges === 0) {
            return 0;
        }
        $count = $this->getFormInsertFormat($ymlArray['config']);
        if ($count === 1) {
            return 1;
        } else {
            return 0;
        }
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
            $this->logger->err($e->getMessage()."-".$e->getTraceAsString());
            throw $e;
        }
        return $id;
    }

    /**
     * @param $appData
     * @param $app
     * @return int
     */
    public function applyAppPrivilege($appData, $app)
    {
        $count = 0;
        if (!empty($appData['app-privilege'])) {
            foreach ($appData['app-privilege'] as $privilege) {
                try {
                    $formData['role_id'] = $privilege['privilege-role'];
                    $formData['privilege_name'] = $privilege['privilege-name'];
                    $formData['permission'] = $privilege['privilege-permission'];
                    $formData['app_id'] = $app;
                    $count = $this->createAppPrivileges($formData);
                } catch (ValidationException $e) {
                    $this->logger->err($e->getMessage()."-".$e->getTraceAsString());
                    throw $e;
                }
            }
        }
        return $count;
    }

    private function createAppPrivileges($data)
    {
        try{
            $sql = $this->getSqlObject();
            $queryString = "select * from ox_role_privilege where role_id=? and privilege_name=? and app_id=? and permission=?";
            $queryParams = array($data['role_id'],$data['privilege_name'],$data['app_id'],$data['permission']);
            $resultSet = $this->executeQueryWithBindParameters($queryString, $queryParams)->toArray();
            $queryResult = $resultSet;
            if (empty($queryResult)) { //Checking to see if we already have entry made to the database
                $insert = $sql->insert('ox_role_privilege');
                $insert->values($data);
                $this->executeUpdate($insert);
                return 1;
            }
            return 0;
        }catch(Exception $e){
            $this->logger->err($e->getMessage()."-".$e->getTraceAsString());
            throw $e;
        }
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
            $this->logger->err($e->getMessage()."-".$e->getTraceAsString());
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
            $result = $this->executeUpdateWithBindParameters($insert, $params);
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
                    $data['uuid'] = isset($apps[$x]['uuid'])? $apps[$x]['uuid'] : Uuid::uuid4()->toString();
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
            $this->logger->err($e->getMessage()."-".$e->getTraceAsString());
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
            $this->logger->err($e->getMessage()."-".$e->getTraceAsString());
            throw $e;
        }
        
    }
}
