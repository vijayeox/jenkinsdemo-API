<?php
namespace Oxzion\Search;

interface Indexer
{
    public function __construct($config);

    public function index($parameters,$app_id,$type);

}
?>