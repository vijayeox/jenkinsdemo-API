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
			    		<p class = "grp_endoUpgrade">
			    			Effective {$upgradeData.update_date} : The Liability Limit are ${$upgradeData.combinedSingleLimit|number_format} per occurance and ${$upgradeData.annualAggregate|number_format} Annual Aggregate.
			    		</p>
		    {/foreach}
		{/if}
	{/if}
	<hr class = "spacing1"></hr>
	<div class = "second_content">
			<p class = "grppolicy_notice">
				The insurance afforded by this policy is a master policy issued to PADI Worldwide Corporation, 30151 Tomas Street, Rancho Santa Margarita, CA 92688. The insurance is provided under terms and conditions of the master policy which is enclosed with this certificate. Please read the policy for a full description of the terms, conditions and exclusions of the policy. This certificate does not amend, alter or extend the coverage afforded by the policy referenced on this certificate.
			</p>
			<p class = "grppolicy_notice">
				Notice of cancelation: The premium and any taxes or fees are fully earned upon inception and no refund is granted unless cancelled by the company.If the company cancels this policy, 45 days notice will be given to the certificate holder unless cancellation is for nonpayment of premium, then 10 days notice will be provided, and any premium not earned will be returned to the certificate holder.
			</p>
		<hr class = "spacing1"></hr>
		{if $business_state == 'Alaska'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/{$surplusLineYear}/SurplusLines/Group/{$surplusLineYear}/AK.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Alabama'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/{$surplusLineYear}/SurplusLines/Group/{$surplusLineYear}/AL.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Arkansas'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/{$surplusLineYear}/SurplusLines/Group/{$surplusLineYear}/AR.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Arizona'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/{$surplusLineYear}/SurplusLines/Group/{$surplusLineYear}/AZ.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Colorado'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/{$surplusLineYear}/SurplusLines/Group/{$surplusLineYear}/CO.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Connecticut'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/{$surplusLineYear}/SurplusLines/Group/{$surplusLineYear}/CT.tpl"}</b>
				</p></center>
			{elseif $business_state == 'District of Columbia'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/{$surplusLineYear}/SurplusLines/Group/{$surplusLineYear}/DC.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Delaware'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/{$surplusLineYear}/SurplusLines/Group/{$surplusLineYear}/DE.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Florida'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/{$surplusLineYear}/SurplusLines/Group/{$surplusLineYear}/FL.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Micronesia'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/{$surplusLineYear}/SurplusLines/Group/{$surplusLineYear}/FM.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Georgia'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/{$surplusLineYear}/SurplusLines/Group/{$surplusLineYear}/GA.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Hawaii'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/{$surplusLineYear}/SurplusLines/Group/{$surplusLineYear}/HI.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Iowa'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/{$surplusLineYear}/SurplusLines/Group/{$surplusLineYear}/IA.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Idaho'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/{$surplusLineYear}/SurplusLines/Group/{$surplusLineYear}/ID.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Illinois'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/{$surplusLineYear}/SurplusLines/Group/{$surplusLineYear}/IL.tpl"}</b>
				</p></center>
			{elseif $business_state == 'International'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/{$surplusLineYear}/SurplusLines/Group/{$surplusLineYear}/International.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Kansas'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/{$surplusLineYear}/SurplusLines/Group/{$surplusLineYear}/KS.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Kentucky'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/{$surplusLineYear}/SurplusLines/Group/{$surplusLineYear}/KY.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Louisiana'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/{$surplusLineYear}/SurplusLines/Group/{$surplusLineYear}/LA.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Massachusetts'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/{$surplusLineYear}/SurplusLines/Group/{$surplusLineYear}/MA.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Maryland'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/Group/{$surplusLineYear}/MD.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Maine'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/Group/{$surplusLineYear}/ME.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Marshall Islands'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/Group/{$surplusLineYear}/MH.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Michigan'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/Group/{$surplusLineYear}/MI.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Minnesota'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/Group/{$surplusLineYear}/MN.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Missouri'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/Group/{$surplusLineYear}/MO.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Mississippi'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/Group/{$surplusLineYear}/MS.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Montana'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/Group/{$surplusLineYear}/MT.tpl"}</b>
				</p></center>
			{elseif $business_state == 'North Carolina'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/Group/{$surplusLineYear}/NC.tpl"}</b>
				</p></center>
			{elseif $business_state == 'North Dakota'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/Group/{$surplusLineYear}/ND.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Nebraska'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/Group/{$surplusLineYear}/NE.tpl"}</b>
				</p></center>
			{elseif $business_state == 'New Hampshire'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/Group/{$surplusLineYear}/NH.tpl"}</b>
				</p></center>
			{elseif $business_state == 'New Jersey'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/Group/{$surplusLineYear}/NJ.tpl"}</b>
				</p></center>
			{elseif $business_state == 'New Mexico'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/Group/{$surplusLineYear}/NM.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Nevada'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/Group/{$surplusLineYear}/NV.tpl"}</b>
				</p></center>
			{elseif $business_state == 'New York'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/Group/{$surplusLineYear}/NY.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Ohio'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/Group/{$surplusLineYear}/OH.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Oklahoma'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/Group/{$surplusLineYear}/OK.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Oregon'}
				<center><center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/Group/{$surplusLineYear}/OR.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Pennsylvania'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/Group/{$surplusLineYear}/PA.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Palau'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/Group/{$surplusLineYear}/PW.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Rhode Island'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/Group/{$surplusLineYear}/RI.tpl"}</b>
				</p></center>
			{elseif $business_state == 'South Carolina'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/Group/{$surplusLineYear}/SC.tpl"}</b>
				</p></center>
			{elseif $business_state == 'South Dakota'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/Group/{$surplusLineYear}/SD.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Tennessee'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/Group/{$surplusLineYear}/TN.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Texas'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/Group/{$surplusLineYear}/TX.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Utah'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/Group/{$surplusLineYear}/UT.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Virginia'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/Group/{$surplusLineYear}/VA.tpl"}</b>
				</p></center>
			{elseif $state == 'Virgin Islands'}
				<center><p class = "notice">both of 
					<b>{include file ="{$smarty.current_dir}/SurplusLines/IPL/{$surplusLineYear}/VI.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Vermont'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/Group/{$surplusLineYear}/VT.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Washington'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/Group/{$surplusLineYear}/WA.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Wisconsin'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/Group/{$surplusLineYear}/WI.tpl"}</b>
				</p></center>
			{elseif $business_state == 'West Virginias'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/Group/{$surplusLineYear}/WV.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Wyoming'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/Group/{$surplusLineYear}/WY.tpl"}</b>
				</p></center>
			{/if}
	</div>
	</div>
</body>
</html>

