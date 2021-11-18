<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\AppDelegate\UserContextTrait;
use Oxzion\AppDelegate\AccountTrait;
use Oxzion\Auth\AuthConstants;
use Oxzion\Auth\AuthContext;

class CleanUpData extends AbstractAppDelegate
{
	use UserContextTrait;
	use AccountTrait;

	private $persistenceService;

	public function __construct()
    {
        parent::__construct();
    }

    public function execute(array $data, Persistence $persistenceService)
    {
    	$this->logger->info("Clean Up data..!".print_r($data,true));

    	$fileData = $data;
    	if(isset($fileData['password'])){
			unset($fileData['password']);
		}

		if(isset($fileData['confirmPassword'])){
			unset($fileData['confirmPassword']);
		}

		if(isset($fileData['confirmationEmail'])){
			unset($fileData['confirmationEmail']);
		}

		if(isset($fileData['stateJson'])){
			unset($fileData['stateJson']);
		}

		if(isset($fileData['post_login_commands'])){
			unset($fileData['post_login_commands']);
		}

		if(isset($fileData['preferences'])){
			unset($fileData['preferences']);
		}

		if(isset($fileData['iCFirstName'])){
			unset($fileData['iCFirstName']);
		}

		if(isset($fileData['IcLastName'])){
			unset($fileData['IcLastName']);
		}

		if(isset($fileData['city1IC'])){
			unset($fileData['city1IC']);
		}

		if(isset($fileData['iCEmail'])){
			unset($fileData['iCEmail']);
		}

		if(isset($fileData['street1IC'])){
			unset($fileData['street1IC']);
		}

		if(isset($fileData['product'])){
			unset($fileData['product']);
		}

		if(isset($fileData['zipCode1IC'])){
			unset($fileData['zipCode1IC']);
		}

        $producerId = $this->getUserByUsername('HubAdmin');
		$fileData['producerId'] = $producerId[0]['uuid'];
	   	$this->logger->info("producerId..!".print_r($producerId,true));

	   	$icUserId = AuthContext::get(AuthConstants::USER_UUID);
	   	$icUserAccountId = AuthContext::get(AuthConstants::ACCOUNT_UUID);

	   	$fileData['ICUserId'] = $icUserId;
	   	$fileData['buyerAccountId'] = $icUserAccountId;
	   	
	   	$fileData['businessOffering'] = array();
	   	$fileData['businessOffering'][0]['businessRole'] = 'Independent Contractor';
        $fileData['businessOffering'][0]['entity'][0] = 'Driver';
        $this->setupBusinessOfferings($fileData, $icUserAccountId, $fileData['appId']);
        $this->logger->info("After filesave---" . print_r($dataForIC, true));

    	return $fileData;
    }
 }