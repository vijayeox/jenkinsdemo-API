<!DOCTYPE html>
<html>

<head>
    <link href="{$smarty.current_dir}/css/divestemplate_css.css" rel="stylesheet" type="text/css" />
    {* <link href="./css/divestemplate_css.css" rel="stylesheet" type="text/css" /> *}
    <script type="text/javascript" src="{$smarty.current_dir}/AgentInfo.js"></script>
</head>

<body>

    <div class="body_div">
        <!-- header Line -->
        <div class="spacer"></div>
        <center>
            <b>
                <p style="margin-top: 5px;" class="info">Store Location: <span class="storeLocation uppercase">{$address1}, {$address2}, {$city}, {$state},
                        {$zip}, {$country}</span></p>
            </b>
        </center>
        <!-- First Section -->
        <div class="main">
            <div class="value_main">
                <p>${$liabilityProRataPremium|number_format:2}</p>
                <p>&nbsp;</p>
                <p>${$propertyProRataPremium|number_format:2}</p>
                <p>${((float)$PropTax+$LiaTax+$AddILocTax)|number_format:2}</p>
                <p>${(float)$AddILocPremium|number_format:2}</p>
                <p>${(float)$padiFee|number_format:2}</p>
            </div>
            <div class="sub_main">
                <p>Dive Center General Liability Premium:</p>
                <p>(Based on estimated annual receipts of ${if isset($dsglestmonthretailreceipt) &&  $dsglestmonthretailreceipt}{(float)$dsglestmonthretailreceipt|number_format:2}{else}0{/if})</p>
                <p>Dive Center Property Premium</p>
                <p>Dive Center Surplus Lines Tax:</p>
                <p>Dive Center PADI Administration Fee:</p>
            </div>
            <div class="clearfix"></div>
            <p class="hrtag sub_line"></p>
        </div>
        <div class="clearfix"></div>
        <div class="total_main">
            <div class="value_main">
                <p>${((float)$ProRataPremium+(float)$PropTax+(float)$LiaTax+(float)$AddILocPremium+(float)$AddILocTax+(float)$padiFee)|number_format:2}</p>
            </div>
            <div class="sub_main">
                <p>Total Store Premium:</p>
            </div>

            <div class="clearfix"></div>
            <p class="spacer sub_line"></p>
        </div>
        <div class="clearfix"></div>
        <!-- second section -->
        <div class="main">
            <div class="value_main">
                <p>
                {if $groupProfessionalLiabilitySelect == 'yes'}{if isset($groupCoverage) && isset($groupExcessLiability)}${((float)$groupCoverage+(float)$groupExcessLiability)|number_format:2}{else}$0.00{/if}{else}$0.00{/if}</p>
                <p>&nbsp;</p>
                <p>{if $groupProfessionalLiabilitySelect == 'yes'}{if isset($groupTaxAmount)}${(float)$groupTaxAmount|number_format:2}{else}$0.00{/if}{else}$0.00{/if}</p>
                <p>{if $groupProfessionalLiabilitySelect == 'yes'}{if isset($groupPadiFeeAmount)}${(float)$groupPadiFeeAmount|number_format:2}{else}$0.00{/if}{else}$0.00{/if}</p>
            </div>
            <div class="sub_main">
                <p>Dive Center Group Instructional Program Premium:</p>
                <p>(Based on estimated annual group receipts of {if isset($annualEstimatedResponseRecieptsGL)}${$annualEstimatedResponseRecieptsGL|number_format:2}{else}$0.00{/if})</p>
                <p>Dive Center Group Instructional Program Surplus Lines Tax:</p>
                <p>Dive Center Group Instructional Program PADI Administration Fee:</p>
            </div>

            <div class="clearfix"></div>
            <p class="hrtag sub_line"></p>
        </div>
        <div class="total_main">
            <div class="value_main">
               <p>{if isset($groupTotalAmount)}${$groupTotalAmount|number_format:2}{else}$0.00{/if}</p>
            </div>
            <div class="sub_main">
                <p>Total Group Premium:</p>
            </div>
        </div>
        <div class="clearfix"></div>
        <p class="hrtag" style="margin-top: 2px;"></p>
        <div class="total_main">
            <div class="value_main">
                 <p>${$totalAmount|number_format:2}</p>
            </div>
            <div class="sub_main">
                 <p>Amount due in full:</p>
            </div>
        </div>
        <div class="clearfix"></div>
        <p></p>
        <p class="spacer"></p>

        <!-- Conditional notice -->
        <div class="second_content">
            <p class="policy_notice">
                The insurance afforded by this policy is a master policy issued to PADI Worldwide Corporation, 30151
                Tomas Street, Rancho Santa Margarita, CA 92688. The insurance is provided under terms and conditions of
                the master policy which is enclosed with this certificate. Please read the policy for a full description
                of the terms, conditions and exclusions of the policy. This certificate does not amend, alter or extend
                the coverage afforded by the policy referenced on this certificate.
            </p>
            <p class="policy_notice">
                Notice of cancelation: The premium and any taxes or fees are fully earned upon inception and no refund
                is granted unless cancelled by the company.If the company cancels this policy, 45 days notice will be
                given to the certificate holder unless cancellation is for nonpayment of premium, then 10 days notice
                will be provided, and any premium not earned will be returned to the certificate holder.
            </p>

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
        <p class="hrtag"></p>
    </div>

</body>

</html>