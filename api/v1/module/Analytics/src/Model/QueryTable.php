<?php

namespace Analytics\Model;

use Oxzion\Db\ModelTable;
use Zend\Db\TableGateway\TableGatewayInterface;
use Oxzion\Model\Entity;

class QueryTable extends ModelTable {
    public function __construct(TableGatewayInterface $tableGateway) {
        parent::__construct($tableGateway);
    }
}
