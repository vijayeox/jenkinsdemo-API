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
        $this->log->info(__CLASS__ . "-> \n Create new Address for the Organization - " . print_r($data, true));
        $form = new Address($data);
        $form->validate();
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->save($form);
            if ($count == 0) {
                $this->rollback();
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
        $this->beginTransaction();
        $count = 0;
        try {
            $count = $this->table->save($form);
            $this->commit();
        } catch (Exception $e) {
            throw $e;
        }
    }

}
