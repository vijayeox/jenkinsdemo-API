<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<link href= "{$smarty.current_dir}/css/divestemplate_css.css" rel="stylesheet" type="text/css" />
</head>
<body>
  <div class ="body_div_endo">
       <!-- {if isset($liabilityCoverageChanges) && $liabilityCoverageChanges}
       <div class = "box">
          <center><b><u>+Policy has been changed to {$increasedCoverage} as of the Effective date of this Endorsement</u></b></center>
          <center><table>
            <tr>
              <th>
                <b>Upgraded Liability Coverage</b>
              </th>
              <th>
                <b>Limits</b>
              </th>
            </tr>
            <tr>
              <td>Commercial General Liability (per Occurance)
              <br></br>
              (Including Personal Injury and Products and Completed Operations)</td>
              {if $excessLiabilityCoverage == "excessLiabilityCoverage1M" && $excessLiabilityCoveragePrimarylimit1000000PL == true}
              <td>$2,000,000</td>
              {elseif $excessLiabilityCoverage == "excessLiabilityCoverage2M" && $excessLiabilityCoveragePrimarylimit1000000PL == true}
              <td>$3,000,000</td>
              {elseif $excessLiabilityCoverage == "excessLiabilityCoverage3M" && $excessLiabilityCoveragePrimarylimit1000000PL == true}
              <td>$4,000,000</td>
              {elseif $excessLiabilityCoverage == "excessLiabilityCoverage4M" && $excessLiabilityCoveragePrimarylimit1000000PL == true}
              <td>$5,000,000</td>
              {elseif $excessLiabilityCoverage == "excessLiabilityCoverage9M" && $excessLiabilityCoveragePrimarylimit1000000PL == true}
              <td>$10,000,000</td>
              {else}
              <td>$1,000,000</td>
              {/if}
            </tr>
            <tr>
              <td>General and Products and Completed Operations Aggregate</td>
              {if $excessLiabilityCoverage == "excessLiabilityCoverage1M" && $excessLiabilityCoveragePrimarylimit1000000PL == true}
              <td>$3,000,000</td>
              {elseif $excessLiabilityCoverage == "excessLiabilityCoverage2M" && $excessLiabilityCoveragePrimarylimit1000000PL == true}
              <td>$4,000,000</td>
              {elseif $excessLiabilityCoverage == "excessLiabilityCoverage3M" && $excessLiabilityCoveragePrimarylimit1000000PL == true}
              <td>$5,000,000</td>
              {elseif $excessLiabilityCoverage == "excessLiabilityCoverage4M" && $excessLiabilityCoveragePrimarylimit1000000PL == true}
              <td>$6,000,000</td>
              {elseif $excessLiabilityCoverage == "excessLiabilityCoverage9M" && $excessLiabilityCoveragePrimarylimit1000000PL == true}
              <td>$11,000,000</td>
              {else}
              <td>$2,000,000</td>
              {/if}
            </tr>
            <tr>
              <td>Travel Agent E & O (Each wrongful act $ Aggregate)
                <br>(Claims made form)</br></td>
              <td>{if isset($increased_travelEnO)}
                        $1,000,000
                  {else}
                        Not Included
                  {/if}
              </td>
            </tr>
          </table></center>
      </div>
      {/if} -->
      {if (isset($liabilityChanges) && $liabilityChanges == true )&& ((isset($increased_medicalPayment_limit) && $increased_medicalPayment_limit)|| (isset($increased_non_owned_liability_limit) && $increased_non_owned_liability_limit) || (isset($increased_liability_limit) && $increased_liability_limit > 0 && $liabilityChanges == true) || (isset($decreased_liability_limit) && $decreased_liability_limit > 0) || (isset($increased_travelEnO) && $increased_travelEnO))}
      <div class = "box">
          <center><b><u>***Liability Changes***</u></b></center>
          {if isset($increased_medicalPayment_limit) && $increased_medicalPayment_limit}
            <p>Medical Expense Liability now applies as of the Effective date on this Endorsement ({$increased_medicalPayment_limit} Limit)</p>
          {/if}
          {if isset($increased_non_owned_liability_limit) && $increased_non_owned_liability_limit}
            <p>+NON-Owned Auto Liability has been increased to {$increased_non_owned_liability_limit} as of the Effective date on this Endorsement</p>
          {/if}

          {if isset($increased_liability_limit) && $increased_liability_limit > 0 && $liabilityChanges == true}
            <p>+Liability Limits have been increased by ${$increased_liability_limit|number_format} as of the Effective date of this Endorsement</p>
          {/if}
          {if isset($decreased_liability_limit) && $decreased_liability_limit > 0 && $liabilityChanges == true}
            <p>+Liability Limits have been decreased by ${$decreased_liability_limit|number_format} as of the Effective date of this Endorsement</p>
          {/if}
          {if isset($increased_travelEnO)}
            <p>Travel Agent E & O now applies as of the Effective date on this Endorsement ($1,000,000 Limit) and ($1,000,000 Aggregate)</p>
          {/if}
          {if isset($removedadditionalLocations) && $removedadditionalLocations != ""}
          {assign var=removedAddLoc value=$removedadditionalLocations|json_decode:true}
            {foreach from=$removedAddLoc item=$location}
              {if isset($location.address)}
               <p>Location : {if isset($location.address)} {$location.addresss} {else} {if isset($location.name)}  {$location.name} {else}  {/if}{/if} has been removed as of the Effective date of this Endorsement</p>
              {/if}
            {/foreach}
          {/if}
      </div>
      {/if}

      {if isset($propertyChanges) && $propertyChanges == true}
      <div class = "box">
          <center><b><u>***Property Changes***</u></b></center>

          {if isset($increased_dspropTotal) && $propertyChanges == true}
            <p>+Contents Limit have been increased by ${$increased_dspropTotal|number_format} as of the Effective date of this Endorsement</p>
          {/if}
          {if isset($decreased_dspropTotal) && $propertyChanges == true}
            <p>+Contents Limit have been decreased by ${$decreased_dspropTotal|number_format} as of the Effective date of this Endorsement</p>
          {/if}
          
          {if isset($increased_lossOfBusIncome) && $propertyChanges == true}
            <p>+Loss of Business Income has been increased by ${$increased_lossOfBusIncome|number_format} as of the Effective date of this Endorsement</p>
          {/if}
          {if isset($decreased_lossOfBusIncome) && $propertyChanges == true}
            <p>+Loss of Business Income has been reduced by ${$decreased_lossOfBusIncome|number_format} as of the Effective date of this Endorsement</p>
          {/if}
          
          {if isset($increased_buildingLimit) && $propertyChanges == true}
            <p>+Building Limit have been increased by ${$increased_buildingLimit|number_format} as of the Effective date of this Endorsement</p>
          {/if}
          {if isset($decreased_buildingLimit) && $propertyChanges == true}
            <p>+Building Limit have been reduced by ${$decreased_liability_limit|number_format} as of the Effective date of this Endorsement</p>
          {/if}
          {if isset($removedadditionalLocations) && $removedadditionalLocations != ""}
          {assign var=removedAddLoc value=$removedadditionalLocations|json_decode:true}
            {foreach from=$removedAddLoc item=$location}
              {if isset($location.address)}
              <p>Location : {if isset($location.address)} {$location.address} {else} {if isset($location.name)}  {$location.name} {else}  {/if}{/if} has been removed as of the Effective date of this Endorsement</p>
              {/if}
            {/foreach}
          {/if}
      </div>
      {/if}


      <!-- {if isset($additionalInsured)}
      <div class = "box">
        <center><b><u>***Additional Insured Schedule***</u></b></center>
        {assign var=list value=$additionalInsured|json_decode:true}
        {foreach from=$list item=$additional}
          {if isset($additional.name) && ($additional.name != '')}
          <p class = "ai_list" style = "font-size:15px;text-transform: uppercase;">
            {$additional.name}
          </p>
      {/if}
        {/foreach}
        <div style="margin-bottom: 5%"></div>
        <center><b>Additional Insured coverage applies only with respect to liability arising out of the operations of the named insureds</b></center>
      </div>
      {/if} -->
     {if (isset($newAddInsured) && $newAddInsured != "") || (isset($removedAddInsured) && $removedAddInsured != "")}
      <div class = "box">
        <center><b><u>***Additional Insured Schedule***</u></b></center>
        {if $newAddInsured != ""}
          {assign var=list value=$newAddInsured|json_decode:true}
          {foreach from=$list item=$additional}
            {if isset($additional.name) && ($additional.name != '')}
            <p class = "ai_list" style = "font-size:15px;">
              <span style = "text-transform: uppercase;">{$additional.name}{if (isset($additional.businessRelation) && $additional.businessRelation != "")}(
              {if $additional.businessRelation == "confinedWaterTrainingLocation"}
                Confined Water Training Location 
              {elseif $additional.businessRelation == "openWaterTrainingLocation"} 
                Open Water Training Location 
              {elseif $additional.businessRelation == "diveBoatOwner"} 
                Dive Boat Owner
              {elseif $additional.businessRelation == "mortgageeLossPayee"} 
                Mortgagee / Loss Payee
              {elseif $additional.businessRelation == "landlord"}
                Landlord
              {elseif $additional.businessRelation == "governmentEntityPermitRequirement"} 
                  Government Entity - Permit Requirement
              {elseif $additional.businessRelation == "diveStore"} 
                 Dive Store
              {elseif $additional.businessRelation == "trainingAgency"} 
                 Training Agency
              {elseif $additional.businessRelation == "cruiseLine"} 
                 Cruise Line
              {elseif $additional.businessRelation == "landOwner"} 
                 Land Owner
              {elseif $additional.businessRelation == "bookingAgent"} 
                 Booking Agent
              {elseif $additional.businessRelation == "other"}                     {$additional.businessRelationOther}
              {/if})
              {/if} </span>
            </p>
            {/if}
          {/foreach}
        {/if}
        {if $removedAddInsured != ""}
          {assign var=removedAdditionalInsured value=$removedAddInsured|json_decode:true}
          {foreach from=$removedAdditionalInsured item=$additional}
            {if isset($additional.name) && ($additional.name != '')}
            <p class = "ai_list" style = "font-size:15px;">
              <span style = "text-transform: uppercase;">{$additional.name}{if (isset($additional.businessRelation) && $additional.businessRelation != "")}(
              {if $additional.businessRelation == "confinedWaterTrainingLocation"}
                Confined Water Training Location 
              {elseif $additional.businessRelation == "openWaterTrainingLocation"} 
                Open Water Training Location 
              {elseif $additional.businessRelation == "diveBoatOwner"} 
                Dive Boat Owner
              {elseif $additional.businessRelation == "mortgageeLossPayee"} 
                Mortgagee / Loss Payee
              {elseif $additional.businessRelation == "landlord"}
                Landlord
              {elseif $additional.businessRelation == "governmentEntityPermitRequirement"} 
                  Government Entity - Permit Requirement
              {elseif $additional.businessRelation == "diveStore"} 
                 Dive Store
              {elseif $additional.businessRelation == "trainingAgency"} 
                 Training Agency
              {elseif $additional.businessRelation == "cruiseLine"} 
                 Cruise Line
              {elseif $additional.businessRelation == "landOwner"} 
                 Land Owner
              {elseif $additional.businessRelation == "bookingAgent"} 
                 Booking Agent
              {elseif $additional.businessRelation == "other"}                     {$additional.businessRelationOther}
              {/if})
              {/if} </span>
            </p>
            {/if}
          {/foreach}
        {/if}
      </div>
      {/if}


     {if $lossPayeesSelect=='yes' && ((isset($newlossPayees) && $newlossPayees != "") || (isset($removedlossPayees) && $removedlossPayees != ""))}
      <div class = "box">
        <center><b><u>***Loss Payees***</u></b></center>
        {if $newlossPayees != ""}
          {assign var=list value=$newlossPayees|json_decode:true}
          {foreach from=$list item=$additional}
            {if isset($additional.name) && ($additional.name != '')}
            <p class = "ai_list" style = "font-size:15px;">
              <span style = "text-transform: uppercase;">{$additional.name} </span>
            </p>
            {/if}
          {/foreach}
        {/if}
        {if $removedlossPayees != ""}
          {assign var=list1 value=$removedlossPayees|json_decode:true}
          {foreach from=$list1 item=$additional}
            {if isset($additional.name) && ($additional.name != '')}
            <p class = "ai_list" style = "font-size:15px;">
              <span style = "text-transform: uppercase;">{$additional.name} </span>
            </p>
            {/if}
          {/foreach}
        {/if}
        <div style="margin-bottom: 5%"></div>
        <center><b>but only as respects the operations of the named insured</b></center>
      </div>
      </div>
      {/if}

     {if $additionalLocationsSelect=='yes' && ((isset($newAdditionalLocations) && $newAdditionalLocations != "") || (isset($newAdditionalLocations) && isset($removedadditionalLocations) && $removedadditionalLocations != ""))}
      <div class = "box">
        <center><b><u>***Additional Locations***</u></b></center>
        {if $newAdditionalLocations != ""}
          {assign var=list value=$newAdditionalLocations|json_decode:true}
          {foreach from=$list item=$additional}
    {if isset($additional.padiNumberAL) && $additional.padiNumberAL != "" && $additional.padiNumberAL != null} <p class = "info"><b>Store/Location Number: </b>{$additional.padiNumberAL}</p>{/if}
  {if (isset($additional.address) && $additional.address != "") ||
    (isset($additional.country) && $additional.country != "") ||
    ( isset($additional.city)  && $additional.city != "" ) ||
    (isset($additional.state) && $additional.state != "" && is_string($additional.state)) ||
    (isset($additional.zip) && $additional.zip != "")}<p  class = "info"><b>
    Location Address: </b>{if isset($additional.address) && is_string($additional.address) && $additional.address !=""}{$additional.address}{/if}<br>
    {if isset($additional.country) && is_string($additional.country) && $additional.country !=""}{$additional.country}{/if}{if isset($additional.city)  && is_string($additional.city) && $additional.city !=""},{$additional.city}{/if}{if isset($additional.state) && is_string($additional.state)},{$additional.state}{/if}{if isset($additional.zip) && is_string($additional.zip) && $additional.zip !=""} - {$additional.zip}{/if}</p>
    {/if}

    <center> <div>
      <p>See the Certificate for Primary Liability coverages.</p>
      <p>These coverages apply to this location only.</p>
    </div></center>

    <div style = "margin-bottom: 5%;"></div>
        <div class="table_sec">
            <table class="proposal_table" cellspacing="0" cellpadding="0">
                <tbody>
                    <tr>
                        <th class = "table_hd">Property Coverages</th>
                        <th class = "table_hd">Limits</th>
                    </tr>
                    <tr><td   class = "info">Policy issued by {$property_carrier}</td></tr>
                    <tr><td   class = "info">Policy #: {$property_policy_id}</td></tr>
                    <tr>
                        <td class = "info">Contents Limit:</td>
                        {if $additional.ALpropertyCoverageSelect == "yes"}
                        <td class = "info">${$additional.additionalLocationPropertyTotal|number_format}</td>{else}
                        <td class = "info">Not Included</td>
                        {/if}
                    </tr>
                    <tr>
                    {if $additional.ALpropertyCoverageSelect == "yes"}
                        <td class = "info" id="space_left">(Sign limited to : $25,000)</td>
                        {else}
                      <td></td>
                          {/if}
                        <td></td>
                    </tr>
                    <tr>
                        <td class = "info">Business Income:</td>
                        {if $additional.ALpropertyCoverageSelect == "yes"}
                        <td class = "info">${$additional.ALLossofBusIncome|number_format}</td>
                        {else}
                        <td class = "info">Not Included</td>
                        {/if}
                    </tr>
                    <tr>
                        <td class = "info">Building Coverage:</td>
                        {if $additional.ALpropertyCoverageSelect == "yes"}
                        {if isset($additional.additionalLocationDoYouOwntheBuilding) && $additional.additionalLocationDoYouOwntheBuilding != "no"}
                            <td  class = "info" >${$additional.ALBuildingReplacementValue|number_format}</td>
                        {else}
                            <td class = "info">Not Included</td>
                        {/if}
                        {else}
                        <td class = "info">Not Included</td>
                        {/if}
                    </tr>
                    <tr>
                        <td class = "info">Equipment Breakdown:</td>
                        {if isset($additional.additionalLocationFurniturefixturesAndEquipment) && (int)$additional.additionalLocationFurniturefixturesAndEquipment != 0}
                            <td class = "info">Included</td>
                        {else}
                            <td class = "info">Not Included</td>
                        {/if}
                    </tr>
                    <tr>
                        <td class = "info">Business Income from dependant properties:</td>
                        {if $additional.ALpropertyCoverageSelect == "yes"}
                        <td class = "info">$5,000</td>
                        {else}
                        <td class = "info">Not Included</td>
                        {/if}
                    </tr>
                    <tr>
                        <td class = "info">Robbery (per Occurrence - Inside):</td>
                        {if $additional.ALpropertyCoverageSelect == "yes"}
                        <td class = "info">$2,500</td>
                        {else}
                        <td class = "info">Not Included</td>
                        {/if}
                    </tr>
                    <tr>
                        <td class = "info">Robbery (per Occurrence - Outside):</td>
                        {if $additional.ALpropertyCoverageSelect == "yes"}
                        <td class = "info">$2,500</td>
                        {else}
                        <td class = "info">Not Included</td>
                        {/if}
                    </tr>
                    <tr>
                        <td class = "info">Transit Coverage (Locked Vehicle):</td>
                        {if $additional.ALpropertyCoverageSelect == "yes"}
                        <td class = "info">$10,000</td>
                        {else}
                        <td class = "info">Not Included</td>
                        {/if}
                    </tr>
                    <tr>
                        <td class = "info">EmployeeTheft Limit:</td>
                        {if $additional.ALpropertyCoverageSelect == "yes"}
                        <td class = "info">$5,000</td>
                        {else}
                        <td class = "info">Not Included</td>
                        {/if}
                    </tr>
                    <tr>
                        <td class = "info">Property of Others:</td>
                        {if $additional.ALpropertyCoverageSelect == "yes"}
                        <td class = "info">$25,000</td>
                        {else}
                        <td class = "info">Not Included</td>
                        {/if}
                    </tr>
                    <tr>
                        <td class = "info">Off premises:</td>
                        {if $additional.ALpropertyCoverageSelect == "yes"}
                        <td class = "info">$10,000</td>
                        {else}
                        <td class = "info">Not Included</td>
                        {/if}
                    </tr>
                    <tr>
                        <td class = "info">Glass:</td>
                        {if $additional.ALpropertyCoverageSelect == "yes"}
                        <td class = "info">$5,000</td>
                        {else}
                        <td class = "info">Not Included</td>
                        {/if}
                    </tr>
                </tbody>
            </table>

            <table class="proposal_table" cellspacing="0" cellpadding="0">
                <tbody>
                    <tr>
                        <th class = "table_hd">Liability Coverages</th>
                        <th class = "table_hd">Limits</th>
                    </tr>
                    <tr><td   class = "info">Policy issued by {$liability_carrier}</td></tr>
                    <tr><td   class = "info">Policy #: {$liability_policy_id}</td></tr>
                    <tr>
                        <td class = "info">NON-Diving Pool Use:</td>
                        {if isset($additional.ALnonDivingPoolAmount) && (int)$additional.ALnonDivingPoolAmount > 0}
                                      <td>$1,000,000</td>
                                  {else}
                                      <td>Excluded</td>
                                  {/if}
                    </tr>
                    <tr>
                        <td class = "info">Travel Agent E&O (Each wrongful act & Aggregate):
                            <p class="info">(Claims made form)</p>
                        </td>
                        {if isset($travelAgentEoPL) && ($travelAgentEoPL === "true" || $travelAgentEoPL == true || $travelAgentEoPL == 1)}
                                <td>$1,000,000</td>
                        {else}
                                <td>Excluded</td>
                        {/if}
                    </tr>
                </tbody>
            </table>
            <div class="clearfix"></div>
            <div style ="margin-bottom: 5%;"></div>
            <table class="deductible_table">
                <tbody>
                    <tr>
                        <td>Deductible:</td>
                        <td class="info">Wind/Hail is 5% of Insured Values per location, $5000 minimum, for Florida,
                            Hawaii, Puerto Rico, USVI, Guam and all Tier 1 locations
                            (coastal Counties) in Texas, Louisiana, Mississippi, Alabama, Georgia, South Carolina, North
                            Carolina and all Harris County Texas locations.
                            Mechanical breakdown is $2500. All other perils is {if $propertyDeductibles == "propertyDeductibles1000"}
                           $1,000
                        {elseif $propertyDeductibles == "propertyDeductibles2500"}
                           $2,500
                        {elseif $propertyDeductibles == "propertyDeductibles5000"}
                           $5,000
                        {else}
                           $0.00
                        {/if}.</td>
                    </tr>
                </tbody>
            </table>
            <div style="margin: 2% 0;">
                {if isset($additional.ALcentralStationAlarm) && $additional.ALcentralStationAlarm != "yes"}
                <center>
                    <b>
                        <p>Burglary Coverage is Excluded as there is no Central Station Alarm</p>
                    </b>
                </center>
                {/if}
            </div>
        </div>
    </div>
    
          {/foreach}
      {/if}
      {/if}
  </div>
</body>
</html>