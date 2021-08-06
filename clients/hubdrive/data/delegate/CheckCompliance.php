<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;

class CheckCompliance extends AbstractAppDelegate
{
    public function __construct()
    {
        parent::__construct();
    }

    public function execute(array $data, Persistence $persistenceService)
    {   
        $this->logger->info("Executing Check compliance with data- " . json_encode($data, JSON_UNESCAPED_SLASHES));
        $nonComp=array();
        $compliant=true;
        if((isset($data['auto']) && $data['auto'] == true) && (isset($data['motorTruckCargo']) && $data['motorTruckCargo'] == true)) {
            $notice=true;    
        }
        else {
            if(!$data['auto']==true) {
                //array_push($nonComp,'Auto');
                $compliant=false;
            }
            if(!$data['motorTruckCargo']==true) {
                $compliant=false;
            }
        }

        if((isset($data['CombinedSingleLimit']) && $data['CombinedSingleLimit'] == true) && (isset($data['addlInsBoxCheckedOnCoi']) && $data['addlInsBoxCheckedOnCoi'] == true) && (isset($data['anyAuto']) && $data['anyAuto'] == true)) {
            $autoLiability=true;
        }
        else {
            if(!$data['CombinedSingleLimit']==true) {
                $compliant=false;
            }
            if(!$data['addlInsBoxCheckedOnCoi']==true) {
                $compliant=false;
            }
            if(!$data['anyAuto']==true) {
                $compliant=false;
            }
        }

        if((isset($data['scheduledOrOwnedAuto']) && $data['scheduledOrOwnedAuto'] == true) && ((isset($data['vinListed']) && $data['vinListed'] == true) || (isset($data['hired']) && $data['hired'] == true) ||(isset($data['nonOwned']) && $data['nonOwned'] == true))) {
            $scheduled=true;
        }
        else {
            if(!$data['scheduledOrOwnedAuto']==true) {
                $compliant=false;
            }
            if((!$data['vinListed']==true) && (!$data['hired']==true) && (!$data['nonOwned']==true)) {
                $compliant=false;
            }
        }
        
        if((isset($data['PerOccurrence']) && $data['PerOccurrence'] == true) && (isset($data['addlInsBoxCheckedOnCoi1']) && $data['addlInsBoxCheckedOnCoi1'] == true)) {
            $cargo=true;
        }
        else {
            if(!$data['PerOccurrence']==true) {
                $compliant=false;
            }
            if(!$data['addlInsBoxCheckedOnCoi1']==true) {
                $compliant=false;
            }
                
        }
        
        if((isset($data['onTracLogistics']) && $data['onTracLogistics'] == true) && (isset($data['signedCoi']) && $data['signedCoi'] == true)) {
            $certificate=true;
        }
        else {
            if(!$data['onTracLogistics']==true) {
                $compliant=false;
            }
            if(!$data['signedCoi']==true) {
                $compliant=false;
            }
        }
        
        if((isset($data['acord25Version']) && $data['acord25Version'] == true) && (isset($data['policiesAreEffective']) && $data['policiesAreEffective'] == true) && (isset($data['insuredNameListed']) && $data['insuredNameListed'] == true) && (isset($data['insuredAddressCompleted']) && $data['insuredAddressCompleted'] == true)
        && (isset($data['policyNumberListed']) && $data['policyNumberListed'] == true) && (isset($data['letterDescribed']) && $data['letterDescribed'] == true) && (isset($data['specifiedNumber']) && $data['specifiedNumber'] == true)) {
            $compliance=true;
        }
        else {
            if(!$data['acord25Version']==true) {
                $compliant=false;
            }
            if(!$data['policiesAreEffective']==true) {
                $compliant=false;
            }
            if(!$data['insuredNameListed']==true) {
                $compliant=false;
            }
            if(!$data['insuredAddressCompleted']==true) {
                $compliant=false;
            }
            if(!$data['policyNumberListed']==true) {
                $compliant=false;
            }
            if(!$data['letterDescribed']==true) {
                $compliant=false;
            }
            if(!$data['specifiedNumber']==true) {
                $compliant=false;
            }
        }

        if($compliant==true) {
            $data['certificateOfInsuranceIsCompliant']=true;
            $data['status'] = "Compliant";
        }
        else {
            $data['certificateOfInsuranceIsDeficient']=true;
            $data['status'] = "Non Compliant";
            
        }

        return $data;
        
    }
}
    








