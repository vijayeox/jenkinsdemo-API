<?php

namespace PaymentGateway\Model;

use Oxzion\Db\ModelTable;
use Oxzion\Model\Entity;
use Zend\Db\TableGateway\TableGatewayInterface;

class PaymentTransactionTable extends ModelTable
{
    public function __construct(TableGatewayInterface $tableGateway)
    {
        parent::__construct($tableGateway);
    }

    public function save(Entity $data)
    {
        return $this->internalSave($data->toArray());
    }
}
