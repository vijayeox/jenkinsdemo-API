<?php
namespace Widget\Model;

use Bos\Db\ModelTable;
use Bos\Model\Entity;
use Zend\Db\TableGateway\TableGatewayInterface;

class WidgetTable extends ModelTable {
	public function __construct(TableGatewayInterface $tableGateway) {
        parent::__construct($tableGateway);
    }

    public function save(Entity $data){
    	return $this->internalSave($data->toArray());
    }
}