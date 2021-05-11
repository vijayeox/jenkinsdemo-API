<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\AppDelegate\AccountTrait;
use Oxzion\Utils\UuidUtil;

class ICRegister extends AbstractAppDelegate
{
    use AccountTrait;
    const APPID = 'a4b1f073-fc20-477f-a804-1aa206938c42';

    public function __construct()
    {
        parent::__construct();
    }

    public function execute(array $data, Persistence $persistenceService)
    {
        $this->logger->info("Executing IC Registration with data- " . json_encode($data, JSON_UNESCAPED_SLASHES));
        // Add logs for created by id and producer name who triggered submission
        $dataForIC = array();
        if (!isset($dataForIC['uuid'])) {
            $dataForIC['uuid'] = UuidUtil::uuid();
        }
        $dataForIC['name'] = $data['name'];
        $dataForIC['email'] = $dataForIC['iCEmail'] = $data['iCEmail'];
        $dataForIC['firstname'] = $data['iCFirstName'];
        $dataForIC['lastname'] = $data['IcLastName'];
        $dataForIC['address1'] = $data['street1IC'];
        $dataForIC['city'] = $data['city1IC'];
        if (!is_array($data['state'])) {
            $stateDecoded = json_decode($data['state'],true);
            $this->logger->info("stateDecoded- " . print_r($stateDecoded, true));
        }
        $dataForIC['state'] = isset($stateDecoded['abbreviation']) ? $stateDecoded['abbreviation'] : $data['state']['abbreviation'];
        $dataForIC['zip'] = $data['zipCode1IC'];
        $dataForIC['country'] = 'United States of America';
        if (!isset($dataForIC['contact'])) {
            $dataForIC['contact'] = array();
            $dataForIC['contact']['username'] = str_replace('@', '.', $data['iCEmail']);
            $dataForIC['contact']['firstname'] = $data['iCFirstName'];
            $dataForIC['contact']['lastname'] = $data['IcLastName'];
            $dataForIC['contact']['email'] = $data['iCEmail'];
        }
        if (!isset($dataForIC['preferences'])) {
            $dataForIC['preferences'] = '{}';
        }
        $dataForIC['app_id'] = self::APPID;
        $dataForIC['type'] = 'BUSINESS';
        $dataForIC['identifier_field'] = $data['identifier_field'];
        $this->registerAccount($dataForIC);
        return $data;
    }

}
