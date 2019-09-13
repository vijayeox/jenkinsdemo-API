<?php
namespace Oxzion\Service;

use Oxzion\Model\FieldTable;
use Oxzion\Model\Field;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\Service\AbstractService;
use Oxzion\ValidationException;
use Zend\Db\Sql\Expression;
use Exception;

class FieldService extends AbstractService
{
    public function __construct($config, $dbAdapter, FieldTable $table)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
    }
    public function saveField($appId, &$data)
    {
        $field = new Field();
        $data['app_id'] = $appId;
        if (!isset($data['id']) || $data['id']==0) {
            $data['created_by'] = AuthContext::get(AuthConstants::USER_ID);
            $data['date_created'] = date('Y-m-d H:i:s');
        }
        $data['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_modified'] = date('Y-m-d H:i:s');
        $field->exchangeArray($data);
        $field->validate();
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->save($field);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
            if (!isset($data['id']) || $data['id']==0) {
                $id = $this->table->getLastInsertValue();
                $data['id'] = $id;
            }
            $this->commit();
        } catch (Exception $e) {
            switch (get_class($e)) {
             case "Oxzion\ValidationException":
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
    public function updateField($id, &$data)
    {
        $obj = $this->table->get($id, array());
        if (is_null($obj)) {
            return 0;
        }
        $data['id'] = $id;
        $data['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $data['date_modified'] = date('Y-m-d H:i:s');
        $file = $obj->toArray();
        $changedArray = array_merge($obj->toArray(), $data);
        $field = new Field();
        $field->exchangeArray($changedArray);
        $field->validate();
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->save($field);
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


    public function deleteField($appId, $id)
    {
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->delete($id, ['app_id'=>$appId]);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
        }
        
        return $count;
    }
    public function deleteFields($formId)
    {
        $this->beginTransaction();
        $count = 0;
        try {
            $delete = "DELETE ox_field from ox_field INNER JOIN ox_form_field ON ox_form_field.field_id=ox_field.id where ox_form_field.form_id=".$formId.";";
            $result = $this->runGenericQuery($delete);
            if ($count == 0) {
                $this->rollback();
                return 0;
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
        }
        
        return $count;
    }

    public function getFields($appId=null, $filterArray = array())
    {
        if (isset($appId)) {
            $filterArray['app_id'] = $appId;
        }
        $resultSet = $this->getDataByParams('ox_field', array("*"), $filterArray, null);
        $response = array();
        $response['data'] = $resultSet->toArray();
        return $response;
    }
    public function getField($appId, $id)
    {
        $sql = $this->getSqlObject();
        $select = $sql->select();
        $select->from('ox_field')
        ->columns(array("*"))
        ->where(array('id' => $id,'app_id'=>$appId));
        $response = $this->executeQuery($select)->toArray();
        if (count($response)==0) {
            return 0;
        }
        return $response[0];
    }
}
