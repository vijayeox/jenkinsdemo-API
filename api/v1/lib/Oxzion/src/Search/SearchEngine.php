<?php
namespace Oxzion\Search;

interface SearchEngine
{
    public function search($parameters, $appId);
}
