<?php

namespace Oxzion\Model\App;

use Oxzion\Db\ModelTable;
use Oxzion\Model\Entity;
use Zend\Db\TableGateway\TableGatewayInterface;

class EntityTable extends ModelTable
{
    public function __construct(TableGatewayInterface $tableGateway)
    {
        parent::__construct($tableGateway);
    }

}
