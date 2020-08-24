<?php

namespace Analytics\Model;

use Oxzion\Db\ModelTable;
use Zend\Db\TableGateway\TableGatewayInterface;

class DataSourceTable extends ModelTable {
    public function __construct(TableGatewayInterface $tableGateway) {
        parent::__construct($tableGateway);
    }
}
