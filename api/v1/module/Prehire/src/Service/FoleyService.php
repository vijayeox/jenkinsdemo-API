<?php
namespace Prehire\Service;

use Exception;
use Oxzion\EntityNotFoundException;
use Oxzion\ServiceException;
use Oxzion\OxServiceException;
use Oxzion\Service\AbstractService;
use Prehire\Model\Prehire;
use Prehire\Model\PrehireTable;
use Oxzion\Utils\RestClient;

class FoleyService extends AbstractService{

    private $table;
    /**
     * @ignore __construct
     */

    public function __construct($config, $dbAdapter, PrehireTable $table)
    {
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
        $this->restClient = new RestClient('https://sandboxapi.foleyservices.com/api/foley/v1.2/json/');
    }
    
    public function invokeApplicantShellCreationAPI($data){
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
        $dataToPost['CreateApplicant']['ClientReferences']['RequireMVR'] = true;
        $dataToPost['CreateApplicant']['ClientReferences']['RequireDrug'] = true;
        $dataToPost['CreateApplicant']['ClientReferences']['JobCategory'] = '';
        $dataToPost['CreateApplicant']['ClientReferences']['JobCode'] = '';
        $dataToPost['CreateApplicant']['ClientReferences']['JobDescription'] = '';
        $dataToPost['CreateApplicant']['ClientReferences']['LocationCode'] = '';
        $dataToPost['CreateApplicant']['ClientReferences']['WorkCountry'] = '';
        $dataToPost['CreateApplicant']['ClientReferences']['RequestorName'] = '';
        $dataToPost['CreateApplicant']['ClientReferences']['RequestorPhone'] = '';
        $dataToPost['CreateApplicant']['ClientReferences']['RequestorEmail'] = '';
        $dataToPost['CreateApplicant']['ClientReferences']['TalentCoordinator'] = '';
        $dataToPost['CreateApplicant']['ClientReferences']['TalentCoordinatorEmail'] = '';

        $dataToPost['CreateApplicant']['driver_applicant']['id'] = '12345';
        $dataToPost['CreateApplicant']['driver_applicant']['report_id'] = 'test12345';

        $dataToPost['CreateApplicant']['PersonalData']['ClientReferenceId'] = '12345';
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
        $dataToPost['CreateApplicant']['PersonalData']['IDNumber'] = '12tt45678';
        $dataToPost['CreateApplicant']['PersonalData']['DateofBirth'] = '09/18/1992'; //mm/dd/yyyy
        $dataToPost['CreateApplicant']['PersonalData']['GenderCode'] = 'F';

        $dataToPost['CreateApplicant']['Screenings']['SearchType']['@type'] = 'x:mvr';
        $dataToPost['CreateApplicant']['Screenings']['SearchType']['DriverLicenseNumber'] = '026409105';
        $dataToPost['CreateApplicant']['Screenings']['SearchType']['DriverLicenseState'] = 'GA';
        $dataToPost['CreateApplicant']['Screenings']['SearchType']['MVRCurrentState'] = true;
        $dataToPost['CreateApplicant']['Screenings']['SearchType']['CDLFlag'] = true;
        $dataToPost['CreateApplicant']['Screenings']['SearchType']['ScreeningReferenceID'] = '9876';
        $dataToPost['CreateApplicant']['Screenings']['SearchType']['ClientReferenceId'] = '12345';
        $dataToPost['CreateApplicant']['Screenings']['SearchType']['ScreeningStatus'] = 'NEW';

        $dataToPost = json_encode($dataToPost, true);
        $headers = array('F-API-username' => 'HUBAPI-136757', 'F-API-key'=>'HUBTestSKpjVM5SiT');
        $response = $this->restClient->postWithHeader('createapplicant/', $dataToPost, $headers);
        print_r($response);
    }
  
}




?>