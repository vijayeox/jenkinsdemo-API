<?php
namespace Oxzion\Service;

use Exception;
use Oxzion\AccessDeniedException;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;
use Oxzion\Messaging\MessageProducer;
use Oxzion\Model\Account;
use Oxzion\Model\AccountTable;
use Oxzion\Security\SecurityManager;
use Oxzion\ServiceException;
use Oxzion\OxServiceException;
use Oxzion\EntityNotFoundException;
use Oxzion\Service\AbstractService;
use Oxzion\Service\EntityService;
use Oxzion\Utils\FileUtils;
use Oxzion\Utils\FilterUtils;
use Oxzion\Utils\UuidUtil;
use Oxzion\ValidationException;
use Oxzion\Utils\ArrayUtils;
use Oxzion\Model\User;
use Oxzion\Model\Person;

class AccountService extends AbstractService
{
    protected $table;
    private $userService;
    private $roleService;
    protected $modelClass;
    private $messageProducer;
    private $privilegeService;
    private $organizationService;
    private $entityService;
    static $userField = array('name' => 'ox_user.name', 'id' => 'ox_user.id', 'city' => 'ox_address.city', 'country' => 'ox_address.country', 'address' => 'ox_address.address1', 'address2' => 'ox_address.address2', 'state' => 'ox_address.state');
    static $groupField = array('name' => 'oxg.name', 'description' => 'oxg.description');
    static $projectField = array('name' => 'oxp.name', 'description' => 'oxp.description', 'date_created' => 'oxp.date_created');
    static $announcementField = array('name' => 'oxa.name', 'description' => 'oxa.description');
    static $roleField = array('name' => 'oxr.name', 'description' => 'oxr.description');
    static $accountField = array('id' => 'og.id', 'uuid' => 'og.uuid', 'name' => 'og.name', 'preferences' => 'og.preferences', 'address1' => 'oa.address1', 'address2' => 'oa.address2', 'city' => 'oa.city', 'state' => 'oa.state', 'country' => 'oa.country', 'zip' => 'oa.zip', 'logo' => 'og.logo');

    public function setMessageProducer($messageProducer)
    {
        $this->messageProducer = $messageProducer;
    }

