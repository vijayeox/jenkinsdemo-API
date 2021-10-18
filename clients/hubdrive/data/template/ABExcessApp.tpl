    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8">
        <title>ABEXCESS</title>
        <style>
            * {
                line-height: 150%;
                text-align: left;
            }

            span {
                padding-right: 2px;
                padding-left: 2px;
            }

            td {
                vertical-align: top;
                text-align: left;
                margin: 4px;
            }

            table {
                width: 100%;
                margin-bottom: 4px;
            }

            .section_black {
                background-color: #000;
                color: #bad421;
            }

            .bold {
                font-weight: bold;
            }

            .m-4 {
                margin: 4px;
            }

            .inline {
                display: inline-block;
            }

            .float-left {
                float: left;
            }

            .float-right {
                float: right;
            }

            .ml-4 {
                margin-left: 4px;
            }

            .ml-6 {
                margin-left: 6px;
            }

            .mr-4 {
                margin-right: 4px;
            }

            .mt-4 {
                margin-top: 4px;
            }

            .mb-4 {
                margin-bottom: 4px;
            }

            .pr-4 {
                padding-right: 4px;
            }

            .pl-4 {
                padding-left: 4px;
            }

            .pt-4 {
                padding-top: 4px;
            }

            .pb-4 {
                padding-bottom: 4px;
            }

            .m-4 {
                margin: 4px;
            }

            .p-4 {
                padding: 4px;
            }

            .underlined {
                padding-bottom: 2px;
                border-bottom: 1px solid #242424;
            }

            .continued {
                color: #646464;
                font-style: italic;
                font-weight: bold;
            }

            .bottom-text {
                height: 100px;
                display: flex;
                justify-content: center;
                align-content: center;
            }

            .rotated-text {
                /* white-space: nowrap; */
                padding: 4px !important;
                margin: 4px !important;
                text-align: left;
                top: 100%;
                width: 5%;
                -webkit-transform: rotate(270deg);
                -webkit-transform-origin: left center;
                -moz-transform: rotate(270deg);
                -moz-transform-origin: left center;
                -ms-transform: rotate(270deg);
                -ms-transform-origin: left center;
                -o-transform: rotate(270deg);
                -o-transform-origin: left center;
                transform: rotate(270deg);
                transform-origin: left center;

            }

            .rotated-data {
                width: 5%;
            }

            #sectionContent {
                font: 13px "Calibri";
                margin-left: 5%;
                width: 90%;
            }

            #driverInfo {
                position: relative;
                display: inline-block;
                width: calc(100% - 17px);
                width: -webkit-calc(100% - 17px);
                width: -moz-calc(100% - 17px);
            }

            .p1 {
                text-align: left;
                padding-left: 4px;
                margin-top: 0px;
                margin-bottom: 0px;
                white-space: nowrap;
                background: #000000;
            }

            .p2 {
                text-align: left;
                padding-left: 7px;
                margin-top: 0px;
                margin-bottom: 0px;
                white-space: nowrap;
            }

            .ft1 {
                font: bold 12px "Arial Black";
                color: #bbd422;
                line-height: 17px;
            }

            .ft2 {
                font: bold 12px "Arial Black";
                line-height: 17px;
            }

            .underline {
                padding: 0 5px 5px 2px;
                margin: 0 5px 5px 0;
                border-bottom: 1px solid #242424;
            }

            .inline-img {
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: center;
                width: 100%;
                padding-bottom: 20px;
            }

            .ft0 {
                font-family: 'Arial Black';
                font-weight: bold;
                text-align: left;
                padding-left: 30%;
                float: right;
            }

            .img {
                width: 10%;
                height: 10%;
                z-index: 999;
                float: left;
            }

            .display-inline {
                display: inline;
            }

            .row:after {
                content: "";
                display: flex;
                clear: both;
                width: 100%;
                font-size: 15px;
            }

            .col-10 {
                width: 10%;
            }

            .col-20 {
                width: 20%;
            }

            .col-30 {
                width: 30%;
            }

            .col-40 {
                width: 40%;
            }

            .col-50 {
                width: 50%;
            }

            .col-60 {
                width: 60%;
            }

            .col-70 {
                width: 70%;
            }

            .col-80 {
                width: 80%;
            }

            .col-90 {
                width: 90%;
            }

            .col-100 {
                width: 100%;
            }

            p {
                /* display:inline-block; */
                box-sizing: border-box;
            }

            .text-right {
                text-align: right;
            }

            .text-left {
                text-align: left;
            }
        </style>
    </head>

    <body>
        {* Section 1 *}
        <section class="mb-4">
            <div class="inline-img">
                <img alt="Avant" width="250px" height="200px" src="{$avantImageSrc}" />
                <p class="ft0">Excess Coverage Application</p>
            </div>
            <div class="mb-4">
                <h3 class="bold"><span class="section_black mr-4">SECTION 1</span> GENERAL INFORMATION </h3>
            </div>

            <div id="sectionContent">
                <div class="row">
                    <div class="float-left" style="width:15%">
                        <strong>INSURED NAME:</strong>
                    </div>
                    <div class="float-left underline" style="width:52%">
                        {if ((isset($insuredName) && $insuredName != ""))}
                            <span>{$insuredName}</span>
                        {else}
                            <span>&nbsp;</span>
                        {/if}
                    </div>
                    <div class="float-left" style="width:15%">
                        <strong>Quote by Date: </strong>
                    </div>
                    <div class="float-left underline" style="width:12%">
                        {if ((isset($quoteByDateFormatted) && $quoteByDateFormatted != ""))}
                            <span>{$quoteByDateFormatted|date_format:"%m/%d/%Y"}</span>
                        {else}
                            <span>&nbsp;</span>
                        {/if}
                    </div>
                </div>
                <br><br>
                <div class="row">
                    <div class="float-left" style="width:13%">
                        <strong>Address: </strong>
                    </div>
                    <div class="float-left underline" style="width:44%">
                        {if ((isset($address) && $address != ""))}
                            <span>{$address}</span>
                        {else}
                            <span>&nbsp;</span>
                        {/if}
                    </div>
                    <div class="float-left" style="width:25%">
                        <strong>Desired Policy Effective Date: </strong>
                    </div>
                    <div class="float-left underline" style="width:12%">
                        {if ((isset($desiredPolicyEffectiveDate) && $desiredPolicyEffectiveDate != ""))}
                            <span>{$desiredPolicyEffDateFormatted|date_format:"%m/%d/%Y"}</span>

                        {else}
                            <span>&nbsp;</span>
                        {/if}
                    </div>
                </div>
                <br><br>
                <div class="row">
                    <div class="float-left" style="width:13%">
                        <strong>City/State/Zip: </strong>
                    </div>
                    <div class="float-left underline" style="width:83%">
                        {if ((isset($csz) && $csz != ""))}
                            <span>{$csz}</span>
                        {else}
                            <span>&nbsp;</span>
                        {/if}
                        <br>
                    </div>
                </div>
                <br><br>
                <div class="row">
                    <div class="float-left col-100">
                        {if $corporation == true || $corporation == "true"}
                            <input id="Corporation" type="checkbox" name="Corporation" checked readonly />
                            <label for="Corporation">Corporation</label>
                        {else}
                            <input id="Corporation" type="checkbox" name="Corporation" readonly />
                            <label for="Corporation">Corporation</label>
                        {/if}

                        {if $partnership == true || $partnership == "true"}
                            <input id="Partnership" type="checkbox" name="Partnership" checked readonly />
                            <label for="Partnership">Partnership</label>
                        {else}
                            <input id="Partnership" type="checkbox" name="Partnership" readonly />
                            <label for="Partnership">Partnership</label>
                        {/if}

                        {if $proprietorship == true || $proprietorship == "true"}
                            <input id="Proprietorship" type="checkbox" name="Proprietorship" checked readonly />
                            <label for="Proprietorship">Proprietorship</label>
                        {else}
                            <input id="Proprietorship" type="checkbox" name="Proprietorship" readonly />
                            <label for="Proprietorship">Proprietorship</label>
                        {/if}

                        {if $llc == true || $llc == "true"}
                            <input id="LLC" type="checkbox" name="LLC" checked readonly />
                            <label for="LLC">LLC</label>
                        {else}
                            <input id="LLC" type="checkbox" name="LLC" readonly />
                            <label for="LLC">LLC</label>
                        {/if}
                    </div>
                </div>
                <br><br>
                <div class="row">
                    <div class="float-left col-100">
                        {if $commonCarrier == true || $commonCarrier == "true"}
                            <input id="Common Carrier" type="checkbox" name="Common Carrier" checked readonly />
                            <label for="Common Carrier">Common Carrier</label>
                        {else}
                            <input id="Common Carrier" type="checkbox" name="Common Carrier" readonly />
                            <label for="Common Carrier">Common Carrier</label>
                        {/if}

                        {if $contractCarrier == true || $contractCarrier == "true"}
                            <input id="Contract Carrier" type="checkbox" name="Contract Carrier" checked readonly />
                            <label for="Contract Carrier">Contract Carrier</label>
                        {else}
                            <input id="Contract Carrier" type="checkbox" name="Contract Carrier" readonly />
                            <label for="Contract Carrier">Contract Carrier</label>
                        {/if}

                        {if $privateCarrier == true || $privateCarrier == "true"}
                            <input id="Private Carrier" type="checkbox" name="Private Carrier" checked readonly />
                            <label for="Private Carrier">Private Carrier</label>
                        {else}
                            <input id="Private Carrier" type="checkbox" name="Private Carrier" readonly />
                            <label for="Private Carrier">Private Carrier</label>
                        {/if}

                        {if $freightBroker == true || $freightBroker == "true"}
                            <input id="Freight Broker" type="checkbox" name="Freight Broker" checked readonly />
                            <label for="Freight Broker">Freight Broker</label>
                        {else}
                            <input id="Freight Broker" type="checkbox" name="Freight Broker" readonly />
                            <label for="Freight Broker">Freight Broker</label>
                        {/if}
                    </div>
                </div>

                <br><br>
                <div class="row">
                    <div class="float-left col-10">
                        <strong>US DOT#: </strong>
                    </div>
                    <div class="float-left col-30 underline">
                        {if ((isset($usDot) && $usDot != ""))}
                            <span>{$usDot}</span>
                        {else}
                            <span>&nbsp;</span>
                        {/if}
                    </div>
                    <div class="float-left col-10">
                        <strong>MC Docket: </strong>
                    </div>
                    <div class="float-left col-30 underline">
                        {if ((isset($mcDocket) && $mcDocket != ""))}
                            <span>{$mcDocket}</span>
                        {else}
                            <span>&nbsp;&nbsp;&nbsp;</span>
                        {/if}
                    </div>
                </div>
                <br><br>
                <div class="row">
                    <div class="float-left" style="width:15%;">
                        <strong>ELD Provider: </strong>
                    </div>
                    <div class="float-left col-20 underline">
                        {if ((isset($eldProvider) && $eldProvider != ""))}
                            <span>{$eldProvider}</span>
                        {else}
                            <span>&nbsp;&nbsp;&nbsp;</span>
                        {/if}
                    </div>
                    <div class="float-left col-20">
                        <strong>ELD Account #: </strong>
                    </div>
                    <div class="float-left col-20 underline">
                        {if ((isset($eldAccountNumber) && $eldAccountNumber != ""))}
                            <span>{$eldAccountNumber}</span>
                        {else}
                            <span>&nbsp;&nbsp;&nbsp;</span>
                        {/if}
                    </div>
                </div>
                <br><br>
                <div class="row">
                    <strong>AGENT INFORMATION</strong>
                    <br><br>
                    <div class="float-left col-10">
                        <strong>Hub Office: </strong>
                    </div>
                    <div class="float-left col-30 underline">
                        {if ((isset($producerRegion) && $producerRegion != ""))}
                            <span>{$producerRegion}</span>
                        {else}
                            <span>&nbsp;&nbsp;&nbsp;</span>
                        {/if}
                    </div>
                    <div class="float-left" style="width:16%;">
                        <strong>Producer’s Name: </strong>
                    </div>
                    <div class="float-left col-30 underline">
                        {if ((isset($producerName) && $producerName != ""))}
                            <span>{$producerName}</span><br>
                        {else}
                            <span>&nbsp;&nbsp;&nbsp;</span>
                        {/if}
                    </div>
                </div>
                <br><br>
                <div class="row">
                    <div class="float-left" style="width:20%">
                        <strong>Hub Office Address: </strong>
                    </div>
                    <div class="float-left underline" style="width:75%">
                        {if ((isset($hubOfficeAddress) && $hubOfficeAddress != ""))}
                            <span>{$hubOfficeAddress}</span>
                        {else}
                            <span>&nbsp;&nbsp;&nbsp;</span>
                        {/if}
                    </div>
                </div>
                <br><br>
                <div class="row">
                    <div class="float-left col-100">
                        Is this quote in excess of the Primary Limit?
                        {if $isThisQuoteInExcessOfThePrimaryLimit == "yes"}
                            <input id="isThisQuoteInExcessOfThePrimaryLimit_Y" type="checkbox"
                                name="isThisQuoteInExcessOfThePrimaryLimit_Y" checked readonly />
                            <label for="isThisQuoteInExcessOfThePrimaryLimit_Y">Y</label>

                            <input id="isThisQuoteInExcessOfThePrimaryLimit_N" type="checkbox"
                                name="isThisQuoteInExcessOfThePrimaryLimit_N" readonly />
                            <label for="isThisQuoteInExcessOfThePrimaryLimit_N">N</label>
                        {elseif $isThisQuoteInExcessOfThePrimaryLimit == "no"}
                            <input id="isThisQuoteInExcessOfThePrimaryLimit_Y" type="checkbox"
                                name="isThisQuoteInExcessOfThePrimaryLimit_Y" readonly />
                            <label for="isThisQuoteInExcessOfThePrimaryLimit_Y">Y</label>

                            <input id="isThisQuoteInExcessOfThePrimaryLimit_N" type="checkbox"
                                name="isThisQuoteInExcessOfThePrimaryLimit_N" checked readonly />
                            <label for="isThisQuoteInExcessOfThePrimaryLimit_N">N</label>
                        {else}
                            <input id="isThisQuoteInExcessOfThePrimaryLimit_Y" type="checkbox"
                                name="isThisQuoteInExcessOfThePrimaryLimit_Y" readonly />
                            <label for="isThisQuoteInExcessOfThePrimaryLimit_Y">Y</label>

                            <input id="isThisQuoteInExcessOfThePrimaryLimit_N" type="checkbox"
                                name="isThisQuoteInExcessOfThePrimaryLimit_N" readonly />
                            <label for="isThisQuoteInExcessOfThePrimaryLimit_N">N</label>
                        {/if}
                    </div>
                </div>
                <br><br>
                <div class="row">
                    <div class="float-left"style="width:35%;">
                        If No, advise what limit is this quote excess of:
                    </div>
                    <div class="float-left underline" style="width:55%;">
                        {if ((isset($ifNoAdviseWhatLimitIsThisQuoteExcessOf) && $ifNoAdviseWhatLimitIsThisQuoteExcessOf != ""))}
                            <span>{$ifNoAdviseWhatLimitIsThisQuoteExcessOf}</span>
                        {else}
                            <span>&nbsp;&nbsp;&nbsp;</span>
                        {/if}
                    </div>
                </div>
                <br><br>
                <div class="row">
                    <strong>Coverage(s) Requested:</strong>
                    <br><br>
                    <div class="float-left col-30">
                        <strong>Limits Needed in Excess Layer:</strong>
                    </div>
                    <div class="float-left col-40 underline">
                        {if $limitsNeededinExcessLayer && $limitsNeededinExcessLayer != ""}
                            {if $limitsNeededinExcessLayer == '1M'}
                                <span>$1,000,000.00</span>
                            {elseif $limitsNeededinExcessLayer == '2M'}
                                <span>$2,000,000.00</span>
                            {elseif $limitsNeededinExcessLayer == '3M'}
                                <span>$3,000,000.00</span>
                            {elseif $limitsNeededinExcessLayer == '4M'}
                                <span>$4,000,000.00</span>
                            {elseif $limitsNeededinExcessLayer == '5M'}
                                <span>$5,000,000.00</span>
                            {elseif $limitsNeededinExcessLayer == 'all'}
                                <span>ALL</span>
                            {/if}
                        {/if}
                    </div>
                </div>
                <br><br>
                <div class="row">
                    <div class="col-100 ml-4">
                        {if $excessCgl == true || $excessCgl == "true"}
                            <input id="Excess CGL Only" type="checkbox" name="Excess CGL Only" checked readonly />
                            <label for="Excess CGL Only">Excess CGL Only</label>
                        {else}
                            <input id="Excess CGL Only" type="checkbox" name="Excess CGL Only" readonly />
                            <label for="Excess CGL Only">Excess CGL Only</label>
                        {/if}
                        <br>
                        {if $excessAutoLiability == true || $excessAutoLiability == "true"}
                            <input id="Excess Auto Liability Only" type="checkbox" name="Excess Auto Liability Only" checked
                                readonly />
                            <label for="Excess Auto Liability Only">Excess Auto Liability Only</label>
                        {else}
                            <input id="Excess Auto Liability Only" type="checkbox" name="Excess Auto Liability Only"
                                readonly />
                            <label for="Excess Auto Liability Only">Excess Auto Liability Only</label>
                        {/if}
                        <br>
                        {if $excessEmployersLiability == true || $excessEmployersLiability == "true"}
                            <input id="Excess GL/Auto/WC EL" type="checkbox" name="Excess GL/Auto/WC EL" checked readonly />
                            <label for="Excess GL/Auto/WC EL">Excess GL/Auto/WC EL</label>
                        {else}
                            <input id="Excess GL/Auto/WC EL" type="checkbox" name="Excess GL/Auto/WC EL" readonly />
                            <label for="Excess GL/Auto/WC EL">Excess GL/Auto/WC EL</label>
                        {/if}
                        <br>
                        {if $excessGlAl == true || $excessGlAl == "true"}
                            <input id="Excess (GL + AL only)" type="checkbox" name="Excess (GL + AL only)" checked
                                readonly />
                            <label for="Excess (GL + AL only)">Excess (GL + AL only)</label>
                        {else}
                            <input id="Excess (GL + AL only)" type="checkbox" name="Excess (GL + AL only)" readonly />
                            <label for="Excess (GL + AL only)">Excess (GL + AL only)</label>
                        {/if}
                        <br>
                    </div>
                </div>
                <br><br>
                <div class="row">
                    <div class="float-left col-20">
                        <strong>Target Premium: </strong></div>
                    <div class="float-left col-40 underline">
                        {if ((isset($targetPremium) && $targetPremium != ""))}
                            <span>${$targetPremium}</span>
                        {else}
                            <span>&nbsp;&nbsp;&nbsp;</span>
                        {/if}
                    </div>
                </div>
                <br><br>
                <table>
                    <tr>
                        <th><strong>Prior Excess Coverage(s)<strong> (Check all that apply):</th>
                        <th><strong>Carrier</strong></th>
                        <th><strong>Expiring Premium</strong></th>
                    </tr>
                    <tr class="ml-4">
                        <td>
                            {if $excessCovCgl == true || $excessCovCgl == "true"}
                                <input id="Excess CGL" type="checkbox" name="Excess CGL" checked readonly />
                                <label for="Excess CGL">Excess CGL</label>
                            {else}
                                <input id="Excess CGL" type="checkbox" name="Excess CGL" readonly />
                                <label for="Excess CGL">Excess CGL</label>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($excessCovCglCarrier) && $excessCovCglCarrier != ""))}
                                <span>{$excessCovCglCarrier}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($excessCovCglPremium) && $excessCovCglPremium != ""))}
                                <span>${$excessCovCglPremium}</span>
                            {else}
                                <span>$&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                    </tr>
                    <tr class="ml-4">
                        <td>
                            {if $commercialAutoLiability == true || $commercialAutoLiability == "true"}
                                <input id="Excess Auto Liability" type="checkbox" name="Excess Auto Liability" checked
                                    readonly />
                                <label for="Excess Auto Liability">Excess Auto Liability</label>
                            {else}
                                <input id="Excess Auto Liability" type="checkbox" name="Excess Auto Liability" readonly />
                                <label for="Excess Auto Liability">Excess Auto Liability</label>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($commercialAutoLiabilityCarrier) && $commercialAutoLiabilityCarrier != ""))}
                                <span>{$commercialAutoLiabilityCarrier}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($commercialAutoLiabilityPremium) && $commercialAutoLiabilityPremium != ""))}
                                <span>${$commercialAutoLiabilityPremium}</span>
                            {else}
                                <span>$&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                    </tr>
                    <tr class="ml-4">
                        <td>
                            {if $employersLiability == true || $employersLiability == "true"}
                                <input id="Excess GL/Auto/WC EL" type="checkbox" name="Excess GL/Auto/WC EL" checked
                                    readonly />
                                <label for="Excess GL/Auto/WC EL">Excess GL/Auto/WC EL</label>
                            {else}
                                <input id="Excess GL/Auto/WC EL" type="checkbox" name="Excess GL/Auto/WC EL" readonly />
                                <label for="Excess GL/Auto/WC EL">Excess GL/Auto/WC EL</label>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($employersLiabilityCarrier) && $employersLiabilityCarrier != ""))}
                                <span>{$employersLiabilityCarrier}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($employersLiabilityPremium) && $employersLiabilityPremium != ""))}
                                <span>${$employersLiabilityPremium}</span>
                            {else}
                                <span>$&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                    </tr>
                    <tr class="ml-4">
                        <td>
                            {if $excessCovGlAl == true || $excessCovGlAl == "true"}
                                <input id="Excess (GL + AL only)" type="checkbox" name="Excess (GL + AL only)" checked
                                    readonly />
                                <label for="Excess (GL + AL only)">Excess (GL + AL only)</label>
                            {else}
                                <input id="Excess (GL + AL only)" type="checkbox" name="Excess (GL + AL only)" readonly />
                                <label for="Excess (GL + AL only)">Excess (GL + AL only)</label>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($excessCovGlAlCarrier) && $excessCovGlAlCarrier != ""))}
                                <span>{$excessCovGlAlCarrier}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($excessCovGlAlPremium) && $excessCovGlAlPremium != ""))}
                                <span>${$excessCovGlAlPremium}</span>
                            {else}
                                <span>$&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                    </tr>
                </table>
                <br><br>
                <table>
                    <tr>
                        <th><strong>Summary of Underlying Coverage(s):</strong></th>
                        <th><strong>Deductible</strong></th>
                        <th><strong>Current Carrier</strong></th>
                        <th><strong>Premium</strong></th>
                        <th><strong>Limit</strong></th>
                    </tr>
                    <tr class="ml-4">
                        <td>
                            {if $autoLiability == true || $autoLiability == "true"}
                                <input id="Auto Liability" type="checkbox" name="Auto Liability" checked readonly />
                                <label for="Auto Liability">Auto Liability</label>
                            {else}
                                <input id="Auto Liability" type="checkbox" name="Auto Liability" readonly />
                                <label for="Auto Liability">Auto Liability</label>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($autoLiabilityDeduct) && $autoLiabilityDeduct != ""))}
                                <span>${$autoLiabilityDeduct}</span>
                            {else}
                                <span>$&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($autoLiabilityCarrier) && $autoLiabilityCarrier != ""))}
                                <span>{$autoLiabilityCarrier}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($autoLiabilityPremium) && $autoLiabilityPremium != ""))}
                                <span>${$autoLiabilityPremium}</span>
                            {else}
                                <span>$&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">

                            {if ((isset($autoLiabilityLimit) && $autoLiabilityLimit != ""))}
                                <span>${$autoLiabilityLimit}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                    </tr>
                    <tr class="ml-4">
                        <td>
                            {if $personalInjuryProtectionPip == true || $personalInjuryProtectionPip == "true"}
                                <input id="Personal Injury Protection (PIP)" type="checkbox"
                                    name="Personal Injury Protection (PIP)" checked readonly />
                                <label for="Personal Injury Protection (PIP)">Personal Injury Protection
                                    (PIP)</label>
                            {else}
                                <input id="Personal Injury Protection (PIP)" type="checkbox"
                                    name="Personal Injury Protection (PIP)" readonly />
                                <label for="Personal Injury Protection (PIP)">Personal Injury Protection
                                    (PIP)</label>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($personalInjuryProtectionPipDeduct) && $personalInjuryProtectionPipDeduct != ""))}
                                <span>${$personalInjuryProtectionPipDeduct}</span>
                            {else}
                                <span>$&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($personalInjuryProtectionPipCarrier) && $personalInjuryProtectionPipCarrier != ""))}
                                <span>{$personalInjuryProtectionPipCarrier}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($personalInjuryProtectionPipPremium) && $personalInjuryProtectionPipPremium != ""))}
                                <span>${$personalInjuryProtectionPipPremium}</span>
                            {else}
                                <span>$&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($personalInjuryProtectionPipLimit) && $personalInjuryProtectionPipLimit != ""))}
                                <span>${$personalInjuryProtectionPipLimit}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                    </tr>
                    <tr class="ml-4">
                        <td>
                            {if $umUim == true || $umUim == "true"}
                                <input id="UM/UIM" type="checkbox" name="UM/UIM" checked readonly />
                                <label for="UM/UIM">UM/UIM</label>
                            {else}
                                <input id="UM/UIM" type="checkbox" name="UM/UIM" readonly />
                                <label for="UM/UIM">UM/UIM</label>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($umUimDeduct) && $umUimDeduct != ""))}
                                <span>${$umUimDeduct}</span>
                            {else}
                                <span>$&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($umUimCarrier) && $umUimCarrier != ""))}
                                <span>{$umUimCarrier}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($umUimPremium) && $umUimPremium != ""))}
                                <span>${$umUimPremium}</span>
                            {else}
                                <span>$&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($umUimLimit) && $umUimLimit != ""))}
                                <span>${$umUimLimit}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                    </tr>
                    <tr class="ml-4">
                        <td>
                            {if $companyPhysicalDamage == true || $companyPhysicalDamage == "true"}
                                <input id="Company Physical Damage" type="checkbox" name="Company Physical Damage" checked
                                    readonly />
                                <label for="Company Physical Damage">Company Physical Damage</label>
                            {else}
                                <input id="Company Physical Damage" type="checkbox" name="Company Physical Damage"
                                    readonly />
                                <label for="Company Physical Damage">Company Physical Damage</label>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($companyPhysicalDamageDeduct) && $companyPhysicalDamageDeduct != ""))}
                                <span>${$companyPhysicalDamageDeduct}</span>
                            {else}
                                <span>$&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($companyPhysicalDamageCarrier) && $companyPhysicalDamageCarrier != ""))}
                                <span>{$companyPhysicalDamageCarrier}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($companyPhysicalDamagePremium) && $companyPhysicalDamagePremium != ""))}
                                <span>${$companyPhysicalDamagePremium}</span>
                            {else}
                                <span>$&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($companyPhysicalDamageLimit) && $companyPhysicalDamageLimit != ""))}
                                <span>${$companyPhysicalDamageLimit}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                    </tr>
                    <tr class="ml-4">
                        <td>
                            {if $hiredAuto == true || $hiredAuto == "true"}
                                <input id="Hired Auto" type="checkbox" name="Hired Auto" checked readonly />
                                <label for="Hired Auto">Hired Auto</label>
                            {else}
                                <input id="Hired Auto" type="checkbox" name="Hired Auto" readonly />
                                <label for="Hired Auto">Hired Auto</label>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($hiredAutoDeduct) && $hiredAutoDeduct != ""))}
                                <span>${$hiredAutoDeduct}</span>
                            {else}
                                <span>$&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($hiredAutoCarrier) && $hiredAutoCarrier != ""))}
                                <span>{$hiredAutoCarrier}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($hiredAutoPremium) && $hiredAutoPremium != ""))}
                                <span>${$hiredAutoPremium}</span>
                            {else}
                                <span>$&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($hiredAutoLimit) && $hiredAutoLimit != ""))}
                                <span>${$hiredAutoLimit}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                    <tr class="ml-4">
                        <td>
                            {if $trailerInterchange == true || $trailerInterchange == "true"}
                                <input id="Trailer Interchange" type="checkbox" name="Trailer Interchange" checked
                                    readonly />
                                <label for="Trailer Interchange">Trailer Interchange</label>
                            {else}
                                <input id="Trailer Interchange" type="checkbox" name="Trailer Interchange" readonly />
                                <label for="Trailer Interchange">Trailer Interchange</label>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($trailerInterchangeDeduct) && $trailerInterchangeDeduct != ""))}
                                <span>${$trailerInterchangeDeduct}</span>
                            {else}
                                <span>$&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($trailerInterchangeCarrier) && $trailerInterchangeCarrier != ""))}
                                <span>{$trailerInterchangeCarrier}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($trailerInterchangePremium) && $trailerInterchangePremium != ""))}
                                <span>${$trailerInterchangePremium}</span>
                            {else}
                                <span>$&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($trailerInterchangeLimit) && $trailerInterchangeLimit != ""))}
                                <span>${$trailerInterchangeLimit}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                    </tr>
                    <tr class="ml-4">
                        <td>
                            {if $generalLiability == true || $generalLiability == "true"}
                                <input id="General Liability" type="checkbox" name="General Liability" checked readonly />
                                <label for="General Liability">General Liability</label>
                            {else}
                                <input id="General Liability" type="checkbox" name="General Liability" readonly />
                                <label for="General Liability">General Liability</label>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($generalLiabilityDeduct) && $generalLiabilityDeduct != ""))}
                                <span>${$generalLiabilityDeduct}</span>
                            {else}
                                <span>$&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($generalLiabilityCarrier) && $generalLiabilityCarrier != ""))}
                                <span>{$generalLiabilityCarrier}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($generalLiabilityPremium) && $generalLiabilityPremium != ""))}
                                <span>${$generalLiabilityPremium}</span>
                            {else}
                                <span>$&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($generalLiabilityLimit) && $generalLiabilityLimit != ""))}
                                <span>${$generalLiabilityLimit}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                    </tr>
                    <tr class="ml-4">
                        <td>
                            {if $umbrella == true || $umbrella == "true"}
                                <input id="Umbrella" type="checkbox" name="Umbrella" checked readonly />
                                <label for="Umbrella">Umbrella</label>
                            {else}
                                <input id="Umbrella" type="checkbox" name="Umbrella" readonly />
                                <label for="Umbrella">Umbrella</label>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($umbrellaDeduct) && $umbrellaDeduct != ""))}
                                <span>${$umbrellaDeduct}</span>
                            {else}
                                <span>$&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($umbrellaCarrier) && $umbrellaCarrier != ""))}
                                <span>{$umbrellaCarrier}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($umbrellaPremium) && $umbrellaPremium != ""))}
                                <span>${$umbrellaPremium}</span>
                            {else}
                                <span>$&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($umbrellaLimit) && $umbrellaLimit != ""))}
                                <span>${$umbrellaLimit}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                    </tr>
                    <tr class="ml-4">
                        <td>
                            {if $employersLiability1 == true || $employersLiability1 == "true"}
                                <input id="Employee Liability" type="checkbox" name="Employee Liability" checked readonly />
                                <label for="Employee Liability">Employee Liability</label>
                            {else}
                                <input id="Employee Liability" type="checkbox" name="Employee Liability" readonly />
                                <label for="Employee Liability">Employee Liability</label>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($employersLiability1Deduct) && $employersLiability1Deduct != ""))}
                                <span>${$employersLiability1Deduct}</span>
                            {else}
                                <span>$&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($employersLiability1Carrier) && $employersLiability1Carrier != ""))}
                                <span>{$employersLiability1Carrier}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($employersLiability1Premium) && $employersLiability1Premium != ""))}
                                <span>${$employersLiability1Premium}</span>
                            {else}
                                <span>$&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($employersLiability1Limit) && $employersLiability1Limit != ""))}
                                <span>${$employersLiability1Limit}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                    </tr>
                    <tr class="ml-4">
                        <td>
                            {if $garageLiability1 == true || $garageLiability1 == "true"}
                                <input id="Garage Liability" type="checkbox" name="Garage Liability" checked readonly />
                                <label for="Garage Liability">Garage Liability</label>
                            {else}
                                <input id="Garage Liability" type="checkbox" name="Garage Liability" readonly />
                                <label for="Garage Liability">Garage Liability</label>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($garageLiability1Deduct) && $garageLiability1Deduct != ""))}
                                <span>${$garageLiability1Deduct}</span>
                            {else}
                                <span>$&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($garageLiability1Carrier) && $garageLiability1Carrier != ""))}
                                <span>${$garageLiability1Carrier}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($garageLiability1Premium) && $garageLiability1Premium != ""))}
                                <span>${$garageLiability1Premium}</span>
                            {else}
                                <span>$&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($garageLiability1Limit) && $garageLiability1Limit != ""))}
                                <span>${$garageLiability1Limit}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                    </tr>
                </table>
                <br><br>
                <div class="ml-4 pl-4 row">
                    <div class="float-left col-10">
                        <strong>Total Units</strong>
                    </div>
                    <div class="float-left col-30 underline">
                        {if ((isset($totalUnits) && $totalUnits != ""))}
                            <span>{$totalUnits}</span><br>
                        {else}
                            &nbsp;&nbsp;&nbsp;<br>
                        {/if}
                    </div>
                </div>
                <br><br>
                <div class="ml-4 pl-4 row">
                    <div class="float-left" style="width: 25%;">
                    <strong>Garage Liability Revenue</strong>
                    </div>
                    <div class="float-left col-30 underline">
                        {if ((isset($garageLiabilityRevenue) && $garageLiabilityRevenue != ""))}
                            <span>{$garageLiabilityRevenue}</span><br>
                        {else}
                            &nbsp;&nbsp;&nbsp; <br>
                        {/if}
                    </div>
                </div>
                <br><br>
                <p class="continued">SECTION 1: GENERAL INFORMATION, continued</p>
                <p>
                    <strong>Provide brief description of Operations and Ownership/Management experience: </strong>
                        {if isset($descriptionOfOperations) && $descriptionOfOperations != ""}
                        <br>{$descriptionOfOperations}<br>
                    {/if}
                    <br><br>
                </p>
                <strong>Garage Locations:</strong><br>
                <table>
                    <tr>
                        <th><strong>Location</strong></th>
                        <th><strong>Address (Street, City, State, Zip Code)</strong></th>
                        <th><strong># Units Each Location</strong></th>
                    </tr>
                    {foreach from=$garageLocations item=item key=key}
                        <tr>
                            <td>#{$key+1}.</td>

                            <td class="underline pr-4">
                                {if ((isset($item.garageLocAddress) && $item.garageLocAddress != "")) && ((isset($item.garageLocCity) && $item.garageLocCity != "")) && ((isset($item.garageLocState.name) && $item.garageLocState.name != "")) && ((isset($item.garageLocZipCode) && $item.garageLocZipCode != ""))}
                                    <span>{$item.garageLocAddress},{$item.garageLocCity},{$item.garageLocState.name},{$item.garageLocZipCode}</span><br>
                                {else}
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
                                {/if}
                            </td>

                            <td class="underline pr-4">
                                {if ((isset($item.garageLocUnitsEachLocation) && $item.garageLocUnitsEachLocation != ""))}
                                    <span>{$item.garageLocUnitsEachLocation}</span><br>
                                {else}
                                    &nbsp;&nbsp;&nbsp;<br>
                                {/if}
                            </td>
                        </tr>
                    {/foreach}
                </table>
                <br><br>
                <table>
                    <tr>
                        <th><strong>Type of Operation</strong></th>
                        <th><strong>Radius of Operation</strong></th>
                        <th><strong>Type of Units</strong></th>
                        <th><strong># of Units</strong></th>
                    </tr>
                    <tr>
                        <td>
                            Flatbed
                            
                            <span class="underline float-right" style="padding-right:15px; width:15%">
                            {if ((isset($flatbed) && $flatbed != ""))}
                                <span>{$flatbed}</span>%
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>%
                            {/if}
                            <span>
                        </td>
                        <td>
                            0-50 Local
                            
                            <span class="underline float-right" style="padding-right:15px; width:15%">
                            {if ((isset($Local) && $Local != ""))}
                                <span>{$Local}</span>%
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>%
                            {/if}
                            <span>
                        </td>
                        <td>Private Passenger</td>
                        <td>
                            
                        <span class="underline float-right" style="padding-right:15px; width:15%">
                        {if ((isset($noOfPrivatePassengers) && $noOfPrivatePassengers != ""))}
                                {$noOfPrivatePassengers}
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        <span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Intermodal
                            
                            <span class="underline float-right" style="padding-right:15px; width:15%">
                            {if ((isset($intermodal) && $intermodal != ""))}
                                <span>{$intermodal}</span>%
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>%
                            {/if}
                            <span>
                        </td>
                        <td>
                            51-200 Intermediate
                            
                            <span class="underline float-right" style="padding-right:15px; width:15%">
                            {if ((isset($Intermediate) && $Intermediate != ""))}
                                <span>{$Intermediate}</span>%

                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>%
                            {/if}
                            <span>
                        </td>
                        <td>Light Truck</td>
                        <td>
                            
                        <span class="underline float-right" style="padding-right:15px; width:15%">
                        {if ((isset($noOfLightTruck) && $noOfLightTruck != ""))}
                                {$noOfLightTruck}
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        <span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Dry van
                            
                            <span class="underline float-right" style="padding-right:15px; width:15%">
                            {if ((isset($dryVan) && $dryVan != ""))}
                                <span>{$dryVan}</span>%

                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>%
                            {/if}
                            <span>
                        </td>
                        <td>
                            201-Over Long Haul
                            
                            <span class="underline float-right" style="padding-right:15px; width:15%">
                            {if ((isset($flatbed) && $flatbed != ""))}
                                <span>{$Local}</span>%
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>%
                            {/if}
                            <span>
                        </td>
                        <td>Medium Truck</td>
                        <td>
                            
                        <span class="underline float-right" style="padding-right:15px; width:15%">
                        {if ((isset($noOfMediumTruck) && $noOfMediumTruck != ""))}
                                {$noOfMediumTruck}
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        <span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Tanker
                            
                            <span class="underline float-right" style="padding-right:15px; width:15%">
                            {if ((isset($tanker) && $tanker != ""))}
                                <span>{$tanker}</span>%

                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>%
                            {/if}
                            <span>
                        </td>
                        <td></td>
                        <td>Heavy Truck</td>
                        <td>
                            
                        <span class="underline float-right" style="padding-right:15px; width:15%">
                        {if ((isset($noOfHeavyTruck) && $noOfHeavyTruck != ""))}
                                {$noOfHeavyTruck}
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        <span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Refrigerated
                            
                            <span class="underline float-right" style="padding-right:15px; width:15%">
                            {if ((isset($refrigerated) && $refrigerated != ""))}
                                <span>{$refrigerated}</span>%

                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>%
                            {/if}
                            <span>
                        </td>
                        <td></td>
                        <td>Extra Heavy</td>
                        <td>
                            
                        <span class="underline float-right" style="padding-right:15px; width:15%">
                        {if ((isset($noOfExtraHeavy) && $noOfExtraHeavy != ""))}
                                {$noOfExtraHeavy}
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        <span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            Dump
                            
                            <span class="underline float-right" style="padding-right:15px; width:15%">
                            {if ((isset($dump) && $dump != ""))}
                                <span>{$dump}</span>%

                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>%
                            {/if}
                            <span>
                        </td>
                        <td></td>
                        <td>Cargo Van / Sprinter</td>
                        <td>
                            
                        <span class="underline float-right" style="padding-right:15px; width:15%">
                        {if ((isset($noOfCargoVan) && $noOfCargoVan != ""))}
                                {$noOfCargoVan}
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        <span>
                        </td>
                    </tr>
                    <tr>
                        <td>Other</td>
                        <td>
                            
                        <span class="underline float-right" style="padding-right:15px; width:15%">
                        {if ((isset($otherContent) && $otherContent != ""))}
                                {$otherContent}
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        <span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Total</strong>
                            <span></span>
                            
                            <span class="underline float-right" style="padding-right:15px; width:15%">
                            {if ((isset($total) && $total != ""))}
                                <span>{$total}</span>%

                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>%
                            {/if}
                            <span>
                        </td>
                        <td></td>
                        <td><strong>Total</strong></td>
                        <td>
                            
                        <span class="underline float-right" style="padding-right:15px; width:15%">
                        {if ((isset($finalTotal) && $finalTotal != ""))}
                                {$finalTotal}
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        <span>
                        </td>
                    </tr>
                </table>
                <strong>Overall Description of Commodities Hauled:</strong><br>
                <table>
                    <tr>
                        <th><strong>Mileage & Revenues</strong></th>
                        <th><strong>Trucking Revenue</strong></th>
                        <th><strong>Brokerage Revenue</strong></th>
                        <th><strong>Total Miles</strong></th>
                        <th><strong># Company Owned Power Units</strong></th>
                        <th><strong># Owner/ Operator Units</strong></th>
                        <th><strong># Subhaulers</strong></th>
                    </tr>
                    <tr>
                        <td>Projection (next 12 mos.)</td>
                        <td class="underline pr-4">
                            {if isset($truckingRevenue1)}
                                $<span>{$truckingRevenue1}</span>
                            {else}
                                <span>$&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if isset($brokerageRevenue1)}
                                $<span>{$brokerageRevenue1}</span>
                            {else}
                                <span>$&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if isset($totalMiles1)}
                                <span>{$totalMiles1}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if isset($companyOwnedPowerUnits1)}
                                <span>{$companyOwnedPowerUnits1}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if isset($ownerOperatorUnits1)}
                                <span>{$ownerOperatorUnits1}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if isset($subhaulers1)}
                                <span>{$subhaulers1}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                    </tr>
                    <tr>
                        <td>Current Policy Year</td>
                        <td class="underline pr-4">
                            {if isset($truckingRevenue2)}
                                $<span>{$truckingRevenue2}</span>
                            {else}
                                <span>$&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if isset($brokerageRevenue2)}
                                $<span>{$brokerageRevenue2}</span>
                            {else}
                                <span>$&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if isset($totalMiles2)}
                                <span>{$totalMiles2}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if isset($companyOwnedPowerUnits2)}
                                <span>{$companyOwnedPowerUnits2}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if isset($ownerOperatorUnits2)}
                                <span>{$ownerOperatorUnits2}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if isset($subhaulers2)}
                                <span>{$subhaulers2}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                    </tr>
                    <tr>
                        <td>1st Prior Year</td>
                        <td class="underline pr-4">
                            {if isset($truckingRevenue3)}
                                $<span>{$truckingRevenue3}</span>
                            {else}
                                <span>$&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if isset($brokerageRevenue3)}
                                $<span>{$brokerageRevenue3}</span>
                            {else}
                                <span>$&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if isset($totalMiles3)}
                                <span>{$totalMiles3}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if isset($companyOwnedPowerUnits3)}
                                <span>{$companyOwnedPowerUnits3}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if isset($ownerOperatorUnits3)}
                                <span>{$ownerOperatorUnits3}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if isset($subhaulers3)}
                                <span>{$subhaulers3}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                    </tr>
                    <tr>
                        <td>2nd Prior Year</td>
                        <td class="underline pr-4">
                            {if isset($truckingRevenue4)}
                                $<span>{$truckingRevenue4}</span>
                            {else}
                                <span>$&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if isset($brokerageRevenue4)}
                                $<span>{$brokerageRevenue4}</span>
                            {else}
                                <span>$&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if isset($totalMiles4)}
                                <span>{$totalMiles4}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if isset($companyOwnedPowerUnits4)}
                                <span>{$companyOwnedPowerUnits4}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if isset($ownerOperatorUnits4)}
                                <span>{$ownerOperatorUnits4}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if isset($subhaulers4)}
                                <span>{$subhaulers4}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                    </tr>
                    <tr>
                        <td>3rd Prior Year</td>
                        <td class="underline pr-4">
                            {if isset($truckingRevenue5)}
                                $<span>{$truckingRevenue5}</span>
                            {else}
                                <span>$&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if isset($brokerageRevenue5)}
                                $<span>{$brokerageRevenue5}</span>
                            {else}
                                <span>$&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if isset($totalMiles5)}
                                <span>{$totalMiles5}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if isset($companyOwnedPowerUnits5)}
                                <span>{$companyOwnedPowerUnits5}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if isset($ownerOperatorUnits5)}
                                <span>{$ownerOperatorUnits5}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if isset($subhaulers5)}
                                <span>{$subhaulers5}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                    </tr>
                    <tr>
                        <td>4th Prior Year</td>
                        <td class="underline pr-4">
                            {if isset($truckingRevenue6)}
                                $<span>{$truckingRevenue6}</span>
                            {else}
                                <span>$&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if isset($brokerageRevenue6)}
                                $<span>{$brokerageRevenue6}</span>
                            {else}
                                <span>$&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if isset($totalMiles6)}
                                <span>{$totalMiles6}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if isset($companyOwnedPowerUnits6)}
                                <span>{$companyOwnedPowerUnits6}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if isset($ownerOperatorUnits6)}
                                <span>{$ownerOperatorUnits6}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if isset($subhaulers6)}
                                <span>{$subhaulers6}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                    </tr>
                </table>
                <p class="continued">SECTION 1: GENERAL INFORMATION, continued</p>
                <strong>General Questions for ALL Operations:</strong>
                <table>
                    <tr>
                        <td>1.</td>
                        <td>Insurance been cancelled or non-renewed in the last 5 years for any reason?</td>
                        {if isset($survey1['insuranceBeenCancelledOrNonRenewedInTheLast5YearsForAnyReason'])}
                            <td>
                                {if $survey1.insuranceBeenCancelledOrNonRenewedInTheLast5YearsForAnyReason == "yes"}
                                    <input id="insuranceBeenCancelledOrNonRenewedInTheLast5YearsForAnyReason" type="checkbox"
                                        name="insuranceBeenCancelledOrNonRenewedInTheLast5YearsForAnyReason" checked readonly />
                                    <label for="insuranceBeenCancelledOrNonRenewedInTheLast5YearsForAnyReason">Y</label>
                                {else}
                                    <input id="insuranceBeenCancelledOrNonRenewedInTheLast5YearsForAnyReason" type="checkbox"
                                        name="insuranceBeenCancelledOrNonRenewedInTheLast5YearsForAnyReason" readonly />
                                    <label for="insuranceBeenCancelledOrNonRenewedInTheLast5YearsForAnyReason">Y</label>
                                {/if}
                            </td>
                            <td>
                                {if $survey1.insuranceBeenCancelledOrNonRenewedInTheLast5YearsForAnyReason == "no"}
                                    <input id="insuranceBeenCancelledOrNonRenewedInTheLast5YearsForAnyReason" type="checkbox"
                                        name="insuranceBeenCancelledOrNonRenewedInTheLast5YearsForAnyReason" checked readonly />
                                    <label for="insuranceBeenCancelledOrNonRenewedInTheLast5YearsForAnyReason">N</label>
                                {else}
                                    <input id="insuranceBeenCancelledOrNonRenewedInTheLast5YearsForAnyReason" type="checkbox"
                                        name="insuranceBeenCancelledOrNonRenewedInTheLast5YearsForAnyReason" readonly />
                                    <label for="insuranceBeenCancelledOrNonRenewedInTheLast5YearsForAnyReason">N</label>
                                {/if}
                            </td>
                        {else}
                            <td>
                                <input id="insuranceBeenCancelledOrNonRenewedInTheLast5YearsForAnyReason" type="checkbox"
                                    name="insuranceBeenCancelledOrNonRenewedInTheLast5YearsForAnyReason" readonly />
                                <label for="insuranceBeenCancelledOrNonRenewedInTheLast5YearsForAnyReason">Y</label>
                                <input id="insuranceBeenCancelledOrNonRenewedInTheLast5YearsForAnyReason" type="checkbox"
                                    name="insuranceBeenCancelledOrNonRenewedInTheLast5YearsForAnyReason" readonly />
                                <label for="insuranceBeenCancelledOrNonRenewedInTheLast5YearsForAnyReason">N</label>
                            </td>
                        {/if}
                    </tr>
                    <tr>
                        <td>2.</td>
                        <td>Involved in the fracking industry?</td>
                        {if isset($survey1['involvedInTheFrackingIndustry'])}
                            <td>
                                {if $survey1.involvedInTheFrackingIndustry == "yes"}
                                    <input id="involvedInTheFrackingIndustry" type="checkbox"
                                        name="involvedInTheFrackingIndustry" checked readonly />
                                    <label for="involvedInTheFrackingIndustry">Y</label>
                                {else}
                                    <input id="involvedInTheFrackingIndustry" type="checkbox"
                                        name="involvedInTheFrackingIndustry" readonly />
                                    <label for="involvedInTheFrackingIndustry">Y</label>
                                {/if}
                            </td>
                            <td>
                                {if $survey1.involvedInTheFrackingIndustry == "no"}
                                    <input id="involvedInTheFrackingIndustry" type="checkbox"
                                        name="involvedInTheFrackingIndustry" checked readonly />
                                    <label for="involvedInTheFrackingIndustry">N</label>
                                {else}
                                    <input id="involvedInTheFrackingIndustry" type="checkbox"
                                        name="involvedInTheFrackingIndustry" readonly />
                                    <label for="involvedInTheFrackingIndustry">N</label>
                                {/if}
                            </td>
                        {else}
                            <td>
                                <input id="involvedInTheFrackingIndustry" type="checkbox"
                                    name="involvedInTheFrackingIndustry" readonly />
                                <label for="involvedInTheFrackingIndustry">Y</label>
                                <input id="involvedInTheFrackingIndustry" type="checkbox"
                                    name="involvedInTheFrackingIndustry" readonly />
                                <label for="involvedInTheFrackingIndustry">N</label>
                            </td>
                        {/if}
                    </tr>
                    <tr>
                        <td>3.</td>
                        <td>Have any interline, intermodal or interchange arrangements?</td>
                        {if isset($survey1['haveAnyInterlineIntermodalOrInterchangeArrangements'])}
                            <td>
                                {if $survey1.haveAnyInterlineIntermodalOrInterchangeArrangements == "yes"}
                                    <input id="haveAnyInterlineIntermodalOrInterchangeArrangements" type="checkbox"
                                        name="haveAnyInterlineIntermodalOrInterchangeArrangements" checked readonly />
                                    <label for="haveAnyInterlineIntermodalOrInterchangeArrangements">Y</label>
                                {else}
                                    <input id="haveAnyInterlineIntermodalOrInterchangeArrangements" type="checkbox"
                                        name="haveAnyInterlineIntermodalOrInterchangeArrangements" readonly />
                                    <label for="haveAnyInterlineIntermodalOrInterchangeArrangements">Y</label>
                                {/if}
                            </td>
                            <td>
                                {if $survey1.haveAnyInterlineIntermodalOrInterchangeArrangements == "no"}
                                    <input id="haveAnyInterlineIntermodalOrInterchangeArrangements" type="checkbox"
                                        name="haveAnyInterlineIntermodalOrInterchangeArrangements" checked readonly />
                                    <label for="haveAnyInterlineIntermodalOrInterchangeArrangements">N</label>
                                {else}
                                    <input id="haveAnyInterlineIntermodalOrInterchangeArrangements" type="checkbox"
                                        name="haveAnyInterlineIntermodalOrInterchangeArrangements" readonly />
                                    <label for="haveAnyInterlineIntermodalOrInterchangeArrangements">N</label>
                                {/if}
                            </td>
                        {else}
                            <td>
                                <input id="haveAnyInterlineIntermodalOrInterchangeArrangements" type="checkbox"
                                    name="haveAnyInterlineIntermodalOrInterchangeArrangements" readonly />
                                <label for="haveAnyInterlineIntermodalOrInterchangeArrangements">Y</label>
                                <input id="haveAnyInterlineIntermodalOrInterchangeArrangements" type="checkbox"
                                    name="haveAnyInterlineIntermodalOrInterchangeArrangements" readonly />
                                <label for="haveAnyInterlineIntermodalOrInterchangeArrangements">N</label>
                            </td>
                        {/if}
                    </tr>
                    <tr>
                        <td>4.</td>
                        <td>Haul any noxious, caustic, toxic, flammable or explosive commodities?</td>
                        {if isset($survey1['haulAnyNoxiousCausticToxicFlammableOrExplosiveCommodities'])}
                            <td>
                                {if $survey1.haulAnyNoxiousCausticToxicFlammableOrExplosiveCommodities == "yes"}
                                    <input id="haulAnyNoxiousCausticToxicFlammableOrExplosiveCommodities" type="checkbox"
                                        name="haulAnyNoxiousCausticToxicFlammableOrExplosiveCommodities" checked readonly />
                                    <label for="haulAnyNoxiousCausticToxicFlammableOrExplosiveCommodities">Y</label>
                                {else}
                                    <input id="haulAnyNoxiousCausticToxicFlammableOrExplosiveCommodities" type="checkbox"
                                        name="haulAnyNoxiousCausticToxicFlammableOrExplosiveCommodities" readonly />
                                    <label for="haulAnyNoxiousCausticToxicFlammableOrExplosiveCommodities">Y</label>
                                {/if}
                            </td>
                            <td>
                                {if $survey1.haulAnyNoxiousCausticToxicFlammableOrExplosiveCommodities == "no"}
                                    <input id="haulAnyNoxiousCausticToxicFlammableOrExplosiveCommodities" type="checkbox"
                                        name="haulAnyNoxiousCausticToxicFlammableOrExplosiveCommodities" checked readonly />
                                    <label for="haulAnyNoxiousCausticToxicFlammableOrExplosiveCommodities">N</label>
                                {else}
                                    <input id="haulAnyNoxiousCausticToxicFlammableOrExplosiveCommodities" type="checkbox"
                                        name="haulAnyNoxiousCausticToxicFlammableOrExplosiveCommodities" readonly />
                                    <label for="haulAnyNoxiousCausticToxicFlammableOrExplosiveCommodities">N</label>
                                {/if}
                            </td>
                        {else}
                            <td>
                                <input id="haulAnyNoxiousCausticToxicFlammableOrExplosiveCommodities" type="checkbox"
                                    name="haulAnyNoxiousCausticToxicFlammableOrExplosiveCommodities" readonly />
                                <label for="haulAnyNoxiousCausticToxicFlammableOrExplosiveCommodities">Y</label>
                                <input id="haulAnyNoxiousCausticToxicFlammableOrExplosiveCommodities" type="checkbox"
                                    name="haulAnyNoxiousCausticToxicFlammableOrExplosiveCommodities" readonly />
                                <label for="haulAnyNoxiousCausticToxicFlammableOrExplosiveCommodities">N</label>
                            </td>
                        {/if}
                    </tr>
                    <tr>
                        <td>5.</td>
                        <td>Operate as a broker or freight forwarder?</td>
                        {if isset($survey1['operateAsABrokerOrFreightForwarder'])}
                            <td>
                                {if $survey1.operateAsABrokerOrFreightForwarder == "yes"}
                                    <input id="operateAsABrokerOrFreightForwarder" type="checkbox"
                                        name="operateAsABrokerOrFreightForwarder" checked readonly />
                                    <label for="operateAsABrokerOrFreightForwarder">Y</label>
                                {else}
                                    <input id="operateAsABrokerOrFreightForwarder" type="checkbox"
                                        name="operateAsABrokerOrFreightForwarder" readonly />
                                    <label for="operateAsABrokerOrFreightForwarder">Y</label>
                                {/if}
                            </td>
                            <td>
                                {if $survey1.operateAsABrokerOrFreightForwarder == "no"}
                                    <input id="operateAsABrokerOrFreightForwarder" type="checkbox"
                                        name="operateAsABrokerOrFreightForwarder" checked readonly />
                                    <label for="operateAsABrokerOrFreightForwarder">N</label>
                                {else}
                                    <input id="operateAsABrokerOrFreightForwarder" type="checkbox"
                                        name="operateAsABrokerOrFreightForwarder" readonly />
                                    <label for="operateAsABrokerOrFreightForwarder">N</label>
                                {/if}
                            </td>
                        {else}
                            <td>
                                <input id="operateAsABrokerOrFreightForwarder" type="checkbox"
                                    name="operateAsABrokerOrFreightForwarder" readonly />
                                <label for="operateAsABrokerOrFreightForwarder">Y</label>
                                <input id="operateAsABrokerOrFreightForwarder" type="checkbox"
                                    name="operateAsABrokerOrFreightForwarder" readonly />
                                <label for="operateAsABrokerOrFreightForwarder">N</label>
                            </td>
                        {/if}
                    </tr>
                    <tr>
                        <td>6.</td>
                        <td>Any other operations under control or authority?</td>
                        {if ((isset($anyOtherOperationsUnderControlOrAuthority) && $anyOtherOperationsUnderControlOrAuthority != ""))}
                            <td>
                                {if $anyOtherOperationsUnderControlOrAuthority == "yes"}
                                    <input id="operateAsABrokerOrFreightForwarder" type="checkbox"
                                        name="operateAsABrokerOrFreightForwarder" checked readonly />
                                    <label for="operateAsABrokerOrFreightForwarder">Y</label>
                                {else}
                                    <input id="operateAsABrokerOrFreightForwarder" type="checkbox"
                                        name="operateAsABrokerOrFreightForwarder" readonly />
                                    <label for="operateAsABrokerOrFreightForwarder">Y</label>
                                {/if}
                            </td>
                            <td>
                                {if $anyOtherOperationsUnderControlOrAuthority == "no"}
                                    <input id="operateAsABrokerOrFreightForwarder" type="checkbox"
                                        name="operateAsABrokerOrFreightForwarder" checked readonly />
                                    <label for="operateAsABrokerOrFreightForwarder">N</label>
                                {else}
                                    <input id="operateAsABrokerOrFreightForwarder" type="checkbox"
                                        name="operateAsABrokerOrFreightForwarder" readonly />
                                    <label for="operateAsABrokerOrFreightForwarder">N</label>
                                {/if}
                            </td>
                        {else}
                            <td>
                                <input id="operateAsABrokerOrFreightForwarder" type="checkbox"
                                    name="operateAsABrokerOrFreightForwarder" readonly />
                                <label for="operateAsABrokerOrFreightForwarder">Y</label>
                                <input id="operateAsABrokerOrFreightForwarder" type="checkbox"
                                    name="operateAsABrokerOrFreightForwarder" readonly />
                                <label for="operateAsABrokerOrFreightForwarder">N</label>
                            </td>
                        {/if}
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            If yes, please provide name and DOT # (if applicable)
                            <span class="float-left underline" style="width: 15%;">
                                {if isset($pleaseProvideName)}
                                    <span>{$pleaseProvideName}</span>
                                {else}
                                    <span>&nbsp;&nbsp;&nbsp;</span>
                                {/if}
                            </span>
                            ,
                            <span class="float-left underline" style="width: 15%;">
                                {if isset($dot)}
                                    <span>{$dot}</span>
                                {else}
                                    <span>&nbsp;&nbsp;&nbsp;</span>
                                {/if}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td>7.</td>
                        <td>Team Drivers?</td>
                        {if isset($survey1['teamDrivers'])}
                            <td>
                                {if $survey1.teamDrivers == "yes"}
                                    <input id="teamDrivers" type="checkbox" name="teamDrivers" checked readonly />
                                    <label for="teamDrivers">Y</label>
                                {else}
                                    <input id="teamDrivers" type="checkbox" name="teamDrivers" readonly />
                                    <label for="teamDrivers">Y</label>
                                {/if}
                            </td>
                            <td>
                                {if $survey1.teamDrivers == "no"}
                                    <input id="teamDrivers" type="checkbox" name="teamDrivers" checked readonly />
                                    <label for="teamDrivers">N</label>
                                {else}
                                    <input id="teamDrivers" type="checkbox" name="teamDrivers" readonly />
                                    <label for="teamDrivers">N</label>
                                {/if}
                            </td>
                        {else}
                            <td>
                                <input id="teamDrivers" type="checkbox" name="teamDrivers" readonly />
                                <label for="teamDrivers">Y</label>
                                <input id="teamDrivers" type="checkbox" name="teamDrivers" readonly />
                                <label for="teamDrivers">N</label>
                            </td>
                        {/if}
                    </tr>
                    <tr>
                        <td>8.</td>
                        <td>Haul Doubles or Triples?</td>
                        {if isset($survey1['haulDoublesOrTriples'])}
                            <td>
                                {if $survey1.haulDoublesOrTriples == "yes"}
                                    <input id="haulDoublesOrTriples" type="checkbox" name="haulDoublesOrTriples" checked
                                        readonly />
                                    <label for="haulDoublesOrTriples">Y</label>
                                {else}
                                    <input id="haulDoublesOrTriples" type="checkbox" name="haulDoublesOrTriples" readonly />
                                    <label for="haulDoublesOrTriples">Y</label>
                                {/if}
                            </td>
                            <td>
                                {if $survey1.haulDoublesOrTriples == "no"}
                                    <input id="haulDoublesOrTriples" type="checkbox" name="haulDoublesOrTriples" checked
                                        readonly />
                                    <label for="haulDoublesOrTriples">N</label>
                                {else}
                                    <input id="haulDoublesOrTriples" type="checkbox" name="haulDoublesOrTriples" readonly />
                                    <label for="haulDoublesOrTriples">N</label>
                                {/if}
                            </td>
                        {else}
                            <td>
                                <input id="haulDoublesOrTriples" type="checkbox" name="haulDoublesOrTriples" readonly />
                                <label for="haulDoublesOrTriples">Y</label>
                                <input id="haulDoublesOrTriples" type="checkbox" name="haulDoublesOrTriples" readonly />
                                <label for="haulDoublesOrTriples">N</label>
                            </td>
                        {/if}
                    </tr>
                    <tr>
                        <td>9.</td>
                        <td>Do you loan, lease or rent vehicles to others with or without drivers?</td>
                        {if isset($survey1['doYouLoanLeaseOrRentVehiclesToOthersWithOrWithoutDrivers'])}
                            <td>
                                {if $survey1.doYouLoanLeaseOrRentVehiclesToOthersWithOrWithoutDrivers == "yes"}
                                    <input id="doYouLoanLeaseOrRentVehiclesToOthersWithOrWithoutDrivers" type="checkbox"
                                        name="doYouLoanLeaseOrRentVehiclesToOthersWithOrWithoutDrivers" checked readonly />
                                    <label for="doYouLoanLeaseOrRentVehiclesToOthersWithOrWithoutDrivers">Y</label>
                                {else}
                                    <input id="doYouLoanLeaseOrRentVehiclesToOthersWithOrWithoutDrivers" type="checkbox"
                                        name="doYouLoanLeaseOrRentVehiclesToOthersWithOrWithoutDrivers" readonly />
                                    <label for="doYouLoanLeaseOrRentVehiclesToOthersWithOrWithoutDrivers">Y</label>
                                {/if}
                            </td>
                            <td>
                                {if $survey1.doYouLoanLeaseOrRentVehiclesToOthersWithOrWithoutDrivers == "no"}
                                    <input id="doYouLoanLeaseOrRentVehiclesToOthersWithOrWithoutDrivers" type="checkbox"
                                        name="doYouLoanLeaseOrRentVehiclesToOthersWithOrWithoutDrivers" checked readonly />
                                    <label for="doYouLoanLeaseOrRentVehiclesToOthersWithOrWithoutDrivers">N</label>
                                {else}
                                    <input id="doYouLoanLeaseOrRentVehiclesToOthersWithOrWithoutDrivers" type="checkbox"
                                        name="doYouLoanLeaseOrRentVehiclesToOthersWithOrWithoutDrivers" readonly />
                                    <label for="doYouLoanLeaseOrRentVehiclesToOthersWithOrWithoutDrivers">N</label>
                                {/if}
                            </td>
                        {else}
                            <td>
                                <input id="doYouLoanLeaseOrRentVehiclesToOthersWithOrWithoutDrivers" type="checkbox"
                                    name="doYouLoanLeaseOrRentVehiclesToOthersWithOrWithoutDrivers" readonly />
                                <label for="doYouLoanLeaseOrRentVehiclesToOthersWithOrWithoutDrivers">Y</label>
                                <input id="doYouLoanLeaseOrRentVehiclesToOthersWithOrWithoutDrivers" type="checkbox"
                                    name="doYouLoanLeaseOrRentVehiclesToOthersWithOrWithoutDrivers" readonly />
                                <label for="doYouLoanLeaseOrRentVehiclesToOthersWithOrWithoutDrivers">N</label>
                            </td>
                        {/if}
                    </tr>
                    <tr>
                        <td>10.</td>
                        <td>Brokerage authority? (if yes, answer a., b., and c.)</td>
                        {if ((isset($brokerageAuthority) && $brokerageAuthority != ""))}
                            <td>
                                {if $brokerageAuthority == "yes"}
                                    <input id="brokerageAuthority" type="checkbox" name="brokerageAuthority" checked readonly />
                                    <label for="brokerageAuthority">Y</label>
                                {else}
                                    <input id="brokerageAuthority" type="checkbox" name="brokerageAuthority" readonly />
                                    <label for="brokerageAuthority">Y</label>
                                {/if}
                            </td>
                            <td>
                                {if $brokerageAuthority == "no"}
                                    <input id="brokerageAuthority" type="checkbox" name="brokerageAuthority" checked readonly />
                                    <label for="brokerageAuthority">N</label>
                                {else}
                                    <input id="brokerageAuthority" type="checkbox" name="brokerageAuthority" readonly />
                                    <label for="brokerageAuthority">N</label>
                                {/if}
                            </td>
                        {else}
                            <td>
                                <input id="brokerageAuthority" type="checkbox" name="brokerageAuthority" readonly />
                                <label for="brokerageAuthority">Y</label>
                                <input id="brokerageAuthority" type="checkbox" name="brokerageAuthority" readonly />
                                <label for="brokerageAuthority">N</label>
                            </td>
                        {/if}
                    </tr>
                    <tr>
                        <td></td>
                        <td>a. Under same name, if yes, % of operations?
                            {if isset($ofOperations)}
                                <span class="underline">{$ofOperations}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            </td>
                        {/if}

                    </tr>
                    <tr>
                        <td></td>
                        <td>b. Are brokerage operations conducted on a contract basis?</td>
                        {if ((isset($areBrokerageOperationsConductedOnAContractBasis) && $areBrokerageOperationsConductedOnAContractBasis != ""))}
                            {if $areBrokerageOperationsConductedOnAContractBasis == "yes"}
                                <td>
                                    <input id="areBrokerageOperationsConductedOnAContractBasis" type="checkbox"
                                        name="areBrokerageOperationsConductedOnAContractBasis" checked readonly />
                                    <label for="areBrokerageOperationsConductedOnAContractBasis">Y</label>
                                </td>
                                <td>
                                    <input id="areBrokerageOperationsConductedOnAContractBasis" type="checkbox"
                                        name="areBrokerageOperationsConductedOnAContractBasis" readonly />
                                    <label for="areBrokerageOperationsConductedOnAContractBasis">N</label>
                                </td>
                            {else $areBrokerageOperationsConductedOnAContractBasis == "no"}
                                <td>
                                    <input id="areBrokerageOperationsConductedOnAContractBasis" type="checkbox"
                                        name="areBrokerageOperationsConductedOnAContractBasis" readonly />
                                    <label for="areBrokerageOperationsConductedOnAContractBasis">Y</label>
                                </td>
                                <td>
                                    <input id="areBrokerageOperationsConductedOnAContractBasis" type="checkbox"
                                        name="areBrokerageOperationsConductedOnAContractBasis" checked readonly />
                                    <label for="areBrokerageOperationsConductedOnAContractBasis">N</label>
                                </td>
                            {/if}
                        {else}
                            <td>
                                <input id="areBrokerageOperationsConductedOnAContractBasis" type="checkbox"
                                    name="areBrokerageOperationsConductedOnAContractBasis" readonly />
                                <label for="areBrokerageOperationsConductedOnAContractBasis">Y</label>
                            </td>
                            <td>
                                <input id="areBrokerageOperationsConductedOnAContractBasis" type="checkbox"
                                    name="areBrokerageOperationsConductedOnAContractBasis" readonly />
                                <label for="areBrokerageOperationsConductedOnAContractBasis">N</label>
                            </td>
                        {/if}
                    </tr>
                    <tr>
                        <td></td>
                        <td>c. Is Insurance verified with these contracts?</td>
                        {if ((isset($isInsuranceVerifiedWithTheseContracts) && $isInsuranceVerifiedWithTheseContracts != ""))}
                            {if $isInsuranceVerifiedWithTheseContracts == "yes"}
                                <td>
                                    <input id="isInsuranceVerifiedWithTheseContracts" type="checkbox"
                                        name="isInsuranceVerifiedWithTheseContracts" checked readonly />
                                    <label for="isInsuranceVerifiedWithTheseContracts">Y</label>
                                </td>
                                <td>
                                    <input id="isInsuranceVerifiedWithTheseContracts" type="checkbox"
                                        name="isInsuranceVerifiedWithTheseContracts" readonly />
                                    <label for="isInsuranceVerifiedWithTheseContracts">N</label>
                                </td>
                            {else $isInsuranceVerifiedWithTheseContracts == "no"}
                                <td>
                                    <input id="isInsuranceVerifiedWithTheseContracts" type="checkbox"
                                        name="isInsuranceVerifiedWithTheseContracts" readonly />
                                    <label for="isInsuranceVerifiedWithTheseContracts">Y</label>
                                </td>
                                <td>
                                    <input id="isInsuranceVerifiedWithTheseContracts" type="checkbox"
                                        name="isInsuranceVerifiedWithTheseContracts" checked readonly />
                                    <label for="isInsuranceVerifiedWithTheseContracts">N</label>
                                </td>
                            {/if}
                        {else}
                            <td>
                                <input id="isInsuranceVerifiedWithTheseContracts" type="checkbox"
                                    name="isInsuranceVerifiedWithTheseContracts" readonly />
                                <label for="isInsuranceVerifiedWithTheseContracts">Y</label>
                            </td>
                            <td>
                                <input id="isInsuranceVerifiedWithTheseContracts" type="checkbox"
                                    name="isInsuranceVerifiedWithTheseContracts" readonly />
                                <label for="isInsuranceVerifiedWithTheseContracts">N</label>
                            </td>
                        {/if}
                    </tr>
                    <tr>
                        <td>11.</td>
                        <td>Passengers Allowed?</td>
                        {if ((isset($isInsuranceVerifiedWithTheseContracts1) && $isInsuranceVerifiedWithTheseContracts1 != ""))}
                            {if $isInsuranceVerifiedWithTheseContracts1 == "yes"}
                                <td>
                                    <input id="isInsuranceVerifiedWithTheseContracts1" type="checkbox"
                                        name="isInsuranceVerifiedWithTheseContracts1" checked readonly />
                                    <label for="isInsuranceVerifiedWithTheseContracts1">Y</label>
                                </td>
                                <td>
                                    <input id="isInsuranceVerifiedWithTheseContracts1" type="checkbox"
                                        name="isInsuranceVerifiedWithTheseContracts1" readonly />
                                    <label for="isInsuranceVerifiedWithTheseContracts1">N</label>
                                </td>
                            {else $isInsuranceVerifiedWithTheseContracts1 == "no"}
                                <td>
                                    <input id="isInsuranceVerifiedWithTheseContracts1" type="checkbox"
                                        name="isInsuranceVerifiedWithTheseContracts1" readonly />
                                    <label for="isInsuranceVerifiedWithTheseContracts1">Y</label>
                                </td>
                                <td>
                                    <input id="isInsuranceVerifiedWithTheseContracts1" type="checkbox"
                                        name="isInsuranceVerifiedWithTheseContracts1" checked readonly />
                                    <label for="isInsuranceVerifiedWithTheseContracts1">N</label>
                                </td>
                            {/if}
                        {else}
                            <td>
                                <input id="isInsuranceVerifiedWithTheseContracts1" type="checkbox"
                                    name="isInsuranceVerifiedWithTheseContracts1" readonly />
                                <label for="isInsuranceVerifiedWithTheseContracts1">Y</label>
                            </td>
                            <td>
                                <input id="isInsuranceVerifiedWithTheseContracts1" type="checkbox"
                                    name="isInsuranceVerifiedWithTheseContracts1" readonly />
                                <label for="isInsuranceVerifiedWithTheseContracts1">N</label>
                            </td>
                        {/if}
                    </tr>
                </table>
            </div>

        </section>
        {* Section 1 *}

        {* Section 2 *}
        <section class="mb-4">
            <div class="mb-4">
                <h3 class="bold"><span class="section_black mr-4">SECTION 2</span> DRIVER INFORMATION </h3>
            </div>
            <div id="sectionContent">
                <table width="100%" id="driverInfo">
                    <tr>
                        <td width="30%">
                            <table width="100%">
                                <tr class="underline">
                                    <th><strong>Driver Types</strong></th>
                                    <th><strong>How Many #</strong></th>
                                </tr>
                                <tr>
                                    <td>Employees</td>
                                    <td class="underline pr-4">
                                        {if isset($employees1)}
                                            {$employees1}
                                        {else}
                                            <span>&nbsp;&nbsp;&nbsp;</span>
                                        {/if}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Owner Operators</td>
                                    <td class="underline pr-4">
                                        {if isset($ownerOperators1)}
                                            {$ownerOperators1}
                                        {else}
                                            <span>&nbsp;&nbsp;&nbsp;</span>
                                        {/if}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Subhaulers</td>
                                    <td class="underline pr-4">
                                        {if isset($subhaulers8)}
                                            {$subhaulers8}
                                        {else}
                                            <span>&nbsp;&nbsp;&nbsp;</span>
                                        {/if}
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Total</strong></td>
                                    <td class="underline pr-4">
                                        {if isset($total3)}
                                            {$total3}
                                        {else}
                                            <span>&nbsp;&nbsp;&nbsp;</span>
                                        {/if}
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td width="35%">
                            <table>
                                <tr class="underline">
                                    <th style="overflow: hidden; white-space: nowrap;"><strong>In the past year, how
                                            many drivers:</strong></th>
                                </tr>
                                <tr style="margin-right:15px">
                                    <td>Hired</td>
                                    <td class="underline pr-4">
                                        {if isset($hired1)}
                                            {$hired1}
                                        {else}
                                            <span>&nbsp;&nbsp;&nbsp;</span>
                                        {/if}
                                    </td>
                                </tr>
                                <tr style="margin-right:15px">
                                    <td>Terminated</td>
                                    <td class="underline pr-4">
                                        {if isset($terminated1)}
                                            {$terminated1}
                                        {else}
                                            <span>&nbsp;&nbsp;&nbsp;</span>
                                        {/if}
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td width="35%">
                            <table>
                                <tr class="underline">
                                    <th style="overflow: hidden; white-space: nowrap;"><strong>What amount of experience
                                            is required:</strong></th>
                                </tr>
                                <tr>
                                    <td>Miles driven</td>
                                    <td class="underline pr-4">
                                        {if isset($milesDriven1)}
                                            {$milesDriven1}
                                        {else}
                                            <span>&nbsp;&nbsp;&nbsp;</span>
                                        {/if}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Years of driving</td>
                                    <td class="underline pr-4">
                                        {if isset($yearsOfDriving1)}
                                            {$yearsOfDriving1}
                                        {else}
                                            <span>&nbsp;&nbsp;&nbsp;</span>
                                        {/if}
                                    </td>
                                </tr>
                                <tr>
                                    <td>Minimum Age</td>
                                    <td class="underline pr-4">
                                        {if isset($minimumAge1)}
                                            {$minimumAge1}
                                        {else}
                                            <span>&nbsp;&nbsp;&nbsp;</span>
                                        {/if}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
                <br>
                <strong>Driver selection procedures include</strong>
                <table>
                    <tr class="underline">
                        <th>the use of:</th>
                        <th>Wages base on:</th>
                    </tr>
                    <tr>
                        {if $writtenApplication == true || $writtenApplication == "true"}
                            <td>
                                <input id="writtenApplication" type="checkbox" name="writtenApplication" checked readonly />
                                <label for="writtenApplication">Written Application</label>
                            </td>
                        {else}
                            <td>
                                <input id="writtenApplication" type="checkbox" name="writtenApplication" readonly />
                                <label for="writtenApplication">Written Application</label>
                            </td>
                        {/if}
                        {if $hours == true || $hours == "true"}
                            <td>
                                <input id="hours" type="checkbox" name="hours" checked readonly />
                                <label for="hours">Hours</label>
                            </td>
                        {else}
                            <td>
                                <input id="hours" type="checkbox" name="hours" readonly />
                                <label for="hours">Hours</label>
                            </td>
                        {/if}
                    </tr>
                    <tr>
                        {if $drivingTests == true || $drivingTests == "true"}
                            <td>
                                <input id="drivingTests" type="checkbox" name="drivingTests" checked readonly />
                                <label for="drivingTests">Driving Tests</label>
                            </td>
                        {else}
                            <td>
                                <input id="drivingTests" type="checkbox" name="drivingTests" readonly />
                                <label for="drivingTests">Driving Tests</label>
                            </td>
                        {/if}
                        {if $miles == true || $miles == "true"}
                            <td>
                                <input id="miles" type="checkbox" name="miles" checked readonly />
                                <label for="miles">Miles</label>
                            </td>
                        {else}
                            <td>
                                <input id="miles" type="checkbox" name="miles" readonly />
                                <label for="miles">Miles</label>
                            </td>
                        {/if}
                    </tr>
                    <tr>
                        {if $interview == true || $interview == "true"}
                            <td>
                                <input id="interview" type="checkbox" name="interview" checked readonly />
                                <label for="interview">Interview</label>
                            </td>
                        {else}
                            <td>
                                <input id="interview" type="checkbox" name="interview" readonly />
                                <label for="interview">Interview</label>
                            </td>
                        {/if}
                        {if $revenue == true || $revenue == "true"}
                            <td>
                                <input id="revenue" type="checkbox" name="revenue" checked readonly />
                                <label for="revenue">Revenue</label>
                            </td>
                        {else}
                            <td>
                                <input id="revenue" type="checkbox" name="revenue" readonly />
                                <label for="revenue">Revenue</label>
                            </td>
                        {/if}
                    </tr>
                    <tr>
                        {if $drugTest == true || $drugTest == "true"}
                            <td>
                                <input id="drugTest" type="checkbox" name="drugTest" checked readonly />
                                <label for="drugTest">Drug Test</label>
                            </td>
                        {else}
                            <td>
                                <input id="drugTest" type="checkbox" name="drugTest" readonly />
                                <label for="drugTest">Drug Test</label>
                            </td>
                        {/if}
                        {if $trips == true || $trips == "true"}
                            <td>
                                <input id="trips" type="checkbox" name="trips" checked readonly />
                                <label for="trips">Trips</label>
                            </td>
                        {else}
                            <td>
                                <input id="trips" type="checkbox" name="trips" readonly />
                                <label for="trips">Trips</label>
                            </td>
                        {/if}
                    </tr>
                    <tr>
                        {if $writtenTest == true || $writtenTest == "true"}
                            <td>
                                <input id="writtenTest" type="checkbox" name="writtenTest" checked readonly />
                                <label for="writtenTest">Written Test</label>
                            </td>
                        {else}
                            <td>
                                <input id="writtenTest" type="checkbox" name="writtenTest" readonly />
                                <label for="writtenTest">Written Test</label>
                            </td>
                        {/if}
                        <td>
                            <strong>Average annual driver pay: $</strong>
                            <span class="underline">
                                {if isset($averageAnnualDriverPay)}
                                    <span>{$averageAnnualDriverPay}</span>
                                {else}
                                    <span>&nbsp;&nbsp;&nbsp;</span>
                                {/if}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        {if $preHirePhysical == true || $preHirePhysical == "true"}
                            <td>
                                <input id="preHirePhysical" type="checkbox" name="preHirePhysical" checked readonly />
                                <label for="preHirePhysical">Pre-Hire Physical</label>
                            </td>
                        {else}
                            <td>
                                <input id="preHirePhysical" type="checkbox" name="preHirePhysical" readonly />
                                <label for="preHirePhysical">Pre-Hire Physical</label>
                            </td>
                        {/if}
                        <td></td>
                    </tr>
                    <tr>
                        {if $referenceCheck == true || $referenceCheck == "true"}
                            <td>
                                <input id="referenceCheck" type="checkbox" name="referenceCheck" checked readonly />
                                <label for="referenceCheck">Reference Check</label>
                            </td>
                        {else}
                            <td>
                                <input id="referenceCheck" type="checkbox" name="referenceCheck" readonly />
                                <label for="referenceCheck">Reference Check</label>
                            </td>
                        {/if}
                        <td>
                            <strong>How often are drivers home?</strong>
                            <span class="underline">
                                {if ((isset($howOftenAreDriversHome) && $howOftenAreDriversHome != ""))}
                                    <span>{$howOftenAreDriversHome}</span>
                                {else}
                                    <span>&nbsp;&nbsp;&nbsp;</span>
                                {/if}
                            </span>
                        </td>
                    </tr>
                </table>
                <p class="continued">SECTION 2: DRIVER INFORMATION, continued</p>
                <strong>If Owner/Operators are used:</strong><br>
                <table>
                    <tr>
                        <td>1.</td>
                        <td>Are permanent/exclusive lease agreements used?</td>
                        {if isset($survey2['arePermanentExclusiveLeaseAgreementsUsed'])}
                            <td>
                                {if $survey2.arePermanentExclusiveLeaseAgreementsUsed == "yes"}
                                    <input id="arePermanentExclusiveLeaseAgreementsUsed" type="checkbox"
                                        name="arePermanentExclusiveLeaseAgreementsUsed" checked readonly />
                                    <label for="arePermanentExclusiveLeaseAgreementsUsed">Y</label>
                                {else}
                                    <input id="arePermanentExclusiveLeaseAgreementsUsed" type="checkbox"
                                        name="arePermanentExclusiveLeaseAgreementsUsed" readonly />
                                    <label for="arePermanentExclusiveLeaseAgreementsUsed">Y</label>
                                {/if}
                            </td>
                            <td>
                                {if $survey2.arePermanentExclusiveLeaseAgreementsUsed == "no"}
                                    <input id="arePermanentExclusiveLeaseAgreementsUsed" type="checkbox"
                                        name="arePermanentExclusiveLeaseAgreementsUsed" checked readonly />
                                    <label for="arePermanentExclusiveLeaseAgreementsUsed">N</label>
                                {else}
                                    <input id="arePermanentExclusiveLeaseAgreementsUsed" type="checkbox"
                                        name="arePermanentExclusiveLeaseAgreementsUsed" readonly />
                                    <label for="arePermanentExclusiveLeaseAgreementsUsed">N</label>
                                {/if}
                            </td>
                        {else}
                            <td>
                                <input id="arePermanentExclusiveLeaseAgreementsUsed" type="checkbox"
                                    name="arePermanentExclusiveLeaseAgreementsUsed" readonly />
                                <label for="arePermanentExclusiveLeaseAgreementsUsed">Y</label>
                                <input id="arePermanentExclusiveLeaseAgreementsUsed" type="checkbox"
                                    name="arePermanentExclusiveLeaseAgreementsUsed" readonly />
                                <label for="arePermanentExclusiveLeaseAgreementsUsed">N</label>
                            </td>
                        {/if}
                    </tr>
                    <tr>
                        <td>2.</td>
                        <td>Are drivers subject to the same driver training as company drivers?</td>
                        {if isset($survey2['areOwnerOperatorDriversSubjectToTheSameDriverTrainingAsCompanyDrivers'])}
                            <td>
                                {if $survey2.areOwnerOperatorDriversSubjectToTheSameDriverTrainingAsCompanyDrivers == "yes"}
                                    <input id="areOwnerOperatorDriversSubjectToTheSameDriverTrainingAsCompanyDrivers"
                                        type="checkbox"
                                        name="areOwnerOperatorDriversSubjectToTheSameDriverTrainingAsCompanyDrivers" checked
                                        readonly />
                                    <label for="areOwnerOperatorDriversSubjectToTheSameDriverTrainingAsCompanyDrivers">Y</label>
                                {else}
                                    <input id="areOwnerOperatorDriversSubjectToTheSameDriverTrainingAsCompanyDrivers"
                                        type="checkbox"
                                        name="areOwnerOperatorDriversSubjectToTheSameDriverTrainingAsCompanyDrivers" readonly />
                                    <label for="areOwnerOperatorDriversSubjectToTheSameDriverTrainingAsCompanyDrivers">Y</label>
                                {/if}
                            </td>
                            <td>
                                {if $survey2.areOwnerOperatorDriversSubjectToTheSameDriverTrainingAsCompanyDrivers == "no"}
                                    <input id="areOwnerOperatorDriversSubjectToTheSameDriverTrainingAsCompanyDrivers"
                                        type="checkbox"
                                        name="areOwnerOperatorDriversSubjectToTheSameDriverTrainingAsCompanyDrivers" checked
                                        readonly />
                                    <label for="areOwnerOperatorDriversSubjectToTheSameDriverTrainingAsCompanyDrivers">N</label>
                                {else}
                                    <input id="areOwnerOperatorDriversSubjectToTheSameDriverTrainingAsCompanyDrivers"
                                        type="checkbox"
                                        name="areOwnerOperatorDriversSubjectToTheSameDriverTrainingAsCompanyDrivers" readonly />
                                    <label for="areOwnerOperatorDriversSubjectToTheSameDriverTrainingAsCompanyDrivers">N</label>
                                {/if}
                            </td>
                        {else}
                            <td>
                                <input id="areOwnerOperatorDriversSubjectToTheSameDriverTrainingAsCompanyDrivers"
                                    type="checkbox"
                                    name="areOwnerOperatorDriversSubjectToTheSameDriverTrainingAsCompanyDrivers" readonly />
                                <label for="areOwnerOperatorDriversSubjectToTheSameDriverTrainingAsCompanyDrivers">Y</label>
                                <input id="areOwnerOperatorDriversSubjectToTheSameDriverTrainingAsCompanyDrivers"
                                    type="checkbox"
                                    name="areOwnerOperatorDriversSubjectToTheSameDriverTrainingAsCompanyDrivers" readonly />
                                <label for="areOwnerOperatorDriversSubjectToTheSameDriverTrainingAsCompanyDrivers">N</label>
                            </td>
                        {/if}
                    </tr>
                    <tr>
                        <td>3.</td>
                        <td>Are trip lease agreements used?</td>
                        {if isset($survey2['areTripLeaseAgreementsUsed'])}
                            <td>
                                {if $survey2.areTripLeaseAgreementsUsed == "yes"}
                                    <input id="areTripLeaseAgreementsUsed" type="checkbox" name="areTripLeaseAgreementsUsed"
                                        checked readonly />
                                    <label for="areTripLeaseAgreementsUsed">Y</label>
                                {else}
                                    <input id="areTripLeaseAgreementsUsed" type="checkbox" name="areTripLeaseAgreementsUsed"
                                        readonly />
                                    <label for="areTripLeaseAgreementsUsed">Y</label>
                                {/if}
                            </td>
                            <td>
                                {if $survey2.areTripLeaseAgreementsUsed == "no"}
                                    <input id="areTripLeaseAgreementsUsed" type="checkbox" name="areTripLeaseAgreementsUsed"
                                        checked readonly />
                                    <label for="areTripLeaseAgreementsUsed">N</label>
                                {else}
                                    <input id="areTripLeaseAgreementsUsed" type="checkbox" name="areTripLeaseAgreementsUsed"
                                        readonly />
                                    <label for="areTripLeaseAgreementsUsed">N</label>
                                {/if}
                            </td>
                        {else}
                            <td>
                                <input id="areTripLeaseAgreementsUsed" type="checkbox" name="areTripLeaseAgreementsUsed"
                                    readonly />
                                <label for="areTripLeaseAgreementsUsed">Y</label>
                                <input id="areTripLeaseAgreementsUsed" type="checkbox" name="areTripLeaseAgreementsUsed"
                                    readonly />
                                <label for="areTripLeaseAgreementsUsed">N</label>
                            </td>
                        {/if}
                    </tr>
                    <tr>
                        <td>4.</td>
                        <td>Are driver files maintained by the insured?</td>
                        {if isset($survey2['areOwnerOperatorDriverFilesMaintainedByTheInsured'])}
                            <td>
                                {if $survey2.areOwnerOperatorDriverFilesMaintainedByTheInsured == "yes"}
                                    <input id="areOwnerOperatorDriverFilesMaintainedByTheInsured" type="checkbox"
                                        name="areOwnerOperatorDriverFilesMaintainedByTheInsured" checked readonly />
                                    <label for="areOwnerOperatorDriverFilesMaintainedByTheInsured">Y</label>
                                {else}
                                    <input id="areOwnerOperatorDriverFilesMaintainedByTheInsured" type="checkbox"
                                        name="areOwnerOperatorDriverFilesMaintainedByTheInsured" readonly />
                                    <label for="areOwnerOperatorDriverFilesMaintainedByTheInsured">Y</label>
                                {/if}
                            </td>
                            <td>
                                {if $survey2.areOwnerOperatorDriverFilesMaintainedByTheInsured == "no"}
                                    <input id="areOwnerOperatorDriverFilesMaintainedByTheInsured" type="checkbox"
                                        name="areOwnerOperatorDriverFilesMaintainedByTheInsured" checked readonly />
                                    <label for="areOwnerOperatorDriverFilesMaintainedByTheInsured">N</label>
                                {else}
                                    <input id="areOwnerOperatorDriverFilesMaintainedByTheInsured" type="checkbox"
                                        name="areOwnerOperatorDriverFilesMaintainedByTheInsured" readonly />
                                    <label for="areOwnerOperatorDriverFilesMaintainedByTheInsured">N</label>
                                {/if}
                            </td>
                        {else}
                            <td>
                                <input id="areOwnerOperatorDriverFilesMaintainedByTheInsured" type="checkbox"
                                    name="areOwnerOperatorDriverFilesMaintainedByTheInsured" readonly />
                                <label for="areOwnerOperatorDriverFilesMaintainedByTheInsured">Y</label>
                                <input id="areOwnerOperatorDriverFilesMaintainedByTheInsured" type="checkbox"
                                    name="areOwnerOperatorDriverFilesMaintainedByTheInsured" readonly />
                                <label for="areOwnerOperatorDriverFilesMaintainedByTheInsured">N</label>
                            </td>
                        {/if}
                    </tr>
                    <tr>
                        <td>5.</td>
                        <td>Is equipment inspected by the insured?</td>
                        {if isset($survey2['isEquipmentInspectedByTheInsured'])}
                            <td>
                                {if $survey2.isEquipmentInspectedByTheInsured == "yes"}
                                    <input id="isEquipmentInspectedByTheInsured" type="checkbox"
                                        name="isEquipmentInspectedByTheInsured" checked readonly />
                                    <label for="isEquipmentInspectedByTheInsured">Y</label>
                                {else}
                                    <input id="isEquipmentInspectedByTheInsured" type="checkbox"
                                        name="isEquipmentInspectedByTheInsured" readonly />
                                    <label for="isEquipmentInspectedByTheInsured">Y</label>
                                {/if}
                            </td>
                            <td>
                                {if $survey2.isEquipmentInspectedByTheInsured == "no"}
                                    <input id="isEquipmentInspectedByTheInsured" type="checkbox"
                                        name="isEquipmentInspectedByTheInsured" checked readonly />
                                    <label for="isEquipmentInspectedByTheInsured">N</label>
                                {else}
                                    <input id="isEquipmentInspectedByTheInsured" type="checkbox"
                                        name="isEquipmentInspectedByTheInsured" readonly />
                                    <label for="isEquipmentInspectedByTheInsured">N</label>
                                {/if}
                            </td>
                        {else}
                            <td>
                                <input id="isEquipmentInspectedByTheInsured" type="checkbox"
                                    name="isEquipmentInspectedByTheInsured" readonly />
                                <label for="isEquipmentInspectedByTheInsured">Y</label>
                                <input id="isEquipmentInspectedByTheInsured" type="checkbox"
                                    name="isEquipmentInspectedByTheInsured" readonly />
                                <label for="isEquipmentInspectedByTheInsured">N</label>
                            </td>
                        {/if}
                    </tr>
                    <tr>
                        <td>6.</td>
                        <td>Are drivers subject to the same maintenance program as the owned equipment?</td>
                        {if isset($survey2['areDriversSubjectToTheSameMaintenanceProgramAsTheOwnedEquipment'])}
                            <td>
                                {if $survey2.areDriversSubjectToTheSameMaintenanceProgramAsTheOwnedEquipment == "yes"}
                                    <input id="areDriversSubjectToTheSameMaintenanceProgramAsTheOwnedEquipment" type="checkbox"
                                        name="areDriversSubjectToTheSameMaintenanceProgramAsTheOwnedEquipment" checked
                                        readonly />
                                    <label for="areDriversSubjectToTheSameMaintenanceProgramAsTheOwnedEquipment">Y</label>
                                {else}
                                    <input id="areDriversSubjectToTheSameMaintenanceProgramAsTheOwnedEquipment" type="checkbox"
                                        name="areDriversSubjectToTheSameMaintenanceProgramAsTheOwnedEquipment" readonly />
                                    <label for="areDriversSubjectToTheSameMaintenanceProgramAsTheOwnedEquipment">Y</label>
                                {/if}
                            </td>
                            <td>
                                {if $survey2.areDriversSubjectToTheSameMaintenanceProgramAsTheOwnedEquipment == "no"}
                                    <input id="areDriversSubjectToTheSameMaintenanceProgramAsTheOwnedEquipment" type="checkbox"
                                        name="areDriversSubjectToTheSameMaintenanceProgramAsTheOwnedEquipment" checked
                                        readonly />
                                    <label for="areDriversSubjectToTheSameMaintenanceProgramAsTheOwnedEquipment">N</label>
                                {else}
                                    <input id="areDriversSubjectToTheSameMaintenanceProgramAsTheOwnedEquipment" type="checkbox"
                                        name="areDriversSubjectToTheSameMaintenanceProgramAsTheOwnedEquipment" readonly />
                                    <label for="areDriversSubjectToTheSameMaintenanceProgramAsTheOwnedEquipment">N</label>
                                {/if}
                            </td>
                        {else}
                            <td>
                                <input id="areDriversSubjectToTheSameMaintenanceProgramAsTheOwnedEquipment" type="checkbox"
                                    name="areDriversSubjectToTheSameMaintenanceProgramAsTheOwnedEquipment" readonly />
                                <label for="areDriversSubjectToTheSameMaintenanceProgramAsTheOwnedEquipment">Y</label>
                                <input id="areDriversSubjectToTheSameMaintenanceProgramAsTheOwnedEquipment" type="checkbox"
                                    name="areDriversSubjectToTheSameMaintenanceProgramAsTheOwnedEquipment" readonly />
                                <label for="areDriversSubjectToTheSameMaintenanceProgramAsTheOwnedEquipment">N</label>
                            </td>
                        {/if}
                    </tr>
                    <tr>
                        <td>7.</td>
                        <td>Are all owner/operators required to carry at least $500,000 non-trucking liability?</td>
                        {if isset($survey2['areAllOwnerOperatorsRequiredToCarryAtLeast500000NonTruckingLiability'])}
                            <td>
                                {if $survey2.areAllOwnerOperatorsRequiredToCarryAtLeast500000NonTruckingLiability == "yes"}
                                    <input id="areAllOwnerOperatorsRequiredToCarryAtLeast500000NonTruckingLiability"
                                        type="checkbox"
                                        name="areAllOwnerOperatorsRequiredToCarryAtLeast500000NonTruckingLiability" checked
                                        readonly />
                                    <label for="areAllOwnerOperatorsRequiredToCarryAtLeast500000NonTruckingLiability">Y</label>
                                {else}
                                    <input id="areAllOwnerOperatorsRequiredToCarryAtLeast500000NonTruckingLiability"
                                        type="checkbox"
                                        name="areAllOwnerOperatorsRequiredToCarryAtLeast500000NonTruckingLiability" readonly />
                                    <label for="areAllOwnerOperatorsRequiredToCarryAtLeast500000NonTruckingLiability">Y</label>
                                {/if}
                            </td>
                            <td>
                                {if $survey2.areAllOwnerOperatorsRequiredToCarryAtLeast500000NonTruckingLiability == "no"}
                                    <input id="areAllOwnerOperatorsRequiredToCarryAtLeast500000NonTruckingLiability"
                                        type="checkbox"
                                        name="areAllOwnerOperatorsRequiredToCarryAtLeast500000NonTruckingLiability" checked
                                        readonly />
                                    <label for="areAllOwnerOperatorsRequiredToCarryAtLeast500000NonTruckingLiability">N</label>
                                {else}
                                    <input id="areAllOwnerOperatorsRequiredToCarryAtLeast500000NonTruckingLiability"
                                        type="checkbox"
                                        name="areAllOwnerOperatorsRequiredToCarryAtLeast500000NonTruckingLiability" readonly />
                                    <label for="areAllOwnerOperatorsRequiredToCarryAtLeast500000NonTruckingLiability">N</label>
                                {/if}
                            </td>
                        {else}
                            <td>
                                <input id="areAllOwnerOperatorsRequiredToCarryAtLeast500000NonTruckingLiability"
                                    type="checkbox"
                                    name="areAllOwnerOperatorsRequiredToCarryAtLeast500000NonTruckingLiability" readonly />
                                <label for="areAllOwnerOperatorsRequiredToCarryAtLeast500000NonTruckingLiability">Y</label>
                                <input id="areAllOwnerOperatorsRequiredToCarryAtLeast500000NonTruckingLiability"
                                    type="checkbox"
                                    name="areAllOwnerOperatorsRequiredToCarryAtLeast500000NonTruckingLiability" readonly />
                                <label for="areAllOwnerOperatorsRequiredToCarryAtLeast500000NonTruckingLiability">N</label>
                            </td>
                        {/if}
                    </tr>
                    <tr>
                        <td>8.</td>
                        <td>Are certificates on file?</td>
                        {if isset($survey2['areCertificatesOnFile'])}
                            <td>
                                {if $survey2.areCertificatesOnFile == "yes"}
                                    <input id="areCertificatesOnFile" type="checkbox" name="areCertificatesOnFile" checked
                                        readonly />
                                    <label for="areCertificatesOnFile">Y</label>
                                {else}
                                    <input id="areCertificatesOnFile" type="checkbox" name="areCertificatesOnFile" readonly />
                                    <label for="areCertificatesOnFile">Y</label>
                                {/if}
                            </td>
                            <td>
                                {if $survey2.areCertificatesOnFile == "no"}
                                    <input id="areCertificatesOnFile" type="checkbox" name="areCertificatesOnFile" checked
                                        readonly />
                                    <label for="areCertificatesOnFile">N</label>
                                {else}
                                    <input id="areCertificatesOnFile" type="checkbox" name="areCertificatesOnFile" readonly />
                                    <label for="areCertificatesOnFile">N</label>
                                {/if}
                            </td>
                        {else}
                            <td>
                                <input id="areCertificatesOnFile" type="checkbox" name="areCertificatesOnFile" readonly />
                                <label for="areCertificatesOnFile">Y</label>
                                <input id="areCertificatesOnFile" type="checkbox" name="areCertificatesOnFile" readonly />
                                <label for="areCertificatesOnFile">N</label>
                            </td>
                        {/if}
                    </tr>
                    <tr>
                        <td>9.</td>
                        <td>Is the insured listed as an additional insured?</td>
                        {if isset($survey2['isTheInsuredListedAsAnAdditionalInsured'])}
                            <td>
                                {if $survey2.isTheInsuredListedAsAnAdditionalInsured == "yes"}
                                    <input id="isTheInsuredListedAsAnAdditionalInsured" type="checkbox"
                                        name="isTheInsuredListedAsAnAdditionalInsured" checked readonly />
                                    <label for="isTheInsuredListedAsAnAdditionalInsured">Y</label>
                                {else}
                                    <input id="isTheInsuredListedAsAnAdditionalInsured" type="checkbox"
                                        name="isTheInsuredListedAsAnAdditionalInsured" readonly />
                                    <label for="isTheInsuredListedAsAnAdditionalInsured">Y</label>
                                {/if}
                            </td>
                            <td>
                                {if $survey2.isTheInsuredListedAsAnAdditionalInsured == "no"}
                                    <input id="isTheInsuredListedAsAnAdditionalInsured" type="checkbox"
                                        name="isTheInsuredListedAsAnAdditionalInsured" checked readonly />
                                    <label for="isTheInsuredListedAsAnAdditionalInsured">N</label>
                                {else}
                                    <input id="isTheInsuredListedAsAnAdditionalInsured" type="checkbox"
                                        name="isTheInsuredListedAsAnAdditionalInsured" readonly />
                                    <label for="isTheInsuredListedAsAnAdditionalInsured">N</label>
                                {/if}
                            </td>
                        {else}
                            <td>
                                <input id="isTheInsuredListedAsAnAdditionalInsured" type="checkbox"
                                    name="isTheInsuredListedAsAnAdditionalInsured" readonly />
                                <label for="isTheInsuredListedAsAnAdditionalInsured">Y</label>
                                <input id="isTheInsuredListedAsAnAdditionalInsured" type="checkbox"
                                    name="isTheInsuredListedAsAnAdditionalInsured" readonly />
                                <label for="isTheInsuredListedAsAnAdditionalInsured">N</label>
                            </td>
                        {/if}
                    </tr>
                </table>
            </div>
        </section>

        {* Section 2 *}

        {* Section 3 *}
        <section class="mb-4">
            <div class="mb-4">
                <h3 class="bold"><span class="section_black mr-4">SECTION 3</span> DRIVER HIRING </h3>
            </div>
            <div id="sectionContent">
                <strong>Please provide your driver training program.</strong><br>
                <table>
                    <tr>
                        <td>1.</td>
                        <td>Is a background check performed prior to hiring?</td>
                        {if isset($survey3['isABackgroundCheckPerformedPriorToHiring'])}
                            <td>
                                {if $survey3.isABackgroundCheckPerformedPriorToHiring == "yes"}
                                    <input id="isABackgroundCheckPerformedPriorToHiring" type="checkbox"
                                        name="isABackgroundCheckPerformedPriorToHiring" checked readonly />
                                    <label for="isABackgroundCheckPerformedPriorToHiring">Y</label>
                                {else}
                                    <input id="isABackgroundCheckPerformedPriorToHiring" type="checkbox"
                                        name="isABackgroundCheckPerformedPriorToHiring" readonly />
                                    <label for="isABackgroundCheckPerformedPriorToHiring">Y</label>
                                {/if}
                            </td>
                            <td>
                                {if $survey3.isABackgroundCheckPerformedPriorToHiring == "no"}
                                    <input id="isABackgroundCheckPerformedPriorToHiring" type="checkbox"
                                        name="isABackgroundCheckPerformedPriorToHiring" checked readonly />
                                    <label for="isABackgroundCheckPerformedPriorToHiring">N</label>
                                {else}
                                    <input id="isABackgroundCheckPerformedPriorToHiring" type="checkbox"
                                        name="isABackgroundCheckPerformedPriorToHiring" readonly />
                                    <label for="isABackgroundCheckPerformedPriorToHiring">N</label>
                                {/if}
                            </td>
                        {else}
                            <td>
                                <input id="isABackgroundCheckPerformedPriorToHiring" type="checkbox"
                                    name="isABackgroundCheckPerformedPriorToHiring" readonly />
                                <label for="isABackgroundCheckPerformedPriorToHiring">Y</label>
                                <input id="isABackgroundCheckPerformedPriorToHiring" type="checkbox"
                                    name="isABackgroundCheckPerformedPriorToHiring" readonly />
                                <label for="isABackgroundCheckPerformedPriorToHiring">N</label>
                            </td>
                        {/if}
                    </tr>
                    <tr>
                        <td>2.</td>
                        <td>Do you allow drivers with major violations?</td>
                        {if isset($survey3['doYouAllowDriversWithMajorViolations'])}
                            <td>
                                {if $survey3.doYouAllowDriversWithMajorViolations == "yes"}
                                    <input id="doYouAllowDriversWithMajorViolations" type="checkbox"
                                        name="doYouAllowDriversWithMajorViolations" checked readonly />
                                    <label for="doYouAllowDriversWithMajorViolations">Y</label>
                                {else}
                                    <input id="doYouAllowDriversWithMajorViolations" type="checkbox"
                                        name="doYouAllowDriversWithMajorViolations" readonly />
                                    <label for="doYouAllowDriversWithMajorViolations">Y</label>
                                {/if}
                            </td>
                            <td>
                                {if $survey3.doYouAllowDriversWithMajorViolations == "no"}
                                    <input id="doYouAllowDriversWithMajorViolations" type="checkbox"
                                        name="doYouAllowDriversWithMajorViolations" checked readonly />
                                    <label for="doYouAllowDriversWithMajorViolations">N</label>
                                {else}
                                    <input id="doYouAllowDriversWithMajorViolations" type="checkbox"
                                        name="doYouAllowDriversWithMajorViolations" readonly />
                                    <label for="doYouAllowDriversWithMajorViolations">N</label>
                                {/if}
                            </td>
                        {else}
                            <td>
                                <input id="doYouAllowDriversWithMajorViolations" type="checkbox"
                                    name="doYouAllowDriversWithMajorViolations" readonly />
                                <label for="doYouAllowDriversWithMajorViolations">Y</label>
                                <input id="doYouAllowDriversWithMajorViolations" type="checkbox"
                                    name="doYouAllowDriversWithMajorViolations" readonly />
                                <label for="doYouAllowDriversWithMajorViolations">N</label>
                            </td>
                        {/if}
                    </tr>
                    <tr>
                        <td>3.</td>
                        <td>Do you order MVRs prior to hiring?</td>
                        {if isset($survey3['doYouOrderMvRsPriorToHiring'])}
                            <td>
                                {if $survey3.doYouOrderMvRsPriorToHiring == "yes"}
                                    <input id="doYouOrderMvRsPriorToHiring" type="checkbox" name="doYouOrderMvRsPriorToHiring"
                                        checked readonly />
                                    <label for="doYouOrderMvRsPriorToHiring">Y</label>
                                {else}
                                    <input id="doYouOrderMvRsPriorToHiring" type="checkbox" name="doYouOrderMvRsPriorToHiring"
                                        readonly />
                                    <label for="doYouOrderMvRsPriorToHiring">Y</label>
                                {/if}
                            </td>
                            <td>
                                {if $survey3.doYouOrderMvRsPriorToHiring == "no"}
                                    <input id="doYouOrderMvRsPriorToHiring" type="checkbox" name="doYouOrderMvRsPriorToHiring"
                                        checked readonly />
                                    <label for="doYouOrderMvRsPriorToHiring">N</label>
                                {else}
                                    <input id="doYouOrderMvRsPriorToHiring" type="checkbox" name="doYouOrderMvRsPriorToHiring"
                                        readonly />
                                    <label for="doYouOrderMvRsPriorToHiring">N</label>
                                {/if}
                            </td>
                        {else}
                            <td>
                                <input id="doYouOrderMvRsPriorToHiring" type="checkbox" name="doYouOrderMvRsPriorToHiring"
                                    readonly />
                                <label for="doYouOrderMvRsPriorToHiring">Y</label>
                                <input id="doYouOrderMvRsPriorToHiring" type="checkbox" name="doYouOrderMvRsPriorToHiring"
                                    readonly />
                                <label for="doYouOrderMvRsPriorToHiring">N</label>
                            </td>
                        {/if}
                    </tr>
                    <tr>
                        <td>4.</td>
                        <td>
                            How often are MVRs reviewed?
                            {if ((isset($howOftenAreMvRsReviewed) && $howOftenAreMvRsReviewed != ""))}
                                <span class="underline">{$howOftenAreMvRsReviewed}</span>
                            {/if}
                        </td>
                    </tr>
                    <tr>
                        <td>5.</td>
                        <td>Are driver files updated annually with information including new MVRs?</td>
                        {if isset($survey3['areDriverFilesUpdatedAnnuallyWithInformationIncludingNewMvRs'])}
                            <td>
                                {if $survey3.areDriverFilesUpdatedAnnuallyWithInformationIncludingNewMvRs == "yes"}
                                    <input id="areDriverFilesUpdatedAnnuallyWithInformationIncludingNewMvRs" type="checkbox"
                                        name="areDriverFilesUpdatedAnnuallyWithInformationIncludingNewMvRs" checked readonly />
                                    <label for="areDriverFilesUpdatedAnnuallyWithInformationIncludingNewMvRs">Y</label>
                                {else}
                                    <input id="areDriverFilesUpdatedAnnuallyWithInformationIncludingNewMvRs" type="checkbox"
                                        name="areDriverFilesUpdatedAnnuallyWithInformationIncludingNewMvRs" readonly />
                                    <label for="areDriverFilesUpdatedAnnuallyWithInformationIncludingNewMvRs">Y</label>
                                {/if}
                            </td>
                            <td>
                                {if $survey3.areDriverFilesUpdatedAnnuallyWithInformationIncludingNewMvRs == "no"}
                                    <input id="areDriverFilesUpdatedAnnuallyWithInformationIncludingNewMvRs" type="checkbox"
                                        name="areDriverFilesUpdatedAnnuallyWithInformationIncludingNewMvRs" checked readonly />
                                    <label for="areDriverFilesUpdatedAnnuallyWithInformationIncludingNewMvRs">N</label>
                                {else}
                                    <input id="areDriverFilesUpdatedAnnuallyWithInformationIncludingNewMvRs" type="checkbox"
                                        name="areDriverFilesUpdatedAnnuallyWithInformationIncludingNewMvRs" readonly />
                                    <label for="areDriverFilesUpdatedAnnuallyWithInformationIncludingNewMvRs">N</label>
                                {/if}
                            </td>
                        {else}
                            <td>
                                <input id="areDriverFilesUpdatedAnnuallyWithInformationIncludingNewMvRs" type="checkbox"
                                    name="areDriverFilesUpdatedAnnuallyWithInformationIncludingNewMvRs" readonly />
                                <label for="areDriverFilesUpdatedAnnuallyWithInformationIncludingNewMvRs">Y</label>
                                <input id="areDriverFilesUpdatedAnnuallyWithInformationIncludingNewMvRs" type="checkbox"
                                    name="areDriverFilesUpdatedAnnuallyWithInformationIncludingNewMvRs" readonly />
                                <label for="areDriverFilesUpdatedAnnuallyWithInformationIncludingNewMvRs">N</label>
                            </td>
                        {/if}
                    </tr>
                    <tr>
                        <td>6.</td>
                        <td>Do you exclude drivers with citations for DWI, DUI, or reckless operations?</td>
                        {if isset($survey3['doYouExcludeDriversWithCitationsForDwiDuiOrRecklessOperations'])}
                            <td>
                                {if $survey3.doYouExcludeDriversWithCitationsForDwiDuiOrRecklessOperations == "yes"}
                                    <input id="doYouExcludeDriversWithCitationsForDwiDuiOrRecklessOperations" type="checkbox"
                                        name="doYouExcludeDriversWithCitationsForDwiDuiOrRecklessOperations" checked readonly />
                                    <label for="doYouExcludeDriversWithCitationsForDwiDuiOrRecklessOperations">Y</label>
                                {else}
                                    <input id="doYouExcludeDriversWithCitationsForDwiDuiOrRecklessOperations" type="checkbox"
                                        name="doYouExcludeDriversWithCitationsForDwiDuiOrRecklessOperations" readonly />
                                    <label for="doYouExcludeDriversWithCitationsForDwiDuiOrRecklessOperations">Y</label>
                                {/if}
                            </td>
                            <td>
                                {if $survey3.doYouExcludeDriversWithCitationsForDwiDuiOrRecklessOperations == "no"}
                                    <input id="doYouExcludeDriversWithCitationsForDwiDuiOrRecklessOperations" type="checkbox"
                                        name="doYouExcludeDriversWithCitationsForDwiDuiOrRecklessOperations" checked readonly />
                                    <label for="doYouExcludeDriversWithCitationsForDwiDuiOrRecklessOperations">N</label>
                                {else}
                                    <input id="doYouExcludeDriversWithCitationsForDwiDuiOrRecklessOperations" type="checkbox"
                                        name="doYouExcludeDriversWithCitationsForDwiDuiOrRecklessOperations" readonly />
                                    <label for="doYouExcludeDriversWithCitationsForDwiDuiOrRecklessOperations">N</label>
                                {/if}
                            </td>
                        {else}
                            <td>
                                <input id="doYouExcludeDriversWithCitationsForDwiDuiOrRecklessOperations" type="checkbox"
                                    name="doYouExcludeDriversWithCitationsForDwiDuiOrRecklessOperations" readonly />
                                <label for="doYouExcludeDriversWithCitationsForDwiDuiOrRecklessOperations">Y</label>
                                <input id="doYouExcludeDriversWithCitationsForDwiDuiOrRecklessOperations" type="checkbox"
                                    name="doYouExcludeDriversWithCitationsForDwiDuiOrRecklessOperations" readonly />
                                <label for="doYouExcludeDriversWithCitationsForDwiDuiOrRecklessOperations">N</label>
                            </td>
                        {/if}
                    </tr>
                    <tr>
                        <td>7.</td>
                        <td>What action is taken when drivers develop unacceptable records?
                            {if ((isset($whatActionIsTakenWhenDriversDevelopUnacceptableRecords) && $whatActionIsTakenWhenDriversDevelopUnacceptableRecords != ""))}
                                <span class="underline">{$whatActionIsTakenWhenDriversDevelopUnacceptableRecords}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                    </tr>
                </table>
            </div>
        </section>
        {* Section 3 *}

        {* Section 4 *}
        <section class="mb-4">
            <div class="mb-4">
                <h3 class="bold"><span class="section_black mr-4">SECTION 4</span> MAINTENANCE PROGRAM </h3>
            </div>

            <div id="sectionContent">
                <table>
                    <tr>
                        <td>1. Is there a written maintenance program?</td>
                        {if isset($survey4['isThereAWrittenMaintenanceProgram'])}
                            <td>
                                {if $survey4.isThereAWrittenMaintenanceProgram == "yes"}
                                    <input id="isThereAWrittenMaintenanceProgram" type="checkbox"
                                        name="isThereAWrittenMaintenanceProgram" checked readonly />
                                    <label for="isThereAWrittenMaintenanceProgram">Y</label>
                                {else}
                                    <input id="isThereAWrittenMaintenanceProgram" type="checkbox"
                                        name="isThereAWrittenMaintenanceProgram" readonly />
                                    <label for="isThereAWrittenMaintenanceProgram">Y</label>
                                {/if}
                            </td>
                            <td>
                                {if $survey4.isThereAWrittenMaintenanceProgram == "no"}
                                    <input id="isThereAWrittenMaintenanceProgram" type="checkbox"
                                        name="isThereAWrittenMaintenanceProgram" checked readonly />
                                    <label for="isThereAWrittenMaintenanceProgram">N</label>
                                {else}
                                    <input id="isThereAWrittenMaintenanceProgram" type="checkbox"
                                        name="isThereAWrittenMaintenanceProgram" readonly />
                                    <label for="isThereAWrittenMaintenanceProgram">N</label>
                                {/if}
                            </td>
                        {else}
                            <td>
                                <input id="isThereAWrittenMaintenanceProgram" type="checkbox"
                                    name="isThereAWrittenMaintenanceProgram" readonly />
                                <label for="isThereAWrittenMaintenanceProgram">Y</label>
                                <input id="isThereAWrittenMaintenanceProgram" type="checkbox"
                                    name="isThereAWrittenMaintenanceProgram" readonly />
                                <label for="isThereAWrittenMaintenanceProgram">N</label>
                            </td>
                        {/if}
                    </tr>
                    <tr>
                        <td>2. Name of Maintenance Manager:</td>
                        <td class="underline pr-4">
                            {if ((isset($nameOfMaintenanceManager) && $nameOfMaintenanceManager != ""))}
                                {$nameOfMaintenanceManager}
                                {else}
                                    <span>&nbsp;&nbsp;&nbsp;</span>
                                {/if}
                        </td>
                    </tr>
                    <tr>
                        <td>3.Years with company:</td>
                        <td class="underline pr-4">
                            
                                {if ((isset($yearsWithCompany) && $yearsWithCompany != ""))}
                                    {$yearsWithCompany}
                                {else}
                                    <span>&nbsp;&nbsp;&nbsp;</span>
                                {/if}
                        </td>
                    </tr>
                    <tr>
                        <td>4. Years in maintenance:</td>
                        <td class="underline pr-4">
                            
                            <span>
                            {if ((isset($yearsInMaintenance) && $yearsInMaintenance != ""))}
                                {$yearsInMaintenance}
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            5. # of full-time maintenance personnel:
                            <span class="underline">
                            {if ((isset($ofFullTimeMaintenancePersonnel) && $ofFullTimeMaintenancePersonnel != ""))}
                                {$ofFullTimeMaintenancePersonnel}
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            6. Maintenance program is provided for: (Check all that apply)<br>
                            <div class="pl-4">
                                {if isset($companyVehicles) && $companyVehicles == true}
                                    <input id="companyVehicles" type="checkbox" name="companyVehicles" checked readonly />
                                    <label for="companyVehicles">Company Vehicles</label><br>
                                {else}
                                    <input id="companyVehicles" type="checkbox" name="companyVehicles" readonly />
                                    <label for="companyVehicles">Company Vehicles</label><br>
                                {/if}
                                {if isset($ownerOperators2) && $ownerOperators2 == true}
                                    <input id="ownerOperators2" type="checkbox" name="ownerOperators2" checked readonly />
                                    <label for="ownerOperators2">Owner/Operators</label><br>
                                {else}
                                    <input id="ownerOperators2" type="checkbox" name="ownerOperators2" readonly />
                                    <label for="ownerOperators2">Owner/Operators</label><br>
                                {/if}
                                {if isset($openToThePublic) && $openToThePublic == true}
                                    <input id="openToThePublic" type="checkbox" name="openToThePublic" checked readonly />
                                    <label for="openToThePublic">Open to the public</label>
                                {else}
                                    <input id="openToThePublic" type="checkbox" name="openToThePublic" readonly />
                                    <label for="openToThePublic">Open to the public</label>
                                {/if}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            7. Vehicle Maintenance is: (Check all that apply)<br>
                            <div class="pl-4">
                                {if isset($internal) && $internal == true}
                                    <input id="internal" type="checkbox" name="internal" checked readonly />
                                    <label for="internal">Internal</label><br>
                                {else}
                                    <input id="internal" type="checkbox" name="internal" readonly />
                                    <label for="internal">Internal</label><br>
                                {/if}
                                {if isset($externalBody) && $externalBody == true}
                                    <input id="externalBody" type="checkbox" name="externalBody" checked readonly />
                                    <label for="externalBody">External (Body)</label><br>
                                {else}
                                    <input id="externalBody" type="checkbox" name="externalBody" readonly />
                                    <label for="externalBody">External (Body)</label><br>
                                {/if}
                                {if isset($both) && $both == true}
                                    <input id="both" type="checkbox" name="both" checked readonly />
                                    <label for="both">Both</label>
                                {else}
                                    <input id="both" type="checkbox" name="both" readonly />
                                    <label for="both">Both</label>
                                {/if}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            8. Indicate which of the following you have: (Check all that apply)<br>
                            <div class="pl-4">
                                {if isset($partsDepartment) && $partsDepartment == true}
                                    <input id="partsDepartment" type="checkbox" name="partsDepartment" checked readonly />
                                    <label for="partsDepartment">Parts department</label><br>
                                {else}
                                    <input id="partsDepartment" type="checkbox" name="partsDepartment" readonly />
                                    <label for="partsDepartment">Parts department</label><br>
                                {/if}
                                {if isset($bodyShop) && $bodyShop == true}
                                    <input id="bodyShop" type="checkbox" name="bodyShop" checked readonly />
                                    <label for="bodyShop">Body shop</label><br>
                                {else}
                                    <input id="bodyShop" type="checkbox" name="bodyShop" readonly />
                                    <label for="bodyShop">Body shop</label><br>
                                {/if}
                                {if isset($serviceBays) && $serviceBays == true}
                                    <input id="serviceBays" type="checkbox" name="serviceBays" checked readonly />
                                    <label for="serviceBays">Service bays</label><br>
                                {else}
                                    <input id="serviceBays" type="checkbox" name="serviceBays" readonly />
                                    <label for="serviceBays">Service bays</label><br>
                                {/if}
                                {if isset($controlledInspectionReports) && $controlledInspectionReports == true}
                                    <input id="controlledInspectionReports" type="checkbox"
                                        name="controlledInspectionReports" checked readonly />
                                    <label for="controlledInspectionReports">Controlled inspection reports</label>
                                {else}
                                    <input id="controlledInspectionReports" type="checkbox"
                                        name="controlledInspectionReports" readonly />
                                    <label for="controlledInspectionReports">Controlled inspection reports</label>
                                {/if}
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td>9. Are pre/post trip inspections made regularly?</td>
                        {if isset($survey4['arePrePostTripInspectionsMadeRegularly'])}
                            <td>
                                {if $survey4.arePrePostTripInspectionsMadeRegularly == "yes"}
                                    <input id="arePrePostTripInspectionsMadeRegularly" type="checkbox"
                                        name="arePrePostTripInspectionsMadeRegularly" checked readonly />
                                    <label for="arePrePostTripInspectionsMadeRegularly">Y</label>
                                {else}
                                    <input id="arePrePostTripInspectionsMadeRegularly" type="checkbox"
                                        name="arePrePostTripInspectionsMadeRegularly" readonly />
                                    <label for="arePrePostTripInspectionsMadeRegularly">Y</label>
                                {/if}
                            </td>
                            <td>
                                {if $survey4.arePrePostTripInspectionsMadeRegularly == "no"}
                                    <input id="arePrePostTripInspectionsMadeRegularly" type="checkbox"
                                        name="arePrePostTripInspectionsMadeRegularly" checked readonly />
                                    <label for="arePrePostTripInspectionsMadeRegularly">N</label>
                                {else}
                                    <input id="arePrePostTripInspectionsMadeRegularly" type="checkbox"
                                        name="arePrePostTripInspectionsMadeRegularly" readonly />
                                    <label for="arePrePostTripInspectionsMadeRegularly">N</label>
                                {/if}
                            </td>
                        {else}
                            <td>
                                <input id="arePrePostTripInspectionsMadeRegularly" type="checkbox"
                                    name="arePrePostTripInspectionsMadeRegularly" readonly />
                                <label for="arePrePostTripInspectionsMadeRegularly">Y</label>
                                <input id="arePrePostTripInspectionsMadeRegularly" type="checkbox"
                                    name="arePrePostTripInspectionsMadeRegularly" readonly />
                                <label for="arePrePostTripInspectionsMadeRegularly">N</label>
                            </td>
                        {/if}
                    </tr>
                    <tr>
                        <td>10. Are all maintenance records on file?</td>
                        {if isset($survey4['areAllMaintenanceRecordsOnFile'])}
                            <td>
                                {if $survey4.areAllMaintenanceRecordsOnFile == "yes"}
                                    <input id="areAllMaintenanceRecordsOnFile" type="checkbox"
                                        name="areAllMaintenanceRecordsOnFile" checked readonly />
                                    <label for="areAllMaintenanceRecordsOnFile">Y</label>
                                {else}
                                    <input id="areAllMaintenanceRecordsOnFile" type="checkbox"
                                        name="areAllMaintenanceRecordsOnFile" readonly />
                                    <label for="areAllMaintenanceRecordsOnFile">Y</label>
                                {/if}
                            </td>
                            <td>
                                {if $survey4.areAllMaintenanceRecordsOnFile == "no"}
                                    <input id="areAllMaintenanceRecordsOnFile" type="checkbox"
                                        name="areAllMaintenanceRecordsOnFile" checked readonly />
                                    <label for="areAllMaintenanceRecordsOnFile">N</label>
                                {else}
                                    <input id="areAllMaintenanceRecordsOnFile" type="checkbox"
                                        name="areAllMaintenanceRecordsOnFile" readonly />
                                    <label for="areAllMaintenanceRecordsOnFile">N</label>
                                {/if}
                            </td>
                        {else}
                            <td>
                                <input id="areAllMaintenanceRecordsOnFile" type="checkbox"
                                    name="areAllMaintenanceRecordsOnFile" readonly />
                                <label for="areAllMaintenanceRecordsOnFile">Y</label>
                                <input id="areAllMaintenanceRecordsOnFile" type="checkbox"
                                    name="areAllMaintenanceRecordsOnFile" readonly />
                                <label for="areAllMaintenanceRecordsOnFile">N</label>
                            </td>
                        {/if}
                    </tr>
                    <tr>
                        <td>11. Are re-treads used?</td>
                        {if isset($survey4['areReTreadsUsed'])}
                            <td>
                                {if $survey4.areReTreadsUsed == "yes"}
                                    <input id="areReTreadsUsed" type="checkbox" name="areReTreadsUsed" checked readonly />
                                    <label for="areReTreadsUsed">Y</label>
                                {else}
                                    <input id="areReTreadsUsed" type="checkbox" name="areReTreadsUsed" readonly />
                                    <label for="areReTreadsUsed">Y</label>
                                {/if}
                            </td>
                            <td>
                                {if $survey4.areReTreadsUsed == "no"}
                                    <input id="areReTreadsUsed" type="checkbox" name="areReTreadsUsed" checked readonly />
                                    <label for="areReTreadsUsed">N</label>
                                {else}
                                    <input id="areReTreadsUsed" type="checkbox" name="areReTreadsUsed" readonly />
                                    <label for="areReTreadsUsed">N</label>
                                {/if}
                            </td>
                        {else}
                            <td>
                                <input id="areReTreadsUsed" type="checkbox" name="areReTreadsUsed" readonly />
                                <label for="areReTreadsUsed">Y</label>
                                <input id="areReTreadsUsed" type="checkbox" name="areReTreadsUsed" readonly />
                                <label for="areReTreadsUsed">N</label>
                            </td>
                        {/if}
                    </tr>
                </table>
            </div>

        </section>
        {* Section 4 *}

        {* Section 5 *}
        <section class="mb-4">
            <div class="mb-4">
                <h3 class="bold"><span class="section_black mr-4">SECTION 5</span> SAFETY </h3>
            </div>

            <div id="sectionContent">
                <strong>Attach copy of safety program</strong><br>
                <table>
                    <tr>
                        <td>
                            <div class="float-left col-40">
                                1. Name of Safety Director:
                            </div>
                            <div class="float-left col-50 underline">
                                {if ((isset($nameOfSafetyDirector) && $nameOfSafetyDirector != ""))}
                                    <span>{$nameOfSafetyDirector}</span>
                                {else}
                                    <span>&nbsp;&nbsp;&nbsp;</span>
                                {/if}
                            </div>
                        </td>

                    </tr>
                    <tr>
                        <td>
                            <div class="float-left col-40">
                                2. Years with company:
                            </div>
                            <div class="float-left col-50 underline">
                                {if isset($yearsWithCompany1)}
                                    <span>{$yearsWithCompany1}</span>
                                {else}
                                    <span>&nbsp;&nbsp;&nbsp;</span>
                                {/if}
                            </div>
                        </td>

                    </tr>
                    <tr>
                        <td>
                            <div class="float-left col-40">
                                3. Years in safety field:
                            </div>
                            <div class="float-left col-50 underline">
                                {if isset($yearsInSafetyField)}
                                    <span>{$yearsInSafetyField}</span>
                                {else}
                                    <span>&nbsp;&nbsp;&nbsp;</span>
                                {/if}
                            </div>

                        </td>

                    </tr>
                    <tr>

                        <td>
                            <div class="float-left col-40">
                                4. Safety director reports to:
                            </div>
                            <div class="float-left col-50 underline">
                                {if ((isset($safetyDirectorReportsTo) && $safetyDirectorReportsTo != ""))}
                                    <span>{$safetyDirectorReportsTo}</span>
                                {else}
                                    <span>&nbsp;&nbsp;&nbsp;</span>
                                {/if}
                            </div>
                        </td>

                    </tr>
                    <tr>

                        <td>
                            <div class="float-left col-40">
                                5. % of time spent on Safety:
                            </div>
                            <div class="float-left col-50 underline">
                                {if isset($ofTimeSpentOnSafety)}
                                    <span>{$ofTimeSpentOnSafety}</span>%
                                {else}
                                    <span>&nbsp;&nbsp;&nbsp;</span>%
                                {/if}
                            </div>
                        </td>

                    </tr>
                    <tr>
                        <td>
                            6. Do you have a safety award program?
                        </td>
                        {if isset($survey5['doYouHaveASafetyAwardProgram'])}
                            <td>
                                {if $survey5.doYouHaveASafetyAwardProgram == "yes"}
                                    <input id="doYouHaveASafetyAwardProgram" type="checkbox" name="doYouHaveASafetyAwardProgram"
                                        checked readonly />
                                    <label for="doYouHaveASafetyAwardProgram">Y</label>
                                {else}
                                    <input id="doYouHaveASafetyAwardProgram" type="checkbox" name="doYouHaveASafetyAwardProgram"
                                        readonly />
                                    <label for="doYouHaveASafetyAwardProgram">Y</label>
                                {/if}
                            </td>
                            <td>
                                {if $survey5.doYouHaveASafetyAwardProgram == "no"}
                                    <input id="doYouHaveASafetyAwardProgram" type="checkbox" name="doYouHaveASafetyAwardProgram"
                                        checked readonly />
                                    <label for="doYouHaveASafetyAwardProgram">N</label>
                                {else}
                                    <input id="doYouHaveASafetyAwardProgram" type="checkbox" name="doYouHaveASafetyAwardProgram"
                                        readonly />
                                    <label for="doYouHaveASafetyAwardProgram">N</label>
                                {/if}
                            </td>
                        {else}
                            <td>
                                <input id="doYouHaveASafetyAwardProgram" type="checkbox" name="doYouHaveASafetyAwardProgram"
                                    readonly />
                                <label for="doYouHaveASafetyAwardProgram">Y</label>
                                <input id="doYouHaveASafetyAwardProgram" type="checkbox" name="doYouHaveASafetyAwardProgram"
                                    readonly />
                                <label for="doYouHaveASafetyAwardProgram">N</label>
                            </td>
                        {/if}
                    </tr>
                    <tr>
                        <td>
                            <div class="float-left col-40">
                                7. How often are safety meetings held?
                            </div>
                            <div class="float-left col-50 underline">
                                {if ((isset($howOftenAreSafetyMeetingsHeld) && $howOftenAreSafetyMeetingsHeld != ""))}
                                    <span>{$howOftenAreSafetyMeetingsHeld}</span>
                                {else}
                                    <span>&nbsp;&nbsp;&nbsp;</span>
                                {/if}
                            </div>

                        </td>

                    </tr>
                    <tr>
                        <td>8. Are safety meetings mandatory?</td>
                        {if isset($survey5['areSafetyMeetingsMandatory'])}
                            <td>
                                {if $survey5.areSafetyMeetingsMandatory == "yes"}
                                    <input id="areSafetyMeetingsMandatory" type="checkbox" name="areSafetyMeetingsMandatory"
                                        checked readonly />
                                    <label for="areSafetyMeetingsMandatory">Y</label>
                                {else}
                                    <input id="areSafetyMeetingsMandatory" type="checkbox" name="areSafetyMeetingsMandatory"
                                        readonly />
                                    <label for="areSafetyMeetingsMandatory">Y</label>
                                {/if}
                            </td>
                            <td>
                                {if $survey5.areSafetyMeetingsMandatory == "no"}
                                    <input id="areSafetyMeetingsMandatory" type="checkbox" name="areSafetyMeetingsMandatory"
                                        checked readonly />
                                    <label for="areSafetyMeetingsMandatory">N</label>
                                {else}
                                    <input id="areSafetyMeetingsMandatory" type="checkbox" name="areSafetyMeetingsMandatory"
                                        readonly />
                                    <label for="areSafetyMeetingsMandatory">N</label>
                                {/if}
                            </td>
                        {else}
                            <td>
                                <input id="areSafetyMeetingsMandatory" type="checkbox" name="areSafetyMeetingsMandatory"
                                    readonly />
                                <label for="areSafetyMeetingsMandatory">Y</label>
                                <input id="areSafetyMeetingsMandatory" type="checkbox" name="areSafetyMeetingsMandatory"
                                    readonly />
                                <label for="areSafetyMeetingsMandatory">N</label>
                            </td>
                        {/if}
                    </tr>
                    <tr>
                        <td>9. Is remedial training required for drivers with accidents/speeding?</td>
                        {if isset($survey5['isRemedialTrainingRequiredForDriversWithAccidentsSpeeding'])}
                            <td>
                                {if $survey5.isRemedialTrainingRequiredForDriversWithAccidentsSpeeding == "yes"}
                                    <input id="isRemedialTrainingRequiredForDriversWithAccidentsSpeeding" type="checkbox"
                                        name="isRemedialTrainingRequiredForDriversWithAccidentsSpeeding" checked readonly />
                                    <label for="isRemedialTrainingRequiredForDriversWithAccidentsSpeeding">Y</label>
                                {else}
                                    <input id="isRemedialTrainingRequiredForDriversWithAccidentsSpeeding" type="checkbox"
                                        name="isRemedialTrainingRequiredForDriversWithAccidentsSpeeding" readonly />
                                    <label for="isRemedialTrainingRequiredForDriversWithAccidentsSpeeding">Y</label>
                                {/if}
                            </td>
                            <td>
                                {if $survey5.isRemedialTrainingRequiredForDriversWithAccidentsSpeeding == "no"}
                                    <input id="isRemedialTrainingRequiredForDriversWithAccidentsSpeeding" type="checkbox"
                                        name="isRemedialTrainingRequiredForDriversWithAccidentsSpeeding" checked readonly />
                                    <label for="isRemedialTrainingRequiredForDriversWithAccidentsSpeeding">N</label>
                                {else}
                                    <input id="isRemedialTrainingRequiredForDriversWithAccidentsSpeeding" type="checkbox"
                                        name="isRemedialTrainingRequiredForDriversWithAccidentsSpeeding" readonly />
                                    <label for="isRemedialTrainingRequiredForDriversWithAccidentsSpeeding">N</label>
                                {/if}
                            </td>
                        {else}
                            <td>
                                <input id="isRemedialTrainingRequiredForDriversWithAccidentsSpeeding" type="checkbox"
                                    name="isRemedialTrainingRequiredForDriversWithAccidentsSpeeding" readonly />
                                <label for="isRemedialTrainingRequiredForDriversWithAccidentsSpeeding">Y</label>
                                <input id="isRemedialTrainingRequiredForDriversWithAccidentsSpeeding" type="checkbox"
                                    name="isRemedialTrainingRequiredForDriversWithAccidentsSpeeding" readonly />
                                <label for="isRemedialTrainingRequiredForDriversWithAccidentsSpeeding">N</label>
                            </td>
                        {/if}
                    </tr>
                    <tr>
                        <td>10. Do you maintain an accident register & conduct periodic accident analysis?</td>
                        {if isset($survey5['doYouMaintainAnAccidentRegisterConductPeriodicAccidentAnalysis'])}
                            <td>
                                {if $survey5.doYouMaintainAnAccidentRegisterConductPeriodicAccidentAnalysis == "yes"}
                                    <input id="doYouMaintainAnAccidentRegisterConductPeriodicAccidentAnalysis" type="checkbox"
                                        name="doYouMaintainAnAccidentRegisterConductPeriodicAccidentAnalysis" checked
                                        readonly />
                                    <label for="doYouMaintainAnAccidentRegisterConductPeriodicAccidentAnalysis">Y</label>
                                {else}
                                    <input id="doYouMaintainAnAccidentRegisterConductPeriodicAccidentAnalysis" type="checkbox"
                                        name="doYouMaintainAnAccidentRegisterConductPeriodicAccidentAnalysis" readonly />
                                    <label for="doYouMaintainAnAccidentRegisterConductPeriodicAccidentAnalysis">Y</label>
                                {/if}
                            </td>
                            <td>
                                {if $survey5.doYouMaintainAnAccidentRegisterConductPeriodicAccidentAnalysis == "no"}
                                    <input id="doYouMaintainAnAccidentRegisterConductPeriodicAccidentAnalysis" type="checkbox"
                                        name="doYouMaintainAnAccidentRegisterConductPeriodicAccidentAnalysis" checked
                                        readonly />
                                    <label for="doYouMaintainAnAccidentRegisterConductPeriodicAccidentAnalysis">N</label>
                                {else}
                                    <input id="doYouMaintainAnAccidentRegisterConductPeriodicAccidentAnalysis" type="checkbox"
                                        name="doYouMaintainAnAccidentRegisterConductPeriodicAccidentAnalysis" readonly />
                                    <label for="doYouMaintainAnAccidentRegisterConductPeriodicAccidentAnalysis">N</label>
                                {/if}
                            </td>
                        {else}
                            <td>
                                <input id="doYouMaintainAnAccidentRegisterConductPeriodicAccidentAnalysis" type="checkbox"
                                    name="doYouMaintainAnAccidentRegisterConductPeriodicAccidentAnalysis" readonly />
                                <label for="doYouMaintainAnAccidentRegisterConductPeriodicAccidentAnalysis">Y</label>
                                <input id="doYouMaintainAnAccidentRegisterConductPeriodicAccidentAnalysis" type="checkbox"
                                    name="doYouMaintainAnAccidentRegisterConductPeriodicAccidentAnalysis" readonly />
                                <label for="doYouMaintainAnAccidentRegisterConductPeriodicAccidentAnalysis">N</label>
                            </td>
                        {/if}
                    </tr>
                </table>
                <p>
                    <strong>What safety technology devices are you using?</strong><br>
                <table>
                    <tr>
                        <th></th>
                        <th>% of Fleet</th>
                        <th>Date Installed</th>
                    </tr>
                    <tr>
                        <td>Accident Event Recorder - self managed:</td>
                        <td class="underline pr-4">
                            {if isset($accidentEventRecorderSelfManagedFleet)}
                                <span>{$accidentEventRecorderSelfManagedFleet}</span>%
                            {else}
                                <span>&nbsp;&nbsp;%</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($accidentEventRecorderSelfmanagedDate) && $accidentEventRecorderSelfmanagedDate != ""))}
                                <span>{$accidentEventRecorderSelfmanagedDate|date_format:"%m/%d/%Y"}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                    </tr>
                    <tr>
                        <td>Accident Event Recorder - third party reporting:</td>
                        <td class="underline pr-4">
                            {if isset($accidentEventRecThirdPartyReportingFleet)}
                                <span>{$accidentEventRecThirdPartyReportingFleet}</span>%
                            {else}
                                <span>&nbsp;&nbsp;%</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($accidentEventRecThirdPartyReportingDate) && $accidentEventRecThirdPartyReportingDate != ""))}
                                <span>{$accidentEventRecThirdPartyReportingDate|date_format:"%m/%d/%Y"}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                    </tr>
                    <tr>
                        <td>Electronic Logging Device:</td>
                        <td class="underline pr-4">
                            {if isset($electronicLoggingDeviceFleet)}
                                <span>{$electronicLoggingDeviceFleet}</span>%
                            {else}
                                <span>&nbsp;&nbsp;%</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($electronicLoggingDeviceDate) && $electronicLoggingDeviceDate != ""))}
                                <span>{$electronicLoggingDeviceDate|date_format:"%m/%d/%Y"}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                    </tr>
                    <tr>
                        <td>Collision Avoidance:</td>
                        <td class="underline pr-4">
                            {if isset($collisionAvoidanceFleet)}
                                <span>{$collisionAvoidanceFleet}</span>%
                            {else}
                                <span>&nbsp;&nbsp;%</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($collisionAvoidanceDate) && $collisionAvoidanceDate != ""))}
                                <span>{$collisionAvoidanceDate|date_format:"%m/%d/%Y"}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                    </tr>
                    <tr>
                        <td>In Vehicle Camera:</td>
                        <td class="underline pr-4">
                            {if isset($inVehicleCameraFleet)}
                                <span>{$inVehicleCameraFleet}</span>%
                            {else}
                                <span>&nbsp;&nbsp;%</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($inVehicleCameraDate) && $inVehicleCameraDate != ""))}
                                <span>{$inVehicleCameraDate|date_format:"%m/%d/%Y"}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                    </tr>
                    <tr>
                        <td>Anti-Rollover Device:</td>
                        <td class="underline pr-4">
                            {if isset($antiRolloverDeviceFleet)}
                                <span>{$antiRolloverDeviceFleet}</span>%
                            {else}
                                <span>&nbsp;&nbsp;%</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($antiRolloverDeviceDate) && $antiRolloverDeviceDate != ""))}
                                <span>{$antiRolloverDeviceDate|date_format:"%m/%d/%Y"}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                    </tr>
                    <tr>
                        <td>Telematics:</td>
                        <td class="underline pr-4">
                            {if isset($telematicsFleet)}
                                <span>{$telematicsFleet}</span>%
                            {else}
                                <span>&nbsp;&nbsp;%</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($telematicsDate) && $telematicsDate != ""))}
                                <span>{$telematicsDate|date_format:"%m/%d/%Y"}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                    </tr>
                    <tr>
                        <td>Other:</td>
                        <td class="underline pr-4">
                            {if isset($otherFleet)}
                                <span>{$otherFleet}</span>%
                            {else}
                                <span>&nbsp;&nbsp;%</span>
                            {/if}
                        </td>
                        <td class="underline pr-4">
                            {if ((isset($otherFleetDate) && $otherFleetDate != ""))}
                                <span>{$otherFleetDate|date_format:"%m/%d/%Y"}</span>
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                    </tr>
                </table>
                </p>
            </div>

        </section>
        {* Section 5 *}

        {* Section 6 *}
        <section class="mb-4">
            <div class="mb-4">
                <h3 class="bold"><span class="section_black mr-4">SECTION 6</span> GENERAL LIABILITY </h3>
            </div>

            <div id="sectionContent">
                <table>
                    <tr class="bottom-text">
                        <th>Loc.</th>
                        <th>Address</th>
                        <th>Function</th>
                        <th class="rotated-text"> Fenced </th>
                        <th class="rotated-text"> Guarded </th>
                        <th class="rotated-text"> Public Access </th>
                        <th class="rotated-text"> Lighted </th>
                        <th class="rotated-text"> # of Employees </th>
                        <th>Owned or Leased</th>
                    </tr>
                    {foreach from=$generalLiabilityList item=item key=key}
                        <tr>
                            <td>
                                #{$key+1}.
                            </td>
                            <td>
                                {if isset($item['generalLiabilityAddress'])}
                                    {$item.generalLiabilityAddress}
                                {else}
                                    <span>&nbsp;&nbsp;&nbsp;</span>
                                {/if}
                            </td>
                            <td>
                                {if isset($item['generalLiabilityFunction'])}
                                    {$item['generalLiabilityFunction']}
                                {else}
                                    <span>&nbsp;&nbsp;&nbsp;</span>
                                {/if}
                            </td>
                            <td class="rotated-data">
                                {if isset($item['glFenced']) && ($item['glFenced'] === "true" || $item['glFenced'] === true)}
                                    <input id="glFenced" type="checkbox" name="glFenced" checked readonly />
                                {else}
                                    <input id="glFenced" type="checkbox" name="glFenced" readonly />
                                {/if}
                            </td>
                            <td class="rotated-data">
                                {if isset($item['glGuardDogS']) && ($item['glGuardDogS'] === "true" || $item['glGuardDogS'] === true)}
                                    <input id="glGuardDogS" type="checkbox" name="glGuardDogS" checked readonly />
                                {else}
                                    <input id="glGuardDogS" type="checkbox" name="glGuardDogS" readonly />
                                {/if}
                            </td>
                            <td class="rotated-data">
                                {if isset($item['glPublicAccess']) && ($item['glPublicAccess'] === "true" || $item['glPublicAccess'] === true)}
                                    <input id="glPublicAccess" type="checkbox" name="glPublicAccess" checked readonly />
                                {else}
                                    <input id="glPublicAccess" type="checkbox" name="glPublicAccess" readonly />
                                {/if}
                            </td>
                            <td class="rotated-data">
                                {if isset($item['glLighted']) && ($item['glLighted'] === "true" || $item['glLighted'] === true)}
                                    <input id="glLighted" type="checkbox" name="glLighted" checked readonly />
                                {else}
                                    <input id="glLighted" type="checkbox" name="glLighted" readonly />
                                {/if}
                            </td>
                            <td class="rotated-data">
                                {if isset($item['gLNoOfEmployees'])}
                                    {$item.gLNoOfEmployees}
                                {else}
                                    <span>&nbsp;&nbsp;&nbsp;</span>
                                {/if}
                            </td>
                            <td>
                                {if isset($item['generalLiabilityOwnedLeased'])}
                                    {$item.generalLiabilityOwnedLeased}
                                {else}
                                    <span>&nbsp;&nbsp;&nbsp;</span>
                                {/if}
                            </td>
                        </tr>
                    {/foreach}
                </table>
                <br><br>
                <table class="ml-4">
                    <tr>
                        <td>1.</td>
                        <td>Is Insured involved in any business activity other than trucking?</td>
                        {if isset($survey6['isInsuredInvolvedInAnyBusinessActivityOtherThanTrucking'])}
                            <td>
                                {if $survey6.isInsuredInvolvedInAnyBusinessActivityOtherThanTrucking == "yes"}
                                    <input id="isInsuredInvolvedInAnyBusinessActivityOtherThanTrucking" type="checkbox"
                                        name="isInsuredInvolvedInAnyBusinessActivityOtherThanTrucking" checked readonly />
                                    <label for="isInsuredInvolvedInAnyBusinessActivityOtherThanTrucking">Y</label>
                                {else}
                                    <input id="isInsuredInvolvedInAnyBusinessActivityOtherThanTrucking" type="checkbox"
                                        name="isInsuredInvolvedInAnyBusinessActivityOtherThanTrucking" readonly />
                                    <label for="isInsuredInvolvedInAnyBusinessActivityOtherThanTrucking">Y</label>
                                {/if}
                            </td>
                            <td>
                                {if $survey6.isInsuredInvolvedInAnyBusinessActivityOtherThanTrucking == "no"}
                                    <input id="isInsuredInvolvedInAnyBusinessActivityOtherThanTrucking" type="checkbox"
                                        name="isInsuredInvolvedInAnyBusinessActivityOtherThanTrucking" checked readonly />
                                    <label for="isInsuredInvolvedInAnyBusinessActivityOtherThanTrucking">N</label>
                                {else}
                                    <input id="isInsuredInvolvedInAnyBusinessActivityOtherThanTrucking" type="checkbox"
                                        name="isInsuredInvolvedInAnyBusinessActivityOtherThanTrucking" readonly />
                                    <label for="isInsuredInvolvedInAnyBusinessActivityOtherThanTrucking">N</label>
                                {/if}
                            </td>
                        {else}
                            <td>
                                <input id="isInsuredInvolvedInAnyBusinessActivityOtherThanTrucking" type="checkbox"
                                    name="isInsuredInvolvedInAnyBusinessActivityOtherThanTrucking" readonly />
                                <label for="isInsuredInvolvedInAnyBusinessActivityOtherThanTrucking">Y</label>
                                <input id="isInsuredInvolvedInAnyBusinessActivityOtherThanTrucking" type="checkbox"
                                    name="isInsuredInvolvedInAnyBusinessActivityOtherThanTrucking" readonly />
                                <label for="isInsuredInvolvedInAnyBusinessActivityOtherThanTrucking">N</label>
                            </td>
                        {/if}
                    </tr>
                    <tr>
                        <td>2.</td>
                        <td>Does applicant have underground or above ground storage facilities?</td>
                        {if isset($survey6['doesApplicantHaveUndergroundOrAboveGroundStorageFacilities'])}
                            <td>
                                {if $survey6.doesApplicantHaveUndergroundOrAboveGroundStorageFacilities == "yes"}
                                    <input id="doesApplicantHaveUndergroundOrAboveGroundStorageFacilities" type="checkbox"
                                        name="doesApplicantHaveUndergroundOrAboveGroundStorageFacilities" checked readonly />
                                    <label for="doesApplicantHaveUndergroundOrAboveGroundStorageFacilities">Y</label>
                                {else}
                                    <input id="doesApplicantHaveUndergroundOrAboveGroundStorageFacilities" type="checkbox"
                                        name="doesApplicantHaveUndergroundOrAboveGroundStorageFacilities" readonly />
                                    <label for="doesApplicantHaveUndergroundOrAboveGroundStorageFacilities">Y</label>
                                {/if}
                            </td>
                            <td>
                                {if $survey6.doesApplicantHaveUndergroundOrAboveGroundStorageFacilities == "no"}
                                    <input id="doesApplicantHaveUndergroundOrAboveGroundStorageFacilities" type="checkbox"
                                        name="doesApplicantHaveUndergroundOrAboveGroundStorageFacilities" checked readonly />
                                    <label for="doesApplicantHaveUndergroundOrAboveGroundStorageFacilities">N</label>
                                {else}
                                    <input id="doesApplicantHaveUndergroundOrAboveGroundStorageFacilities" type="checkbox"
                                        name="doesApplicantHaveUndergroundOrAboveGroundStorageFacilities" readonly />
                                    <label for="doesApplicantHaveUndergroundOrAboveGroundStorageFacilities">N</label>
                                {/if}
                            </td>
                        {else}
                            <td>
                                <input id="doesApplicantHaveUndergroundOrAboveGroundStorageFacilities" type="checkbox"
                                    name="doesApplicantHaveUndergroundOrAboveGroundStorageFacilities" readonly />
                                <label for="doesApplicantHaveUndergroundOrAboveGroundStorageFacilities">Y</label>
                                <input id="doesApplicantHaveUndergroundOrAboveGroundStorageFacilities" type="checkbox"
                                    name="doesApplicantHaveUndergroundOrAboveGroundStorageFacilities" readonly />
                                <label for="doesApplicantHaveUndergroundOrAboveGroundStorageFacilities">N</label>
                            </td>
                        {/if}
                    </tr>
                    <tr>
                        <td>3.</td>
                        <td>Does insured have mobile equipment?</td>
                        {if isset($survey6['doesInsuredHaveMobileEquipment'])}
                            <td>
                                {if $survey6.doesInsuredHaveMobileEquipment == "yes"}
                                    <input id="doesInsuredHaveMobileEquipment" type="checkbox"
                                        name="doesInsuredHaveMobileEquipment" checked readonly />
                                    <label for="doesInsuredHaveMobileEquipment">Y</label>
                                {else}
                                    <input id="doesInsuredHaveMobileEquipment" type="checkbox"
                                        name="doesInsuredHaveMobileEquipment" readonly />
                                    <label for="doesInsuredHaveMobileEquipment">Y</label>
                                {/if}
                            </td>
                            <td>
                                {if $survey6.doesInsuredHaveMobileEquipment == "no"}
                                    <input id="doesInsuredHaveMobileEquipment" type="checkbox"
                                        name="doesInsuredHaveMobileEquipment" checked readonly />
                                    <label for="doesInsuredHaveMobileEquipment">N</label>
                                {else}
                                    <input id="doesInsuredHaveMobileEquipment" type="checkbox"
                                        name="doesInsuredHaveMobileEquipment" readonly />
                                    <label for="doesInsuredHaveMobileEquipment">N</label>
                                {/if}
                            </td>
                        {else}
                            <td>
                                <input id="doesInsuredHaveMobileEquipment" type="checkbox"
                                    name="doesInsuredHaveMobileEquipment" readonly />
                                <label for="doesInsuredHaveMobileEquipment">Y</label>
                                <input id="doesInsuredHaveMobileEquipment" type="checkbox"
                                    name="doesInsuredHaveMobileEquipment" readonly />
                                <label for="doesInsuredHaveMobileEquipment">N</label>
                            </td>
                        {/if}
                    </tr>
                    <tr>
                        <td>4.</td>
                        <td>Does applicant sell any product either wholesale or retail?</td>
                        {if isset($survey6['doesApplicantSellAnyProductEitherWholesaleOrRetail'])}
                            <td>
                                {if $survey6.doesApplicantSellAnyProductEitherWholesaleOrRetail == "yes"}
                                    <input id="doesApplicantSellAnyProductEitherWholesaleOrRetail" type="checkbox"
                                        name="doesApplicantSellAnyProductEitherWholesaleOrRetail" checked readonly />
                                    <label for="doesApplicantSellAnyProductEitherWholesaleOrRetail">Y</label>
                                {else}
                                    <input id="doesApplicantSellAnyProductEitherWholesaleOrRetail" type="checkbox"
                                        name="doesApplicantSellAnyProductEitherWholesaleOrRetail" readonly />
                                    <label for="doesApplicantSellAnyProductEitherWholesaleOrRetail">Y</label>
                                {/if}
                            </td>
                            <td>
                                {if $survey6.doesApplicantSellAnyProductEitherWholesaleOrRetail == "no"}
                                    <input id="doesApplicantSellAnyProductEitherWholesaleOrRetail" type="checkbox"
                                        name="doesApplicantSellAnyProductEitherWholesaleOrRetail" checked readonly />
                                    <label for="doesApplicantSellAnyProductEitherWholesaleOrRetail">N</label>
                                {else}
                                    <input id="doesApplicantSellAnyProductEitherWholesaleOrRetail" type="checkbox"
                                        name="doesApplicantSellAnyProductEitherWholesaleOrRetail" readonly />
                                    <label for="doesApplicantSellAnyProductEitherWholesaleOrRetail">N</label>
                                {/if}
                            </td>
                        {else}
                            <td>
                                <input id="doesApplicantSellAnyProductEitherWholesaleOrRetail" type="checkbox"
                                    name="doesApplicantSellAnyProductEitherWholesaleOrRetail" readonly />
                                <label for="doesApplicantSellAnyProductEitherWholesaleOrRetail">Y</label>
                                <input id="doesApplicantSellAnyProductEitherWholesaleOrRetail" type="checkbox"
                                    name="doesApplicantSellAnyProductEitherWholesaleOrRetail" readonly />
                                <label for="doesApplicantSellAnyProductEitherWholesaleOrRetail">N</label>
                            </td>
                        {/if}
                    </tr>
                    <tr>
                        <td>5.</td>
                        <td>Does insured lease mobile equipment?</td>
                        {if isset($survey6['doesInsuredLeaseMobileEquipment'])}
                            <td>
                                {if $survey6.doesInsuredLeaseMobileEquipment == "yes"}
                                    <input id="doesInsuredLeaseMobileEquipment" type="checkbox"
                                        name="doesInsuredLeaseMobileEquipment" checked readonly />
                                    <label for="doesInsuredLeaseMobileEquipment">Y</label>
                                {else}
                                    <input id="doesInsuredLeaseMobileEquipment" type="checkbox"
                                        name="doesInsuredLeaseMobileEquipment" readonly />
                                    <label for="doesInsuredLeaseMobileEquipment">Y</label>
                                {/if}
                            </td>
                            <td>
                                {if $survey6.doesInsuredLeaseMobileEquipment == "no"}
                                    <input id="doesInsuredLeaseMobileEquipment" type="checkbox"
                                        name="doesInsuredLeaseMobileEquipment" checked readonly />
                                    <label for="doesInsuredLeaseMobileEquipment">N</label>
                                {else}
                                    <input id="doesInsuredLeaseMobileEquipment" type="checkbox"
                                        name="doesInsuredLeaseMobileEquipment" readonly />
                                    <label for="doesInsuredLeaseMobileEquipment">N</label>
                                {/if}
                            </td>
                        {else}
                            <td>
                                <input id="doesInsuredLeaseMobileEquipment" type="checkbox"
                                    name="doesInsuredLeaseMobileEquipment" readonly />
                                <label for="doesInsuredLeaseMobileEquipment">Y</label>
                                <input id="doesInsuredLeaseMobileEquipment" type="checkbox"
                                    name="doesInsuredLeaseMobileEquipment" readonly />
                                <label for="doesInsuredLeaseMobileEquipment">N</label>
                            </td>
                        {/if}
                    </tr>
                    <tr>
                        <td>6.</td>
                        <td>Does applicant do any rigging?</td>
                        {if isset($survey6['doesApplicantDoAnyRigging'])}
                            <td>
                                {if $survey6.doesApplicantDoAnyRigging == "yes"}
                                    <input id="doesApplicantDoAnyRigging" type="checkbox" name="doesApplicantDoAnyRigging"
                                        checked readonly />
                                    <label for="doesApplicantDoAnyRigging">Y</label>
                                {else}
                                    <input id="doesApplicantDoAnyRigging" type="checkbox" name="doesApplicantDoAnyRigging"
                                        readonly />
                                    <label for="doesApplicantDoAnyRigging">Y</label>
                                {/if}
                            </td>
                            <td>
                                {if $survey6.doesApplicantDoAnyRigging == "no"}
                                    <input id="doesApplicantDoAnyRigging" type="checkbox" name="doesApplicantDoAnyRigging"
                                        checked readonly />
                                    <label for="doesApplicantDoAnyRigging">N</label>
                                {else}
                                    <input id="doesApplicantDoAnyRigging" type="checkbox" name="doesApplicantDoAnyRigging"
                                        readonly />
                                    <label for="doesApplicantDoAnyRigging">N</label>
                                {/if}
                            </td>
                        {else}
                            <td>
                                <input id="doesApplicantDoAnyRigging" type="checkbox" name="doesApplicantDoAnyRigging"
                                    readonly />
                                <label for="doesApplicantDoAnyRigging">Y</label>
                                <input id="doesApplicantDoAnyRigging" type="checkbox" name="doesApplicantDoAnyRigging"
                                    readonly />
                                <label for="doesApplicantDoAnyRigging">N</label>
                            </td>
                        {/if}
                    </tr>
                    <tr>
                        <td>7.</td>
                        <td>If yes to any of the above please explain:
                            {if isset($ifYesToAnyOfTheAbovePleaseExplain1)}
                                {$ifYesToAnyOfTheAbovePleaseExplain1}
                            {else}
                                <span>&nbsp;&nbsp;&nbsp;</span>
                            {/if}
                        </td>
                    </tr>
                </table>
                <p class="continued">SECTION 6: GENERAL LIABILITY, continued</p>
                <strong>Limits of coverage:</strong><br>
                
                <div class="row">
                    <div class="float-left col-60">
                        General Aggregate Limit (other than products-completed operations):
                    </div>
                    <div class="float-left underline" style="width:32%">
                        {if isset($generalAggregateLimitOtherThanProductsCompletedOperations)}
                            <span>{$generalAggregateLimitOtherThanProductsCompletedOperations}</span>
                        {else}
                            <span>&nbsp;&nbsp;&nbsp;</span>
                        {/if}
                    </div>
                </div>
                <br><br>
                <div class="row">
                    <div class="float-left col-60">
                        Product-completed Operations Aggregate Limit:
                    </div>
                    <div class="float-left underline" style="width:32%">
                        {if isset($productCompletedOperationsAggregateLimit)}
                            <span>{$productCompletedOperationsAggregateLimit}</span>
                        {else}
                            <span>&nbsp;&nbsp;&nbsp;</span>
                        {/if}
                    </div>
                </div>
                <br><br>
                <div class="row">
                    <div class="float-left col-50">
                        Personal & Advertising Injury Limit:
                    </div>
                    <div class="float-left underline" style="width:42%">
                        {if isset($personalAdvertisingInjuryLimit)}
                            <span>{$personalAdvertisingInjuryLimit}</span>
                        {else}
                            <span>&nbsp;&nbsp;&nbsp;</span>
                        {/if}
                    </div>
                </div>
                <br><br>
                <div class="row">
                    <div class="float-left col-50">
                        Each Occurrence Limit:
                    </div>
                    <div class="float-left underline" style="width:42%">
                        {if isset($eachOccurrenceLimit)}
                            <span>{$eachOccurrenceLimit}</span>
                        {else}
                            <span>&nbsp;&nbsp;&nbsp;</span>
                        {/if}
                    </div>
                </div>
                <br><br>
                <div class="row">
                    <div class="float-left col-50">
                        Fire Damage Limit:
                    </div>
                    <div class="float-left underline" style="width:42%">
                        {if isset($fireDamageLimit)}
                            <span>{$fireDamageLimit}</span>
                        {else}
                            <span>&nbsp;&nbsp;&nbsp;</span>
                        {/if}
                    </div>
                </div>
                <br><br>
                <div class="row">
                    <div class="float-left col-50">
                        Medical Expense Limit:
                    </div>
                    <div class="float-left underline" style="width:42%">
                        {if isset($medicalExpenseLimit)}
                            <span>{$medicalExpenseLimit}</span>
                        {else}
                            <span>&nbsp;&nbsp;&nbsp;</span>
                        {/if}
                    </div>
                </div>
                <br><br>
            </div>
        </section>
        {* Section 6 *}
    </body>

</html>