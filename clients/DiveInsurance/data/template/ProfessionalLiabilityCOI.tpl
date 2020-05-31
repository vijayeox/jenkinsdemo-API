<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<link href= "{$smarty.current_dir}/css/template_css.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="{$smarty.current_dir}/AgentInfo.js"></script>
</head>
<body onload = "agentInfo()">
	<div class ="body_div"> 
		<div>&nbsp</div>
		<div class = "content">
			<div class ="content1">
					<b class = "caption">Agent Information</b>
					<div class = "caption1">
						<p class ="info" id = "nameVal"></p>
						<p class ="info" id = "addressLineVal"></p>
						<p class ="info" id = "addressLine2Val"></p>
						<p class ="info" style="margin-bottom:2px;"><span id= "phone1Val"></span>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspFAX <span id= "faxVal"></span></p>
						<p class ="info" id = "phone2Val" style="margin-bottom:2px;"></p>
						<p class = "info">License#: {$license_number}</p>
					</div>
					<b class = "caption2">Insured's Name and Mailing Address:</b>
					<p class = "details">{$lastname},{$firstname} {if isset($initial)},{$initial}{/if}</p>
					<p class = "details">{$address1}</p>
					<p class = "details">{$address2}</p>
					<p class = "details">{$city},{$state_in_short} - {$zip}</p>
					<p class = "details">{$country}</p>
			</div>
			<div class ="content2">
				<div class = "certificate_data">
					<p class = "p_margin"><b>Certificate #:</b></p>
					<p class = "p_margin"><b>Member #:</b></p>
					<p class = "p_margin"><b>Effective Date:</b></p>
					<p class = "p_margin"><b>Expiration Date:</b></p>
				</div>
				<div class = "certificate_data1">
					<p class = "p_margin">{$certificate_no}</p>
					<p class = "p_margin">{$padi}</p>
					<p class = "p_margin">{$start_date|date_format:"%d %B %Y"}</p>
					<p class = "p_margin">{$end_date|date_format:"%d %B %Y"}&nbsp12:01:00 AM</p>
					<p class = "p_margin">90 DAY DISCOVERY PERIOD</p>
				</div>
				<hr></hr>
				<p class = "policy">Policy issued by &nbsp{$carrier}</p>
				<p class = "policy2">Policy #: {$policy_id}</p>
				<hr></hr>
			</div>
		</div>
		<div class="spacing">&nbsp</div>
		<hr class="hrtag"></hr>
		{if isset($previous_policy_data) && !empty($previous_policy_data)}
			{assign var=previousPolicyData value=$previous_policy_data|json_decode:true}
				{if isset($previousPolicyData) && !empty($previousPolicyData) && (count($previousPolicyData) > 0)}
				{assign var=policyIndex value=count($previousPolicyData) - 1}
				{assign var=careerCov value=$previousPolicyData.$policyIndex.previous_careerCoverageLabel}
				{assign var=initialSingleLimit value=$previousPolicyData.$policyIndex.prevSingleLimit}
				{assign var=initialAnnualAggregate value=$previousPolicyData.$policyIndex.prevAnnualAggregate}
				{assign var=initialEquipment value=$previousPolicyData.$policyIndex.previous_equipment}
				{assign var=initialCylinderCoverage value=$previousPolicyData.$policyIndex.previous_cylinder}
				{assign var=initialScubaFit value=$previousPolicyData.$policyIndex.previous_scubaFit}
										{else}
				{assign var=careerCov value=$careerCoverageVal}
				{assign var=previous_scubaFit value=""}
				{/if}
		{/if}
		<div>
			<div class="i_type">
				<div class="i_type1">
						<table>
							<tr>
								<th nowrap><p class = "ins_font1">Type of Insurance:</p></th>
								<td><p class = "ins_font"> Professional Liability - Claims Made Form </p></td>
							</tr>
							<tr>
								<th nowrap><b class = "ins_font">COVERAGE:</b></th>
								<td><p class = "ins_font">Insured's Status: {if isset($careerCov)}
											{$careerCov}
										{else}
											{$careerCoverageVal}
										{/if}
										{if isset($initialScubaFit)}
											{if $initialScubaFit != "scubaFitInstructorDeclined"}
											<span> and Scuba Fit Instructor </span>
											{/if}
										{else}
											{if $scubaFit != "scubaFitInstructorDeclined"} <span> and Scuba Fit Instructor </span>
											{/if}
										{/if}
										</p></td>
							</tr>
							<tr>
								<th nowrap><b class = "ins_font">COMBINED SINGLE LIMIT:</COMBINED></th>
								<td><p class = "ins_font">
								{if isset($initialSingleLimit)}
								${$initialSingleLimit|number_format}&nbsp&nbsp&nbsp(per occurrence)
								{else}
								${$single_limit|number_format}&nbsp&nbsp&nbsp(per occurrence)
								{/if}</p></td>
							</tr>
							<tr>
								<th nowrap><b class = "ins_font">ANNUAL AGGREGATE:</b></th>
								<td><p class = "ins_font">
								{if isset($initialAnnualAggregate)}
								${$initialAnnualAggregate|number_format}
								{else}
								${$annual_aggregate|number_format}
								{/if}</p></td>
							</tr>
						</table>
				</div>
				<div class="i_type2 marginTop">
						<table>
							<tr>
								<th nowrap><p class = "ins_font"><b>Equipment Liability:</b></p></th>
								<td><p class = "ins_font">
								{if isset($initialEquipment)}
									{if $initialEquipment != "equipmentLiabilityCoverageDeclined"}
											Included
										{else}
											Not Included
										{/if}
								{else}
									{if $equipment != "equipmentLiabilityCoverageDeclined"}
											Included
										{else}
											Not Included
										{/if}
								{/if}
									</p>
								</td>
							</tr>
							<tr>
								<th nowrap><p class = "ins_font"><b>Cylinder Coverage:</b></p></th>
								<td><p class = "ins_font">
								{if isset($initialCylinderCoverage)}
									{if $initialCylinderCoverage != "cylinderInspectorOrInstructorDeclined"}
											{$cylinderPriceVal}
										{else}
											Not Covered
										{/if}
								{else}
									{if $cylinder != "cylinderInspectorOrInstructorDeclined"}
											{$cylinderPriceVal}
										{else}
											Not Covered
										{/if}
								{/if}
									</p>
								</td>
							</tr>
						</table>
				</div>
			</div>
    	
			<div class="policy_notice_div">
				<hr class="hrtag"></hr>
					<center><p class = "policy_notice1">Retroactive Date: {$start_date}, or the first day 		of uninterrupted coverage,whichever is earlier (refer to section VI of the 			   policy). However, in the event of a claim which invokes a Retroactive Date prior 	   to {$start_date}, the Certificate Holder must submit proof of uninterrupted 		   insurance coverage dating prior
						to the date that the alleged negligent act, error, or omission occurred.
					</p></center>
					<hr class = "spacing1"></hr>
					<b><center><p class = "phy_add">Physical Address {if ($sameasmailingaddress == "false" || $sameasmailingaddress == false) && (isset($mailaddress1) && $mailaddress1 != "") }
													: {$mailaddress1}
													{if isset($mailaddress2) && !empty($mailaddress2)}
													,{$mailaddress2}
													{/if}
												{else}
													is the same as the mailing address
												{/if}
						</p></center></b>
				<hr class="hrtag1"></hr>
			</div>
		</div>
		<div class = "second_content">
			{if isset($update_date)}
				<p class ="policy_update"><b>Endorsements & Upgrades:</b></p>	
				{if isset($previousPolicyData) && !empty($previousPolicyData)}

					{foreach from=$previousPolicyData item=$upgradeData}
						{if isset($upgradeData.careerCoverageName)}
							<p class = "policy_status">
	                            Status of Insured:  {$upgradeData.careerCoverageName} as of {$upgradeData.update_date|date_format:"%m/%d/%Y"}
	                        </p>
	                    {/if}

	                    {if isset($upgradeData.upgraded_single_limit)}
							<p class = "policy_status">
	                            Liability Limits :  ${$upgradeData.upgraded_single_limit|number_format} Combined and ${$upgradeData.upgraded_annual_aggregate|number_format} Annual Aggregate as of {$upgradeData.update_date|date_format:"%m/%d/%Y"}
	                        </p>
	                    {/if}

	                    {if isset($upgradeData.scubaCoverageName)}
							<p class = "policy_status">ScubaFit Instructor: Added to this certificate as of {$upgradeData.update_date|date_format:"%m/%d/%Y"}</p>
	                    {/if}

	                    {if isset($upgradeData.equipmentCoverageName)}
							<p class = "policy_status">Equipment Liability: Added to this certificate as of {$upgradeData.update_date|date_format:"%m/%d/%Y"}</p>
	                    {/if}


	                    {if isset($upgradeData.cylinderCoverageName)}
							< <p class = "policy_status">
	                            Cylinder Coverage :  {$upgradeData.cylinderCoverageName} as of {$upgradeData.update_date|date_format:"%m/%d/%Y"}
	                        </p>
	                    {/if}

	                    {if isset($upgradeData.tecRecCoverageName)}
							<p class = "policy_status">
	                            Status of Insured:  {$upgradeData.tecRecCoverageName} as of {$upgradeData.update_date|date_format:"%m/%d/%Y"}
	                        </p>
	                    {/if}
					{/foreach}
				{/if}
			{/if}
				
			<hr></hr>
			<p class = "policy_notice">
				The insurance afforded by this policy is a master policy issued to PADI Worldwide Corporation, 30151 Tomas Street, Rancho Santa Margarita, CA 92688. The insurance is provided under terms and conditions of the master policy which is enclosed with this certificate. Please read the policy for a full description of the terms, conditions and exclusions of the policy. This certificate does not amend, alter or extend the coverage afforded by the policy referenced on this certificate.
			</p>
			<p class = "policy_notice">
				Notice of cancelation: The premium and any taxes or fees are fully earned upon inception and no refund is granted unless cancelled by the company.If the company cancels this policy, 45 days notice will be given to the certificate holder unless cancellation is for nonpayment of premium, then 10 days notice will be provided, and any premium not earned will be returned to the certificate holder.
			</p>


			{if $state == 'Alaska'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/AK.tpl"}</b>
				</p></center>
			{elseif $state == 'Alabama'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/AL.tpl"}</b>
				</p></center>
			{elseif $state == 'Arkansas'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/AR.tpl"}</b>
				</p></center>
			{elseif $state == 'Arizona'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/AZ.tpl"}</b>
				</p></center>
			{elseif $state == 'Colorado'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/CO.tpl"}</b>
				</p></center>
			{elseif $state == 'Connecticut'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/CT.tpl"}</b>
				</p></center>
			{elseif $state == 'District of Columbia'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/DC.tpl"}</b>
				</p></center>
			{elseif $state == 'Delaware'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/DE.tpl"}</b>
				</p></center>
			{elseif $state == 'Florida'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/FL.tpl"}</b>
				</p></center>
			{elseif $state == 'Micronesia'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/FM.tpl"}</b>
				</p></center>
			{elseif $state == 'Georgia'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/GA.tpl"}</b>
				</p></center>
			{elseif $state == 'Hawaii'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/HI.tpl"}</b>
				</p></center>
			{elseif $state == 'Iowa'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/IA.tpl"}</b>
				</p></center>
			{elseif $state == 'Idaho'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/ID.tpl"}</b>
				</p></center>
			{elseif $state == 'Illinois'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/IL.tpl"}</b>
				</p></center>
			{elseif $state == '' || !isset($state)}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/International.tpl"}</b>
				</p></center>
			{elseif $state == 'Kansas'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/KS.tpl"}</b>
				</p></center>
			{elseif $state == 'Kentucky'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/KY.tpl"}</b>
				</p></center>
			{elseif $state == 'Louisiana'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/LA.tpl"}</b>
				</p></center>
			{elseif $state == 'Massachusetts'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/MA.tpl"}</b>
				</p></center>
			{elseif $state == 'Maryland'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/MD.tpl"}</b>
				</p></center>
			{elseif $state == 'Maine'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/ME.tpl"}</b>
				</p></center>
			{elseif $state == 'Marshall Islands'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/MH.tpl"}</b>
				</p></center>
			{elseif $state == 'Michigan'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/MI.tpl"}</b>
				</p></center>
			{elseif $state == 'Minnesota'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/MN.tpl"}</b>
				</p></center>
			{elseif $state == 'Missouri'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/MO.tpl"}</b>
				</p></center>
			{elseif $state == 'Mississippi'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/MS.tpl"}</b>
				</p></center>
			{elseif $state == 'Montana'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/MT.tpl"}</b>
				</p></center>
			{elseif $state == 'North Carolina'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/NC.tpl"}</b>
				</p></center>
			{elseif $state == 'North Dakota'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/ND.tpl"}</b>
				</p></center>
			{elseif $state == 'Nebraska'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/NE.tpl"}</b>
				</p></center>
			{elseif $state == 'New Hampshire'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/NH.tpl"}</b>
				</p></center>
			{elseif $state == 'New Jersey'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/NJ.tpl"}</b>
				</p></center>
			{elseif $state == 'New Mexico'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/NM.tpl"}</b>
				</p></center>
			{elseif $state == 'Nevada'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/NV.tpl"}</b>
				</p></center>
			{elseif $state == 'New York'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/NY.tpl"}</b>
				</p></center>
			{elseif $state == 'Ohio'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/OH.tpl"}</b>
				</p></center>
			{elseif $state == 'Oklahoma'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/OK.tpl"}</b>
				</p></center>
			{elseif $state == 'Oregon'}
				<center><center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/OR.tpl"}</b>
				</p></center>
			{elseif $state == 'Pennsylvania'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/PA.tpl"}</b>
				</p></center>
			{elseif $state == 'Palau'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/PW.tpl"}</b>
				</p></center>
			{elseif $state == 'Rhode Island'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/RI.tpl"}</b>
				</p></center>
			{elseif $state == 'South Carolina'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/SC.tpl"}</b>
				</p></center>
			{elseif $state == 'South Dakota'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/SD.tpl"}</b>
				</p></center>
			{elseif $state == 'Tennessee'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/TN.tpl"}</b>
				</p></center>
			{elseif $state == 'Texas'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/TX.tpl"}</b>
				</p></center>
			{elseif $state == 'Utah'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/v/UT.tpl"}</b>
				</p></center>
			{elseif $state == 'Virginia'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/VA.tpl"}</b>
				</p></center>
			{elseif $state == 'Virgin Islands'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/VT.tpl"}</b>
				</p></center>
			{elseif $state == 'Washington'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/WA.tpl"}</b>
				</p></center>
			{elseif $state == 'Wisconsin'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/WI.tpl"}</b>
				</p></center>
			{elseif $state == 'West Virginias'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/WV.tpl"}</b>
				</p></center>
			{elseif $state == 'Wyoming'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/WY.tpl"}</b>
				</p></center>
			{/if}
		</div>
	</div>
</body>
</html>

