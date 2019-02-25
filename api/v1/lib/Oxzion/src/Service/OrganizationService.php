<?php
namespace Oxzion\Service;

use Oxzion\Model\OrganizationTable;
use Oxzion\Model\Organization;
use Bos\Auth\AuthContext;
use Bos\Auth\AuthConstants;
use Bos\Service\AbstractService;
use Bos\ValidationException;
use Zend\Db\Sql\Expression;
use Exception;

class OrganizationService extends AbstractService{
    /**
    * @ignore __construct
    */
    public function __construct($config, $dbAdapter, OrganizationTable $table){
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
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
    public function createOrganization(&$data){
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
        try{
            $count = $this->table->save($form);
            if($count == 0){
                $this->rollback();
                return 0;
            }
            $id = $this->table->getLastInsertValue();
            $data['id'] = $id;
            $this->commit();
        }catch(Exception $e){
            switch (get_class ($e)) {
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
        return $count;
    }
    /**
    * Update Organization API
    * @method updateOrganization
    * @param array $id ID of Organization to update 
    * @param array $data 
    * @return array Returns a JSON Response with Status Code and Created Organization.
    */
    public function updateOrganization($id,&$data){
        $obj = $this->table->get($id,array());
        if(is_null($obj)){
            return 0;
        }
        $org = $obj->toArray();
        $form = new Organization();
        $changedArray = array_merge($obj->toArray(),$data);
        $changedArray['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $changedArray['date_modified'] = date('Y-m-d H:i:s');
        $form->exchangeArray($changedArray);
        $form->validate();
        $this->beginTransaction();
        $count = 0;
        try{
            $count = $this->table->save($form);
            if($count == 0){
                $this->rollback();
                return 0;
            }
            $this->commit();
        }catch(Exception $e){
            switch (get_class ($e)) {
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
        return $count;
    }
    /**
    * Delete Organization Service
    * @method deleteOrganization
    * @link /organization[/:orgId]
    * @param $id ID of Organization to Delete
    * @return array success|failure response
    */
    public function deleteOrganization($id){
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
    public function getOrganization($id) {
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_organization')
        ->columns(array("*"))
        ->where(array('ox_organization.id' => $id,'status' => "Active" ));
        $response = $this->executeQuery($select)->toArray();
        if(count($response)==0){
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
    public function getOrganizations() {
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
        $queryString = "select id from avatars";
        $where = "where id =" . $userId;
        $resultSet = $this->executeQuerywithParams($queryString, $where, null, null);
        if ($resultSet) {
            $query = "select id from ox_organization";
            $where = "where id=" . $organizationId." AND status = 'Active' ";
            $result = $this->executeQuerywithParams($query, $where, null, null);
            if ($result) {
                $query = "select * from ox_user_org";
                $where = "where user_id =" . $userId . " and org_id =" . $organizationId;
                $endresult = $this->executeQuerywithParams($query, $where, null, null)->toArray();
                if (!$endresult) {
                    $data = array(array('user_id' => $userId, 'org_id' => $organizationId));
                    $result_update = $this->multiInsertOrUpdate('ox_user_org', $data, array());
                    if ($result_update->getAffectedRows() == 0) {
                        return $result_update;
                    }
                    return 1;
                }
                else {
                    return 3;
                }
            }
            else {
                return 2;
            }    
        }
        return 0;
    }
}
?>