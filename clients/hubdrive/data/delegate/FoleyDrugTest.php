<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\DelegateException;
use Oxzion\AppDelegate\PrehireTrait;
use Oxzion\Utils\UuidUtil;

class FoleyDrugTest extends AbstractAppDelegate
{
    const APPID = 'a4b1f073-fc20-477f-a804-1aa206938c42';
    use PrehireTrait;
    

    public function __construct()
    {
        parent::__construct();
        
    }

    public function execute(array $data, Persistence $persistenceService)
    {   
        
        $this->logger->info("in drug delegate- " . json_encode($data, JSON_UNESCAPED_SLASHES));
        $driver_id = UuidUtil::uuid();
        $dataToPost = array();
        $dataToPost['OrderTest']['Account']['FoleyAccountCode'] = '0000136757'; //config
        $dataToPost['OrderTest']['Account']['AccountName'] = 'Hub International Limited'; //config
        $dataToPost['OrderTest']['Account']['DOTNumber'] = '';
        $dataToPost['OrderTest']['Account']['FoleyAccountID'] = '';
        $dataToPost['OrderTest']['Account']['ClientCode'] = '';
        
        $dataToPost['OrderTest']['driver_applicant']['id'] = $driver_id;
        $dataToPost['OrderTest']['driver_applicant']['report_id'] = 'driverreport'.$driver_id;
        
        $dataToPost['OrderTest']['RequesterDetail']['RequesterID'] = 'Hub International Limited';
        $dataToPost['OrderTest']['RequesterDetail']['RequesterTimeZone'] = 'EST';
        $dataToPost['OrderTest']['RequesterDetail']['ProcessType'] = 'P';
        $dataToPost['OrderTest']['RequesterDetail']['OrderReferenceID'] = 'driverreport'.$driver_id;
        
        $dataToPost['OrderTest']['OrderSettings']['WhoOrderedTest'] = 'Hub International Limited';
        $dataToPost['OrderTest']['OrderSettings']['DateOrdered'] = '';
        $dataToPost['OrderTest']['OrderSettings']['ScheduledDate'] = '';
        $dataToPost['OrderTest']['OrderSettings']['ExpirationDate'] = '';
        $dataToPost['OrderTest']['OrderSettings']['HardExpire'] = 'Y';
        $dataToPost['OrderTest']['OrderSettings']['GenerateSchedulingLink'] = 'Y';
        $dataToPost['OrderTest']['OrderSettings']['CollectionSiteID'] = '';
        $dataToPost['OrderTest']['OrderSettings']['ReasonForTest'] = 'Pre-Employment';

        $dataToPost['OrderTest']['PersonData']['PrimaryIDType'] = 'SSN';
        $dataToPost['OrderTest']['PersonData']['EmployerID'] = '';
        $dataToPost['OrderTest']['PersonData']['SSN'] = $data['ssn'];
        $dataToPost['OrderTest']['PersonData']['DriversLicenseNumber'] = $data['driverLicense'];
        $dataToPost['OrderTest']['PersonData']['LicenseStateofIssue'] = $data['licenseState1']['abbreviation'];
        $dataToPost['OrderTest']['PersonData']['CandidateTrackingNumber'] = $driver_id;
        $dataToPost['OrderTest']['PersonData']['PersonName']['GivenName'] = $data['firstName'];
        $dataToPost['OrderTest']['PersonData']['PersonName']['MiddleName'] = '';
        $dataToPost['OrderTest']['PersonData']['PersonName']['FamilyName'] = $data['lastName'];
        $dataToPost['OrderTest']['PersonData']['Gender']['GenderValue'] = $data['gender'];
        $dob = date_format(date_create(explode("T",$data['dob1'])[0]),'m/d/Y');
        $dataToPost['OrderTest']['PersonData']['DateofBirth'] = $dob; 
        
        $dataToPost['OrderTest']['PersonData']['ContactMethod']['Telephone']['CellPhoneNumber'] = $data['phoneNumber'];
        $dataToPost['OrderTest']['PersonData']['ContactMethod']['Telephone']['HomePhoneNumber'] = $data['phoneNumber'];
        $dataToPost['OrderTest']['PersonData']['ContactMethod']['Telephone']['WorkPhoneNumber'] = $data['phoneNumber'];
        $dataToPost['OrderTest']['PersonData']['ContactMethod']['email'] = $data['email'];

        $dataToPost['OrderTest']['PersonData']['DonorAddress']['CountryCode'] = 'US';
        $dataToPost['OrderTest']['PersonData']['DonorAddress']['PostalCode'] = $data['zipCode'];;
        $dataToPost['OrderTest']['PersonData']['DonorAddress']['Region'] = $data['stateObj']['abbreviation'];;
        $dataToPost['OrderTest']['PersonData']['DonorAddress']['Municipality'] = $data['city'];
        $dataToPost['OrderTest']['PersonData']['DonorAddress']['DeliveryAddress']['AddressLine'] = $data['streetAddress'];

        $dataToPost['OrderTest']['PersonData']['DemographicDetail']['Company']['FoleyAccountNumber'] = '0000136757';
        $dataToPost['OrderTest']['PersonData']['DemographicDetail']['Company']['ClientCode'] = '';
        $dataToPost['OrderTest']['PersonData']['DemographicDetail']['Company']['FoleyProgramID'] = '';
        $dataToPost['OrderTest']['PersonData']['DemographicDetail']['Company']['PostalAddress']['CountryCode'] = 'US';
        $dataToPost['OrderTest']['PersonData']['DemographicDetail']['Company']['PostalAddress']['PostalCode'] = '';'';
        $dataToPost['OrderTest']['PersonData']['DemographicDetail']['Company']['PostalAddress']['Region'] = '';
        $dataToPost['OrderTest']['PersonData']['DemographicDetail']['Company']['PostalAddress']['Municipality'] = '';
        $dataToPost['OrderTest']['PersonData']['DemographicDetail']['Company']['PostalAddress']['DeliveryAddress'] = '';
        $dataToPost['OrderTest']['PersonData']['DemographicDetail']['Company']['ContactMethod']['ContactName'] = '';
        $dataToPost['OrderTest']['PersonData']['DemographicDetail']['Company']['ContactMethod']['Telephone']['CellPhoneNumber'] = '';
        $dataToPost['OrderTest']['PersonData']['DemographicDetail']['Company']['ContactMethod']['Fax']['FaxNumber'] = '';
        $dataToPost['OrderTest']['PersonData']['DemographicDetail']['Location']['IdValue'] ='';
        $dataToPost['OrderTest']['PersonData']['DemographicDetail']['Location']['IdName'] ='';
        $dataToPost['OrderTest']['PersonData']['DemographicDetail']['UserDefined1'] = '';
        $dataToPost['OrderTest']['PersonData']['DemographicDetail']['UserDefined2'] = '';
        $dataToPost['OrderTest']['PersonData']['DemographicDetail']['UserDefined3'] = '';
        $dataToPost['OrderTest']['PersonData']['DemographicDetail']['UserDefined4'] = '';
        $dataToPost['OrderTest']['PersonData']['DemographicDetail']['TestingOversightID'] = '';
        
        $dataToPost['OrderTest']['DonorNotification']['AuthorizationLetterSendOptions']['EmailDonor'] = 'Y';
        $dataToPost['OrderTest']['DonorNotification']['AuthorizationLetterSendOptions']['TextDonor'] = 'Y';
        
        $dataToPost['OrderTest']['Screenings'][0]['Screening']['TestType'] = 'Drug';
        $dataToPost['OrderTest']['Screenings'][0]['Screening']['DOTTest'] = 'Y';
        $dataToPost['OrderTest']['Screenings'][0]['Screening']['TestingAuthority'] = 'FMCSA';
        $dataToPost['OrderTest']['Screenings'][0]['Screening']['RequestObservation'] = 'N';
        $dataToPost['OrderTest']['Screenings'][0]['Screening']['RequestSplitSample'] = 'Y';
        $dataToPost['OrderTest']['Screenings'][0]['Screening']['SampleType'] = 'UR';
        $dataToPost['OrderTest']['Screenings'][0]['Screening']['LaboratoryTest'] = 'YES';
        $dataToPost['OrderTest']['Screenings'][0]['Screening']['PanelCodes'] = ['tg567','gtj67'];
        $dataToPost['OrderTest']['Screenings'][0]['Screening']['OrderCommentsToCollector'] = 'Megha';
        
        
        $result = $this->createdrugtest('ordertest/',$dataToPost);
        $this->logger->info("in foley delegate respone - " . json_encode($result, JSON_UNESCAPED_SLASHES));
        //print_r($result);
    }

    

}