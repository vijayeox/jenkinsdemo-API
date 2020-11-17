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
        
        $person = new Person($this->table);
        $person->assign($personData);
        $count = 0;
        try {
            $this->beginTransaction();
            $person->save();
            $temp = $person->getGenerated(true);
            $data['person_id'] = $temp['id'];
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
    }

    public function updatePerson($id, $data)
    {
        $person = new Person($this->table);
        $person->loadById($id);
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
            $person->exchangeArray($personData);
            $person->save();
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
    }

}