    /**
     * @ignore __construct
     */
    public function __construct($config, $dbAdapter, AccountTable $table, UserService $userService, RoleService $roleService, PrivilegeService $privilegeService, OrganizationService $organizationService, EntityService $entityService, MessageProducer $messageProducer)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
        $this->userService = $userService;
        $this->roleService = $roleService;
        $this->modelClass = new Account();
        $this->privilegeService = $privilegeService;
        $this->messageProducer = $messageProducer;
        $this->organizationService = $organizationService;
        $this->entityService = $entityService;
    }

    /**
     * Create Account Service
     * @method createAccount
     * @param array $data Array of elements as shown
     * <code> {
     *               id : integer,
     *               name : string,
     *               logo : string,
     *               status : String(Active|Inactive),
     *   } </code>
     * @return array Returns a JSON Response with Status Code and Created Account.
     */
    public function createAccount(&$data, $files)
    {
        $data['uuid'] = isset($data['uuid']) ? $data['uuid'] : UuidUtil::uuid();
        if (!isset($data['contact'])) {
            throw new ServiceException("Contact Person details are required", "account.contact.required", OxServiceException::ERR_CODE_PRECONDITION_FAILED);
        }
        if (is_string($data['contact'])) {
            $data['contact'] = json_decode($data['contact'], true);
        }
        if (!is_string($data['preferences'])) {
            $data['preferences'] = json_encode($data['preferences']);
        }
        $data['created_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_created'] = date('Y-m-d H:i:s');
        $data['date_modified'] = date('Y-m-d H:i:s');

        try{
            $count = 0;
            $this->beginTransaction();
            if (isset($data['type']) && $data['type'] == Account::INDIVIDUAL) {
                $data['name'] = $data['contact']['firstname']. " ". $data['contact']['lastname'];
            }else if(!isset($data['name'])){
                throw new ValidaTionException("Account name required");
            }
            $select = "SELECT count(name),oxo.status,oxo.uuid from ox_account oxo where oxo.name = '" . $data['name'] . "'";
            $result = $this->executeQuerywithParams($select)->toArray();
            if ($result[0]['count(name)'] > 0) {
                if ($result[0]['status'] == 'Inactive') {
                    $data['reactivate'] = isset($data['reactivate']) ? $data['reactivate'] : 0;
                    if ($data['reactivate'] == 1) {
                        $data['status'] = 'Active';
                        $this->logger->info("Data modified before Account Update - " . print_r($data, true));
                        $count = $this->updateAccount($result[0]['uuid'], $data, $files);
                        $this->uploadAccountLogo($result[0]['uuid'], $files);
                    } else {
                        throw new ServiceException("Account already exists would you like to reactivate?", "account.already.exists", OxServiceException::ERR_CODE_PRECONDITION_FAILED);
                    }
                } else {
                    throw new ServiceException("Account already exists", "account.exists", OxServiceException::ERR_CODE_PRECONDITION_FAILED);
                }
            }else{
                if (!isset($data['type']) || (isset($data['type']) && $data['type'] == Account::BUSINESS)) {
                    $this->organizationService->addOrganization($data);
                    $data['type'] = Account::BUSINESS;        
                }
                $count = $this->saveAccountInternal($data, $files);
                $this->messageProducer->sendTopic(json_encode(array('accountName' => $data['name'], 'status' => $data['status'])), 'ACCOUNT_ADDED');
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }    
        
        return $count;
    }

    public function registerAccount(&$data){
        $this->logger->info("Register Account ----".print_r($data,true));
        // if(!isset($data["business_role"])){
        //     throw new ServiceException("Business Role not specified", "business.role.required");
        // }
        if(!isset($data['type'])){
            throw new ServiceException("Business Type not specified", "business.type.required", OxServiceException::ERR_CODE_PRECONDITION_FAILED);
        }
        if (!ArrayUtils::isKeyDefined($data, 'username')) {
            $data['username'] = $data['email'];
        }
        $result = $this->userService->checkUserExists($data);
        if($result == 1){
            return $result;
        }
        $user = new User($data);
        $person = new Person($data);
        $data['contact'] = array_merge($user->toArray(),$person->toArray());
        $params = $data;
        $params['preferences'] = array();
        $appId = null;
        if (isset($params['app_id'])){
            $appId = $this->getAppId($params['app_id']);
            $params['app_id'] = $appId;
        }
        if(!$appId){
            throw new EntityNotFoundException("Invalid App Id");
        }
        
        try{
            $this->beginTransaction();
            AuthContext::put(AuthConstants::REGISTRATION, TRUE);
            $result = $this->createAccount($params, NULL);
            $data['accountId'] = $params['uuid'];
            $this->addIdentifierForAccount($appId, $params);
            $this->commit();
            
        }catch(Exception $e){
            $this->logger->error($e->getMessage(), $e);
            $this->rollback();
            throw $e;
        }

        return $result;
    }

    private function getAppId($appId){
        if ($app = $this->getIdFromUuid('ox_app', $appId)) {
            $appId = $app;
        } 

        return $appId;
    }

    private function setupBusinessOfferings(&$params){
        if (!isset($params['app_id']) || !isset($params['businessOffering'])){
            return;
        }
        $appId = $params['app_id'];
        $offerings = $params['businessOffering'];
        $params['business_role_id'] = array();
        foreach ($offerings as $offering) {
            $offering['app_id'] = $appId;
            $offering['id'] = $params['id'];
            $this->setupBusinessRole($offering);
            if(isset($appId) && isset($offering['account_business_role_id']) && count($offering['account_business_role_id']) > 0){
                $acctBusinessRoleId = $offering['account_business_role_id'][0];
                $this->setupAcctOffering($appId, $acctBusinessRoleId, $offering['entity']);
                $params['business_role_id'][] = $offering['business_role_id'][0];
            }
        }
        
    }

    private function setupAcctOffering($appId, $acctBusinessRoleId, $offerings){
        $query = "DELETE FROM ox_account_offering where account_business_role_id = :acctBusinessRoleId";
        $params = array("acctBusinessRoleId" => $acctBusinessRoleId);
        $this->executeUpdateWithBindParameters($query, $params);
        $query = "INSERT INTO ox_account_offering (account_business_role_id, entity_id) VALUES 
                    (:acctBusinessRoleId, :entityId)";
        
        foreach ($offerings as $value) {
            $entity = $this->entityService->getEntityByName($appId, $value);
            if(!$entity){
                continue;
            }
            $params['entityId'] = $entity['id'];
            $this->executeUpdateWithBindParameters($query, $params);
        }
        
    }
    private function setupBusinessRole(&$params){
        if (!isset($params['app_id']) || !isset($params['businessRole'])){
            return;
        }
        $appId = $this->getAppId($params['app_id']);
        $query = "delete from ox_account_business_role where account_id = :accountId";
        $queryParams = ["accountId" => $params['id']];
        $resultSet = $this->executeUpdateWithBindParameters($query, $queryParams);
        if(is_string($params['businessRole'])){
            $businessRole = json_decode($params['businessRole'], true);
            $businessRole = [$params['businessRole']];
        }
        $businessRole = isset($businessRole) ? $businessRole : $params['businessRole'];
        $bRole = "";
        $queryParams = ['appId'=> $appId];
        foreach ($businessRole as $key => $value) {
            if($bRole != "") {
                $bRole .= ", ";     
            }else{
                $bRole = "(";
            }
            $bRole.=":param$key";
            $queryParams["param$key"] = $value;
        }
        $bRole .=")";
        $query = "INSERT INTO ox_account_business_role (account_id, business_role_id)
                    SELECT ".$params['id'].", id from ox_business_role 
                    WHERE app_id = :appId and name in $bRole";
        $this->logger->info("Executing query - $query with params - ".json_encode($queryParams));
        $this->executeUpdateWithBindParameters($query, $queryParams);
        $query = "SELECT id, business_role_id from ox_account_business_role where account_id = :accountId";
        $queryParams = ["accountId" => $params['id']];
        $result = $this->executeQueryWithBindParameters($query, $queryParams)->toArray();
        $params['business_role_id'] = array();
        $params['account_business_role_id'] = array();
        foreach ($result as $value) {
            $params['business_role_id'][] = $value['business_role_id'];
            $params['account_business_role_id'][] = $value['id'];
        }
    }
    private function addIdentifierForAccount($appId, $params){
        if ($appId && isset($params['identifier_field'])) {
            $this->logger->info("Add identifier for Account");
            $query = "INSERT INTO ox_wf_user_identifier(`app_id`,`account_id`,`user_id`,`identifier_name`,`identifier`) VALUES (:appId, :accountId, :userId, :identifierName, :identifier)";
            $queryParams = array("appId" => $appId,
                                    "accountId" => $params['id'],
                                    "userId" => $params['contact']['id'],
                                    "identifierName" => $params['identifier_field'],
                                    "identifier" => $params[$params['identifier_field']]);
            $this->logger->info("Executing Query - $query with Parametrs - " . print_r($queryParams, true));
            $resultSet = $this->executeUpdateWithBindParameters($query, $queryParams);
        }
    }
    private function saveAccountInternal(&$data, $files = NULL){
        $form = new Account($data);
        $form->validate();
        $count = 0;
        $this->logger->info("Data modified before Account Create - " . print_r($form, true));
        $count = $this->table->save($form);
        if ($count == 0) {
            throw new ServiceException("Failed to create new Account", "failed.create.account", OxServiceException::ERR_CODE_UNPROCESSABLE_ENTITY);
        }
        $form->id = $this->table->getLastInsertValue();
        $data['preferences'] = json_decode($data['preferences'], true);
        $data['id'] = $form->id;
        $this->setupBusinessRole($data);
        $defaultRoles = true;
        if(isset($data['business_role_id'])){
            $defaultRoles = false;
        }
        $this->setupBusinessOfferings($data);
        $userId = $this->setupBasicAccount($data, $data['contact'], $data['preferences'], $defaultRoles);
        unset($data['business_role_id']);
        if (isset($userId)) {
            $userId = $this->getIdFromUuid('ox_user', $userId);
            $data['contact']['id'] = $userId;
            $update = "UPDATE `ox_account` SET `contactid` = '" . $userId . "' where uuid = '" . $data['uuid'] . "'";
            $resultSet = $this->executeQueryWithParams($update);
        } else {
            throw new ServiceException("Failed to create new Account", "failed.create.account", OxServiceException::ERR_CODE_UNPROCESSABLE_ENTITY);
        }
        $insert = "INSERT INTO ox_app_registry (`account_id`,`app_id`,`date_created`,`start_options`) SELECT " . $form->id . ",id,CURRENT_TIMESTAMP(),start_options from ox_app where isdefault = 1";
        $resultSet = $this->executeQueryWithParams($insert);
        $this->uploadAccountLogo($data['uuid'], $files);
        $data['status'] = $form->status;
        return $count;
    }

    /**
     * uploadAccountLogo
     *
     * Upload files from Front End and store it in temp Folder
     *
     *  @param files Array of files to upload
     *  @return JSON array of filenames
     */
    public function uploadAccountLogo($id, $file)
    {
        if (isset($file)) {
            $destFile = $this->getAccountLogoPath($id, true);
            $image = FileUtils::convetImageTypetoPNG($file);
            if ($image) {
                if (FileUtils::fileExists($destFile)) {
                    imagepng($image, $destFile . '/logo.png');
                    $image = null;
                } else {
                    mkdir($destFile);
                    imagepng($image, $destFile . '/logo.png');
                    $image = null;
                }
            }
        }
    }

    private function setupBasicAccount($account, $contactPerson, $accountPreferences, $defaultRoles)
    {
        // adding basic roles
        $returnArray['roles'] = $this->roleService->createBasicRoles($account['id'], isset($account['business_role_id']) ? $account['business_role_id'] : NULL, $defaultRoles);
        // adding a user
        $returnArray['user'] = $this->userService->createAdminForAccount($account, $contactPerson, $accountPreferences);
        return $returnArray['user'];
    }

    public function saveAccount(&$accountData)
    {
        $create = true;
        if (isset($accountData['uuid'])) {
            try {
                $result = $this->updateAccount($accountData['uuid'], $accountData, null);
                $create = false;
            } catch (ServiceException $e) {
                if ($e->getMessageCode() != 'account.not.found') {
                    throw $e;
                }
            }
        }
        if ($create) {
            $result = $this->createAccount($accountData, null);
        }
    

        return $result;
    }

    /**
     * Update Account API
     * @method updateAccount
     * @param array $id ID of Account to update
     * @param array $data
     * @return array Returns a JSON Response with Status Code and Created Account.
     */
    public function updateAccount($id, &$data, $files = null)
    {
        $obj = $this->table->getByUuid($id, array());
        if (is_null($obj)) {
            throw new ServiceException("Entity not found for UUID", "account.not.found");
        }
        if (isset($data['contactid'])) {
            $data['contactid'] = $this->userService->getUserByUuid($data['contactid']);
        }
        if (isset($data['preferences']) && (!is_string($data['preferences']))) {
            $data['preferences'] = json_encode($data['preferences']);
        }
        
        $form = new Account();
        $changedArray = array_merge($obj->toArray(), $data);
        if (isset($changedArray['organization_id'])) {
            $this->organizationService->updateOrganization($changedArray['organization_id'],$data);
        }
        $changedArray['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $changedArray['date_modified'] = date('Y-m-d H:i:s');
        $form->exchangeArray($changedArray);
        $form->validate();
        $count = 0;

        try {
            $this->beginTransaction();
            $count = $this->table->save($form);
            if (isset($files)) {
                $this->uploadAccountLogo($id, $files);
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
        if ($obj->name != $data['name']) {
            $this->messageProducer->sendTopic(json_encode(array('new_accountName' => $data['name'], 'old_accountName' => $obj->name, 'status' => $form->status)), 'ACCOUNT_UPDATED');
        }
        if ($form->status == 'InActive') {
            $this->messageProducer->sendTopic(json_encode(array('accountName' => $obj->name, 'status' => $form->status)), 'ACCOUNT_DELETED');
        }
        return $count;
    }

    /**
     * Delete Account Service
     * @method deleteAccount
     * @param $id ID of Account to Delete
     * @return array success|failure response
     */
    public function deleteAccount($id)
    {
        $obj = $this->table->getByUuid($id, array());
        if (is_null($obj)) {
            return 0;
        }
        $originalArray = $obj->toArray();
        $form = new Account();
        $originalArray['status'] = 'Inactive';
        $form->exchangeArray($originalArray);
        try{
            $this->beginTransaction();
            $result = $this->table->save($form);
            $this->commit();
        }catch(Exception $e){
            $this->rollback();
            throw $e;
        }
        $this->messageProducer->sendTopic(json_encode(array('accountName' => $originalArray['name'], 'status' => $originalArray['status'])), 'ACCOUNT_DELETED');
        return $result;
    }

    /**
     * GET Account Service
     * @method getAccount
     * @param $id ID of Account to GET
     * @return array $data
     * <code> {
     *               id : integer,
     *               name : string,
     *               logo : string,
     *               status : String(Active|Inactive),
     *   } </code>
     * @return array Returns a JSON Response with Status Code and Created Account.
     */
    public function getAccount($id)
    {
        if(!is_numeric($id)){
            $id = $this->getIdFromUuid('ox_account', $id);
        }
        $select = "SELECT oxo.uuid, oxo.name, oxo.status, oxo.preferences, oxo.logo, oxo.theme from ox_account oxo 
                    where oxo.id =:id and oxo.status=:status";
        $params = array("id" => $id, "status" => "Active");
        $response = $this->executeQueryWithBindParameters($select,$params)->toArray();
        if (count($response) == 0) {
            return 0;
        }

        return $response[0];
    }

    public function getAccountIdByUuid($uuid)
    {
        $select = "SELECT id from ox_account where uuid = '" . $uuid . "'";
        $result = $this->executeQueryWithParams($select)->toArray();
        if (isset($result[0])) {
            return $result[0]['id'];
        } else {
            return null;
        }
    }

    /**
     * GET Account Service
     * @method getAccountByUuid
     * @param $id ID of Account to GET
     * @return array $data
     * <code> {
     *               id : integer,
     *               name : string,
     *               logo : string,
     *               status : String(Active|Inactive),
     *   } </code>
     * @return array Returns a JSON Response with Status Code and Created Account.
     */
    public function getAccountByUuid($id)
    {
        $select = "SELECT og.uuid,og.name,og.subdomain,oa.address1,oa.address2,oa.city,oa.state,oa.country,oa.zip,og.preferences, ou.uuid as contactid 
                    from ox_account as og 
                    inner join ox_user ou on ou.id = og.contactid
                    left join ox_organization oxop on oxop.id= og.organization_id 
                    left join ox_address as oa on oxop.address_id = oa.id  
                    WHERE og.uuid = '" . $id . "' AND og.status = 'Active'";
        $response = $this->executeQuerywithParams($select)->toArray();
        if (count($response) == 0) {
            return 0;
        } 
        return $response[0];
    }

    private function getAccountContactPersonDetails($id)
    {
        $query = "SELECT ou.uuid from `ox_user` as ou 
                        inner join ox_account a on a.contactid = ou.id 
                        where a.uuid = :accountId";
        $params = ['accountId' => $id];
        $userData = $this->executeQueryWithBindParameters($query, $params)->toArray();
        return $userData[0]['uuid'];
    }

    /**
     * GET Account Service
     * @method getAccounts
     * @return array $data
     * <code> {
     *               id : integer,
     *               name : string,
     *               logo : string,
     *               status : String(Active|Inactive),
     *   } </code>
     * @return array Returns a JSON Response with Status Code and Created Account.
     */
    public function getAccounts($filterParams = null)
    {
        $where = "";
        $pageSize = 20;
        $offset = 0;
        $sort = "name";
        $select = "SELECT og.uuid,og.name,og.subdomain,oa.address1,oa.address2,oa.city,oa.state,oa.country,oa.zip,og.preferences";
        $from = " from ox_account as og 
                    left join ox_organization as oxop on oxop.id = og.organization_id 
                    left join ox_address as oa on oxop.address_id = oa.id";
        $cntQuery = "SELECT count(og.id) " . $from;
        if (count($filterParams) > 0 || sizeof($filterParams) > 0) {
            $filterArray = json_decode($filterParams['filter'], true);
            $where = $this->createWhereClause($filterArray, self::$accountField);
            if (isset($filterArray[0]['sort']) && count($filterArray[0]['sort']) > 0) {
                $sort = $this->createSortClause($filterArray[0]['sort'], self::$accountField);
            }
            $pageSize = isset($filterArray[0]['take']) ? $filterArray[0]['take'] : 20;
            $offset = isset($filterArray[0]['skip']) ? $filterArray[0]['skip'] : 0;
        }
        $where .= strlen($where) > 0 ? " AND og.status = 'Active'" : " WHERE og.status = 'Active'";
        $sort = " ORDER BY " . $sort;
        $limit = " LIMIT " . $pageSize . " offset " . $offset;
        $resultSet = $this->executeQuerywithParams($cntQuery . $where);
        $count = $resultSet->toArray();
        $query = $select . " " . $from . " " . $where . " " . $sort . " " . $limit;
        $resultSet = $this->executeQuerywithParams($query)->toArray();
        for ($x = 0; $x < sizeof($resultSet); $x++) {
            $resultSet[$x]['contactid'] = $this->getAccountContactPersonDetails($resultSet[$x]['uuid']);
        }
        
        return array('data' => $resultSet, 'total' => $count[0]['count(og.id)']);
    }

    public function saveUser($id, $data)
    {
        $obj = $this->table->getByUuid($id, array());
        if (is_null($obj)) {
            throw new EntityNotFoundException("Entity Not Found");
        }
        if (!isset($data['userIdList']) || empty($data['userIdList'])) {
            throw new EntityNotFoundException("Users not selected");
        }
        $accountId = $obj->id;
        $userArray = $this->userService->getUserIdList($data['userIdList']);
        if ($userArray) {
            $userSingleArray = array_unique(array_map('current', $userArray));
            $querystring = "SELECT u.username FROM ox_account_user as ouo
            inner join ox_user as u on u.id = ouo.user_id
            inner join ox_account as account on ouo.account_id = account.id and account.id =" . $accountId . "
            where ouo.account_id =" . $accountId . " and ouo.user_id not in (" . implode(',', $userSingleArray) . ") and ouo.user_id != account.contactid";
            $deletedUser = $this->executeQuerywithParams($querystring)->toArray();
            $query = "SELECT ou.username from ox_user as ou 
                        LEFT OUTER JOIN ox_account_user as our on our.user_id = ou.id 
                                    AND our.account_id = ou.account_id and our.account_id =" . $accountId . "
            WHERE ou.id in (" . implode(',', $userSingleArray) . ") AND our.account_id is Null and ou.id not in (select user_id from  ox_account_user where user_id in (" . implode(',', $userSingleArray) . ") and account_id =" . $accountId . ")";
            $insertedUser = $this->executeQuerywithParams($query)->toArray();
            $this->beginTransaction();
            try {
                $query = "UPDATE ox_user as ou
                inner join ox_account as account on account.id = ou.account_id
                and ou.id != account.contactid
                SET ou.account_id = NULL WHERE ou.id not in (" . implode(',', $userSingleArray) . ") AND ou.account_id = $accountId";
                $resultSet = $this->executeQuerywithParams($query);
                $select = "SELECT u.id FROM ox_account_user as ouo
                inner join ox_user as u on u.id = ouo.user_id
                inner join ox_account as account on ouo.account_id = account.id and account.id =" . $accountId . "
                where ouo.account_id =" . $accountId . " and ouo.user_id not in (" . implode(',', $userSingleArray) . ") and ouo.user_id != account.contactid";
                $accountUserId = $this->executeQuerywithParams($select)->toArray();
                $subQuery = "inner join ox_user as u on u.id = ouo.user_id
                inner join ox_account as account on ouo.account_id = account.id and account.id =" . $accountId . "
                where ouo.account_id =" . $accountId . " and ouo.user_id not in (" . implode(',', $userSingleArray) . ") and ouo.user_id != account.contactid";
                $query = "DELETE ur FROM ox_user_role ur INNER JOIN ox_account_user as ouo on ur.account_user_id = ouo.id $subQuery";
                $resultSet = $this->executeQuerywithParams($query);
                $query = "DELETE ouo FROM ox_account_user as ouo $subQuery"; 
                $resultSet = $this->executeQuerywithParams($query);
                $insert = "INSERT INTO ox_account_user (user_id,account_id,`default`)
                SELECT ou.id," . $accountId . ",case when (ou.account_id is NULL)
                then 1
                end
                from ox_user as ou 
                LEFT OUTER JOIN ox_account_user as our on our.user_id = ou.id AND our.account_id = ou.account_id and our.account_id =" . $accountId . "
                WHERE ou.id in (" . implode(',', $userSingleArray) . ") AND our.account_id is Null AND ou.id not in (select user_id from  ox_account_user where user_id in (" . implode(',', $userSingleArray) . ") and account_id =" . $accountId . ")";
                $resultSet = $this->executeQuerywithParams($insert);
                //handle user_role update using default role
                $insert = "INSERT INTO ox_user_role (account_user_id, role_id)
                SELECT our.id, r.id
                from ox_role as r 
                INNER JOIN (SELECT au.id, au.account_id FROM ox_account_user au inner join ox_user ou on ou.id = au.user_id where au.account_id = $accountId and ou.username in ('".implode("','", array_unique(array_map('current', $insertedUser)))."') ) as our on our.account_id = r.account_id
                WHERE r.account_id =" . $accountId . " AND r.default_role = 1";
                $resultSet = $this->executeQuerywithParams($insert);
                $update = "UPDATE ox_user SET account_id = $accountId WHERE id in (" . implode(',', $userSingleArray) . ") AND account_id is NULL";
                $resultSet = $this->executeQuerywithParams($update);
                if (count($accountUserId) > 0) {
                    $accountUserIdArray = array_unique(array_map('current', $accountUserId));
                    $update = "UPDATE ox_user SET account_id = NULL WHERE id in (" . implode(',', $accountUserIdArray) . ")";
                    $resultSet = $this->executeQuerywithParams($update);
                }
                $this->commit();
            } catch (Exception $e) {
                $this->rollback();
                throw $e;
            }
            foreach ($deletedUser as $key => $value) {
                $this->messageProducer->sendTopic(json_encode(array('accountName' => $obj->name, 'username' => $value["username"])), 'USERTOACCOUNT_DELETED');
            }
            foreach ($insertedUser as $key => $value) {
                $this->messageProducer->sendTopic(json_encode(array('accountName' => $obj->name, 'status' => 'Active', 'username' => $value["username"])), 'USERTOACCOUNT_ADDED');
            }
        }else{
            throw new EntityNotFoundException("Entity Not Found");
        }
    }

    public function getAccountUserList($id, $filterParams = null, $baseUrl = '')
    {
        if (!isset($id)) {
            throw new EntityNotFoundException("Invalid Account");
        }

        $pageSize = 20;
        $offset = 0;
        $where = "";
        $sort = "ox_user.name";

        $query = "SELECT ox_user.uuid,ox_user.name,ox_user.username,oxup.email,ox_address.address1,ox_address.address2,ox_address.city,ox_address.state,ox_address.country,ox_address.zip,oxemp.designation,
        case when (ox_account.contactid = ox_user.id)
        then 1
        end as is_admin";
        $from = " FROM ox_user inner join ox_person oxup on oxup.id = ox_user.person_id 
                    inner join ox_employee oxemp on oxemp.person_id = oxup.id 
                    inner join ox_account_user on ox_user.id = ox_account_user.user_id 
                    left join ox_account on ox_account.id = ox_account_user.account_id 
                    LEFT join ox_address on oxup.address_id = ox_address.id";

        $cntQuery = "SELECT count(ox_user.id)" . $from;

        if (count($filterParams) > 0 || sizeof($filterParams) > 0) {
            $filterArray = json_decode($filterParams['filter'], true);
            $where = $this->createWhereClause($filterArray, self::$userField);
            if (isset($filterArray[0]['sort']) && count($filterArray[0]['sort']) > 0) {
                $sort = $this->createSortClause($filterArray[0]['sort'], self::$userField);
            }
            $pageSize = isset($filterArray[0]['take']) ? $filterArray[0]['take'] : 20;
            $offset = isset($filterArray[0]['skip']) ? $filterArray[0]['skip'] : 0;
        }

        $where .= strlen($where) > 0 ? " AND " : " WHERE "; 
        $where .= "ox_account.uuid = '" . $id . "' AND ox_user.status = 'Active'";

        $sort = " ORDER BY " . $sort;
        $limit = " LIMIT " . $pageSize . " offset " . $offset;
        $this->logger->info("Executing query $cntQuery$where");
        $resultSet = $this->executeQuerywithParams($cntQuery . $where);
        $count = $resultSet->toArray();
        $query = $query . " " . $from . " " . $where . " " . $sort . " " . $limit;
        $this->logger->info("Executing query - $query");
        $resultSet = $this->executeQuerywithParams($query)->toArray();
        for ($x = 0; $x < sizeof($resultSet); $x++) {
            $resultSet[$x]['icon'] = $baseUrl . "/user/profile/" . $resultSet[$x]['uuid'];
        }
        return array('data' => $resultSet,
            'total' => $count[0]['count(ox_user.id)']);
    }

    public function getAdminUsers($filterParams, $accountId = null)
    {
        if (!isset($accountId)) {
            $accountId = AuthContext::get(AuthConstants::ACCOUNT_UUID);
        }
        if (!SecurityManager::isGranted('MANAGE_ACCOUNT_WRITE') &&
            SecurityManager::isGranted('MANAGE_MYACCOUNT_WRITE') &&
            $accountId != AuthContext::get(AuthConstants::ACCOUNT_UUID)) {
            throw new AccessDeniedException("You do not have permissions");
        }

        $pageSize = 20;
        $offset = 0;
        $where = "";
        $sort = "name";

        $select = "SELECT DISTINCT ox_user.uuid,ox_user.name ";
        $from = " from ox_user 
                  inner join ox_account_user as oug on ox_user.id = oug.user_id 
                  inner join ox_user_role as our on oug.id = our.account_user_id 
                  inner join ox_role as oro on our.role_id = oro.id 
                  ";

        $cntQuery = "SELECT count(DISTINCT ox_user.uuid)" . $from;

        if (count($filterParams) > 0 || sizeof($filterParams) > 0) {
            $filterArray = json_decode($filterParams['filter'], true);
            $where = $this->createWhereClause($filterArray, self::$userField);
            if (isset($filterArray[0]['sort']) && count($filterArray[0]['sort']) > 0) {
                $sort = $this->createSortClause($filterArray[0]['sort'], self::$userField);
            }
            $pageSize = isset($filterArray[0]['take']) ? $filterArray[0]['take'] : 20;
            $offset = isset($filterArray[0]['skip']) ? $filterArray[0]['skip'] : 0;
        }

        $accountId = $this->getAccountIdByUuid($accountId);
        $where .= strlen($where) > 0 ? " AND " : " WHERE ";
        $where .= "oug.account_id =" . $accountId . " and oro.name = 'ADMIN'";


        $sort = " ORDER BY " . $sort;
        $limit = " LIMIT " . $pageSize . " offset " . $offset;
        $resultSet = $this->executeQuerywithParams($cntQuery . $where);
        $count = $resultSet->toArray();
        $query = $select . " " . $from . " " . $where . " " . $sort . " " . $limit;
        $resultSet = $this->executeQuerywithParams($query)->toArray();
        return array('data' => $resultSet,
            'total' => $count[0]['count(DISTINCT ox_user.uuid)']);
    }

    public function getAccountGroupsList($id, $filterParams = null)
    {
        if (!isset($id)) {
            throw new EntityNotFoundException("Invalid Account");
        }

        $pageSize = 20;
        $offset = 0;
        $where = "";
        $sort = "oxg.name";

        $select = "SELECT oxg.uuid,oxg.name,oxg.description,oxu.uuid as managerId, oxg1.uuid as parent_id, oxo.uuid as accountId";
        $from = "FROM `ox_group` as oxg
                    LEFT JOIN ox_user as oxu on oxu.id = oxg.manager_id
                    LEFT JOIN ox_group as oxg1 on oxg.parent_id = oxg1.id
                    LEFT JOIN ox_account as oxo on oxg.account_id = oxo.id";

        $cntQuery = "SELECT count(oxg.uuid) " . $from;

        if (count($filterParams) > 0 || sizeof($filterParams) > 0) {
            $filterArray = json_decode($filterParams['filter'], true);
            $where = $this->createWhereClause($filterArray, self::$groupField);
            if (isset($filterArray[0]['sort']) && count($filterArray[0]['sort']) > 0) {
                $sort = $this->createSortClause($filterArray[0]['sort'], self::$groupField);
            }
            $pageSize = isset($filterArray[0]['take']) ? $filterArray[0]['take'] : 20;
            $offset = isset($filterArray[0]['skip']) ? $filterArray[0]['skip'] : 0;
        }
        $accountId = $this->getAccountIdByUuid($id);
        if (!$accountId) {
            throw new EntityNotFoundException("Invalid Account");
        }
        $where .= strlen($where) > 0 ? " AND " : " WHERE ";
        $where .= "oxg.account_id =" . $accountId . " and oxg.status = 'Active'";

        $sort = " ORDER BY " . $sort;
        $limit = " LIMIT " . $pageSize . " offset " . $offset;

        $resultSet = $this->executeQuerywithParams($cntQuery . $where);
        $count = $resultSet->toArray();
        $query = $select . " " . $from . " " . $where . " " . $sort . " " . $limit;
        $resultSet = $this->executeQuerywithParams($query)->toArray();

        return array('data' => $resultSet,
            'total' => $count[0]['count(oxg.uuid)']);

    }

    public function getAccountProjectsList($id, $filterParams = null)
    {

        if (!isset($id)) {
            throw new EntityNotFoundException("Invalid Account");
        }

        $pageSize = 20;
        $offset = 0;
        $where = "";
        $sort = "oxp.name";

        $select = "SELECT oxp.uuid,oxp.name,oxp.description, oxu.uuid as managerId, oxo.uuid as accountId";
        $from = "FROM `ox_project` as oxp
                    LEFT JOIN ox_user as oxu on oxu.id = oxp.manager_id
                    LEFT JOIN ox_account as oxo on oxp.account_id = oxo.id";

        $cntQuery = "SELECT count(oxp.uuid) " . $from;

        if (count($filterParams) > 0 || sizeof($filterParams) > 0) {
            $filterArray = json_decode($filterParams['filter'], true);
            $where = $this->createWhereClause($filterArray, self::$projectField);
            if (isset($filterArray[0]['sort']) && count($filterArray[0]['sort']) > 0) {
                $sort = $this->createSortClause($filterArray[0]['sort'], self::$projectField);
            }
            $pageSize = isset($filterArray[0]['take']) ? $filterArray[0]['take'] : 20;
            $offset = isset($filterArray[0]['skip']) ? $filterArray[0]['skip'] : 0;
        }
        $accountId = $this->getAccountIdByUuid($id);
        if (!$accountId) {
            throw new EntityNotFoundException("Invalid Account");
        }
        $where .= strlen($where) > 0 ? " AND oxp.account_id =" . $accountId . " and oxp.isdeleted != 1" : " WHERE oxp.account_id =" . $accountId . " and oxp.isdeleted != 1 and oxp.parent_id IS NULL";

        $sort = " ORDER BY " . $sort;
        $limit = " LIMIT " . $pageSize . " offset " . $offset;

        $resultSet = $this->executeQuerywithParams($cntQuery . $where);
        $count = $resultSet->toArray();
        $query = $select . " " . $from . " " . $where . " " . $sort . " " . $limit;
        $resultSet = $this->executeQuerywithParams($query)->toArray();

        return array('data' => $resultSet,
            'total' => $count[0]['count(oxp.uuid)']);

    }

    public function getAccountAnnouncementsList($id, $filterParams = null)
    {

        if (!isset($id)) {
            throw new EntityNotFoundException("Invalid Account");
        }

        $pageSize = 20;
        $offset = 0;
        $where = "";
        $sort = "oxa.name";

        $select = "SELECT oxa.uuid,oxa.name,oxa.description,oxa.link, oxa.type,oxa.end_date,
                    oxa.start_date,oxa.media_type,oxa.media,oxo.uuid as accountId";
        $from = "FROM `ox_announcement` as oxa
                    LEFT JOIN ox_account as oxo on oxa.account_id = oxo.id";

        $cntQuery = "SELECT count(oxa.uuid) " . $from;

        if (count($filterParams) > 0 || sizeof($filterParams) > 0) {
            $filterArray = json_decode($filterParams['filter'], true);
            $where = $this->createWhereClause($filterArray, self::$announcementField);
            if (isset($filterArray[0]['sort']) && count($filterArray[0]['sort']) > 0) {
                $sort = $this->createSortClause($filterArray[0]['sort'], self::$announcementField);
            }
            $pageSize = isset($filterArray[0]['take']) ? $filterArray[0]['take'] : 20;
            $offset = isset($filterArray[0]['skip']) ? $filterArray[0]['skip'] : 0;
        }
        $accountId = $this->getAccountIdByUuid($id);
        if (!$accountId) {
            throw new EntityNotFoundException("Invalid Account");
        }

        $where .= strlen($where) > 0 ? " AND " : " WHERE ";
        $where .= "oxa.account_id =" . $accountId . " and oxa.status = 1";

        $sort = " ORDER BY " . $sort;
        $limit = " LIMIT " . $pageSize . " offset " . $offset;

        $resultSet = $this->executeQuerywithParams($cntQuery . $where);
        $count = $resultSet->toArray();
        $query = $select . " " . $from . " " . $where . " " . $sort . " " . $limit;
        $resultSet = $this->executeQuerywithParams($query)->toArray();

        return array('data' => $resultSet,
            'total' => $count[0]['count(oxa.uuid)']);

    }

    public function getAccountLogoPath($id, $ensureDir = false)
    {
        $baseFolder = $this->config['UPLOAD_FOLDER'];
        //TODO : Replace the User_ID with USER uuid
        $folder = $baseFolder . "account/";
        if (isset($id)) {
            $folder = $folder . $id . "/";
        }

        if ($ensureDir && !file_exists($folder)) {
            FileUtils::createDirectory($folder);
        }

        return $folder;
    }

    public function getAccountRolesList($id, $filterParams = null)
    {
        if (!isset($id)) {
            throw new EntityNotFoundException("Invalid Account");
        }

        $pageSize = 20;
        $offset = 0;
        $where = "";
        $sort = "oxr.name";

        $select = "SELECT oxr.uuid,oxr.name,oxr.description,oxr.is_system_role,oxo.uuid as accountId";
        $from = "FROM `ox_role` as oxr
                    LEFT JOIN ox_account as oxo on oxr.account_id = oxo.id";

        $cntQuery = "SELECT count(oxr.uuid) " . $from;

        if (count($filterParams) > 0 || sizeof($filterParams) > 0) {
            $filterArray = json_decode($filterParams['filter'], true);
            $where = $this->createWhereClause($filterArray, self::$roleField);
            if (isset($filterArray[0]['sort']) && count($filterArray[0]['sort']) > 0) {
                $sort = $this->createSortClause($filterArray[0]['sort'], self::$roleField);
            }
            $pageSize = isset($filterArray[0]['take']) ? $filterArray[0]['take'] : 20;
            $offset = isset($filterArray[0]['skip']) ? $filterArray[0]['skip'] : 0;
        }

        $accountId = $this->getAccountIdByUuid($id);
        if (!$accountId) {
            throw new EntityNotFoundException("Invalid Account");
        }

        $where .= strlen($where) > 0 ? " AND " : " WHERE ";
        $where .= "oxr.account_id =" . $accountId;

        $sort = " ORDER BY " . $sort;

        $limit = " LIMIT " . $pageSize . " offset " . $offset;
        $resultSet = $this->executeQuerywithParams($cntQuery . $where);

        $count = $resultSet->toArray();
        $query = $select . " " . $from . " " . $where . " " . $sort . " " . $limit;
        $resultSet = $this->executeQuerywithParams($query)->toArray();

        return array('data' => $resultSet,
            'total' => $count[0]['count(oxr.uuid)']);
    }

    private function createWhereClause($filterArray, $fieldName = null)
    {
        if (isset($filterArray[0]['filter'])) {
            $filterlogic = isset($filterArray[0]['filter']['logic']) ? $filterArray[0]['filter']['logic'] : "AND";
            $filterList = $filterArray[0]['filter']['filters'];
            $where = " WHERE " . FilterUtils::filterArray($filterList, $filterlogic, $fieldName);
            return $where;
        } else {
            return "";
        }
    }

    private function createSortClause($sort, $fieldName = null)
    {
        $sort = FilterUtils::sortArray($sort, $fieldName);
        return $sort;
    }
    // YET TO BE DONE
    // private function setUpOrgAssociationRelation(&$data){
    //     $this->beginTransaction();
    //     try{
    //         foreach ($data['associations'] as &$orgAssociation) {
    //             $orgAssociation['uuid'] = isset($orgAssociation['uuid']) ? $orgAssociation['uuid'] : UuidUtil::uuid();
    //             $select = "SELECT id FROM `ox_association` WHERE uuid = :uuid AND org_id =:orgId";
    //             $params = array('uuid' => $orgAssociation['uuid'],'orgId' => $data['id']);
    //             $result = $this->executeQueryWithBindParameters($select, $params)->toArray();
    //             $params['associationName'] = $orgAssociation['name'];
    //             if (count($result) == 0) {
    //                 $query = "INSERT INTO `ox_association` (`uuid`,`name`,`org_id`,`user_identifier_field`,`org_identifier_field`) VALUES (:uuid,:associationName,:orgId,:usserIdentifier,:orgIdentifier)";
    //             }else{
    //                 $query = "UPDATE ox_association SET name=:associationName WHERE uuid=:uuid";
    //                 unset($params['orgId']);
    //             }
    //             $resultSet = $this->executeUpdateWithBindParameters($query,$params);
    //         }
    //         $this->commit();
    //     }catch(Exception $e){
    //         $this->rollback();
    //         $this->logger->error($e->getMessage(), $e);
    //         throw $e;
    //     }
    // }
}
