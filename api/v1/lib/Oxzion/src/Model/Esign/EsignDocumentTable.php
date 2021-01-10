<?php

namespace Oxzion\Model\Esign;

use Oxzion\Db\ModelTable;
use Zend\Db\TableGateway\TableGatewayInterface;

class EsignDocumentTable extends ModelTable
{
    public function __construct(TableGatewayInterface $tableGateway) {
        parent::__construct($tableGateway);
    }

    public function getByDocId($docId){
        $this->init();
        if (is_null($filter)) {
            $filter = array();
        }

        $filter["doc_id"] = $docId;
        $rowset = $this->tableGateway->select($filter);
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
