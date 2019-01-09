<?php
namespace App\Service;

use Bos\Service\AbstractService;
use App\Model\AppTable;
use App\Model\App;
use Bos\Auth\AuthContext;
use Bos\Auth\AuthConstants;
use Bos\ValidationException;
use Zend\Db\ResultSet\ResultSet;
use PHPUnit\DbUnit\DataSet\YamlDataSet;
use \PhpZip;
use Oxzion\Service\FormService;
use Bos\Service\UserService;
use Exception;
use Symfony\Component\Yaml\Parser;

class AppService extends AbstractService{

    private $table;
    private $formService;
    protected $config;
    /**
     * @ignore __construct
     */
    public function __construct($config, $dbAdapter, AppTable $table, FormService $formService) {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
        $this->formService = $formService;
        $this->config = $config;
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
    public function getApps() {
        $queryString = "Select ap.id, ap.name, ap.uuid, ap.description, ap.type, ap.logo, ap.date_created, ap.date_modified from ox_app as ap 
        left join ox_app_registry as ar on ap.id = ar.app_id
        left join ox_role_privilege as rp on ar.app_id = rp.app_id";
        $where = "where rp.org_id = " . AuthContext::get(AuthConstants::ORG_ID) . " and rp.role_id IN (12)";
        $group = "group by rp.app_id";
        $resultSet = $this->executeQuerywithParams($queryString, $where, $group);
        return $resultSet->toArray();
    }

    /**
     * Create App Service
     * @method installAppForOrg
     * @param array $data Array of elements as shown</br>
     * <code>
     * </code>
     * @return array Returns a JSON Response with Status Code and Created App.</br>
     * <code> status : "success|error",
     *        data : array Created App Object
     * </code>
     */
    public function installAppForOrg($data) {
        $form = new App();
        $data['uuid'] = uniqid();
        $data['name'] = $data['name'];
        $data['description'] = $data['description'];
        $data['type'] = $data['type'];
        $data['logo'] = $data['logo'] ? $data['logo'] : "defult_app.png";
        $data['date_created'] = date('Y-m-d H:i:s');
        $data['date_modified'] = date('Y-m-d H:i:s');
        $form->exchangeArray($data);
        $form->validate();
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->save($form);
            if($count == 0) {
                $this->rollback();
                return 0;
            }
            $id = $this->table->getLastInsertValue();
//Code to add the app registry and the app privilages. I need to find a way to get the privileges and permissions to view certain apps. This I think will come when the developer creates a app and installs it from the background.
            $appData['app_id'] = $id;
            $appData['org_id'] = AuthContext::get(AuthConstants::ORG_ID);
            // $this->createAppRegistry($appData); //Create App Registry
//Adding more data to the array to pass it to the role_privilege table
            $appData['role_id'] = 1;
            $appData['privilege_name'] = "MANAGE_APP";
            $appData['permission'] = 15;
            // $this->createAppPrivileges($appData); //Create App Registry;  Commenting at this point to wait for the actual installation functionality is implemented.
            $this->commit();
        } catch(Exception $e) {
            $this->rollback();
            return 0;
        }
        return $count;
    }


    private function createAppRegistry($data) {
        $sql = $this->getSqlObject();
//Code to check if the app is already registered for the organization
        $queryString = "select * from ox_app_registry ";
        $where = "where app_id = " . $data['app_id'] . " and org_id = " . $data['org_id'] . " ";
        $resultSet = $this->executeQuerywithParams($queryString, $where);
        $queryResult = $resultSet->toArray();

        if (empty($queryResult)) { //Checking to see if we already have entry made to the database
            $insert = $sql->insert('ox_app_registry');
            // $data = array('group_id'=>$id['id'],'announcement_id'=>$announcementId);
            $insert->values($data);
            $result = $this->executeUpdate($insert);
            return 0;
        }

        return "App already registered to the Organization.";
    }

// I am not doing anything here because we dont know how the app installation process will be when we do that, so I am creating a place holder to use for the future.
// The purpose of this function is to give permission and privileges to the app that is getting istalled in the OS
    private function createAppPrivileges($data) {
        $sql = $this->getSqlObject();
        $select = $sql->update('ox_role_privilege')->set($data)
            ->where(array('ox_role_privilege.alert_id' => $data['alert_id'],'ox_role_privilege.user_id' => $data['user_id']));
        $result = $this->executeUpdate($select);
        return 0;
    }


    /**
     * Extract the zip file from the upload form
     * @method extractZipFilefromAppUploader
     * @param array $fileName Name of the file, $destinationFolder Folder to which the extracted App file should save</br>
     * <code>
     * </code>
     * @return array Returns the file object.</br>
     * <code> status : "success|error",
     *        data : File Object
     * </code>
     */
    public function extractZipFilefromAppUploader($fileName, $destinationFolder) {
        try {
            $zipFile = new \PhpZip\ZipFile();
            $zipFile->openFile($fileName); // open archive from file
            $extract = $zipFile->extractTo($destinationFolder); // extract files to the specified directory
        } catch (Exception $e) {
            return 0;
        }
        return $extract;
    }

    /**
     * Parse the XML File and return a array
     * @method xmlToArrayParser
     * @param array $fileName Name of the file, $destinationFolder Folder to which the extracted App file should save</br>
     * <code>
     * </code>
     * @return array Returns the file object.</br>
     * <code> status : "success|error",
     *        data : File Object
     * </code>
     */
    public function xmlToArrayParser($file) {
        return $xmlArray = json_decode(json_encode(simplexml_load_string($file)), TRUE);
    }

    public function ymlToArrayParser($file) {
        $yml = new Parser();
        return $parsed = $yml->parse($file);
    }

    public function getFormInsertFormat($formArray) {
        if(!empty($formArray['app-form-assign'])) {
            $formData['app_id'] = $formArray['app-id'];
            $formData['uuid'] = $formArray['app-uuid'];
            $formData['name'] = $formArray['app-form-assign']['form-name'];
            $formData['description'] = $formArray['app-form-assign']['form-description'];
            if(!empty($formArray['app-form-assign']['form-statuslist'])) {
//{"data":{"1":"RCA - Not Done","2":"In Progress","3":"Audit","102":"Closed"}}
                foreach($formArray['app-form-assign']['form-statuslist'] as $statusArray) {
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

    public function getAppUploadFolder() {
        return $upload = $this->config["APP_UPLOAD_FOLDER"];
    }
}
?>