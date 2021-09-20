<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\DelegateException;
use Oxzion\AppDelegate\PrehireTrait;
use function GuzzleHttp\json_decode;

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

        $dataToPost['CreateApplicant']['driver_applicant']['id'] = '123456';
        $dataToPost['CreateApplicant']['driver_applicant']['report_id'] = 'test123456';

        $dataToPost['CreateApplicant']['PersonalData']['ClientReferenceId'] = '123456';
        $dataToPost['CreateApplicant']['PersonalData']['FirstName'] = 'Megha';
        $dataToPost['CreateApplicant']['PersonalData']['MiddleName'] = '';
        $dataToPost['CreateApplicant']['PersonalData']['LastName'] = 'Gupta';
        $dataToPost['CreateApplicant']['PersonalData']['ZipCode'] = '33333';
        $dataToPost['CreateApplicant']['PersonalData']['State'] = 'GA';
        $dataToPost['CreateApplicant']['PersonalData']['City'] = 'mnbnm';
        $dataToPost['CreateApplicant']['PersonalData']['StreetAddress'] = 'cfdcede';
        $dataToPost['CreateApplicant']['PersonalData']['PhoneNumber'] = '123456789';
        $dataToPost['CreateApplicant']['PersonalData']['EmailAddress'] = 'foleyapitest@abc.com';
        $dataToPost['CreateApplicant']['PersonalData']['IDCountry'] = 'US';
        $dataToPost['CreateApplicant']['PersonalData']['IDType'] = 'SSN';
        $dataToPost['CreateApplicant']['PersonalData']['IDNumber'] = '12tt456789';
        $dataToPost['CreateApplicant']['PersonalData']['DateofBirth'] = '09/18/1992'; //mm/dd/yyyy
        $dataToPost['CreateApplicant']['PersonalData']['GenderCode'] = 'F';

        $dataToPost['CreateApplicant']['Screenings']['SearchType']['@type'] = 'x:mvr';
        $dataToPost['CreateApplicant']['Screenings']['SearchType']['DriverLicenseNumber'] = '026409105';
        $dataToPost['CreateApplicant']['Screenings']['SearchType']['DriverLicenseState'] = 'GA';
        $dataToPost['CreateApplicant']['Screenings']['SearchType']['MVRCurrentState'] = "true";
        $dataToPost['CreateApplicant']['Screenings']['SearchType']['CDLFlag'] = "true";
        $dataToPost['CreateApplicant']['Screenings']['SearchType']['ScreeningReferenceID'] = '9876';
        $dataToPost['CreateApplicant']['Screenings']['SearchType']['ClientReferenceId'] = '123456';
        $dataToPost['CreateApplicant']['Screenings']['SearchType']['ScreeningStatus'] = 'NEW';
        
        $result = $this->createApplicantShell('createapplicant/',$dataToPost);
        print_r($result);
    }

    

}