<?php
namespace Oxzion\Search;

interface Indexer
{
    public function  index($app_name, $id, $entity_name, $body, $fieldTypeAarray=null);

    public function delete($appId, $id);
}
