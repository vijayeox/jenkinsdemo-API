<?php
namespace Oxzion\Service;

use Exception;
use Oxzion\Model\Employee;
use Oxzion\Model\EmployeeTable;
use Oxzion\ServiceException;
use Oxzion\Service\AbstractService;
use Oxzion\Utils\UuidUtil;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;

class EmployeeService extends AbstractService
{
    protected $table;
    protected $modelClass;
    /**
     * @ignore __construct
     */
    public function __construct($config, $dbAdapter, EmployeeTable $table)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
        $this->modelClass = new Employee();
    }

    public function addEmployeeRecord(&$data)
    {
        $this->logger->info("Adding Employee Record - " . print_r($data, true));
        $EmpData = $data;
        $EmpData['uuid'] = UuidUtil::uuid();
        $EmpData['created_by'] = AuthContext::get(AuthConstants::USER_ID) ? AuthContext::get(AuthConstants::USER_ID) : 1;
        $EmpData['date_created'] = date('Y-m-d H:i:s');
        $EmpData['org_id'] = $EmpData['orgid'];
        $orgProfile = $this->getDataByParams('ox_organization', array('org_profile_id'), array('id' => $EmpData['orgid']))->toArray();
        $EmpData['org_profile_id'] = $orgProfile[0]['org_profile_id'];
        if (!isset($EmpData['date_of_join'])) {
            $EmpData['date_of_join'] = date('Y-m-d');
        }
        if (!isset($EmpData['designation'])) {
            $EmpData['designation'] = "Staff";
        }
        
        $form = new Employee($EmpData);
        $form->validate();
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->save($form);
            if ($count == 0) {
                $this->rollback();
                throw new ServiceException("Failed to add the Employee Record", "failed.add.employee");
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
    }

    public function updateEmployeeDetails($id, $data)
    {
        $this->logger->info("Employee ID--------\n".print_r($id,true));
        $obj = $this->table->get($id, array());
        if (is_null($obj)) {
            throw new ServiceException("Employee not found", "employee.not.found");
        } 
        
        unset($data['id']);
        $EmpData = $data;
        $EmpData['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $EmpData['date_modified'] = date('Y-m-d H:i:s');
        $orgProfile = $this->getDataByParams('ox_organization', array('org_profile_id'), array('id' => $EmpData['orgid']))->toArray();
        $EmpData['org_profile_id'] = $orgProfile[0]['org_profile_id'];
        $form = new Employee();
        $changedArray = array_merge($obj->toArray(), $EmpData);
        $form->exchangeArray($changedArray);
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->save($form);
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
    }

}
