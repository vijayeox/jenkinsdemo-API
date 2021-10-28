<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\AppDelegate\InsuranceTrait;

class InsureLearnIntegration extends AbstractAppDelegate
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
        // if (!empty($data['debug'])) $data = $this->getSampleData();
        $this->logger->info("HERE INSURE LEARN");
        if ($data['insureLearnIntegration'] == 'no') return $data;

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
        foreach ($data['driverDataGrid'] as $driver) {
            $driver['driverEmail'] = strtolower($driver['driverEmail']);
            $driverUser = $this->insuranceService->search('userData', [
                'loginID' => $driver['driverEmail']
            ]);
            $driverUserData = [
                'loginID' => $driver['driverEmail'],
                'userData' => $driver['driverEmail'],
                'passwd' => 'Welcome2eox!',
                'firstName' => $driver['driverFirstName'],
                'middleName' => $driver['driverMiddleName'],
                'lastName' => $driver['driverLastName'],
                'email' => $driver['driverEmail']
            ];
            if (empty($driverUser['userProfiles'])) {
                $this->insuranceService->create('user', $driverUserData + [
                    'accessLevel' => 1,
                    'accessMode' => 0,
                    'roleName' => 'IC Driver - English',
                    'locationName' => $group['@attributes']['groupName']
                ]);
                $driverUser = $this->insuranceService->search('userData', [
                    'loginID' => $driver['driverEmail']
                ]);
            } else {
                $this->insuranceService->create('user', $driverUserData);
                $driverUser = $driverUser['userProfiles']['userProfile'];
                $this->insuranceService->perform('group', [
                    'groupID' => 12,
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
        return json_decode('{"accountNumber":"","accountNumber1":"","account_id":1837,"ackAgreement":"yes","ackDate":"2021-10-11T00:00:00+05:30","ackLocation":"asdasfdsf","ackName":"ACDF","address1":"sgdfg","appId":"a4b1f073-fc20-477f-a804-1aa206938c42","areYouASoleProprietorIndividual":"yes","areyouinvolvedinanyotherbusinessendeavorotherthantrucking":"","attachments":[{"docId":"de8d6e22-9bd0-4047-831a-63e10eda9a3a","file":"6b88905a-fa7b-47a4-af18-a5eed6ade5c5//CC.pdf","fullPath":"/app/api/v1/config/autoload/../../data/file_docs/6b88905a-fa7b-47a4-af18-a5eed6ade5c5//CC.pdf","originalName":"CC.pdf","signingLink":"https://lab.insuresign.com?d=ZGU4ZDZlMjItOWJkMC00MDQ3LTgzMWEtNjNlMTBlZGE5YTNhJmZzZGZAZmRoZmcuc2RnZA==","status":"UNSIGNED","type":"file/pdf"}],"authorizeBackgroundCheckOwnerUpload":[],"backgroundCheckVendorInfoUpload":[],"businessArticlesUpload":[],"city":"dfgdfg","coiComplianceUpload":[],"coiUpload":[],"commands":[{"command":"verify_user"},{"command":"delegate","delegate":"ICRegister","entity_name":"Independent Contractor Onboarding"},{"command":"delegate","delegate":"DriverRegister","entity_name":"Independent Contractor Onboarding"},{"command":"setupBusinessRelationship"},{"command":"fileSave","entity_name":"Independent Contractor Onboarding"},{"command":"delegate","delegate":"OnboardingCompletionMail","entity_name":"Independent Contractor Onboarding"},{"command":"delegate","delegate":"ZenDriveFleetIntegration","entity_name":"Independent Contractor Onboarding"}],"companyName":"EOX Test","contractDate":"2021-10-20T00:00:00+05:30","created_by":7,"data":{},"date_created":"2021-10-22 12:08:21","doYouHaveInsurance":"yes","documentsigner":"CC.pdf","doesYourCompanyHaveACurrentBackgroundCheckVendor":"","doesYourCompanyHaveACurrentDrugScreenVendor":"","dotPermitUpload":[],"driverDataGrid":[{"doesthedriverhave2YearsofcommercialdrivingexperienceinNorthAmerica":"","driverDateofBirth":"2021-10-25T00:00:00+05:30","driverEmail":"himanshu@eoxvantage.com","driverFirstName":"Himanshu","driverId":1,"driverLastName":"S","driverLicense":"23535","driverMiddleName":"","driverSsn":"XXX3253552","pleaseindicatetypeofdriver":"","pleaseselectthepaidbyoption":""},{"doesthedriverhave2YearsofcommercialdrivingexperienceinNorthAmerica":"","driverDateofBirth":"2021-10-18T00:00:00+05:30","driverEmail":"mehul@eoxvantage.com","driverFirstName":"Mehul","driverId":2,"driverLastName":"Kenia","driverLicense":"353566","driverMiddleName":"","driverSsn":"XXX3523555","pleaseindicatetypeofdriver":"","pleaseselectthepaidbyoption":""}],"email":"varun@eoxvantage.com","end_date":"2021-10-22","entity_id":2,"entity_name":"Independent Contractor Onboarding","eulaAck":"yes","fileTitle":"Independent Contractor Onboarding sddgsdg sdgdfgdf","firstname":"Varun","freightBrokerAgreementUpload":[],"freightBrokerDetailsTerminal":"atlanta","identifier_field":"email","ifNoEvidenceSignUpWithIntellishieldOrFoley":{"":false,"no":false,"yes":false},"ifNoEvidenceSignupWithArcpoint":{"":false,"no":false,"yes":false},"laborInfoDuties":"","laborInfoName":"","lastname":"Badarinath","medicalExamCertificateUpload":[],"mvrUpload":[],"name":"Varun Badarinath","operatingAuthorityUpload":[],"pdfDateCC":"2021-10-20","personalInfoDOB":"","personalInfoDOT":"","personalInfoHomePhone":"(124) 242-3523","personalInfoPhoneNumber":"","personalInfoSSN":"XXX2424444","personalInfoSecondaryEmail":"","personalInfoStateIssued":{},"personalInfoStateUnemploymentId":"","personalInfoWebsite":"","phone":"(235) 235-3253","pleaseselectthejobtype":"","pleaseselectthepaidbyoption":"","privacyNoticeAck":"yes","product":"fullTimeIC","roadTestCertificationUpload":[],"rygStatus":"GREEN","start_date":"2021-10-22","stateJsonListNew":[],"stateObj":{"abbreviation":"CO","name":"Colorado"},"statePermitUpload":[],"subcontractorAgreementAck":"yes","termsAndConditionsAck":"yes","title":"","unitDataGrid":[{"addresswheretheunitisgaraged":"","doYouWantToAddAdditionalInsured":"","doesTheUnitHaveADriver":"","isthisunitleasedorfinanced":"","page7PanelWell3Doyouwanttoadddriverdetails":"","registeredownerfullname":"","unitGaragingCity":"","unitGaragingState":"","unitMake":"","unitModel":"","unitVin":"","zipCode":""}],"uuid":"8b723578-308d-44f0-8405-bcf5c797a7b0","venderContactName":"","venderContactName1":"","vendorEmail":"","vendorEmail1":"","vendorName":"","vendorName1":"","vendorPhone":"","vendorPhone1":"","version":1,"wouldYouLikeAnInsuranceQuote":"yes","wouldYouLikeToAddDriverDetails":"yes","insureLearnIntegration":"yes","zip":23523}', true);
    }

}