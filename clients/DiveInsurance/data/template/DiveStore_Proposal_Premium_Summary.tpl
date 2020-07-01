<!DOCTYPE html>
<html>

<head>
    <link href="{$smarty.current_dir}/css/divestemplate_css.css" rel="stylesheet" type="text/css" />
    <link href="./css/divestemplate_css.css" rel="stylesheet" type="text/css" />
    <!-- <script type="text/javascript" src="{$smarty.current_dir}/AgentInfo.js"></script> -->
</head>

<body>
    <p></p>
    <div class="main" style="margin-top: 3%;">
            <div class="value_main">
                <p>${if isset($liabilityProRataPremium)} {$liabilityProRataPremium|number_format:2} {else} $0 {/if}</p>
                <p>&nbsp;</p>
                <p>${$propertyProRataPremium|number_format:2}</p>
                <p>${((float)$PropTax+$LiaTax+$AddILocTax)|number_format:2}</p>
                <p>${(float)$AddILocPremium|number_format:2}</p>
                <p>${(float)$padiFee|number_format:2}</p>
            </div>
            <div class="sub_main">
                <p>Dive Center General Liability Premium:</p>
                <p>(Based on estimated annual receipts of ${if isset($dsglestmonthretailreceipt) &&  $dsglestmonthretailreceipt}{$dsglestmonthretailreceipt|number_format:2}{else}0{/if})</p>
                <p>Dive Center Property Premium</p>
                <p>Dive Center Surplus Lines Tax:</p>
                <p>Dive Center Additional Location Premium:</p>
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
            <p class="spacer2 sub_line"></p>
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
                <p>(Based on estimated annual group receipts of {if isset($annualReceipt)}${$annualReceipt|number_format:2}{else}$0.00{/if})</p>
                <p>Dive Center Group Instructional Program Surplus Lines Tax:</p>
                <p>Dive Center Group Instructional Program PADI Administration Fee:</p>
            </div>

            <div class="clearfix"></div>
            <p class="hrtag sub_line"></p>
        </div>
        <div class="total_main">
            <div class="value_main">
                <p>{if $groupProfessionalLiabilitySelect == 'yes'}{if isset($groupTotalAmount)}${$groupTotalAmount|number_format:2}{else}$0.00{/if}{else}$0.00{/if}</p>
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
        <p class="spacer2"></p>
        <p></p>

        <!-- Payment Section -->
        <div>
            <div class="paymentSection">
                <p>PAYMENT AMOUNT:&nbsp;&nbsp;<span>______________________________________</span></p>
                <div>
                    <div class="payment_option">
                        <div class="checkbox"></div>
                        <div>
                            <p>CHECK or MONEY ORDER</p>
                            <p>Make payable to HUB International (U.S. funds only)</p>
                        </div>
                    </div>
                    <div class="payment_option">
                        <div class="checkbox"></div>
                        <div>
                            <p>CREDIT CARD</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="paymentSection">
                <center>
                    <p>CREDIT CARD INFORMATION (If paying by Credit Card)</p>
                </center>
                <center style="margin-left: 20%;">
                    <div class="clearfix"></div>
                    <div class="checkbox"></div>
                    <div class="check_p">VISA</div>
                    <div class="checkbox"></div>
                    <div class="check_p">MasterCard</div>
                    <div class="checkbox"></div>
                    <div class="check_p">American Express</div>
                    <div class="clearfix"></div>
                </center>

                <div style="margin-left: 10%;">
                    <p>Card Number:&nbsp;&nbsp;<span>______________________________________</span></p>
                    <div>
                        <span>
                            Expiration Date:&nbsp;&nbsp;<span>_________________</span>
                        </span>&nbsp;
                        <span>
                            CVV2:&nbsp;&nbsp;<span>___________________</span>
                        </span>
                    </div>
                    <p>Card Holder Name (Print):&nbsp;&nbsp;<span>_______________________________</span></p>
                    <p>Card Holder Signature:&nbsp;&nbsp;<span>_______________________________</span></p>
                </div>
            </div>
            <div class="clearfix"></div>
            <p></p>
            <div class="spacer2"></div>
            <center>
                <p class="info">
                    We (I) know and acknowledge that this policy does not provide any insurance coverage or defense for
                    snow-ski rentals or snow-ski binding adjustments. No
                    coverage is provided for firearms (this exclusion does not include spearguns). No coverage is
                    provided
                    under this policy for any professional liability except
                    under the terms of the designated services exclusion with exception coverage. Professional Liability
                    includes, but is not limited to, instruction of scuba diving,
                    snorkeling, swimming and freediving. The policy defines these and other exclusions as they apply.
                    Burglary Coverage is afforded only with an armed Central
                    Station Alarm (Policy Warranty). This policy does not provide coverage for Workers Compensation or
                    employer&apos;s liability.
                </p>
            </center>
            <p></p>
            <div class="spacer2"></div>

            <center>
                <p>I accept this proposal and understand this is a summary of coverage and the actual policy language
                    determines the coverage.</p>
            </center>

            <div>
                <table style="width: 100%;" cellpadding="0" cellspacing="1">
                    <tbody>
                        <tr>
                            <td style="width: 50%;text-align: center;">
                                <p>Signature:<span class="">______________________________________</span></p>
                            </td>
                            <td style="width: 20%;text-align: center;">
                                <p>Date:<span class="">______________________________________</span></p>
                            </td>
                            <td style="width: 30%;text-align: center;">
                                <p>Title:<span class="">______________________________________</span></p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>

</html>