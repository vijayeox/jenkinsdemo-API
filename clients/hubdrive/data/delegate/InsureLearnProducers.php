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
        
        $data['companyName'] = 'Hub International';
        $contractorGroup = $this->insuranceService->search('groupName', [
            'groupName' => $data['companyName']
        ]);
        
        /*if (empty($contractorGroup['group'])) {
            $this->insuranceService->create('group', [
                'groupName' => $data['companyName'],
                'groupTypeID' => 2,
                'groupDescription' => 'created by eox vantage',
                'userData' => $data['companyName']
            ]);
            $contractorGroup = $this->insuranceService->search('groupName', [
                'groupName' => $data['companyName']
            ]);
        }*/
        $this->logger->info("Contractor Group". print_r($contractorGroup, true));
        //$this->checkManager($data, $contractorGroup['group']);
        $this->checkProducer($data, $contractorGroup['group']);
        $this->logger->info("HERE data INSURE LEARN ADDEDD");
        return $data;
    }

    

    private function checkProducer($data, $group)
    {
        $data['email'] = strtolower($data['email']);
        $dataUser = $this->insuranceService->search('userData', [
            'loginID' => $data['email']
        ]);
        $dataUserData = [
            'loginID' => $data['email'],
            'userData' => $data['email'],
            'passwd' => 'Welcome2eox!',
            'firstName' => $data['firstName'],
            'middleName' => $data['middleName'],
            'lastName' => $data['lastName'],
            'email' => $data['email']
        ];
        if (empty($dataUser['userProfiles'])) {
            $this->insuranceService->create('user', $dataUserData + [
                'accessLevel' => 1,
                'accessMode' => 0,
                'roleName' => 'Hub Producer',
                'locationName' => $group['@attributes']['groupName']
            ]);
            $dataUser = $this->insuranceService->search('userData', [
                'loginID' => $data['email']
            ]);
        } else {
            $this->insuranceService->create('user', $dataUserData);
            $dataUser = $dataUser['userProfiles']['userProfile'];
            $this->insuranceService->perform('group', [
                'groupID' => $group['@attributes']['groupID'],
                'groupTypeID' => 1,
                'userProfileID' => $dataUser['@attributes']['userProfileID']
            ], 'adduser');
            $groupdatas[] = $dataUser['@attributes']['userProfileID'];
        }
        
        if (!empty($groupdatas)) {
            $this->insuranceService->perform('group', [
                'groupID' => $group['@attributes']['groupID'],
                'groupTypeID' => 2,
                'userProfileID' => implode('|', $groupdatas)
            ], 'adduser');
        }
    }

    private function getSampleData()
    {
        /*return json_decode('{
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
        }', true);*/

        return json_decode('{
            "email": "supreetha@eoxvantage.com",
            "firstName": "Supreetha",
            "middleName" : "",
            "lastName": "D"
            
            
        }', true);
    }

}