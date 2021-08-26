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
            //&& ((isset($data['FLHownedAutoNonowned']) && $data['FLHownedAutoNonowned'] == true) || (isset($data['FLHownedAutohiredCoverage']) && $data['FLHownedAutohiredCoverage'] == true))
            if((isset($data['FLHownedAuto']) && $data['FLHownedAuto'] == true))
            {
                $owned=true;
            }
            else {
                if(!$data['FLHownedAuto']==true) {
                    $compliant=false;
                }
                /*if((!$data['FLHownedAutoNonowned']==true) && (!$data['FLHownedAutohiredCoverage']==true)) {
                    $compliant=false;
                }*/
            }
            //&& ((isset($data['FLHvinListed']) && $data['FLHvinListed'] == true) || (isset($data['FLHscheduledAutohiredCoverage']) && $data['FLHscheduledAutohiredCoverage'] == true) ||(isset($data['FLHscheduledAutononOwned']) && $data['FLHscheduledAutononOwned'] == true))
            if((isset($data['FLHscheduledAuto']) && $data['FLHscheduledAuto'] == true) ) {
                $scheduled=true;
            }
            else {
                if(!$data['FLHscheduledAuto']==true) {
                    $compliant=false;
                }
                /*if((!$data['FLHvinListed']==true) && (!$data['FLHscheduledAutohiredCoverage']==true) && (!$data['FLHscheduledAutononOwned']==true)) {
                    $compliant=false;
                }*/
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

            if(isset($data['FLHGeneralCommercialCombinedSingleLimit']) && $data['FLHGeneralCommercialCombinedSingleLimit'] == true){
                $generalcommercialsingle = true;
            }else{
                if(!$data['FLHGeneralCommercialCombinedSingleLimit'] == true){
                    $compliant=false;
                }
            }
            
            if(isset($data['FLHGeneralCommercialaddlInsBoxCheckedOnCoi']) && $data['FLHGeneralCommercialaddlInsBoxCheckedOnCoi'] == true){
                $generalcommercialadd = true;
            }else{
                if(!$data['FLHGeneralCommercialaddlInsBoxCheckedOnCoi'] == true){
                    $compliant=false;
                }
            }
        }

        if($drivertype == 'pickupDelivery'){
            if((isset($data['auto']) && $data['auto'] == true) && (isset($data['motorTruckCargo']) && $data['motorTruckCargo'] == true) && (isset($data['crimeFidelityEmployeeDishonesty']) && $data['crimeFidelityEmployeeDishonesty'] == true) && (isset($data['PDgeneralCommercial']) && $data['PDgeneralCommercial'] == true) ) {
                $notice=true;    
            }
            else {
                if(!$data['auto']==true) {
                    $compliant=false;
                }
                if(!$data['motorTruckCargo']==true) {
                    $compliant=false;
                }
                if(!$data['crimeFidelityEmployeeDishonesty']==true) {
                    $compliant=false;
                }
                if(!$data['PDgeneralCommercial']==true) {
                    $compliant=false;
                }
            }
            
            if((isset($data['PDCombinedSingleLimit']) && $data['PDCombinedSingleLimit'] == true) && (isset($data['PDaddlInsBoxCheckedOnCoi']) && $data['PDaddlInsBoxCheckedOnCoi'] == true) && (isset($data['PDanyAuto']) && $data['PDanyAuto'] == true)){
                $autoLiability=true;
            }
            else {
                if(!$data['PDCombinedSingleLimit']==true) {
                    $compliant=false;
                }
                if(!$data['PDaddlInsBoxCheckedOnCoi']==true) {
                    $compliant=false;
                }
                if(!$data['PDanyAuto']==true) {
                    $compliant=false;
                }
            }
            //&& ((isset($data['PDownedAutononOwned']) && $data['PDownedAutononOwned'] == true) || (isset($data['PDownedAutohired']) && $data['PDownedAutohired'] == true))
            if((isset($data['PDownedAuto']) && $data['PDownedAuto'] == true) )
            {
                $owned=true;
            }
            else {
                if(!$data['PDownedAuto']==true) {
                    $compliant=false;
                }
                /*if((!$data['PDownedAutononOwned']==true) && (!$data['PDownedAutohired']==true)) {
                    $compliant=false;
                }*/
            }
            //&& ((isset($data['PDscheduledAutovinListed']) && $data['PDscheduledAutovinListed'] == true) || (isset($data['PDscheduledAutohired']) && $data['PDscheduledAutohired'] == true) ||(isset($data['PDscheduledAutononOwned']) && $data['PDscheduledAutononOwned'] == true))
            if((isset($data['PDscheduledAuto']) && $data['PDscheduledAuto'] == true) ) {
                $scheduled=true;
            }
            else {
                if(!$data['PDscheduledAuto']==true) {
                    $compliant=false;
                }
                /*if((!$data['PDscheduledAutovinListed']==true) && (!$data['PDscheduledAutohired']==true) && (!$data['PDscheduledAutononOwned']==true)) {
                    $compliant=false;
                }*/
            }

            if(isset($data['PDMotorTRuckCargoPerOccurrence']) && $data['PDMotorTRuckCargoPerOccurrence'] == true){
                $motorTruck = true;
            }else{
                if(!$data['PDMotorTRuckCargoPerOccurrence'] == true){
                    $compliant=false;
                }
            }

            if(isset($data['PDnoLessThan']) && $data['PDnoLessThan'] == true){
                $dishonesty = true;
            }else{
                if(!$data['PDnoLessThan'] == true){
                    $compliant=false;
                }
            }

            if(isset($data['PDGeneralCommercialCombinedSingleLimit']) && $data['PDGeneralCommercialCombinedSingleLimit'] == true){
                $generalcommercialsingle = true;
            }else{
                if(!$data['PDGeneralCommercialCombinedSingleLimit'] == true){
                    $compliant=false;
                }
            }
            
            if(isset($data['PDGeneralCommercialaddlInsBoxCheckedOnCoi']) && $data['PDGeneralCommercialaddlInsBoxCheckedOnCoi'] == true){
                $generalcommercialadd = true;
            }else{
                if(!$data['PDGeneralCommercialaddlInsBoxCheckedOnCoi'] == true){
                    $compliant=false;
                }
            }
        }
        
        if($drivertype == 'areaServiceProvider' ){
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

            if((isset($data['ASPSplitLimitOrCombinedSingleLimitCa']) && $data['ASPSplitLimitOrCombinedSingleLimitCa'] == true) && (isset($data['ASPaddlInsBoxCheckedOnCoi']) && $data['ASPaddlInsBoxCheckedOnCoi'] == true) && (isset($data['ASPanyAuto']) && $data['ASPanyAuto'] == true)){
                $autoLiability=true;
            }
            else {
                if(!$data['ASPSplitLimitOrCombinedSingleLimitCa']==true) {
                    $compliant=false;
                }
                if(!$data['ASPaddlInsBoxCheckedOnCoi']==true) {
                    $compliant=false;
                }
                if(!$data['ASPanyAuto']==true) {
                    $compliant=false;
                }
            }
            //&& ((isset($data['ASPownedAutononOwned']) && $data['ASPownedAutononOwned'] == true) || (isset($data['ASPownedAutohired']) && $data['ASPownedAutohired'] == true))
            if((isset($data['ASPownedAuto']) && $data['ASPownedAuto'] == true) )
            {
                $owned=true;
            }
            else {
                if(!$data['ASPownedAuto']==true) {
                    $compliant=false;
                }
                /*if((!$data['ASPownedAutononOwned']==true) && (!$data['ASPownedAutohired']==true)) {
                    $compliant=false;
                }*/
            }
            //&& ((isset($data['ASPvinListed']) && $data['ASPvinListed'] == true) )
            if((isset($data['ASPscheduledAuto']) && $data['ASPscheduledAuto'] == true) ) {
                $scheduled=true;
            }
            else {
                if(!$data['ASPscheduledAuto']==true) {
                    $compliant=false;
                }
                /*if((!$data['ASPvinListed']==true) ) {
                    $compliant=false;
                }*/
            }

            if(isset($data['ASPMotorTRuckCargoPerOccurrence']) && $data['ASPMotorTRuckCargoPerOccurrence'] == true){
                $motorTruck = true;
            }else{
                if(!$data['ASPMotorTRuckCargoPerOccurrence'] == true){
                    $compliant=false;
                }
            }
        }
        
        if($drivertype == 'serviceProvider' ){
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

            if((isset($data['SPSplitLimitOrCombinedSingleLimitCa']) && $data['SPSplitLimitOrCombinedSingleLimitCa'] == true) && (isset($data['SPaddlInsBoxCheckedOnCoi']) && $data['SPaddlInsBoxCheckedOnCoi'] == true) && (isset($data['SPanyAuto']) && $data['SPanyAuto'] == true)){
                $autoLiability=true;
            }
            else {
                if(!$data['SPSplitLimitOrCombinedSingleLimitCa']==true) {
                    $compliant=false;
                }
                if(!$data['SPaddlInsBoxCheckedOnCoi']==true) {
                    $compliant=false;
                }
                if(!$data['SPanyAuto']==true) {
                    $compliant=false;
                }
            }
            //&& ((isset($data['SPownedAutononOwned']) && $data['SPownedAutononOwned'] == true) || (isset($data['SPownedAutohired']) && $data['SPownedAutohired'] == true))
            if((isset($data['SPownedAuto']) && $data['SPownedAuto'] == true) )
            {
                $owned=true;
            }
            else {
                if(!$data['SPownedAuto']==true) {
                    $compliant=false;
                }
                /*if((!$data['SPownedAutononOwned']==true) && (!$data['SPownedAutohired']==true)) {
                    $compliant=false;
                }*/
            }
            //&& ((isset($data['SPscheduledAutovinListed']) && $data['SPscheduledAutovinListed'] == true) )
            if((isset($data['SPscheduledAuto']) && $data['SPscheduledAuto'] == true) ) {
                $scheduled=true;
            }
            else {
                if(!$data['SPscheduledAuto']==true) {
                    $compliant=false;
                }
                /*if((!$data['SPscheduledAutovinListed']==true) ) {
                    $compliant=false;
                }*/
            }

            if(isset($data['SPMotorTRuckCargoPerOccurrence']) && $data['SPMotorTRuckCargoPerOccurrence'] == true){
                $motorTruck = true;
            }else{
                if(!$data['SPMotorTRuckCargoPerOccurrence'] == true){
                    $compliant=false;
                }
            }
        }

        if($drivertype == 'rsp' ){
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

            if((isset($data['RSPCombinedSingleLimit']) && $data['RSPCombinedSingleLimit'] == true) && (isset($data['RSPaddlInsBoxCheckedOnCoi']) && $data['RSPaddlInsBoxCheckedOnCoi'] == true) && (isset($data['RSPanyAuto']) && $data['RSPanyAuto'] == true)){
                $autoLiability=true;
            }
            else {
                if(!$data['RSPCombinedSingleLimit']==true) {
                    $compliant=false;
                }
                if(!$data['RSPaddlInsBoxCheckedOnCoi']==true) {
                    $compliant=false;
                }
                if(!$data['RSPanyAuto']==true) {
                    $compliant=false;
                }
            }
            //&& ((isset($data['RSPscheduledOrOwnedAutovinListed']) && $data['RSPscheduledOrOwnedAutovinListed'] == true) || (isset($data['RSPscheduledOrOwnedAutohired']) && $data['RSPscheduledOrOwnedAutohired'] == true) ||(isset($data['RSPscheduledOrOwnedAutononOwned']) && $data['RSPscheduledOrOwnedAutononOwned'] == true))
            if((isset($data['RSPscheduledOrOwnedAuto']) && $data['RSPscheduledOrOwnedAuto'] == true) ) {
                $scheduled=true;
            }
            else {
                if(!$data['RSPscheduledOrOwnedAuto']==true) {
                    $compliant=false;
                }
                /*if((!$data['RSPscheduledOrOwnedAutovinListed']==true) && (!$data['RSPscheduledOrOwnedAutohired']==true) && (!$data['RSPscheduledOrOwnedAutononOwned']==true)) {
                    $compliant=false;
                }*/
            }

            if(isset($data['RSPMotorTRuckCargoPerOccurrence']) && $data['RSPMotorTRuckCargoPerOccurrence'] == true){
                $motorTruck = true;
            }else{
                if(!$data['RSPMotorTRuckCargoPerOccurrence'] == true){
                    $compliant=false;
                }
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
    








