<?php
namespace Oxzion\Model;

use Oxzion\Db\ModelTable;
use Oxzion\Model\Entity;
use Zend\Db\TableGateway\TableGatewayInterface;

class UserTable extends ModelTable
{
    public function __construct(TableGatewayInterface $tableGateway)
    {
        parent::__construct($tableGateway);
        $this->tableGateway = $tableGateway;
    }
}
