<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\AppDelegate\InsuranceTrait;

class InsureLearnProducers extends AbstractAppDelegate
{
    use InsuranceTrait;

    public function __construct()
    {
        parent::__construct();
    }

    public function execute(array $data, Persistence $persistenceService)
    {
        $this->logger->info("HERE INSURE LEARN PRODUCERS".print_r($data,true));
        $this->setInsuranceConfig([
            "client" => "InsureLearn"
        ]);
        // if (!empty($data['debug'])) $data = $this->getSampleData();
        $this->logger->info("HERE INSURE LEARN");
        //if ($data['insureLearnIntegration'] == 'no') return $data;

        $contractorGroup = $this->insuranceService->search('groupName', [
            'groupName' => $data['companyName']
        ]);
        
        if (empty($contractorGroup['group'])) {
            $this->insuranceService->create('group', [
                'groupName' => $data['companyName'],
                'groupTypeID' => 2,
                'groupDescription' => 'created by eox vantage',
                'userData' => $data['companyName']
            ]);
            $contractorGroup = $this->insuranceService->search('groupName', [
                'groupName' => $data['companyName']
            ]);
        }
        $this->logger->info("Contractor Group". print_r($contractorGroup, true));
        //$this->checkManager($data, $contractorGroup['group']);
        $this->checkDriver($data, $contractorGroup['group']);
        $this->logger->info("HERE DRIVER INSURE LEARN ADDEDD");
        return $data;
    }

    private function checkManager($data, $group)
    {
        $data['email'] = strtolower($data['email']);
        $contractorUser = $this->insuranceService->search('userData', [
            'loginID' => $data['email']
        ]);
        $contractorUserData = [
            'loginID' => $data['email'],
            'userData' => $data['email'],
            'passwd' => 'Welcome2eox!',
            'firstName' => $data['firstname'],
            'lastName' => $data['lastname'],
            'addr1' => $data['address1'],
            'city' => $data['city'],
            'state' => $data['stateObj']['abbreviation'],
            'zip' => $data['zip'],
            'email' => $data['email'],
            'phone' => $data['phone']
        ];
        if (empty($contractorUser['userProfiles'])) {
            $this->insuranceService->create('user', $contractorUserData + [
                'accessLevel' => 2,
                'accessMode' => 5400,
                'roleName' => 'Safety Director/Manager/Owner',
                'locationName' => $group['@attributes']['groupName']
            ]);
            $contractorUser = $this->insuranceService->search('userData', [
                'loginID' => $data['email']
            ]);
            $contractorUser = $contractorUser['userProfiles']['userProfile'];
        } else {
            $contractorUser = $contractorUser['userProfiles']['userProfile'];
            $this->insuranceService->create('user', $contractorUserData);
            $this->insuranceService->perform('group', [
                'groupID' => $group['@attributes']['groupID'],
                'groupTypeID' => 2,
                'userProfileID' => $contractorUser['@attributes']['userProfileID']
            ], 'adduser');
            $this->insuranceService->perform('group', [
                'groupID' => 8,
                'groupTypeID' => 1,
                'userProfileID' => $contractorUser['@attributes']['userProfileID']
            ], 'adduser');
        }

        $this->insuranceService->perform('group', [
            'groupID' => $group['@attributes']['groupID'],
            'groupTypeID' => 2,
            'userProfileID' => $contractorUser['@attributes']['userProfileID']
        ], 'addmanager');
    }

    private function checkDriver($data, $group)
    {
        foreach ($data['userGrid'] as $driver) {
            $driver['email'] = strtolower($driver['email']);
            $driverUser = $this->insuranceService->search('userData', [
                'loginID' => $driver['email']
            ]);
            $driverUserData = [
                'loginID' => $driver['email'],
                'userData' => $driver['email'],
                'passwd' => 'Welcome2eox!',
                'firstName' => $driver['firstName'],
                'middleName' => $driver['middleName'],
                'lastName' => $driver['lastName'],
                'email' => $driver['email']
            ];
            if (empty($driverUser['userProfiles'])) {
                $this->insuranceService->create('user', $driverUserData + [
                    'accessLevel' => 1,
                    'accessMode' => 0,
                    'roleName' => 'Hub Producer',
                    'locationName' => $group['@attributes']['groupName']
                ]);
                $driverUser = $this->insuranceService->search('userData', [
                    'loginID' => $driver['email']
                ]);
            } else {
                $this->insuranceService->create('user', $driverUserData);
                $driverUser = $driverUser['userProfiles']['userProfile'];
                $this->insuranceService->perform('group', [
                    'groupID' => $group['@attributes']['groupID'],
                    'groupTypeID' => 1,
                    'userProfileID' => $driverUser['@attributes']['userProfileID']
                ], 'adduser');
                $groupDrivers[] = $driverUser['@attributes']['userProfileID'];
            }
        }
        if (!empty($groupDrivers)) {
            $this->insuranceService->perform('group', [
                'groupID' => $group['@attributes']['groupID'],
                'groupTypeID' => 2,
                'userProfileID' => implode('|', $groupDrivers)
            ], 'adduser');
        }
    }

    private function getSampleData()
    {
        return json_decode('{
            "appId": "a4b1f073-fc20-477f-a804-1aa206938c42",
            "city": "dfgdfg",
            "companyName": "HUB EOX",
            "date_created": "2021-10-22 12:08:21",
            "userGrid": [{
                "dateofBirth": "2021-10-25T00:00:00+05:30",
                "email": "meghag@eoxvantage.com",
                "firstName": "Megha",
                "lastName": "Gupta"
            }],
            "email": "meghana@eoxvantage.com",
            "firstname": "Meghana",
            "lastname": "Patil",
            "name": "Meghana Patil"
        }', true);
    }

}