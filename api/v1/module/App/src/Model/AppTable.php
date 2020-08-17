<?php

namespace App\Model;

use Oxzion\Db\ModelTable;
use Zend\Db\TableGateway\TableGatewayInterface;
use Oxzion\Model\Entity;

class AppTable extends ModelTable
{
    public function __construct(TableGatewayInterface $tableGateway)
    {
        parent::__construct($tableGateway);
    }

    public function save(Entity $data)
    {
        return $this->internalSave($data->toArray());
    }

    public function save2(Entity $data) {
        return $this->internalSave2($data->toArray());
    }

    public function getByName($name)
    {
        $this->init();
        $filter = array();
        
        $filter['name'] = $name;

        $rowset = $this->tableGateway->select($filter);

        $row = $rowset->current();

        return $row;
    }

}
