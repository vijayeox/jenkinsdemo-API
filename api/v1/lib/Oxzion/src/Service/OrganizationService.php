<?php
namespace Oxzion\Service;

use Bos\Auth\AuthContext;
use Bos\Auth\AuthConstants;
use Bos\ValidationException;
use Bos\Service\AbstractService;
use Oxzion\Model\Organization;
use Oxzion\Model\OrganizationTable;
use Oxzion\Messaging\MessageProducer;

class OrganizationService extends AbstractService
{

    protected $table;
    private $userService;
    private $roleService;
    protected $modelClass;
    private $messageProducer;
    private $privilegeService;


    public function setMessageProducer($messageProducer)
    {
        $this->messageProducer = $messageProducer;
    }

    /**
     * @ignore __construct
     */
    public function __construct($config, $dbAdapter, OrganizationTable $table, UserService $userService, RoleService $roleService, PrivilegeService $privilegeService)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
        $this->userService = $userService;
        $this->roleService = $roleService;
        $this->modelClass = new Organization();
        $this->privilegeService = $privilegeService;
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
        $data['created_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_created'] = date('Y-m-d H:i:s');
        $data['date_modified'] = date('Y-m-d H:i:s');

        $form = new Organization($data);
        $form->validate();
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->save($form);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
            $form->id = $this->table->getLastInsertValue();
            $this->setupBasicOrg($form);
            $this->commit();
            $this->messageProducer->sendTopic(json_encode(array('orgname' => $form->name, 'status' => $form->status)),'ORGANIZATION_ADDED');
        } catch (Exception $e) {
            $this->rollback();
            return 0;
        }
        return $count;
    }

    private function setupBasicOrg(Organization $org) {
        // adding basic roles
        $returnArray['roles'] = $this->roleService->createBasicRoles($org->id);

        // adding basic privileges
        $returnArray['privileges'] = $this->privilegeService->createBasicPrivileges($org->id);

        // adding a user
        $returnArray['user'] = $this->userService->createAdminForOrg($org);

        return true;
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

        if($obj->name != $data['name']){
            $this->messageProducer->sendTopic(json_encode(array('new_orgname' => $data['name'], 'old_orgname' => $obj->name,'status' => $data['status'])),'ORGANIZATION_UPDATED');
        }
        if($data['status'] == 'InActive'){
            $this->messageProducer->sendTopic(json_encode(array('orgname' => $obj->name,'status' => $data['status'])),'ORGANIZATION_DELETED');
        }
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
        $this->messageProducer->sendTopic(json_encode(array('orgname' => $originalArray['name'],'status' => $originalArray['status'])),'ORGANIZATION_DELETED');
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

    public function addUserToOrg($userId, $organizationId) {
        if ($user = $this->getDataByParams('ox_user', array('id', 'username'), array('id' => $userId))) {
            if ($org = $this->getDataByParams('ox_organization', array('id', 'name'), array('id' => $organizationId, 'status' => 'Active'))) {
                if (!$this->getDataByParams('ox_user_org', array(), array('user_id' => $userId, 'org_id' => $organizationId))) {
                    $data = array(array(
                        'user_id' => $userId,
                        'org_id' => $organizationId
                    ));
                    $result_update = $this->multiInsertOrUpdate('ox_user_org', $data);
                    if ($result_update->getAffectedRows() == 0) {
                        return $result_update;
                    }
                    $this->messageProducer->sendTopic(json_encode(array('username' => $user[0]['username'], 'orgname' => $org[0]['name'] , 'status' => 'Active')),'USERTOORGANIZATION_ADDED');
                    return 1;
                } else {
                    $this->messageProducer->sendTopic(json_encode(array('username' => $user[0]['username'], 'orgname' => $org[0]['name'] , 'status' => 'Active')),'USERTOORGANIZATION_ALREADYEXISTS');
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