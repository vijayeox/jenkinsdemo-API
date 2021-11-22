<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\AppDelegate\InsuranceTrait;

class insureLearnInsuredCreation extends AbstractAppDelegate
{
    use InsuranceTrait;

    public function __construct()
    {
        parent::__construct();
    }

    public function execute(array $data, Persistence $persistenceService)
    {
        $this->logger->info("HERE INSURE LEARN".print_r($data,true));
        $this->setInsuranceConfig([
            "client" => "InsureLearn"
        ]);
        $this->logger->info("HERE INSURE LEARN");

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
        $this->checkManager($data, $contractorGroup['group']);
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
                'accessMode' => 4096,
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
}