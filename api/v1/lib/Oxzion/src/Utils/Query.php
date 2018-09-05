<?php
namespace Oxzion\Utils;
use Zend\Db\Adapter\Adapter;
class Query {
    public static function queryExecute($select,$sql,$dbAdapter){
        $selectString = $sql->getSqlStringForSqlObject($select);
        $results = $dbAdapter->query($selectString, Adapter::QUERY_MODE_EXECUTE);
        return $results->toArray();
    }

}