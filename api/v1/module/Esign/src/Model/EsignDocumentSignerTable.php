<?php

namespace Esign\Model;

use Oxzion\Db\ModelTable;
use Zend\Db\TableGateway\TableGatewayInterface;

class EsignDocumentSignerTable extends ModelTable
{
    public function __construct(TableGatewayInterface $tableGateway)
    {
        parent::__construct($tableGateway);
    }

}
