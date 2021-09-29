<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;


class CleanUpDelegate extends AbstractAppDelegate
{
	public function __construct()
    {
        parent::__construct();
    }

    // Premium Calculation values are fetched here
    public function execute(array $data, Persistence $persistenceService)
    {
		if(isset($data['stateListJson'])){
            unset($data['stateListJson']);
        }

    	return $data;
    }

 }