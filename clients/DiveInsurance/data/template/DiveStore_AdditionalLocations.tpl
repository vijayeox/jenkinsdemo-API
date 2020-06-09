{assign var=additionalLocationData value=$additionalLocationData|json_decode:true}
{assign var=dspropcentralfire value=$additionalLocationData.dsPropCentralFireAL}
<!DOCTYPE html>
<html>

<head>
    <link href="{$smarty.current_dir}/css/divestemplate_css.css" rel="stylesheet" type="text/css" />
    <link href="{$smarty.current_dir}/css/divestemplate_css.css" rel="stylesheet" type="text/css" />
    <!-- <script type="text/javascript" src="{$smarty.current_dir}/AgentInfo.js"></script> -->
</head>

<body>
    <p></p>
    <div>

    <b><p>Store/Location Description : {$business_name}</p></b>
    <b><p>Store/Location Number: {$padi}</p></b>
    <p><b>Additional Named Insured:</b></p>
    {assign var=list value=$additionalNamedInsured|json_decode:true}
    {if isset($additional_named_insureds_option) && $additional_named_insureds_option=='yes'}
    {foreach from=$list item=$additional}
        <p class = "ai_list">
            &nbsp&nbsp&nbsp{$additional.name},{$additional.address},{$additional.country},{$additional.city},{$additional.state},{$additional.zip}
        </p>
    {/foreach}
    {/if}
    <p><b>Location Address: {$additionalLocationData.address},<br>
    {$additionalLocationData.country},{$additionalLocationData.city},{$additionalLocationData.state} - {$additionalLocationData.zip}</p>
        <div class="table_sec">
            <table class="proposal_table" cellspacing="0" cellpadding="0">
                <tbody>
                    <tr>
                        <th>Property Coverages</th>
                        <th>Limits</th>
                    </tr>
                    <tr><td>Policy issued by {$carrier}</td></tr>
                    <tr><td>Policy #: PPK1992907</td></tr>
                    <tr>
                        <td>Contents Limit:</td>
                        <td>${$additionalLocationData.additionalLocationPropertyTotal|number_format}</td>
                    </tr>
                    <tr>
                        <td id="space_left">(Sign limited to : $25,000)</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Business Income:</td>
                        {if $additionalLocationData.ALLossofBusIncomeCheckBox != "false"}
                            <td>${$additionalLocationData.ALLossofBusIncome|number_format}</td>
                        {else}
                            <td>$0</td>
                        {/if}
                    </tr>
                    <tr>
                        <td>Building Coverage:</td>
                        {if isset($additionalLocationData.additionalLocationDoYouOwntheBuilding) && $additionalLocationData.additionalLocationDoYouOwntheBuilding != "no"}
                            <td>${$additionalLocationData.ALBuildingReplacementValue|number_format}</td>
                        {else}
                            <td>Not Included</td>
                        {/if}
                    </tr>
                    <tr>
                        <td>Equipment Breakdown:</td>
                        {if isset($additionalLocationData.additionalLocationFurniturefixturesAndEquipment) && (int)$additionalLocationData.additionalLocationFurniturefixturesAndEquipment != 0}
                            <td>Included</td>
                        {else}
                            <td>Not Included</td>
                        {/if}
                    </tr>
                    <tr>
                        <td>Business Income from dependant properties:</td>
                        <td>$5,000</td>
                    </tr>
                    <tr>
                        <td>Robbery (per Occurrence - Inside):</td>
                        <td>$2,500</td>
                    </tr>
                    <tr>
                        <td>Robbery (per Occurrence - Outside):</td>
                        <td>$2,500</td>
                    </tr>
                    <tr>
                        <td>Transit Coverage (Locked Vehicle):</td>
                        <td>$10,000</td>
                    </tr>
                    <tr>
                        <td>EmployeeTheft Limit:</td>
                        <td>$5,000</td>
                    </tr>
                    <tr>
                        <td>Property of Others:</td>
                        <td>$25,000</td>
                    </tr>
                    <tr>
                        <td>Off premises:</td>
                        <td>$10,000</td>
                    </tr>
                    <tr>
                        <td>Glass:</td>
                        <td>$5,000</td>
                    </tr>
                </tbody>
            </table>

            <table class="proposal_table" cellspacing="0" cellpadding="0">
                <tbody>
                    <tr>
                        <th>Liability Coverages</th>
                        <th>Limits</th>
                    </tr>
                    <tr><td>Policy issued by {$carrier}</td></tr>
                    <tr><td>Policy #: PPK1992907</td></tr>
                    <tr>
                        <td>NON-Diving Pool Use:</td>
                        {if isset($additionalLocationData.ALPoolLiability) && (int)$additionalLocationData.ALPoolLiability > 0}
                            <td>$1,000,000</td>
                        {else}
                            <td>Not Included</td>
                        {/if}
                    </tr>
                    <tr>
                        <td>Travel Agent E&O (Each wrongful act & Aggregate):
                            <p class="info">(Claims made form)</p>
                        </td>
                        {if $additionalLocationData.estimatedMonthlyReceipts && (int)$additionalLocationData.estimatedMonthlyReceipts > 0}
                            <td>$1,000,000</td>
                        {else}
                            <td>Not Included</td>
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
            <hr class="line_divide">
            </hr>
            <hr class="line_divide">
            </hr>

            <!-- Report Header -->

            <!-- Alarm Calc -->
            <div style="margin: 2% 0;">
                {if $dspropcentralfire.centralStationAlarmAL != "yes"}
                <center>
                    <b>
                        <p>Burglary Coverage is Excluded as there is no Central Station Alarm</p>
                    </b>
                </center>
                {/if}
            </div>
            <!-- Additional Location -->
        </div>

    </div>

</body>

</html>