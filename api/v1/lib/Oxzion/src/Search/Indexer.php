<?php
namespace Oxzion\Search;

interface Indexer
{
    public function index($appId, $id, $type, $body, $fieldtypearray);

    public function delete($appId, $id);
}
