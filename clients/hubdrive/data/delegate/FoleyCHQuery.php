<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\DelegateException;
use Oxzion\AppDelegate\PrehireTrait;
use Oxzion\Utils\UuidUtil;

class FoleyCHQuery extends AbstractAppDelegate
{
    const APPID = 'a4b1f073-fc20-477f-a804-1aa206938c42';
    use PrehireTrait;
    

    public function __construct()
    {
        parent::__construct();
        
    }

    public function execute(array $data, Persistence $persistenceService)
    {   
        
        $this->logger->info("in FCH delegate- " . json_encode($data, JSON_UNESCAPED_SLASHES));
        $driver_id = UuidUtil::uuid();
        $dataToPost = array();
        $dataToPost['CHQueryOrder']['Account']['FoleyAccountCode'] = '0000136757'; //config
        $dataToPost['CHQueryOrder']['Account']['AccountName'] = 'Hub International Limited'; //config
        $dataToPost['CHQueryOrder']['Account']['DOTNumber'] = '';
        $dataToPost['CHQueryOrder']['Account']['FoleyAccountID'] = '';
        $dataToPost['CHQueryOrder']['Account']['ClientCode'] = '';
        
        $dataToPost['CHQueryOrder']['driver_applicant']['id'] = $driver_id;
        $dataToPost['CHQueryOrder']['driver_applicant']['report_id'] = 'driverreport'.$driver_id;
        
        $dataToPost['CHQueryOrder']['Person']['FirstName'] = $data['firstName'];
        $dataToPost['CHQueryOrder']['Person']['LastName'] = $data['lastName'];
        $dataToPost['CHQueryOrder']['Person']['EmployerID'] = '';
        $dataToPost['CHQueryOrder']['Person']['SSN'] = $data['ssn'];
        $dob = date_format(date_create(explode("T",$data['dob1'])[0]),'m/d/Y');
        $dataToPost['CHQueryOrder']['Person']['DOB'] = $dob; 
        $dataToPost['CHQueryOrder']['Person']['DriversLicenseNumber'] = $data['driverLicense'];
        $dataToPost['CHQueryOrder']['Person']['LicenseStateofIssue'] = $data['licenseState1']['abbreviation'];
        $dataToPost['CHQueryOrder']['Person']['PhoneNumber'] = $data['phoneNumber'];
        $dataToPost['CHQueryOrder']['Person']['EmailAddress'] = $data['email'];
        
        $dataToPost['CHQueryOrder']['Query']['QueryType'] = 'Pre-Employment';
        $dataToPost['CHQueryOrder']['Query']['LimitedConsentOnFile'] = 'Y';
        $dataToPost['CHQueryOrder']['Query']['RequesterName'] = 'gggg'; //IC NAme
        $dataToPost['CHQueryOrder']['Query']['RequesterOrganization'] = 'Hub International Limited';
        $dataToPost['CHQueryOrder']['Query']['OrderReferenceID'] = 'driverreport'.$driver_id;
        $dataToPost['CHQueryOrder']['Query']['LinkedRequestID'] = '';
        
        $result = $this->createCHQuery('CHQueryOrder/',$dataToPost);
        $this->logger->info("in foley delegate respone - " . json_encode($result, JSON_UNESCAPED_SLASHES));
        //print_r($result);
    }

    

}