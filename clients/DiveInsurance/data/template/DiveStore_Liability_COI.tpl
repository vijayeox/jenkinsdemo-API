<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<link href= "{$smarty.current_dir}/css/divestemplate_css.css" rel="stylesheet" type="text/css" />

</head>
<body>
	<div class ="body_div">
	  <hr class = "s_hrtag"></hr>
      <center><b>Store Location:&nbsp
      	{if isset($sameasmailingaddress) && ($sameasmailingaddress == false||$sameasmailingaddress === "false" || $sameasmailingaddress == 0)}
              <span class="store_location">{$mailaddress1}</span>
              <span class="store_location">{if $mailaddress2 != ""},{$mailaddress2}{/if}</span>,
              <span class="store_location">{$physical_city}</span>,
              <span class="store_location">{if $physical_state != '[]'}{$physical_state}{/if}</span> <span class="store_location">{$physical_zip}</span>
        {else}
              <span class="store_location">{$address1}</span>,
              <span class="store_location">{if $address2 != ""},{$address2}{/if}</span>,
              <span class="store_location">{$city}</span>,
              <span class="store_location">{$state}</span> <span class="store_location">{$zip}</span>
        {/if}
      </b></center>
      <hr class = "s_hrtag"></hr>
      <br/>
		<div class="section_col">
			<div class ="sec1">
				<p class ="title">SECTION I: LIABILITY COVERAGES</p>
			</div>
			<div class ="sec2">
				<p class ="title">Limits</p>
			</div>
		</div>

			<div class = "sec_content">
				<div class = "sec3">
					<p class = "sec_title">Commercial General Liability (Each Occurrence Limit):</p>
					<p class = "sec_title">Personal Injury (per Occurrence):</p>
					<p class = "sec_title">General Liability Aggregate:</p>
					<p class = "sec_title">Products and Completed Operations Aggregate:</p>
					<p class = "sec_title">Damage to premises rented to you:</p>
					<p class = "sec_title">Medical Expense:</p>
					<p class = "sec_title">NON-Owned Auto:</p>
					<p class = "sec_title">NON-Diving Pool Use:</p>
					<p class = "sec_title">Travel Agent E&O (Each wrongful act & Aggregate):</p>
					<p class = "sec_title">&nbsp&nbsp&nbsp&nbsp(Claims made form)</p>
				</div>
				<b><div class = "sec4">
					<p class = "sec_title2">
                        {if $excessLiabilityCoverage == "excessLiabilityCoverage1M" && $excessLiabilityCoveragePrimarylimit1000000PL == true}
                            $2,000,000
                        {elseif $excessLiabilityCoverage == "excessLiabilityCoverage2M" && $excessLiabilityCoveragePrimarylimit1000000PL == true}
                            $3,000,000
                        {elseif $excessLiabilityCoverage == "excessLiabilityCoverage3M" && $excessLiabilityCoveragePrimarylimit1000000PL == true}
                            $4,000,000
                        {elseif $excessLiabilityCoverage == "excessLiabilityCoverage4M" && $excessLiabilityCoveragePrimarylimit1000000PL == true}
                            $5,000,000
                        {elseif $excessLiabilityCoverage == "excessLiabilityCoverage9M" && $excessLiabilityCoveragePrimarylimit1000000PL == true}
                            $10,000,000
                        {else}
                            $1,000,000
                        {/if}
					</p>
					<p class = "sec_title2">
                        {if $excessLiabilityCoverage == "excessLiabilityCoverage1M" && $excessLiabilityCoveragePrimarylimit1000000PL == true}
                            $2,000,000
                        {elseif $excessLiabilityCoverage == "excessLiabilityCoverage2M" && $excessLiabilityCoveragePrimarylimit1000000PL == true}
                            $3,000,000
                        {elseif $excessLiabilityCoverage == "excessLiabilityCoverage3M" && $excessLiabilityCoveragePrimarylimit1000000PL == true}
                        	$4,000,000
                        {elseif $excessLiabilityCoverage == "excessLiabilityCoverage4M" && $excessLiabilityCoveragePrimarylimit1000000PL == true}
                            $5,000,000
                        {elseif $excessLiabilityCoverage == "excessLiabilityCoverage9M" && $excessLiabilityCoveragePrimarylimit1000000PL == true}
                            $10,000,000
                        {else}
                            $1,000,000
                        {/if}
					</p>
					<p class = "sec_title2">
                        {if $excessLiabilityCoverage == "excessLiabilityCoverage1M" && $excessLiabilityCoveragePrimarylimit1000000PL == true}
                            $3,000,000
                        {elseif $excessLiabilityCoverage == "excessLiabilityCoverage2M" && $excessLiabilityCoveragePrimarylimit1000000PL == true}
                            $4,000,000
                        {elseif $excessLiabilityCoverage == "excessLiabilityCoverage3M" && $excessLiabilityCoveragePrimarylimit1000000PL == true}
                            $5,000,000
                        {elseif $excessLiabilityCoverage == "excessLiabilityCoverage4M" && $excessLiabilityCoveragePrimarylimit1000000PL == true}
                            $6,000,000
                        {elseif $excessLiabilityCoverage == "excessLiabilityCoverage9M" && $excessLiabilityCoveragePrimarylimit1000000PL == true}
                            $11,000,000
                        {else}
                            $2,000,000
                        {/if}
					</p>
					<p class = "sec_title2">
                        {if $excessLiabilityCoverage == "excessLiabilityCoverage1M" && $excessLiabilityCoveragePrimarylimit1000000PL == true}
                            $3,000,000
                        {elseif $excessLiabilityCoverage == "excessLiabilityCoverage2M" && $excessLiabilityCoveragePrimarylimit1000000PL == true}
                            $4,000,000
                        {elseif $excessLiabilityCoverage == "excessLiabilityCoverage3M" && $excessLiabilityCoveragePrimarylimit1000000PL == true}
                            $5,000,000
                        {elseif $excessLiabilityCoverage == "excessLiabilityCoverage4M" && $excessLiabilityCoveragePrimarylimit1000000PL == true}
                            $6,000,000
                        {elseif $excessLiabilityCoverage == "excessLiabilityCoverage9M" && $excessLiabilityCoveragePrimarylimit1000000PL == true}
                            $11,000,000
                         {else}
                            $2,000,000
                        {/if}
					</p>
					<p class = "sec_title2">
                            $1,000,000
					</p>
					<p class = "sec_title2">
					{if isset($medicalPayment) && ($medicalPayment == "true" || $medicalPayment == true || $medicalPayment == 1) }
                            <td>$5000</td>
                        {else}
                            <td>Excluded</td>{/if}
                        </p>
					<p class = "sec_title2">
										{if isset($doYouWantToApplyForNonOwnerAuto) && $doYouWantToApplyForNonOwnerAuto == true && $doYouWantToApplyForNonOwnerAuto == "true"}
										{if $nonOwnedAutoLiabilityPL == "nonOwnedAutoLiability100K"}
												<td>$100,000</td>
										{else if $nonOwnedAutoLiabilityPL == "nonOwnedAutoLiability1M"}
												<td>$1,000,000</td>
										{else}
												<td>Excluded</td>
										{/if}
										{else}
												<td>Excluded</td>
												{/if}
					</p>
					<p class = "sec_title2">
					    {if isset($nonDivingPoolAmount) && (int)$nonDivingPoolAmount > 0}
                            <td>$1,000,000</td>
                        {else}
                            <td>Excluded</td>
                        {/if}
					</p>
					<p class = "sec_title2">
						{if isset($travelAgentEoPL) && ($travelAgentEoPL === "true" || $travelAgentEoPL == true || $travelAgentEoPL == 1)}
								<td>$1,000,000</td>
						{else}
								<td>Excluded</td>
						{/if}
					</p>
				</div></b>
			</div>

		<div class="spacing">&nbsp</div>

		{if isset(excludedOperation) && $excludedOperation != ""}
			<div class="section_col1" style="border-style: solid">
				<p class ="title">SECTION II: EXCLUDED DESIGNATED OPERATION</p>
			</div>
			<p class ="exop" style="margin-top:1px;font-size: 15px;">{$excludedOperation}</p>
		{/if}

		<hr class="addIn"></hr>
		<b><center><p class="addIn">{if $additional_insured_select == "addAdditionalInsureds"}
			Certificate has Additional Insureds (See Attached)
			{else}
			Certificate Does Not Have Additional Insured.
			{/if}</p></center></b>
		<hr class="addIn"></hr>

		<p  class = "policy_notice">The insurance afforded by this policy is a master policy issued to PADI Worldwide Corporation, 30151 Tomas Street, Rancho Santa Margarita, CA 92688.
