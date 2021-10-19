<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\DelegateException;
use Oxzion\AppDelegate\PrehireTrait;
use Oxzion\Utils\UuidUtil;

class FoleyOrderMvr extends AbstractAppDelegate
{
    const APPID = 'a4b1f073-fc20-477f-a804-1aa206938c42';
    use PrehireTrait;
    

    public function __construct()
    {
        parent::__construct();
        
    }

    public function execute(array $data, Persistence $persistenceService)
    {   
        
        $this->logger->info("in FOM delegate- " . json_encode($data, JSON_UNESCAPED_SLASHES));
        $driver_id = UuidUtil::uuid();
        $dataToPost = array();
        $dataToPost['OrderMVR']['Account']['FoleyAccountCode'] = '0000136757'; //config
        $dataToPost['OrderMVR']['Account']['AccountName'] = 'Hub International Limited'; //config
        $dataToPost['OrderMVR']['Account']['DOTNumber'] = '';
        $dataToPost['OrderMVR']['Account']['FoleyAccountID'] = '';
        $dataToPost['OrderMVR']['Account']['ClientCode'] = '';

        $dataToPost['OrderMVR']['driver_applicant']['id'] = $driver_id;
        $dataToPost['OrderMVR']['driver_applicant']['report_id'] = 'driverreport'.$driver_id;
        $dataToPost['OrderMVR']['driver_applicant']['authorized'] = true;
        $dataToPost['OrderMVR']['driver_applicant']['auth_doc'] = '';
        $dataToPost['OrderMVR']['driver_applicant']['first_name'] = $data['firstName'];
        $dataToPost['OrderMVR']['driver_applicant']['middle_name'] = '';
        $dataToPost['OrderMVR']['driver_applicant']['last_name'] = $data['lastName'];
        $dataToPost['OrderMVR']['driver_applicant']['nickname'] = '';
        $dataToPost['OrderMVR']['driver_applicant']['name_suffix'] = '';
        $dataToPost['OrderMVR']['driver_applicant']['primary_phone'] = $data['phoneNumber'];
        $dataToPost['OrderMVR']['driver_applicant']['email_address'] = $data['email'];
        $dataToPost['OrderMVR']['driver_applicant']['ssn'] = $data['ssn'];

        $dataToPost['OrderMVR']['driver_applicant']['current_address']['postal_code'] = $data['zipCode'];
        $dataToPost['OrderMVR']['driver_applicant']['current_address']['state'] = $data['stateObj']['abbreviation'];
        $dataToPost['OrderMVR']['driver_applicant']['current_address']['city'] = $data['city'];
        $dataToPost['OrderMVR']['driver_applicant']['current_address']['address_1'] = $data['streetAddress'];
        $dataToPost['OrderMVR']['driver_applicant']['current_address']['address_2'] = '';
        
        $dob = date_format(date_create(explode("T",$data['dob1'])[0]),'m/d/Y');
        $expiration_date = date_format(date_create(explode("T",$data['cdlExpirationDate'])[0]),'m/d/Y');
        $dataToPost['OrderMVR']['driver_applicant']['birthdate'] = $dob; 
        $dataToPost['OrderMVR']['driver_applicant']['purpose'] = 'Employment';
        $dataToPost['OrderMVR']['driver_applicant']['cdl_class'] = $data['cdlClass'];
        $dataToPost['OrderMVR']['driver_applicant']['cdl_state'] = $data['cdlState']['abbreviation'];
        $dataToPost['OrderMVR']['driver_applicant']['cdl_expiration'] = $expiration_date;
        $dataToPost['OrderMVR']['driver_applicant']['cdl_number'] = $data['cdlNumber'];
        
        
        $result = $this->createOrderMvr('ordermvr/',$dataToPost);
        $this->logger->info("in foley delegate respone - " . json_encode($result, JSON_UNESCAPED_SLASHES));
        //print_r($result);
    }

    

}