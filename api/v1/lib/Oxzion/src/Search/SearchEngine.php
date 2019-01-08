<?php
namespace Oxzion\Search;

interface SearchEngine
{
    public function __construct($config);

    public function search($parameters);

    public function index($parameters);

}
?>