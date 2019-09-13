<?php
namespace Screen\Model;

use Oxzion\Db\ModelTable;
use Oxzion\Model\Entity;
use Zend\Db\TableGateway\TableGatewayInterface;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;

class ScreenwidgetTable extends ModelTable {
	public function __construct(TableGatewayInterface $tableGateway) {
        parent::__construct($tableGateway);
    }

    public function save(Entity $data){
    	return $this->internalSave($data->toArray());
    }

}