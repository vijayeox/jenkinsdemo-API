<?php
namespace Oxzion\Service;

use Exception;
use Oxzion\Model\Address;
use Oxzion\Model\AddressTable;
use Oxzion\ServiceException;
use Oxzion\Service\AbstractService;

class AddressService extends AbstractService
{
    protected $table;
    protected $modelClass;
    /**
     * @ignore __construct
     */
    public function __construct($config, $dbAdapter, AddressTable $table)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
        $this->modelClass = new Address();
    }

    public function addAddress($data)
    {
        $this->logger->info("Create new Address - " . print_r($data, true));
        $form = new Address($data);
        $form->validate();
        $count = 0;
        try {
            $this->beginTransaction();
            $count = $this->table->save($form);
            if ($count == 0) {
                throw new ServiceException("Failed to add the address", "failed.add.address");
            }
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
        return $this->table->getLastInsertValue();
    }

    public function updateAddress($id, $data)
    {
        $obj = $this->table->get($id, array());
        if (is_null($obj)) {
            throw new ServiceException("Address not found", "address.not.found");
        }
        $org = $obj->toArray();
        $form = new Address();
        $changedArray = array_merge($obj->toArray(), $data);
        $form->exchangeArray($changedArray);
        $form->validate();
        try {
            $this->beginTransaction();
            $this->table->save($form);
            $this->commit();
        } catch (Exception $e) {
            $this->rollback();
            throw $e;
        }
    }

    public function getOrganizationAddress($accountId)
    {
        $select = "SELECT oa.address1,oa.address2,oa.city,oa.state,oa.country,oa.id,oa.zip
                    from ox_account as acct 
                    join ox_organization as org on org.id=acct.organization_id 
                    join ox_address 
                    as oa on org.address_id = oa.id  
                    where acct.uuid = :accountId";
        $params = array("accountId"=> $accountId);
        $this->logger->info("Executing Query $select with params - ".print_r($params, true));
        $result = $this->executeQueryWithBindParameters($select,$params)->toArray();
        return count($result) > 0 ? $result[0] : array() ;
    }
}
