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
        $drivertype = $data['dataGrid'][0]['pleaseSelectDriverType'];

        if($drivertype == 'areaServiceProvider' || $drivertype == 'serviceProvider' || $drivertype == 'rsp'){
            if((isset($data['auto']) && $data['auto'] == true) && (isset($data['motorTruckCargo']) && $data['motorTruckCargo'] == true)) {
                $notice=true;    
            }
            else {
                if(!$data['auto']==true) {
                    $compliant=false;
                }
                if(!$data['motorTruckCargo']==true) {
                    $compliant=false;
                }
            }
        }
        if($drivertype == 'fleetLineHaul'){
            if((isset($data['auto']) && $data['auto'] == true) && (isset($data['motorTruckCargo']) && $data['motorTruckCargo'] == true) && (isset($data['crimeFidelityEmployeeDishonesty1']) && $data['crimeFidelityEmployeeDishonesty1'] == true) && (isset($data['generalCommercial']) && $data['generalCommercial'] == true) && (isset($data['trailerInterchange']) && $data['trailerInterchange'] == true)) {
                $notice=true;    
            }
            else {
                if(!$data['auto']==true) {
                    $compliant=false;
                }
                if(!$data['motorTruckCargo']==true) {
                    $compliant=false;
                }
                if(!$data['crimeFidelityEmployeeDishonesty1']==true) {
                    $compliant=false;
                }
                if(!$data['generalCommercial']==true) {
                    $compliant=false;
                }
                if(!$data['trailerInterchange']==true) {
                    $compliant=false;
                }
            }

            if((isset($data['FLHCombinedSingleLimit']) && $data['FLHCombinedSingleLimit'] == true) && (isset($data['FLHaddlInsBoxCheckedOnCoi']) && $data['FLHaddlInsBoxCheckedOnCoi'] == true) && (isset($data['FLHanyAuto']) && $data['FLHanyAuto'] == true)){
                $autoLiability=true;
            }
            else {
                if(!$data['FLHCombinedSingleLimit']==true) {
                    $compliant=false;
                }
                if(!$data['FLHaddlInsBoxCheckedOnCoi']==true) {
                    $compliant=false;
                }
                if(!$data['FLHanyAuto']==true) {
                    $compliant=false;
                }
            }
            

            if((isset($data['FLHownedAuto']) && $data['FLHownedAuto'] == true) && ((isset($data['FLHownedAutoNonowned']) && $data['FLHownedAutoNonowned'] == true) || (isset($data['FLHownedAutohiredCoverage']) && $data['FLHownedAutohiredCoverage'] == true)))
            {
                $owned=true;
            }
            else {
                if(!$data['FLHownedAuto']==true) {
                    $compliant=false;
                }
                if((!$data['FLHownedAutoNonowned']==true) && (!$data['FLHownedAutohiredCoverage']==true)) {
                    $compliant=false;
                }
            }

            if((isset($data['FLHscheduledOrOwnedAuto']) && $data['FLHscheduledOrOwnedAuto'] == true) && ((isset($data['FLHvinListed']) && $data['FLHvinListed'] == true) || (isset($data['FLHscheduledAutohiredCoverage']) && $data['FLHscheduledAutohiredCoverage'] == true) ||(isset($data['FLHscheduledAutononOwned']) && $data['FLHscheduledAutononOwned'] == true))) {
                $scheduled=true;
            }
            else {
                if(!$data['FLHscheduledOrOwnedAuto']==true) {
                    $compliant=false;
                }
                if((!$data['FLHvinListed']==true) && (!$data['FLHscheduledAutohiredCoverage']==true) && (!$data['FLHscheduledAutononOwned']==true)) {
                    $compliant=false;
                }
            }

            if(isset($data['FLHnoLessThan']) && $data['FLHnoLessThan'] == true){
                $trailer = true;
            }else{
                if(!$data['FLHnoLessThan'] == true){
                    $compliant=false;
                }
            }

            if(isset($data['FLHMotorTRuckCargoPerOccurrence']) && $data['FLHMotorTRuckCargoPerOccurrence'] == true){
                $motorTruck = true;
            }else{
                if(!$data['FLHMotorTRuckCargoPerOccurrence'] == true){
                    $compliant=false;
                }
            }
            
            if(isset($data['FLHDishonestyCoveragenoLessThan']) && $data['FLHDishonestyCoveragenoLessThan'] == true){
                $dishonesty = true;
            }else{
                if(!$data['FLHDishonestyCoveragenoLessThan'] == true){
                    $compliant=false;
                }
            }
        }

        if($drivertype == 'pickupDelivery'){
            if((isset($data['auto']) && $data['auto'] == true) && (isset($data['motorTruckCargo']) && $data['motorTruckCargo'] == true) && (isset($data['crimeFidelityEmployeeDishonesty1']) && $data['crimeFidelityEmployeeDishonesty1'] == true) && (isset($data['generalCommercial']) && $data['generalCommercial'] == true) ) {
                $notice=true;    
            }
            else {
                if(!$data['auto']==true) {
                    $compliant=false;
                }
                if(!$data['motorTruckCargo']==true) {
                    $compliant=false;
                }
                if(!$data['crimeFidelityEmployeeDishonesty1']==true) {
                    $compliant=false;
                }
                if(!$data['generalCommercial']==true) {
                    $compliant=false;
                }
            }
        }
    
        
        /*if((isset($data['CombinedSingleLimit']) && $data['CombinedSingleLimit'] == true) && (isset($data['addlInsBoxCheckedOnCoi']) && $data['addlInsBoxCheckedOnCoi'] == true) && (isset($data['anyAuto']) && $data['anyAuto'] == true)) {
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
        }*/
        
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
    








