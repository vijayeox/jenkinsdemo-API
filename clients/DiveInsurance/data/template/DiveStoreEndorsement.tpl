<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<link href= "{$smarty.current_dir}/css/divestemplate_css.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<div class ="body_div_endo">
	     {if isset($increased_liability)}
       <div class = "box">
          <b><u>+Policy has been changed to Liability only {$increased_liability} as of the Effective date of this Endorsement</u></b>
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
              <td>$1,000,000</td>
            </tr>
            <tr>
              <td>General and Products and Completed Operations Aggregate</td>
              <td>$2,000,000</td>
            </tr>
            <tr>
              <td>Travel Agent E & O (Each wrongful act $ Aggregate)
                <br>(Claims made form)</br></td>
              <td>{if isset($travelAgent)}
                        {$travelagentPrice}
                  {else}
                        Not Included
                  {/if}
              </td>
            </tr>
          </table></center>
      </div>
      {/if}

      {if isset($liabilityChanges)}
      <div class = "box">
          <center><b><u>***Liability Changes***</u></b></center>
          {if isset($new_auto_liability)}
            <p>+NON-Owned Auto Liability now applies as of the Effective date on this Endorsement ($1,000,000 Limit)</p>
          {/if}

          {if isset($increased_liability_limit)}
            <p>+Liability Limits have been increased by {$increased_liability_limit} as of the Effective date of this Endorsement</p>
          {/if}

          {if isset($travelAgentEoPL)}
            <p>Travel Agent E & O now applies as of the Effective date on this Endorsement ($1,000,000 Limit) and ($1,000,000 Aggregate)</p>
          {/if}
      </div>
      {/if}


      {if isset($additionalInsured)}
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
      {/if}

      <div class = "box">

    <b><p>Store/Location Number: {$business_padi}</p></b>
    <p><b>Location Address: {$address1},<br>
      {$address2}<br>
    {$country},{$city},{$state} - {$zip}</p>

    <center> <div>
      <p>See the Certificate for Primary Liability coverages.</p>
      <p>These coverages apply to this location only.</p>
    </div></center>
        <div class="table_sec">
            <table class="proposal_table" cellspacing="0" cellpadding="0">
                <tbody>
                    <tr>
                        <th>Property Coverages</th>
                        <th>Limits</th>
                    </tr>
                    <tr>
                        <td>Contents Limit:</td>
                        <td>{if isset($dspropTotal)}
                            ${$dspropTotal|number_format}
                            {else}
                            Not Included
                            {/if}
                        </td>
                    </tr>
                    <tr>
                        <td id="space_left">(Sign limited to : $25,000)</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Business Income:</td>
                        <td>{if isset($lossOfBusIncome) && (int)$lossOfBusIncome != 0}                           ${$lossOfBusIncome|number_format}{else}$0{/if}</td>
                    </tr>
                    <tr>
                        <td>Building Coverage:</td>
                        {if $dspropownbuilding != "no" && $dspropownbuilding != ""}
                            <td>${$dspropreplacementvalue|number_format}</td>
                        {else}
                            <td>Not Included</td>
                        {/if}
                    </tr>
                    <tr>
                        <td>Equipment Breakdown:</td>
                        {if isset($dspropFurniturefixturesandequip) && (int)$dspropFurniturefixturesandequip != 0}
                            <td>Included</td>
                        {else}
                            <td>Not Included</td>
                        {/if}
                    </tr>
                </tbody>
            </table>

            <table class="proposal_table" cellspacing="0" cellpadding="0">
                <tbody>
                    <tr>
                        <th>Liability Coverages</th>
                        <th>Limits</th>
                    </tr>
                    <tr>
                        <td>NON-Diving Pool Use:</td>
                        {if isset($poolLiability) && ((int)$poolLiability > 0)}
                            <td>$1,000,000</td>
                        {else}
                            <td>Not Included</td>
                        {/if}
                    </tr>
                    <tr>
                        <td>Travel Agent E&O (Each wrongful act & Aggregate):
                            <p class="info">(Claims made form)</p>
                        </td>
                        {if isset($travelAgentEOReceiptsPL) && ((int)$travelAgentEOReceiptsPL > 0)}
                            <td>$1,000,000</td>
                        {else}
                            <td>Not Included</td>
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
                            Mechanical breakdown is $2500. All other perils is ${$PropDeductibleCredit}.</td>
                    </tr>
                </tbody>
            </table>
            <div style="margin: 2% 0;">
                {if isset($centralStationAlarm) && $centralStationAlarm != "yes"}
                <center>
                    <b>
                        <p>Burglary Coverage is Excluded as there is no Central Station Alarm</p>
                    </b>
                </center>
                {/if}
            </div>
        </div>
    </div>
	</div>
</body>
</html>