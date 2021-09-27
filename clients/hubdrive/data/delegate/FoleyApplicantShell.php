<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\DelegateException;
use Oxzion\AppDelegate\PrehireTrait;
use Oxzion\Utils\UuidUtil;

class FoleyApplicantShell extends AbstractAppDelegate
{
    const APPID = 'a4b1f073-fc20-477f-a804-1aa206938c42';
    use PrehireTrait;
    

    public function __construct()
    {
        parent::__construct();
        
    }

    public function execute(array $data, Persistence $persistenceService)
    {   
        
        $this->logger->info("in foley delegate- " . json_encode($data, JSON_UNESCAPED_SLASHES));
        $driver_id = UuidUtil::uuid();
        $dataToPost = array();
        $dataToPost['CreateApplicant']['Account']['FoleyAccountCode'] = '0000136757'; //config
        $dataToPost['CreateApplicant']['Account']['AccountName'] = 'Hub International Limited'; //config
        $dataToPost['CreateApplicant']['Account']['DOTNumber'] = '';
        $dataToPost['CreateApplicant']['Account']['FoleyAccountID'] = '';
        $dataToPost['CreateApplicant']['Account']['ClientCode'] = '';
        
        $dataToPost['CreateApplicant']['Results']['Status'] = '';
        $dataToPost['CreateApplicant']['Results']['Result'] = '';
        $dataToPost['CreateApplicant']['Results']['DateReceived'] = '';

        $dataToPost['CreateApplicant']['ClientReferences']['Subcode'] = '';
        $dataToPost['CreateApplicant']['ClientReferences']['Location'] = '';
        $dataToPost['CreateApplicant']['ClientReferences']['CustomerReference'] = '';
        $dataToPost['CreateApplicant']['ClientReferences']['RequireMVR'] = '';
        $dataToPost['CreateApplicant']['ClientReferences']['RequireDrug'] = '';
        $dataToPost['CreateApplicant']['ClientReferences']['JobCategory'] = '';
        $dataToPost['CreateApplicant']['ClientReferences']['JobCode'] = '';
        $dataToPost['CreateApplicant']['ClientReferences']['JobDescription'] = '';
        $dataToPost['CreateApplicant']['ClientReferences']['LocationCode'] = '';
        $dataToPost['CreateApplicant']['ClientReferences']['WorkCountry'] = '';
        $dataToPost['CreateApplicant']['ClientReferences']['RequestorName'] = ''; //IC Name
        $dataToPost['CreateApplicant']['ClientReferences']['RequestorPhone'] = '';
        $dataToPost['CreateApplicant']['ClientReferences']['RequestorEmail'] = ''; //IC Email
        $dataToPost['CreateApplicant']['ClientReferences']['TalentCoordinator'] = '';
        $dataToPost['CreateApplicant']['ClientReferences']['TalentCoordinatorEmail'] = '';

        $dataToPost['CreateApplicant']['driver_applicant']['id'] = $driver_id;
        $dataToPost['CreateApplicant']['driver_applicant']['report_id'] = 'driverreport'.$driver_id;

        $dataToPost['CreateApplicant']['PersonalData']['ClientReferenceId'] = $driver_id;
        $dataToPost['CreateApplicant']['PersonalData']['FirstName'] = $data['firstName'];
        $dataToPost['CreateApplicant']['PersonalData']['MiddleName'] = '';
        $dataToPost['CreateApplicant']['PersonalData']['LastName'] = $data['lastName'];
        $dataToPost['CreateApplicant']['PersonalData']['ZipCode'] = $data['zipCode'];
        $dataToPost['CreateApplicant']['PersonalData']['State'] = 'GA';//$data['state1];
        $dataToPost['CreateApplicant']['PersonalData']['City'] = $data['city'];
        $dataToPost['CreateApplicant']['PersonalData']['StreetAddress'] = $data['streetAddress'];
        $dataToPost['CreateApplicant']['PersonalData']['PhoneNumber'] = '123456789';
        $dataToPost['CreateApplicant']['PersonalData']['EmailAddress'] = $data['email'];
        $dataToPost['CreateApplicant']['PersonalData']['IDCountry'] = 'US';
        $dataToPost['CreateApplicant']['PersonalData']['IDType'] = 'SSN';
        $dataToPost['CreateApplicant']['PersonalData']['IDNumber'] = $data['ssn'];
        
        $dob = date_format(date_create(explode("T",$data['dob1'])[0]),'m/d/Y');

        $dataToPost['CreateApplicant']['PersonalData']['DateofBirth'] = $dob; 
        $dataToPost['CreateApplicant']['PersonalData']['GenderCode'] = 'F'; //$data['gender']

        $dataToPost['CreateApplicant']['Screenings']['SearchType']['@type'] = 'x:mvr';
        $dataToPost['CreateApplicant']['Screenings']['SearchType']['DriverLicenseNumber'] = $data['driverLicense'];
        $dataToPost['CreateApplicant']['Screenings']['SearchType']['DriverLicenseState'] = 'GA';//$data['licenseState1']
        $dataToPost['CreateApplicant']['Screenings']['SearchType']['MVRCurrentState'] = "true";
        $dataToPost['CreateApplicant']['Screenings']['SearchType']['CDLFlag'] = "true";
        $dataToPost['CreateApplicant']['Screenings']['SearchType']['ScreeningReferenceID'] = "driverscreening".$driver_id;
        $dataToPost['CreateApplicant']['Screenings']['SearchType']['ClientReferenceId'] = $driver_id;
        $dataToPost['CreateApplicant']['Screenings']['SearchType']['ScreeningStatus'] = 'NEW';
        
        $result = $this->createApplicantShell('createapplicant/',$dataToPost);
        $this->logger->info("in foley delegate respone - " . json_encode($result, JSON_UNESCAPED_SLASHES));
        //print_r($result);
    }

    

}