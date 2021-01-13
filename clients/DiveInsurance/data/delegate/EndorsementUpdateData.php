<?php

use Oxzion\AppDelegate\AbstractAppDelegate;
use Oxzion\Db\Persistence\Persistence;

class EndorsementUpdateData extends AbstractAppDelegate
{
    public function __construct(){
        parent::__construct();
    }
    public function execute(array $data,Persistence $persistenceService)
    {
       $this->logger->info("Executing Endorsement Setup - Dive Boat".print_r($data,true));
       if($data['layUpPeriodIfAny'] == 'no'){
            if(isset($data['layup_period_from_date_time'])){
               $data['layup_period_from_date_time'] = "";
            }
            if(isset($data['layup_period_to_date_time'])){
               $data['layup_period_to_date_time'] = "";
            }     
       }

       if($data['captain_and_crew_schedule_survey']['CrewInBoatYN'] == 'no'){
            if(isset($data['CrewInBoatCount'])){
               $data['CrewInBoatCount'] = 0;
            }   
       }

       if($data['captain_and_crew_schedule_survey']['CrewInWaterYN'] == 'no'){
            if(isset($data['CrewInWaterCount'])){
               $data['CrewInWaterCount'] = 0;
            }
       }


       if($data['dingy_or_tender'] == 'none'){
          if(isset($data['dingy_manufacturer'])){
               $data['dingy_manufacturer'] = "";
          }
          if(isset($data['dingy_age'])){
               $data['dingy_age'] = "";
          }
          if(isset($data['dingy_length'])){
               $data['dingy_length'] = "";
          }
          if(isset($data['dingy_value'])){
               $data['dingy_value'] = 0.00;
          }
       }

       if($data['trailer'] == 'none'){
          if(isset($data['trailer_manufacturer'])){
               $data['trailer_manufacturer'] = "";
          }
          if(isset($data['trailer_serial_number'])){
               $data['trailer_serial_number'] = "";
          }
          if(isset($data['trailer_year_built'])){
               $data['trailer_year_built'] = "";
          }
          if(isset($data['trailer_value'])){
               $data['trailer_value'] = 0.00;
          }
       }

       if($data['additional_insured_select'] == 'noAdditionalInsureds'){
          if(isset($data['additionalInsured'])){
               $data['additionalInsured'] = array();
          }
       }

       if($data['additional_named_insureds_option'] == 'none'){
          if(isset($data['additionalNamedInsured'])){
               $data['additionalNamedInsured'] = array();
          } 
       }

     if(isset($data['loss_payees'])){
          if($data['loss_payees'] == 'none'){
               if(isset($data['lossPayees'])){
                     $data['lossPayees'] = array();
               } 
          }
     }
       
     
     if(isset($data['groupProfessionalLiability'])){
          if($data['groupProfessionalLiability'] == 'none'){
               if(isset($data['annualReceipt'])){
                 $data['annualReceipt'] = 0.00;
               }
               
               if(isset($data['groupExcessLiabilitySelect'])){
                 $data['groupExcessLiabilitySelect'] = "";
               }
               
               if(isset($data['groupCoverage'])){
                 $data['groupCoverage'] = 0.00;
               }
     
     
               if(isset($data['groupExcessLiability'])){
                 $data['groupExcessLiability'] = "";
               }
               
               if(isset($data['groupPL'])){
                 $data['groupPL'] = array();
               }
     
               if(isset($data['additional_insured'])){
                     $data['additional_insured'] == 'none';
                     $data['groupAdditionalInsured'] = array();
               }
     
               if(isset($data['named_insureds'])){
                     $data['named_insureds'] == 'none';
                     $data['namedInsureds'] = array();
               }
     
               if(isset($data['groupTaxPercentage'])){
                 $data['groupTaxPercentage'] = 0.00;
               }
     
               if(isset($data['groupTaxAmount'])){
                 $data['groupTaxAmount'] = 0.00;
               }
               
               if(isset($data['groupPadiFeeAmount'])){
                 $data['groupPadiFeeAmount'] = 0.00;
               }
               
               if(isset($data['groupTotalAmount'])){
                 $data['groupTotalAmount'] = 0.00;
               }  
            }else{
              if(isset($data['additional_insured'])){
                 if($data['additional_insured'] == 'none'){
                     $data['groupAdditionalInsured'] = array();
                 }
               }
     
               if(isset($data['named_insureds'])){
                 if($data['named_insureds'] == 'none'){
                     $data['namedInsureds'] = array();
                 }
               }
            }     
     }
              return $data;
    }
}
