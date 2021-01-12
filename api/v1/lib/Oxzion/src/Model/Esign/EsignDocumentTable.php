<?php

namespace Oxzion\Model\Esign;

use Oxzion\Db\ModelTable;
use Zend\Db\TableGateway\TableGatewayInterface;
use Oxzion\MultipleRowException;

class EsignDocumentTable extends ModelTable
{
    public function __construct(TableGatewayInterface $tableGateway) {
        parent::__construct($tableGateway);
    }

    public function getByDocId($docId){
        $this->init();

        $filter["doc_id"] = $docId;
        $rowset = $this->tableGateway->select(array());
        if (0 == count($rowset)) {
            return null;
        }
        if (count($rowset) > 1) {
            throw new MultipleRowException('Multiple rows found when queried by docId.',
                ['table' => $this->tableGateway->getTable(), 'doc_id' => $docId]);
        }
        $row = $rowset->current();
        return $row;
    }
}
