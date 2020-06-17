{assign var=additionalLocationData value=$additionalLocationData|json_decode:true}
{assign var=dspropcentralfire value=$dsPropCentralFirePL|json_decode:true}
<!DOCTYPE html>
<html>

<head>
    <link href="{$smarty.current_dir}/css/divestemplate_css.css" rel="stylesheet" type="text/css" />
    <!-- <script type="text/javascript" src="{$smarty.current_dir}/AgentInfo.js"></script> -->
</head>
 
<body>
    <p></p>
    <div>
    <p class = "info"><b>Store/Location Description : </b>{$additionalLocationData.address}</p>
    <p class = "info"><b>Store/Location Number: </b>{$padi}</p>
    {assign var=list value=$additionalNamedInsured|json_decode:true}
    {if isset($additional_named_insureds_option) && $additional_named_insureds_option =='yes'}
    <p class = "info"><b>Additional Named Insured:</b></p>
    {foreach from=$list item=$additional}
        <p class = "ai_list info">
            &nbsp&nbsp&nbsp{$additional.name},{$additional.address},{$additional.country},{$additional.city},{$additional.state},{$additional.zip}
        </p>
    {/foreach}
    {/if}
    <p  class = "info"><b>Location Address: </b>{$additionalLocationData.address},<br>
    {$additionalLocationData.country},{$additionalLocationData.city},{$additionalLocationData.state} - {$additionalLocationData.zip}</p>
        <div class="table_sec">
            <table class="proposal_table" cellspacing="0" cellpadding="0">
                <tbody>
                    <tr>
                        <th class = "table_hd">Property Coverages</th>
                        <th class = "table_hd">Limits</th>
                    </tr>
                    <tr><td   class = "info">Policy issued by {$carrier}</td></tr>
                    <tr><td   class = "info">Policy #: {$policy_id}</td></tr>
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
                    <tr><td   class = "info">Policy issued by {$carrier}</td></tr>
                    <tr><td   class = "info">Policy #: {$policy_id}</td></tr>
                    <tr>
                        <td class = "info">NON-Diving Pool Use:</td>
                        {if isset($additionalLocationData.ALPoolLiability) && (int)$additionalLocationData.ALPoolLiability > 0}
                            <td class = "info">$1,000,000</td>
                        {else}
                            <td class = "info">Not Included</td>
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
            <table class="deductible_table">
                <tbody>
                    <tr>
                        <td>Deductible:</td>
                        <td class="info">Wind/Hail is 5% of Insured Values per location, $5000 minimum, for Florida,
                            Hawaii, Puerto Rico, USVI, Guam and all Tier 1 locations
                            (coastal Counties) in Texas, Louisiana, Mississippi, Alabama, Georgia, South Carolina, North
                            Carolina and all Harris County Texas locations.
                            Mechanical breakdown is $2500. All other perils is $1000.</td>
                    </tr>
                </tbody>
            </table>
            <hr class="line_divide"></hr>
            <hr class="line_divide">
            </hr>

            <!-- Report Header -->

            <!-- Alarm Calc -->
            <div style="margin-top: 2% 0;">
                {if $dspropcentralfire.centralStationAlarmPL != "yes"}
                <center>
                    <b>
                        <p>Burglary Coverage is Excluded as there is no Central Station Alarm</p>
                    </b>
                </center>
                {/if}
            </div>
        </div>
  </div>
</body>

</html>