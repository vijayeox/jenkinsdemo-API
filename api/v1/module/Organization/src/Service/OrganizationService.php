<?php
namespace Organization\Service;

use Oxzion\Service\AbstractService;
use Organization\Model\OrganizationTable;
use Organization\Model\Organization;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\ValidationException;
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
             case "Oxzion\ValidationException" :
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
             case "Oxzion\ValidationException" :
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
        $this->beginTransaction();
        $count = 0;
        try{
            $count = $this->table->delete($id);
            if($count == 0){
                $this->rollback();
                return 0;
            }
            $this->commit();
        }catch(Exception $e){
            $this->rollback();
        }
        
        return $count;
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
        ->where(array('ox_organization.id' => $id));
        $response = $this->executeQuery($select)->toArray();
        if(count($response)==0){
            return 0;
        }
        return $response[0];
    }
}
?>