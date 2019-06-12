<?php
namespace App\Service;

use App\Model\App;
use App\Model\AppTable;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\Service\AbstractService;
use Oxzion\ValidationException;
use Exception;
use Ramsey\Uuid\Uuid;
use Oxzion\Utils\FileUtils;
use Oxzion\Utils\YMLUtils;
use Oxzion\Utils\ZipUtils;
use Oxzion\Service\WorkflowService;
use Oxzion\Service\FormService;
use Oxzion\Service\FieldService;
use Oxzion\Utils\FilterUtils;



class AppService extends AbstractService
{

    protected $config;
    private $table;
    protected $workflowService;
    protected $fieldService;
    protected $formService;

    /**
     * @ignore __construct
     */
    public function __construct($config, $dbAdapter, AppTable $table, WorkflowService $workflowService, FormService $formService, FieldService $fieldService)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
        $this->workflowService = $workflowService;
        $this->formService = $formService;
        $this->fieldService = $fieldService;
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
        $queryString = "Select ap.name,ap.uuid,ap.description,ap.type,ap.logo,ap.category,ap.date_created,ap.date_modified,ap.created_by,ap.modified_by,ap.status,ar.org_id,ar.start_options from ox_app as ap
        left join ox_app_registry as ar on ap.id = ar.app_id";
        $where = "where ar.org_id = " . AuthContext::get(AuthConstants::ORG_ID) . " AND ap.status!=1";
        $resultSet = $this->executeQuerywithParams($queryString, $where);
        return $resultSet->toArray();
    }

    public function getApp($id)
    {
        $queryString = "Select ap.name,ap.uuid,ap.description,ap.type,ap.logo,ap.category,ap.date_created,ap.date_modified,ap.created_by,ap.modified_by,ap.status,ar.org_id,ar.start_options from ox_app as ap
        left join ox_app_registry as ar on ap.id = ar.app_id";
        $where = "where ar.org_id = " . AuthContext::get(AuthConstants::ORG_ID) . " AND ap.status!=1 AND ap.id =" . $id;
        $resultSet = $this->executeQuerywithParams($queryString, $where);
        return $resultSet->toArray();
    }

    public function getAppList($filterParams = null)
    {
        $pageSize = 20;
        $offset = 0;
        $where = "";
        $sort = "name";
            
        $cntQuery ="SELECT count(id) FROM `ox_app`";
          
            if(count($filterParams) > 0 || sizeof($filterParams) > 0){
                $filterArray = json_decode($filterParams['filter'],true); 
                if(isset($filterArray[0]['filter'])){
                  $filterlogic = isset($filterArray[0]['filter']['logic']) ? $filterArray[0]['filter']['logic'] : "AND" ;
                   $filterList = $filterArray[0]['filter']['filters'];
                   $where = " WHERE ".FilterUtils::filterArray($filterList,$filterlogic);
                }
                if(isset($filterArray[0]['sort']) && count($filterArray[0]['sort']) > 0){
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
             for($x=0;$x<sizeof($result);$x++) {
                 $result[$x]['start_options'] = json_decode($result[$x]['start_options'],true);
            }
            return array('data' => $result, 
                     'total' => $count);  
    }

    public function updateApp($id, &$data)
    {
        $obj = $this->table->get($id, array());
        if (is_null($obj)) {
            return 0;
        }
        $form = new App();
        $data = array_merge($obj->toArray(), $data); //Merging the data from the db for the ID
        $data['id'] = $id;
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
            $this->rollback();
            return 0;
        }
        return $count;
    }

    public function deleteApp($id)
    {
        $obj = $this->table->get($id, array());
        if (is_null($obj)) {
            return 0;
        }
        $form = new App();
        $data = $obj->toArray();
        $data['id'] = $id;
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
            $this->rollback();
            return 0;
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
            return 0;
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
            return 0;
        }
        return $id;
    }

    /**
     * Create App Service
     * @method deployAppForOrg
     * @param array $data Array of elements as shown</br>
     * <code>
     * </code>
     * @return array Returns a JSON Response with Status Code and Created App.</br>
     * <code> status : "success|error",
     *        data : array Created App Object
     * </code>
     */
    public function deployAppForOrg($data)
    {   
        $form = new App();
        $data['created_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_created'] = date('Y-m-d H:i:s');
        $data['status'] = App::PUBLISHED;
        $data['uuid'] = Uuid::uuid4();
		if(!isset($data['org_id'])){
            return 0;
        }
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
            $id = $this->table->getLastInsertValue();
            $data['id'] = $id;
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            return 0;
        }
        return $count;
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
                    return 0;
                }
            }
        }
        return $count;
    }

    private function createAppPrivileges($data)
    {
        $sql = $this->getSqlObject();
        $queryString = "select * from ox_role_privilege ";
        $where = "where role_id = " . $data['role_id'] . " and privilege_name = '" . $data['privilege_name'] . "' and app_id = " . $data['app_id'] . " and permission = " . $data['permission'];
        $resultSet = $this->executeQuerywithParams($queryString, $where);
        $queryResult = $resultSet->toArray();
        if (empty($queryResult)) { //Checking to see if we already have entry made to the database
            $insert = $sql->insert('ox_role_privilege');
            $insert->values($data);
            $this->executeUpdate($insert);
            return 1;
        }
        return 0;
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
            return 0;
        }
        return $count;
    }

    private function createAppRegistry($data)
    {
        $sql = $this->getSqlObject();
//Code to check if the app is already registered for the organization
        $queryString = "select * from ox_app_registry ";
        $where = "where app_id = " . $data['app_id'] . " and org_id = " . $data['org_id'] . " ";
        $resultSet = $this->executeQuerywithParams($queryString, $where);
        $queryResult = $resultSet->toArray();

        if (empty($queryResult)) {
            $insert = $sql->insert('ox_app_registry');
            $insert->values($data);
            $result = $this->executeUpdate($insert);
            return 1;
        }

        return "App already registered to the Organization.";
    }
    public function deployWorkflow($appId, $params, $file = null)
    {
        if (isset($file)) {
            $this->workflowService->deploy($file, $appId, $params);
        } else {
            return 0;
        }
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
            $data['name'] = $apps[$x]['name'];
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
                    $data['name'] = $apps[$x]['name'];
                    $data['category'] = $apps[$x]['category'];
                    $data['isdefault'] = $apps[$x]['isdefault'];
                    $data['start_options'] = json_encode($apps[$x]['options']);
                    //this API call is done by the server hence hardcoding the created by value
                    $data['created_by'] = 1;
                    $data['date_created'] = date('Y-m-d H:i:s');
                    $data['status'] = App::PUBLISHED;
                    $data['type'] = App::PRE_BUILT;
                    $data['uuid'] = Uuid::uuid4();
                    $form->exchangeArray($data);
                    $form->validate();
                    $count += $this->table->save($form);
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
            // print_r($e->getMessage());
            $this->rollback();
            return 0;
        }

        return $count;
    }
    public function getAssignments($appId){
        $assignments = $this->workflowService->getAssignments($params['appId']);
    }
}