The insurance is provided under terms and conditions of the master policy which is enclosed with this certificate. Please read the policy for a full description
of the terms, conditions and exclusions of the policy. This certificate does not amend, alter or extend the coverage afforded by the policy referenced on this
certificate.</p>

<p  class = "policy_notice">Notice of cancelation: If the company cancels this policy, 45 days notice will be given to the certificate holder unless cancellation is for nonpayment of
premium, then 10 days notice will be provided, and any premium not earned will be returned to the certificate holder.</p>

{if $state == 'Alaska'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/AK.tpl"}</b>
				</p></center>
			{elseif $state == 'Alabama'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/AL.tpl"}</b>
				</p></center>
			{elseif $state == 'Arkansas'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/AR.tpl"}</b>
				</p></center>
			{elseif $state == 'Arizona'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/AZ.tpl"}</b>
				</p></center>
			{elseif $state == 'Colorado'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/CO.tpl"}</b>
				</p></center>
			{elseif $state == 'Connecticut'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/CT.tpl"}</b>
				</p></center>
			{elseif $state == 'District of Columbia'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/DC.tpl"}</b>
				</p></center>
			{elseif $state == 'Delaware'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/DE.tpl"}</b>
				</p></center>
			{elseif $state == 'Florida'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/FL.tpl"}</b>
				</p></center>
			{elseif $state == 'Micronesia'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/FM.tpl"}</b>
				</p></center>
			{elseif $state == 'Georgia'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/GA.tpl"}</b>
				</p></center>
			{elseif $state == 'Hawaii'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/HI.tpl"}</b>
				</p></center>
			{elseif $state == 'Iowa'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/IA.tpl"}</b>
				</p></center>
			{elseif $state == 'Idaho'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/ID.tpl"}</b>
				</p></center>
			{elseif $state == 'Illinois'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/IL.tpl"}</b>
				</p></center>
			{elseif $state == 'International'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/International.tpl"}</b>
				</p></center>
			{elseif $state == 'Kansas'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/KS.tpl"}</b>
				</p></center>
			{elseif $state == 'Kentucky'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/KY.tpl"}</b>
				</p></center>
			{elseif $state == 'Louisiana'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/LA.tpl"}</b>
				</p></center>
			{elseif $state == 'Massachusetts'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/MA.tpl"}</b>
				</p></center>
			{elseif $state == 'Maryland'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/MD.tpl"}</b>
				</p></center>
			{elseif $state == 'Maine'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/ME.tpl"}</b>
				</p></center>
			{elseif $state == 'Marshall Islands'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/MH.tpl"}</b>
				</p></center>
			{elseif $state == 'Michigan'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/MI.tpl"}</b>
				</p></center>
			{elseif $state == 'Minnesota'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/MN.tpl"}</b>
				</p></center>
			{elseif $state == 'Missouri'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/MO.tpl"}</b>
				</p></center>
			{elseif $state == 'Mississippi'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/MS.tpl"}</b>
				</p></center>
			{elseif $state == 'Montana'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/MT.tpl"}</b>
				</p></center>
			{elseif $state == 'North Carolina'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/NC.tpl"}</b>
				</p></center>
			{elseif $state == 'North Dakota'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/ND.tpl"}</b>
				</p></center>
			{elseif $state == 'Nebraska'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/NE.tpl"}</b>
				</p></center>
			{elseif $state == 'New Hampshire'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/NH.tpl"}</b>
				</p></center>
			{elseif $state == 'New Jersey'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/NJ.tpl"}</b>
				</p></center>
			{elseif $state == 'New Mexico'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/NM.tpl"}</b>
				</p></center>
			{elseif $state == 'Nevada'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/NV.tpl"}</b>
				</p></center>
			{elseif $state == 'New York'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/NY.tpl"}</b>
				</p></center>
			{elseif $state == 'Ohio'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/OH.tpl"}</b>
				</p></center>
			{elseif $state == 'Oklahoma'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/OK.tpl"}</b>
				</p></center>
			{elseif $state == 'Oregon'}
				<center><center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/OR.tpl"}</b>
				</p></center>
			{elseif $state == 'Pennsylvania'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/PA.tpl"}</b>
				</p></center>
			{elseif $state == 'Palau'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/PW.tpl"}</b>
				</p></center>
			{elseif $state == 'Rhode Island'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/RI.tpl"}</b>
				</p></center>
			{elseif $state == 'South Carolina'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/SC.tpl"}</b>
				</p></center>
			{elseif $state == 'South Dakota'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/SD.tpl"}</b>
				</p></center>
			{elseif $state == 'Tennessee'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/TN.tpl"}</b>
				</p></center>
			{elseif $state == 'Texas'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/TX.tpl"}</b>
				</p></center>
			{elseif $state == 'Utah'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/UT.tpl"}</b>
				</p></center>
			{elseif $state == 'Virginia'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/VA.tpl"}</b>
				</p></center>
			{elseif $state == 'Virgin Islands'}
				<center><p class = "notice">both of
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/VI.tpl"}</b>
				</p></center>
			{elseif $state == 'Vermont'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/VT.tpl"}</b>
				</p></center>
			{elseif $state == 'Washington'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/WA.tpl"}</b>
				</p></center>
			{elseif $state == 'Wisconsin'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/WI.tpl"}</b>
				</p></center>
			{elseif $state == 'West Virginias'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/WV.tpl"}</b>
				</p></center>
			{elseif $state == 'Wyoming'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/WY.tpl"}</b>
				</p></center>
			{/if}
	</div>
</body>
</html>