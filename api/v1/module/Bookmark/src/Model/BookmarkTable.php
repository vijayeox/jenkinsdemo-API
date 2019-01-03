<?php

namespace Bookmark\Model;

use Bos\Db\ModelTable;
use Zend\Db\TableGateway\TableGatewayInterface;
use Bos\Model\Entity;

class BookmarkTable extends ModelTable {

    public function __construct(TableGatewayInterface $tableGateway) {
        parent::__construct($tableGateway);
    }

    public function save(Entity $data) {
        return $this->internalSave($data->toArray());
    }

}
