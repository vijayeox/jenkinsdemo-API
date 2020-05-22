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
				{assign var=careerCov value=$previousPolicyData[0].previous_careerCoverageLabel}
				{assign var=previous_scubaFit value=$previousPolicyData[0].previous_scubaFit}
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
										{/if}{if $scubaFit != "scubaFitInstructorDeclined"} <span> and {$scubaFitVal} </span>{/if}</p></td>
							</tr>
							<tr>
								<th nowrap><b class = "ins_font">COMBINED SINGLE LIMIT:</COMBINED></th>
								<td><p class = "ins_font">${$single_limit|number_format}&nbsp&nbsp&nbsp(per occurrence)</p></td>
							</tr>
							<tr>
								<th nowrap><b class = "ins_font">ANNUAL AGGREGATE:</b></th>
								<td><p class = "ins_font">${$annual_aggregate|number_format}</p></td>
							</tr>
						</table>
				</div>
				<div class="i_type2 marginTop">
						<table>
							<tr>
								<th nowrap><p class = "ins_font"><b>Equipment Liability:</b></p></th>
								<td><p class = "ins_font">{if $equipment != "equipmentLiabilityCoverageDeclined"}
											Included
										{else}
											Not Included
										{/if}
									</p>
								</td>
							</tr>
							<tr>
								<th nowrap><p class = "ins_font"><b>Cylinder Coverage:</b></p></th>
								<td><p class = "ins_font">{if $cylinder != "cylinderInspectorOrInstructorDeclined"}
											{$cylinderPriceVal}
										{else}
											Not Covered
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
				{if isset($previousPolicyData) && !empty($previousPolicyData)}
				<p class ="policy_update"><b>Endorsements & Upgrades:</b></p>
				{if !empty($previousCoverage)}
				{assign var=list value=$previousCoverage|json_decode:true}
            		{foreach from=$list item=$upgradeData}
                        <p class = "policy_status">
                            Status of Insured:  {$upgradeData.careerCoverage} as of {$upgradeData.update_date}
                        </p>
            		{/foreach}
            	{/if}

				{if !empty($previousTecRec)}
				{assign var=list value=$previousTecRec|json_decode:true}
						{if isset($tecRecEndorsment) && !empty($tecRecEndorsment)}
            		{foreach from=$list item=$upgradeData}
								<p class = "policy_status">TecRec Coverage: {$upgradeData.tecRecEndorsment} as of {$upgradeData.update_date|date_format:"%m/%d/%Y"}</p>
            		{/foreach}
						{/if}
						{/if}
            	{if !empty($previousExcess)}
				{assign var=listLiability value=$previousExcess|json_decode:true}
            		{foreach from=$listLiability item=$upgradeLiabilityData}
                        <p class = "policy_status">
							{if $upgradeLiabilityData.excessLiability == '1M Excess'}
                            	Liability Limits :  $2,000,000 Combined and $3,000,000 Annual Aggregate as of {$upgradeLiabilityData.update_date}
							{elseif $upgradeLiabilityData.excessLiability == '2M Excess'}
							Liability Limits :  $3,000,000 Combined and $4,000,000 Annual Aggregate as of {$upgradeLiabilityData.update_date}
							{elseif $upgradeLiabilityData.excessLiability == '3M Excess'}
							Liability Limits :  $4,000,000 Combined and $5,000,000 Annual Aggregate as of {$upgradeLiabilityData.update_date}
							{elseif $upgradeLiabilityData.excessLiability == '4M Excess'}
							Liability Limits :  $5,000,000 Combined and $6,000,000 Annual Aggregate as of {$upgradeLiabilityData.update_date}
							{elseif $upgradeLiabilityData.excessLiability == '9M Excess'}
							Liability Limits :  $10,000,000 Combined and $11,000,000 Annual Aggregate as of {$upgradeLiabilityData.update_date}
                            {/if}
                        </p>
            		{/foreach}
            	{/if}
				{if !empty($previousEquipment)}
				{assign var=list value=$previousEquipment|json_decode:true}
						{if isset($equipment) && !empty($equipment)}
            		{foreach from=$list item=$upgradeData}
						{if $equipment != "equipmentLiabilityCoverage"}
							<p class = "policy_status">Equipment Liability: Added to this certificate as of {$upgradeData.update_date|date_format:"%m/%d/%Y"}</p>
						{/if}
            		{/foreach}
						{/if}
            	{/if}
				{if !empty($previousScubaFit)}
				{assign var=list value=$previousScubaFit|json_decode:true}
						{if isset($scubaFit) && !empty($scubaFit)}
            		{foreach from=$list item=$upgradeData}
						{if $scubaFit != "scubaFitInstructorDeclined"}
							<p class = "policy_status">ScubaFit Instructor: Added to this certificate as of {$upgradeData.update_date|date_format:"%m/%d/%Y"}</p>
						{/if}
            		{/foreach}
						{/if}
            	{/if}

						{if !empty($previousCylinder)}
				{assign var=listcylinder value=$previousCylinder|json_decode:true}
            		{foreach from=$listcylinder item=$upgradeCylinderData}
                        <p class = "policy_status">
                            Cylinder Coverage :  {$upgradeCylinderData.cylinder} as of {$upgradeCylinderData.update_date}
                        </p>
            		{/foreach}
            	{/if}
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
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/AK.tpl"}</b>
				</p></center>
			{elseif $state == 'Alabama'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/AL.tpl"}</b>
				</p></center>
			{elseif $state == 'Arkansas'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/AR.tpl"}</b>
				</p></center>
			{elseif $state == 'Arizona'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/AZ.tpl"}</b>
				</p></center>
			{elseif $state == 'Colorado'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/CO.tpl"}</b>
				</p></center>
			{elseif $state == 'Connecticut'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/CT.tpl"}</b>
				</p></center>
			{elseif $state == 'District of Columbia'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/DC.tpl"}</b>
				</p></center>
			{elseif $state == 'Delaware'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/DE.tpl"}</b>
				</p></center>
			{elseif $state == 'Florida'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/FL.tpl"}</b>
				</p></center>
			{elseif $state == 'Micronesia'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/FM.tpl"}</b>
				</p></center>
			{elseif $state == 'Georgia'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/GA.tpl"}</b>
				</p></center>
			{elseif $state == 'Hawaii'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/HI.tpl"}</b>
				</p></center>
			{elseif $state == 'Iowa'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/IA.tpl"}</b>
				</p></center>
			{elseif $state == 'Idaho'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/ID.tpl"}</b>
				</p></center>
			{elseif $state == 'Illinois'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/IL.tpl"}</b>
				</p></center>
			{elseif $state == '' || !isset($state)}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/International.tpl"}</b>
				</p></center>
			{elseif $state == 'Kansas'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/KS.tpl"}</b>
				</p></center>
			{elseif $state == 'Kentucky'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/KY.tpl"}</b>
				</p></center>
			{elseif $state == 'Louisiana'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/LA.tpl"}</b>
				</p></center>
			{elseif $state == 'Massachusetts'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/MA.tpl"}</b>
				</p></center>
			{elseif $state == 'Maryland'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/MD.tpl"}</b>
				</p></center>
			{elseif $state == 'Maine'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/ME.tpl"}</b>
				</p></center>
			{elseif $state == 'Marshall Islands'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/MH.tpl"}</b>
				</p></center>
			{elseif $state == 'Michigan'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/MI.tpl"}</b>
				</p></center>
			{elseif $state == 'Minnesota'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/MN.tpl"}</b>
				</p></center>
			{elseif $state == 'Missouri'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/MO.tpl"}</b>
				</p></center>
			{elseif $state == 'Mississippi'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/MS.tpl"}</b>
				</p></center>
			{elseif $state == 'Montana'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/MT.tpl"}</b>
				</p></center>
			{elseif $state == 'North Carolina'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/NC.tpl"}</b>
				</p></center>
			{elseif $state == 'North Dakota'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/ND.tpl"}</b>
				</p></center>
			{elseif $state == 'Nebraska'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/NE.tpl"}</b>
				</p></center>
			{elseif $state == 'New Hampshire'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/NH.tpl"}</b>
				</p></center>
			{elseif $state == 'New Jersey'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/NJ.tpl"}</b>
				</p></center>
			{elseif $state == 'New Mexico'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/NM.tpl"}</b>
				</p></center>
			{elseif $state == 'Nevada'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/NV.tpl"}</b>
				</p></center>
			{elseif $state == 'New York'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/NY.tpl"}</b>
				</p></center>
			{elseif $state == 'Ohio'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/OH.tpl"}</b>
				</p></center>
			{elseif $state == 'Oklahoma'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/OK.tpl"}</b>
				</p></center>
			{elseif $state == 'Oregon'}
				<center><center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/OR.tpl"}</b>
				</p></center>
			{elseif $state == 'Pennsylvania'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/PA.tpl"}</b>
				</p></center>
			{elseif $state == 'Palau'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/PW.tpl"}</b>
				</p></center>
			{elseif $state == 'Rhode Island'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/RI.tpl"}</b>
				</p></center>
			{elseif $state == 'South Carolina'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/SC.tpl"}</b>
				</p></center>
			{elseif $state == 'South Dakota'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/SD.tpl"}</b>
				</p></center>
			{elseif $state == 'Tennessee'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/TN.tpl"}</b>
				</p></center>
			{elseif $state == 'Texas'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/TX.tpl"}</b>
				</p></center>
			{elseif $state == 'Utah'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/v/UT.tpl"}</b>
				</p></center>
			{elseif $state == 'Virginia'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/VA.tpl"}</b>
				</p></center>
			{elseif $state == 'Virgin Islands'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/VT.tpl"}</b>
				</p></center>
			{elseif $state == 'Washington'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/WA.tpl"}</b>
				</p></center>
			{elseif $state == 'Wisconsin'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/WI.tpl"}</b>
				</p></center>
			{elseif $state == 'West Virginias'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/WV.tpl"}</b>
				</p></center>
			{elseif $state == 'Wyoming'}
				<center><p class = "notice">
					<b>{include file ="{$smarty.current_dir}/IPLSurplus/WY.tpl"}</b>
				</p></center>
			{/if}
		</div>
	</div>
</body>
</html>

