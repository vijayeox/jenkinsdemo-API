<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;
use Oxzion\DelegateException;
use Oxzion\AppDelegate\PrehireTrait;
use Oxzion\Utils\UuidUtil;

class FoleyBackgroundCheck extends AbstractAppDelegate
{
    const APPID = 'a4b1f073-fc20-477f-a804-1aa206938c42';
    use PrehireTrait;
    

    public function __construct()
    {
        parent::__construct();
        
    }

    public function execute(array $data, Persistence $persistenceService)
    {   
        
        $this->logger->info("in BGC delegate- " . json_encode($data, JSON_UNESCAPED_SLASHES));
        $driver_id = UuidUtil::uuid();
        $dataToPost = array();
        $dataToPost['OrderBGC']['Account']['FoleyAccountCode'] = '0000136757'; //config
        $dataToPost['OrderBGC']['Account']['AccountName'] = 'Hub International Limited'; //config
        $dataToPost['OrderBGC']['Account']['DOTNumber'] = '';
        $dataToPost['OrderBGC']['Account']['FoleyAccountID'] = '';
        $dataToPost['OrderBGC']['Account']['ClientCode'] = '';
        
        $dataToPost['OrderBGC']['authorized'] =  "true";
        $dataToPost['OrderBGC']['auth_doc'] =  ""; //required how to send
        $dataToPost['OrderBGC']['package_name'] =  "Driver and Criminal Package";

        $dataToPost['OrderBGC']['driver_applicant']['id'] = $driver_id;
        $dataToPost['OrderBGC']['driver_applicant']['report_id'] = 'driverreport'.$driver_id;
        $dataToPost['OrderBGC']['driver_applicant']['first_name'] = $data['firstName'];
        $dataToPost['OrderBGC']['driver_applicant']['middle_name'] = '';
        $dataToPost['OrderBGC']['driver_applicant']['last_name'] = $data['lastName'];
        $dataToPost['OrderBGC']['driver_applicant']['nickname'] = '';
        $dataToPost['OrderBGC']['driver_applicant']['name_suffix'] = '';

        $dob = date_format(date_create(explode("T",$data['dob1'])[0]),'m/d/Y');
        $expiration_date = date_format(date_create(explode("T",$data['cdlExpirationDate'])[0]),'m/d/Y');
        $dataToPost['OrderBGC']['driver_applicant']['birthdate'] = $dob; 
        $dataToPost['OrderBGC']['driver_applicant']['ssn'] = $data['ssn'];
        $dataToPost['OrderBGC']['driver_applicant']['email_address'] = $data['email'];
        $dataToPost['OrderBGC']['driver_applicant']['primary_phone'] = $data['phoneNumber'];
        $dataToPost['OrderBGC']['driver_applicant']['current_address']['address_1'] = $data['streetAddress'];
        $dataToPost['OrderBGC']['driver_applicant']['current_address']['address_2'] = '';
        $dataToPost['OrderBGC']['driver_applicant']['current_address']['city'] = $data['city'];
        $dataToPost['OrderBGC']['driver_applicant']['current_address']['state'] = $data['stateObj']['abbreviation'];
        $dataToPost['OrderBGC']['driver_applicant']['current_address']['postal_code'] = $data['zipCode'];
        $dataToPost['OrderBGC']['driver_applicant']['previous_addresses_within_3_years'][0]['address_1'] = '';
        $dataToPost['OrderBGC']['driver_applicant']['previous_addresses_within_3_years'][0]['address_2'] = '';
        $dataToPost['OrderBGC']['driver_applicant']['previous_addresses_within_3_years'][0]['city'] = '';
        $dataToPost['OrderBGC']['driver_applicant']['previous_addresses_within_3_years'][0]['state'] = '';
        $dataToPost['OrderBGC']['driver_applicant']['previous_addresses_within_3_years'][0]['postal_code'] = '';
        $dataToPost['OrderBGC']['driver_applicant']['cdl_class'] = $data['cdlClass'];
        $dataToPost['OrderBGC']['driver_applicant']['cdl_state'] = $data['cdlState']['abbreviation'];
        $dataToPost['OrderBGC']['driver_applicant']['cdl_expiration'] = $expiration_date;
        $dataToPost['OrderBGC']['driver_applicant']['cdl_number'] = $data['cdlNumber'];

        $dataToPost['OrderBGC']['driver_applicant']['previous_employers'][0]['name'] = '';
        $dataToPost['OrderBGC']['driver_applicant']['previous_employers'][0]['end_date'] = '';
        $dataToPost['OrderBGC']['driver_applicant']['previous_employers'][0]['start_date'] = '';
        $dataToPost['OrderBGC']['driver_applicant']['previous_employers'][0]['employer_city'] = '';
        $dataToPost['OrderBGC']['driver_applicant']['previous_employers'][0]['employer_state'] = '';
        $dataToPost['OrderBGC']['driver_applicant']['previous_employers'][0]['employer_email'] = '';
        $dataToPost['OrderBGC']['driver_applicant']['previous_employers'][0]['current_employer'] = '';
        $dataToPost['OrderBGC']['driver_applicant']['previous_employers'][0]['employer_zip_code'] = '';
        $dataToPost['OrderBGC']['driver_applicant']['previous_employers'][0]['employer_telephone'] = '';
        $dataToPost['OrderBGC']['driver_applicant']['previous_employers'][0]['reason_for_leaving'] = '';
        $dataToPost['OrderBGC']['driver_applicant']['previous_employers'][0]['employer_fax_number'] = '';
        $dataToPost['OrderBGC']['driver_applicant']['previous_employers'][0]['most_common_vehicle'] = '';
        $dataToPost['OrderBGC']['driver_applicant']['previous_employers'][0]['employer_position_held'] = '';
        $dataToPost['OrderBGC']['driver_applicant']['previous_employers'][0]['type_of_work_performed'] = '';
        $dataToPost['OrderBGC']['driver_applicant']['previous_employers'][0]['may_we_contact_employer'] = '';
        $dataToPost['OrderBGC']['driver_applicant']['previous_employers'][0]['commercial_motor_vehicle'] = '';
        $dataToPost['OrderBGC']['driver_applicant']['previous_employers'][0]['employer_street_address_1'] = '';
        $dataToPost['OrderBGC']['driver_applicant']['previous_employers'][0]['employer_street_address_2'] = '';
        $dataToPost['OrderBGC']['driver_applicant']['previous_employers'][0]['subject_to_the_federal_motor_carrier'] = '';
        $dataToPost['OrderBGC']['driver_applicant']['employment gaps'][0]['start_date'] = '';
        $dataToPost['OrderBGC']['driver_applicant']['employment gaps'][0]['end_date'] = '';
        $dataToPost['OrderBGC']['driver_applicant']['driving_experience'] ='';
        $dataToPost['OrderBGC']['driver_applicant']['driver_training_schools'][0]['training_school_city'] ='';
        $dataToPost['OrderBGC']['driver_applicant']['driver_training_schools'][0]['training_school_name'] ='';
        $dataToPost['OrderBGC']['driver_applicant']['driver_training_schools'][0]['federal_motor_carrier'] ='';
        $dataToPost['OrderBGC']['driver_applicant']['driver_training_schools'][0]['training_school_state'] ='';
        $dataToPost['OrderBGC']['driver_applicant']['driver_training_schools'][0]['training_school_email'] ='';
        $dataToPost['OrderBGC']['driver_applicant']['driver_training_schools'][0]['training_school_end_date'] ='';
        $dataToPost['OrderBGC']['driver_applicant']['driver_training_schools'][0]['training_school_start_date'] ='';
        $dataToPost['OrderBGC']['driver_applicant']['driver_training_schools'][0]['training_school_fax_number'] ='';
        $dataToPost['OrderBGC']['driver_applicant']['driver_training_schools'][0]['training_school_phone_number'] ='';

        

        $result = $this->createBGCOrder('orderbgc/',$dataToPost);
        $this->logger->info("in foley delegate respone - " . json_encode($result, JSON_UNESCAPED_SLASHES));
        //print_r($result);
    }

    

}