<?php

namespace SplashPage\Model;

use Bos\Db\ModelTable;
use Bos\Model\Entity;
use Zend\Db\TableGateway\TableGatewayInterface;

class SplashPageTable extends ModelTable {
    protected $tableGateway;
	public function __construct(TableGatewayInterface $tableGateway) {
        parent::__construct($tableGateway);
        $this->tableGateway = $tableGateway;
    }

    public function save(Entity $data){
        $data = $data->toArray();
    	return $this->internalSave($data);
    }
}