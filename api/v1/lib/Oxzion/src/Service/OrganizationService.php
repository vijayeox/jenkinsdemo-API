<?php
namespace Oxzion\Service;

use Oxzion\Model\OrganizationTable;
use Oxzion\Model\Organization;
use Bos\Auth\AuthContext;
use Bos\Auth\AuthConstants;
use Bos\Service\AbstractService;
use Bos\ValidationException;
use Oxzion\Utils\BosUtils;
use Zend\Db\Sql\Expression;
use Oxzion\Service\OrganizationService;
use Oxzion\Messaging\MessageProducer;
use Exception;

class OrganizationService extends AbstractService
{

    private $emailService;
    private $messageProducer;

    public function setMessageProducer($messageProducer)
    {
		$this->messageProducer = $messageProducer;
    }

    /**
     * @ignore __construct
     */
    public function __construct($config, $dbAdapter, OrganizationTable $table, UserService $userService)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
        $this->userService = $userService;
        $this->messageProducer = MessageProducer::getInstance();
    }

    /**
     * Create Organization Service
     * @method createOrganization
     * @param array $data Array of elements as shown
     * <code> {
     *               id : integer,
     *               name : string,
     *               logo : string,
     *               status : String(Active|Inactive),
     *   } </code>
     * @return array Returns a JSON Response with Status Code and Created Organization.
     */
    public function createOrganization(&$data)
    {
        $form = new Organization();
        $data['created_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_created'] = date('Y-m-d H:i:s');
        $data['date_modified'] = date('Y-m-d H:i:s');
        $data['status'] = "Active";
        $form->exchangeArray($data);
        $form->validate();
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->save($form);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
            $id = $this->table->getLastInsertValue();
            $this->createAdminUserForOrg($form, $id);
            $data['id'] = $id;
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            return 0;
        }
        // $result = $this->messageProducer->sendTopic(json_encode(array('orgname' => $data['name'], 'status' => 'Active')),'ORGANIZATION_ADDED');
        return $count;
    }


    /**
     * Create Admin User Service - Once the Organization is created, we need to create a user that can take control of
     * all the admin related activities for that organization
     * @method createAdminUserForOrg
     * @param array $data Array of elements as shown
     * <code> {
     *               id : integer,
     *               name : string,
     *               logo : string,
     *               status : String(Active|Inactive),
     *   } </code>
     * @return array Returns success response after the user is created successfully.
     */
    public function createAdminUserForOrg($data, $orgId)
    {
        $userData = [
            "firstname" => str_replace(' ', '', $data->name),
            "lastname" => "Adminisrator",
            "email" => strtolower(str_replace(' ', '', $data->name)) . "@oxzion.com",
            "company_name" => $data->name,
            "address_1" => $data->address,
            "address_2" => $data->city,
            "country" => "US",
            "preferences" => "[{ 'create_user' => 'true', 'show_notification' => 'true' }]",
            "username" => BosUtils::randomUserName($data->name),
            "date_of_birth" => "1986/01/01",
            "designation" => "Admin",
            "orgid" => $orgId,
            "status" => "Active",
            "timezone" => "United States/New York",
            "gender" => "Male",
            "managerid" => "1",
            "date_of_join" => Date("Y-m-d"),
            "password" => BosUtils::randomPassword()
        ];
        return $this->userService->createUser($userData);
    }

    /**
     * Update Organization API
     * @method updateOrganization
     * @param array $id ID of Organization to update
     * @param array $data
     * @return array Returns a JSON Response with Status Code and Created Organization.
     */
    public function updateOrganization($id, &$data)
    {
        $obj = $this->table->get($id, array());
        if (is_null($obj)) {
            return 0;
        }
        $org = $obj->toArray();
        $form = new Organization();
        $changedArray = array_merge($obj->toArray(), $data);
        $changedArray['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $changedArray['date_modified'] = date('Y-m-d H:i:s');
        $form->exchangeArray($changedArray);
        $form->validate();
        $this->beginTransaction();
        $count = 0;
        try { 
            $count = $this->table->save($form);    
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
            $this->commit();
        }
         catch (Exception $e) { 
            switch (get_class($e)) {
                case "Bos\ValidationException" :
                    $this->rollback();
                    throw $e;
                    break;
                default:
                    $this->rollback();
                    return 0;
                    break;
            }
        }
        
        // if($obj->name != $data['name']){
        //     $result = $this->messageProducer->sendTopic(json_encode(array('new_orgname' => $data['name'], 'old_orgname' => $obj->name,'status' => $data['status'])),'ORGANIZATION_UPDATED');
        // }
        // if($data['status'] == 'InActive'){
        //     $result = $this->messageProducer->sendTopic(json_encode(array('orgname' => $obj->name,'status' => $data['status'])),'ORGANIZATION_DELETED');
        // }
        return $count;
    }

    /**
     * Delete Organization Service
     * @method deleteOrganization
     * @link /organization[/:orgId]
     * @param $id ID of Organization to Delete
     * @return array success|failure response
     */
    public function deleteOrganization($id)
    {
        $obj = $this->table->get($id, array());
        if (is_null($obj)) {
            return 0;
        }
        $originalArray = $obj->toArray();
        $form = new Organization();
        $originalArray['status'] = 'Inactive';
        $form->exchangeArray($originalArray);
        $form->validate();
        $result = $this->table->save($form);
        // $this->messageProducer->sendTopic(json_encode(array('orgname' => $originalArray['name'],'status' => $originalArray['status'])),'ORGANIZATION_DELETED');
        return $result;
    }

    /**
     * GET Organization Service
     * @method getOrganization
     * @param $id ID of Organization to Delete
     * @return array $data
     * <code> {
     *               id : integer,
     *               name : string,
     *               logo : string,
     *               status : String(Active|Inactive),
     *   } </code>
     * @return array Returns a JSON Response with Status Code and Created Organization.
     */
    public function getOrganization($id)
    {
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_organization')
            ->columns(array("*"))
            ->where(array('ox_organization.id' => $id, 'status' => "Active"));
        $response = $this->executeQuery($select)->toArray();
        if (count($response) == 0) {
            return 0;
        }
        return $response[0];
    }

    /**
     * GET Organization Service
     * @method getOrganizations
     * @return array $data
     * <code> {
     *               id : integer,
     *               name : string,
     *               logo : string,
     *               status : String(Active|Inactive),
     *   } </code>
     * @return array Returns a JSON Response with Status Code and Created Organization.
     */
    public function getOrganizations()
    {
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_organization')
            ->columns(array("*"))
            ->where(array('status' => "Active"));
        $response = $this->executeQuery($select)->toArray();
        return $response;
    }

    public function addUserToOrg($userId, $organizationId)
    {
        $sql = $this->getSqlObject();
        $queryString = "select id,username from ox_user";
        $where = "where id =" . $userId;
        $resultSet = $this->executeQuerywithParams($queryString, $where, null, null);
        if ($resultSet) {
            $query = "select id,name from ox_organization";
            $where = "where id=" . $organizationId . " AND status = 'Active' ";
            $result = $this->executeQuerywithParams($query, $where, null, null);
            if ($result) {
                $query = "select * from ox_user_org";
                $where = "where user_id =" . $userId . " and org_id =" . $organizationId;
                $endresult = $this->executeQuerywithParams($query, $where, null, null)->toArray();
                if (!$endresult) {
                    $data = array(array('user_id' => $userId, 'org_id' => $organizationId));
                    $result_update = $this->multiInsertOrUpdate('ox_user_org', $data, array());
                    // $result = $this->messageProducer->sendTopic(json_encode(array('username' => $resultSet->toArray()[0]['username'], 'orgname' => $result->toArray()[0]['name'] , 'status' => 'Active')),'USERTOORGANIZATION_ADDED');
                    if ($result_update->getAffectedRows() == 0) {
                        return $result_update;
                    }
                    return 1;
                } else {
                    // $result = $this->messageProducer->sendTopic(json_encode(array('username' => $resultSet->toArray()[0]['username'], 'orgname' => $result->toArray()[0]['name'] , 'status' => 'Active')),'USERTOORGANIZATION_ALREADYEXISTS');
                    return 3;
                }
            } else {
                return 2;
            }
        }
        return 0;
    }
}

?>