{assign var=additionalLocationData value=$additionalLocationData|json_decode:true}
<!DOCTYPE html>
<html>

<head>
    <link href="{$smarty.current_dir}/css/divestemplate_css.css" rel="stylesheet" type="text/css" />
    <!-- <script type="text/javascript" src="{$smarty.current_dir}/AgentInfo.js"></script> -->
</head>
 
<body>
    <p></p>
    <div>
    {if isset($additionalLocationData.name) && $additionalLocationData.name != ""} <p class = "info"><b>Store/Location Description : </b>{$additionalLocationData.name}</p>{/if}
    {if isset($additionalLocationData.padiNumberAL) && $additionalLocationData.padiNumberAL != "" && $additionalLocationData.padiNumberAL != null} <p class = "info"><b>Store/Location Number: </b>{$additionalLocationData.padiNumberAL}</p>{/if}
    {assign var=list value=$additionalNamedInsured|json_decode:true}
    {if isset($additional_named_insureds_option) && $additional_named_insureds_option =='yes'}
    <p class = "info"><b>Additional Named Insured:</b></p>
    {foreach from=$list item=$additional}
        <p class = "ai_list info">
            &nbsp&nbsp&nbsp {if isset($additional.name) && is_string($additional.name)}{$additional.name}{/if}{if isset($additional.address) && is_string($additional.address) && $additional.address !=""},{$additional.address}{/if}{if isset($additional.city)  && is_string($additional.city) && $additional.city !=""},{$additional.city}{/if}{if isset($additional.state) && is_string($additional.state)},{$additional.state}{/if}{if isset($additional.zip) && is_string($additional.zip) && $additional.city !=""}{$additional.zip}{/if}
        </p>
    {/foreach}
    {/if}
    {if (isset($additionalLocationData.address) && $additionalLocationData.address != "") || 
    (isset($additionalLocationData.country) && $additionalLocationData.country != "") || 
    ( isset($additionalLocationData.city)  && $additionalLocationData.city != "" ) || 
    (isset($additionalLocationData.state) && $additionalLocationData.state != "" && is_string($additionalLocationData.state)) || 
    (isset($additionalLocationData.zip) && $additionalLocationData.zip != "")}<p  class = "info"><b>
    Location Address: </b>{if isset($additionalLocationData.address) && is_string($additionalLocationData.address) && $additionalLocationData.address !=""},{$additionalLocationData.country}{/if}<br>
    {if isset($additionalLocationData.country) && is_string($additionalLocationData.country) && $additionalLocationData.country !=""},{$additionalLocationData.country}{/if}{if isset($additionalLocationData.city)  && is_string($additionalLocationData.city) && $additionalLocationData.city !=""},{$additionalLocationData.city}{/if}{if isset($additionalLocationData.state) && is_string($additionalLocationData.state)},{$additionalLocationData.state}{/if}{if isset($additionalLocationData.zip) && is_string($additionalLocationData.zip) && $additionalLocationData.zip !=""}{$additionalLocationData.zip}{/if}</p>
    {/if}
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
                        <td class = "info">${$additionalLocationData.additionalLocationPropertyTotal|number_format}</td>
                    </tr>
                    <tr>
                        <td class = "info" id="space_left">(Sign limited to : $25,000)</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td class = "info">Business Income:</td>
                        <td class = "info">${$additionalLocationData.ALLossofBusIncome|number_format}</td>
                    </tr>
                    <tr>
                        <td class = "info">Building Coverage:</td>
                        {if isset($additionalLocationData.additionalLocationDoYouOwntheBuilding) && $additionalLocationData.additionalLocationDoYouOwntheBuilding != "no"}
                            <td  class = "info" >${$additionalLocationData.ALBuildingReplacementValue|number_format}</td>
                        {else}
                            <td class = "info">Not Included</td>
                        {/if}
                    </tr>
                    <tr>
                        <td class = "info">Equipment Breakdown:</td>
                        {if isset($additionalLocationData.additionalLocationFurniturefixturesAndEquipment) && (int)$additionalLocationData.additionalLocationFurniturefixturesAndEquipment != 0}
                            <td class = "info">Included</td>
                        {else}
                            <td class = "info">Not Included</td>
                        {/if}
                    </tr>
                    <tr>
                        <td class = "info">Business Income from dependant properties:</td>
                        <td class = "info">$5,000</td>
                    </tr>
                    <tr>
                        <td class = "info">Robbery (per Occurrence - Inside):</td>
                        <td class = "info">$2,500</td>
                    </tr>
                    <tr>
                        <td class = "info">Robbery (per Occurrence - Outside):</td>
                        <td class = "info">$2,500</td>
                    </tr>
                    <tr>
                        <td class = "info">Transit Coverage (Locked Vehicle):</td>
                        <td class = "info">$10,000</td>
                    </tr>
                    <tr>
                        <td class = "info">EmployeeTheft Limit:</td>
                        <td class = "info">$5,000</td>
                    </tr>
                    <tr>
                        <td class = "info">Property of Others:</td>
                        <td class = "info">$25,000</td>
                    </tr>
                    <tr>
                        <td class = "info">Off premises:</td>
                        <td class = "info">$10,000</td>
                    </tr>
                    <tr>
                        <td class = "info">Glass:</td>
                        <td class = "info">$5,000</td>
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

                        {if isset($additionalLocationData.nonOwnedAutoLiabilityPL)}
                        {if $additionalLocationData.nonOwnedAutoLiabilityPL == "nonOwnedAutoLiability100K"}
                            <td>$100,000</td>
                        {else if $additionalLocationData.nonOwnedAutoLiabilityPL == "nonOwnedAutoLiability1M"}
                            <td>$1,000,000</td>
                        {else}
                            <td>Not Included</td>
                        {/if}
                        {/if}
                    </tr>
                    <tr>
                        <td class = "info">Travel Agent E&O (Each wrongful act & Aggregate):
                            <p class="info">(Claims made form)</p>
                        </td>
                        {if $additionalLocationData.estimatedMonthlyReceipts && (int)$additionalLocationData.estimatedMonthlyReceipts > 0}
                            <td class = "info">$1,000,000</td>
                        {else}
                            <td class = "info">Not Included</td>
                        {/if}
                    </tr>
                </tbody>
            </table>
            <div class="clearfix"></div>
            <p class="info"><b>Deductible:</b></p>
            <p class="info">Wind/Hail is 5% of Insured Values per location, $5000 minimum, for Florida,
                Hawaii, Puerto Rico, USVI, Guam and all Tier 1 locations
                (coastal Counties) in Texas, Louisiana, Mississippi, Alabama, Georgia, South Carolina, North
                Carolina and all Harris County Texas locations.
                Mechanical breakdown is $2500. All other perils is $1000.</p>
            <!-- Report Header -->

            <!-- Alarm Calc -->
            
                {if isset($ALcentralStationAlarm) && $ALcentralStationAlarm != "yes"}
                    <hr class="line_divide"></hr>
              <div style="margin-top: 2% 0;">
                <center>
                    <b>
                        <p>Burglary Coverage is Excluded as there is no Central Station Alarm</p>
                    </b>
                </center>
                 </div>
                {/if}
           
        </div>
  </div>
</body>

</html>