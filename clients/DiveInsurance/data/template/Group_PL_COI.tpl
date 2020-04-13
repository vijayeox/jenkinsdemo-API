<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<link href= "{$smarty.current_dir}/css/template_css.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<div>
	{if isset($upgradeStatus)}
		{if $upgradeStatus == true || $upgradeStatus == 'true' }
			{assign var=list value=$upgradeGroupLiability|json_decode:true}
			{foreach from=$list item=$upgradeData}
			    		<p class = "ai_list">
			    			Effective {$upgradeData.update_date} : The Liability Limit are ${$upgradeData.combinedSingleLimit} per occurance and ${$upgradeData.annualAggregate} Annual Aggregate.
			    		</p>
		    {/foreach}
		{/if}
	{/if}
	<div class = "second_content">
		<hr class = "spacing1"></hr>
			<p class = "grppolicy_notice">
				The insurance afforded by this policy is a master policy issued to PADI Worldwide Corporation, 30151 Tomas Street, Rancho Santa Margarita, CA 92688. The insurance is provided under terms and conditions of the master policy which is enclosed with this certificate. Please read the policy for a full description of the terms, conditions and exclusions of the policy. This certificate does not amend, alter or extend the coverage afforded by the policy referenced on this certificate.
			</p>
			<p class = "grppolicy_notice">
				Notice of cancelation: The premium and any taxes or fees are fully earned upon inception and no refund is granted unless cancelled by the company.If the company cancels this policy, 45 days notice will be given to the certificate holder unless cancellation is for nonpayment of premium, then 10 days notice will be provided, and any premium not earned will be returned to the certificate holder.
			</p>
		<hr class = "spacing1"></hr>
		{if $business_state == 'Alaska'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/AK.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Alabama'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/AL.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Arkansas'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/AR.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Arizona'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/AZ.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Colorado'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/CO.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Connecticut'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/CT.tpl"}</b>
				</p></center>
			{elseif $business_state == 'District of Columbia'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/DC.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Delaware'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/DE.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Florida'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/FL.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Micronesia'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/FM.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Georgia'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/GA.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Hawaii'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/HI.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Iowa'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/IA.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Idaho'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file = "{$smarty.current_dir}/EFR/ID.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Illinois'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/IL.tpl"}</b>
				</p></center>
			{elseif $business_state == 'International'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/International.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Kansas'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/KS.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Kentucky'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/KY.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Louisiana'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file = "{$smarty.current_dir}/EFR/LA.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Massachusetts'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/MA.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Maryland'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/MD.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Maine'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/ME.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Marshall Islands'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/MH.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Michigan'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file = "{$smarty.current_dir}/EFR/MI.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Minnesota'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file = "{$smarty.current_dir}/EFR/MN.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Missouri'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/MO.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Mississippi'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/MS.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Montana'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/MT.tpl"}</b>
				</p></center>
			{elseif $business_state == 'North Carolina'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/NC.tpl"}</b>
				</p></center>
			{elseif $business_state == 'North Dakota'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/ND.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Nebraska'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/NE.tpl"}</b>
				</p></center>
			{elseif $business_state == 'New Hampshire'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/NH.tpl"}</b>
				</p></center>
			{elseif $business_state == 'New Jersey'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/NJ.tpl"}</b>
				</p></center>
			{elseif $business_state == 'New Mexico'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/NM.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Nevada'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/NV.tpl"}</b>
				</p></center>
			{elseif $business_state == 'New York'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file = "{$smarty.current_dir}/EFR/NY.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Ohio'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/OH.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Oklahoma'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/OK.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Oregon'}
				<center><center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/OR.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Pennsylvania'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/PA.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Palau'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/PW.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Rhode Island'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/RI.tpl"}</b>
				</p></center>
			{elseif $business_state == 'South Carolina'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/SC.tpl"}</b>
				</p></center>
			{elseif $business_state == 'South Dakota'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/SD.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Tennessee'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/TN.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Texas'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/TX.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Utah'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/UT.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Virginia'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/VA.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Virgin Islands'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file = "{$smarty.current_dir}/EFR/VT.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Washington'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/WA.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Wisconsin'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/WI.tpl"}</b>
				</p></center>
			{elseif $business_state == 'West Virginias'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/WV.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Wyoming'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/EFR/WY.tpl"}</b>
				</p></center>
			{/if}
	</div>

	{if $groupProfessionalLiability == 'yes'}
		<b><p class ="grp_add">Additional Insured (See Additional Insured Endorsement on Reverse):</p></b>
		{assign var=list value=$groupPL|json_decode:true}
		{foreach from=$list item=$additional}
	    		<p class = "grpai_list">
	    			&nbsp&nbsp&nbsp{$additional.firstname}&nbsp{$additional.lastname}
	    		</p>
    		{/foreach}
	{/if}
	</div>
</body>
</html>

