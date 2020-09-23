<?php
namespace Oxzion\Service;

use Exception;
use Oxzion\Model\Person;
use Oxzion\Model\PersonTable;
use Oxzion\ServiceException;
use Oxzion\Service\AbstractService;
use Oxzion\Utils\UuidUtil;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;

class PersonService extends AbstractService
{
    protected $table;
    protected $modelClass;
    private $addressService;
    /**
     * @ignore __construct
     */
    public function __construct($config, $dbAdapter,AddressService $addressService, PersonTable $table)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
        $this->modelClass = new Person();
        $this->addressService = $addressService;
    }

    public function addPerson(&$data)
    {
        $this->logger->info("Adding Person - " . print_r($data, true));
        $personData = $data;
        $personData['uuid'] = UuidUtil::uuid();
        $personData['created_by'] = AuthContext::get(AuthConstants::USER_ID) ? AuthContext::get(AuthConstants::USER_ID) : 1;
        $personData['date_created'] = date('Y-m-d H:i:s');
        $addressid = $this->addressService->addAddress($personData);
        $personData['address_id'] = $addressid;
        if (!isset($personData['date_of_birth'])) {
            $personData['date_of_birth'] = date('Y-m-d');
        }
        
        $person = new Person($personData);
        $person->validate();
        $count = 0;
        try {
            $this->beginTransaction();
            $count = $this->table->save($person);
            if ($count == 0) {
                $this->rollback();
                throw new ServiceException("Failed to add the Person record", "failed.add.Person");
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
        $data['person_id'] = $this->table->getLastInsertValue();
        return $data;
    }

    public function updatePerson($id, $data)
    {
        $obj = $this->table->get($id, array());
        if (is_null($obj)) {
            throw new ServiceException("Person not found", "person.not.found");
        }      
        unset($data['id']);
        unset($data['uuid']);
        $this->logger->info("Person DATA--------\n".print_r($data,true));
        $personData = $data;
        $personData['modified_by'] = AuthContext::get(AuthConstants::USER_ID);
        $personData['date_modified'] = date('Y-m-d H:i:s');
        try {
            $this->beginTransaction();
            if (isset($personData['address_id'])) {
                $this->addressService->updateAddress($personData['address_id'], $personData);
            }else{
                if(!empty($personData['address1']) || !empty($personData['city']) || 
                            !empty($personData['state']) || !empty($personData['country']) || !empty($personData['zip'])) {
                    $addressid = $this->addressService->addAddress($personData);
                    $personData['address_id'] = $addressid;
                }

            }
            $person = new Person();
            $changedArray = array_merge($obj->toArray(), $personData);
            $this->logger->info("Person Data changedArray".print_r($changedArray,true));
            $person->exchangeArray($changedArray);
            $person->validate();
            $this->logger->info("Person ".print_r($person,true));
            $this->table->save($person);
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
    }

}
