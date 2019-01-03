<?php

namespace Oxzion\Model;

use Bos\Db\ModelTable;
use Bos\Model\Entity;
use Zend\Db\TableGateway\TableGatewayInterface;

class MetafielddTable extends ModelTable {

	public function __construct(TableGatewayInterface $tableGateway) {
        parent::__construct($tableGateway);
    }

    public function save(Entity $data){
    	return $this->internalSave($data->toArray());
    }
}