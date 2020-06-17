{assign var=dspropcentralfire value=$dsPropCentralFirePL|json_decode:true}
<!DOCTYPE html>
<html>

<head>
    <link href="{$smarty.current_dir}/css/divestemplate_css.css" rel="stylesheet" type="text/css" />
    <link href="./css/divestemplate_css.css" rel="stylesheet" type="text/css" />
    <!-- <script type="text/javascript" src="{$smarty.current_dir}/AgentInfo.js"></script> -->
</head>

<body>
    <p></p>
    <div>
        <div class="table_sec">
            <table class="proposal_table" cellspacing="0" cellpadding="0">
                <tbody>
                    <tr>
                        <th>Property Coverages</th>
                        <th>Limits</th>
                    </tr>
                    <tr>
                        <td>Contents Limit:</td>
                        <td>${$dspropTotal|number_format}</td>
                    </tr>
                    <tr>
                        <td id="space_left">(Sign limited to : $25,000)</td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Business Income:</td>
                        <td>${((float)$lossOfBusIncome)|number_format}</td>
                    </tr>
                    <tr>
                        <td>Building Coverage:</td>
                        {if isset($dspropownbuilding) && $dspropownbuilding != "no"}
                            {if isset($dspropreplacementvalue)}
                            <td>${$dspropreplacementvalue|number_format}</td>
                            {else}
                            <td>$0</td>
                            {/if}
                        {else}
                            <td>$0</td>
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
                    <tr>
                        <td>Commercial General Liability (Each Occurrence Limit):</td>
                        {if $excessLiabilityCoverage == ""}
                            <td>$1,000,000</td>
                        {elseif $excessLiabilityCoverage == "excessLiabilityCoverage1M"}
                            <td>$2,000,000</td>
                        {elseif $excessLiabilityCoverage == "excessLiabilityCoverage2M"}
                            <td>$3,000,000</td>
                        {elseif $excessLiabilityCoverage == "excessLiabilityCoverage3M"}
                            <td>$4,000,000</td>
                        {elseif $excessLiabilityCoverage == "excessLiabilityCoverage4M"}
                            <td>$5,000,000</td>
                        {elseif $excessLiabilityCoverage == "excessLiabilityCoverage9M"}
                            <td>$10,000,000</td>
                        {/if}
                    </tr>
                    <tr>
                        <td>Personal Injury (per Occurence):</td>
                        {if $excessLiabilityCoverage == ""}
                            <td>$1,000,000</td>
                        {elseif $excessLiabilityCoverage == "excessLiabilityCoverage1M"}
                            <td>$2,000,000</td>
                        {elseif $excessLiabilityCoverage == "excessLiabilityCoverage2M"}
                            <td>$3,000,000</td>
                        {elseif $excessLiabilityCoverage == "excessLiabilityCoverage3M"}
                            <td>$4,000,000</td>
                        {elseif $excessLiabilityCoverage == "excessLiabilityCoverage4M"}
                            <td>$5,000,000</td>
                        {elseif $excessLiabilityCoverage == "excessLiabilityCoverage9M"}
                            <td>$10,000,000</td>
                        {/if}
                    </tr>
                    <tr>
                        <td>General Liability Aggregate:</td>
                        {if $excessLiabilityCoverage == ""}
                            <td>$2,000,000</td>
                        {elseif $excessLiabilityCoverage == "excessLiabilityCoverage1M"}
                            <td>$3,000,000</td>
                        {elseif $excessLiabilityCoverage == "excessLiabilityCoverage2M"}
                            <td>$4,000,000</td>
                        {elseif $excessLiabilityCoverage == "excessLiabilityCoverage3M"}
                            <td>$5,000,000</td>
                        {elseif $excessLiabilityCoverage == "excessLiabilityCoverage4M"}
                            <td>$6,000,000</td>
                        {elseif $excessLiabilityCoverage == "excessLiabilityCoverage9M"}
                            <td>$11,000,000</td>
                        {/if}
                    </tr>
                    <tr>
                        <td>Products and Completed Operations Aggregate:</td>
                        {if $excessLiabilityCoverage == ""}
                            <td>$2,000,000</td>
                        {elseif $excessLiabilityCoverage == "excessLiabilityCoverage1M"}
                            <td>$3,000,000</td>
                        {elseif $excessLiabilityCoverage == "excessLiabilityCoverage2M"}
                            <td>$4,000,000</td>
                        {elseif $excessLiabilityCoverage == "excessLiabilityCoverage3M"}
                            <td>$5,000,000</td>
                        {elseif $excessLiabilityCoverage == "excessLiabilityCoverage4M"}
                            <td>$6,000,000</td>
                        {elseif $excessLiabilityCoverage == "excessLiabilityCoverage9M"}
                            <td>$11,000,000</td>
                        {/if}
                    </tr>
                    <tr>
                        <td>Damage to premises rented to you:</td>
                        {if $excessLiabilityCoverage == ""}
                            <td>$1,000,000</td>
                        {elseif $excessLiabilityCoverage == "excessLiabilityCoverage1M"}
                            <td>$2,000,000</td>
                        {elseif $excessLiabilityCoverage == "excessLiabilityCoverage2M"}
                            <td>$3,000,000</td>
                        {elseif $excessLiabilityCoverage == "excessLiabilityCoverage3M"}
                            <td>$4,000,000</td>
                        {elseif $excessLiabilityCoverage == "excessLiabilityCoverage4M"}
                            <td>$5,000,000</td>
                        {elseif $excessLiabilityCoverage == "excessLiabilityCoverage9M"}
                            <td>$10,000,000</td>
                        {/if}
                    </tr>
                    <tr>
                        <td>Medical Expense:</td>
                        <td>$5,000</td>
                    </tr>
                    <tr>
                        <td>NON-Owned Auto:</td>
                        {if $nonOwnedAutoLiabilityPL == "no"}
                            <td>Not Included</td>
                        {else}
                            <td>Included</td>
                        {/if}
                    </tr>
                    <tr>
                        <td>NON-Diving Pool Use:</td>
                        {if isset($poolLiability) && (int)$poolLiability > 0}
                            <td>$1,000,000</td>
                        {else}
                            <td>Not Included</td>
                        {/if}
                    </tr>
                    <tr>
                        <td>Travel Agent E&O (Each wrongful act & Aggregate):
                            <p class="info">(Claims made form)</p>
                        </td>
                        {if isset($travelAgentEOReceiptsPL) && (int)$travelAgentEOReceiptsPL > 0}
                            <td>$1,000,000</td>
                        {else}
                            <td>Not Included</td>
                        {/if}
                    </tr>
                    <tr>
                        <td>Group Professional Liability:</td>
                        {if isset($groupProfessionalLiability) && (int)$groupProfessionalLiability > 0}
                            <td>$1,000,000</td>
                        {else}
                            <td>Not Included</td>
                        {/if}
                    </tr>
                    <tr>
                        <td>Group Professional Liability Aggregate:</td>
                        {if isset($groupProfessionalLiability) && (int)$groupProfessionalLiability > 0}
                            <td>$2,000,000</td>
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
                            Mechanical breakdown is $2500. All other perils is {if isset($PropDeductibleCredit)}${$PropDeductibleCredit}}.{else}$0.00{/if}
                            </td>
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
                {if isset($dspropcentralfire.centralStationAlarmPL) && $dspropcentralfire.centralStationAlarmPL != "yes"}
                <center>
                    <b>
                        <p>Burglary Coverage is Excluded as there is no Central Station Alarm</p>
                    </b>
                </center>
                {/if}
            </div>
            <!-- Additional Location -->

            <!-- Surplus Lines -->

            {if $state == 'Alaska'}
            <center>
                <p class="notice">
                    <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/AK.tpl"}</b>
                </p>
            </center>
            {elseif $state == 'Alabama'}
            <center>
                <p class="notice">
                    <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/AL.tpl"}</b>
                </p>
            </center>
            {elseif $state == 'Arkansas'}
            <center>
                <p class="notice">
                    <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/AR.tpl"}</b>
                </p>
            </center>
            {elseif $state == 'Arizona'}
            <center>
                <p class="notice">
                    <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/AZ.tpl"}</b>
                </p>
            </center>
            {elseif $state == 'Colorado'}
            <center>
                <p class="notice">
                    <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/CO.tpl"}</b>
                </p>
            </center>
            {elseif $state == 'Connecticut'}
            <center>
                <p class="notice">
                    <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/CT.tpl"}</b>
                </p>
            </center>
            {elseif $state == 'District of Columbia'}
            <center>
                <p class="notice">
                    <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/DC.tpl"}</b>
                </p>
            </center>
            {elseif $state == 'Delaware'}
            <center>
                <p class="notice">
                    <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/DE.tpl"}</b>
                </p>
            </center>
            {elseif $state == 'Florida'}
            <center>
                <p class="notice">
                    <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/FL.tpl"}</b>
                </p>
            </center>
            {elseif $state == 'Micronesia'}
            <center>
                <p class="notice">
                    <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/FM.tpl"}</b>
                </p>
            </center>
            {elseif $state == 'Georgia'}
            <center>
                <p class="notice">
                    <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/GA.tpl"}</b>
                </p>
            </center>
            {elseif $state == 'Hawaii'}
            <center>
                <p class="notice">
                    <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/HI.tpl"}</b>
                </p>
            </center>
            {elseif $state == 'Iowa'}
            <center>
                <p class="notice">
                    <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/IA.tpl"}</b>
                </p>
            </center>
            {elseif $state == 'Idaho'}
            <center>
                <p class="notice" style="color:red;">
                    <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/ID.tpl"}</b>
                </p>
            </center>
            {elseif $state == 'Illinois'}
            <center>
                <p class="notice">
                    <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/IL.tpl"}</b>
                </p>
            </center>
            {elseif $state == 'International'}
            <center>
                <p class="notice">
                    <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/International.tpl"}</b>
                </p>
            </center>
            {elseif $state == 'Kansas'}
            <center>
                <p class="notice">
                    <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/KS.tpl"}</b>
                </p>
            </center>
            {elseif $state == 'Kentucky'}
            <center>
                <p class="notice">
                    <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/KY.tpl"}</b>
                </p>
            </center>
            {elseif $state == 'Louisiana'}
            <center>
                <p class="notice" style="color:red;">
                    <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/LA.tpl"}</b>
                </p>
            </center>
            {elseif $state == 'Massachusetts'}
            <center>
                <p class="notice">
                    <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/MA.tpl"}</b>
                </p>
            </center>
            {elseif $state == 'Maryland'}
            <center>
                <p class="notice">
                    <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/MD.tpl"}</b>
                </p>
            </center>
            {elseif $state == 'Maine'}
            <center>
                <p class="notice">
                    <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/ME.tpl"}</b>
                </p>
            </center>
            {elseif $state == 'Marshall Islands'}
            <center>
                <p class="notice">
                    <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/MH.tpl"}</b>
                </p>
            </center>
            {elseif $state == 'Michigan'}
            <center>
                <p class="notice" style="color:red;">
                    <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/MI.tpl"}</b>
                </p>
            </center>
            {elseif $state == 'Minnesota'}
            <center>
                <p class="notice" style="color:red;">
                    <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/MN.tpl"}</b>
                </p>
            </center>
            {elseif $state == 'Missouri'}
            <center>
                <p class="notice">
                    <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/MO.tpl"}</b>
                </p>
            </center>
            {elseif $state == 'Mississippi'}
            <center>
                <p class="notice">
                    <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/MS.tpl"}</b>
                </p>
            </center>
            {elseif $state == 'Montana'}
            <center>
                <p class="notice">
                    <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/MT.tpl"}</b>
                </p>
            </center>
            {elseif $state == 'North Carolina'}
            <center>
                <p class="notice">
                    <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/NC.tpl"}</b>
                </p>
            </center>
            {elseif $state == 'North Dakota'}
            <center>
                <p class="notice">
                    <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/ND.tpl"}</b>
                </p>
            </center>
            {elseif $state == 'Nebraska'}
            <center>
                <p class="notice">
                    <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/NE.tpl"}</b>
                </p>
            </center>
            {elseif $state == 'New Hampshire'}
            <center>
                <p class="notice">
                    <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/NH.tpl"}</b>
                </p>
            </center>
            {elseif $state == 'New Jersey'}
            <center>
                <p class="notice">
                    <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/NJ.tpl"}</b>
                </p>
            </center>
            {elseif $state == 'New Mexico'}
            <center>
                <p class="notice">
                    <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/NM.tpl"}</b>
                </p>
            </center>
            {elseif $state == 'Nevada'}
            <center>
                <p class="notice">
                    <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/NV.tpl"}</b>
                </p>
            </center>
            {elseif $state == 'New York'}
            <center>
                <p class="notice" style="color:red;">
                    <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/NY.tpl"}</b>
                </p>
            </center>
            {elseif $state == 'Ohio'}
            <center>
                <p class="notice">
                    <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/OH.tpl"}</b>
                </p>
            </center>
            {elseif $state == 'Oklahoma'}
            <center>
                <p class="notice">
                    <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/OK.tpl"}</b>
                </p>
            </center>
            {elseif $state == 'Oregon'}
            <center>
                <center>
                    <p class="notice">
                        <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/OR.tpl"}</b>
                    </p>
                </center>
                {elseif $state == 'Pennsylvania'}
                <center>
                    <p class="notice">
                        <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/PA.tpl"}</b>
                    </p>
                </center>
                {elseif $state == 'Palau'}
                <center>
                    <p class="notice">
                        <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/PW.tpl"}</b>
                    </p>
                </center>
                {elseif $state == 'Rhode Island'}
                <center>
                    <p class="notice">
                        <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/RI.tpl"}</b>
                    </p>
                </center>
                {elseif $state == 'South Carolina'}
                <center>
                    <p class="notice">
                        <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/SC.tpl"}</b>
                    </p>
                </center>
                {elseif $state == 'South Dakota'}
                <center>
                    <p class="notice">
                        <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/SD.tpl"}</b>
                    </p>
                </center>
                {elseif $state == 'Tennessee'}
                <center>
                    <p class="notice">
                        <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/TN.tpl"}</b>
                    </p>
                </center>
                {elseif $state == 'Texas'}
                <center>
                    <p class="notice">
                        <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/TX.tpl"}</b>
                    </p>
                </center>
                {elseif $state == 'Utah'}
                <center>
                    <p class="notice">
                        <b>{include file = "{$smarty.current_dir}/v/UT.tpl"}</b>
                    </p>
                </center>
                {elseif $state == 'Virginia'}
                <center>
                    <p class="notice">
                        <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/VA.tpl"}</b>
                    </p>
                </center>
                {elseif $state == 'Virgin Islands'}
                <center>
                    <p class="notice" style="color:red;">
                        <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/VT.tpl"}</b>
                    </p>
                </center>
                {elseif $state == 'Washington'}
                <center>
                    <p class="notice">
                        <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/WA.tpl"}</b>
                    </p>
                </center>
                {elseif $state == 'Wisconsin'}
                <center>
                    <p class="notice">
                        <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/WI.tpl"}</b>
                    </p>
                </center>
                {elseif $state == 'West Virginias'}
                <center>
                    <p class="notice">
                        <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/WV.tpl"}</b>
                    </p>
                </center>
                {elseif $state == 'Wyoming'}
                <center>
                    <p class="notice">
                        <b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/WY.tpl"}</b>
                    </p>
                </center>
                {/if}

        </div>

        <!-- Second Page -->
    </div>

</body>

</html>