<?php
namespace Oxzion\Search;

interface Indexer
{

    public function index($app_id,$id,$type,$body);

    public function delete($app_id,$id);

}
?>