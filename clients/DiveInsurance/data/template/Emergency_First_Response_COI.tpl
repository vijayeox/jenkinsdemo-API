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
				</div>
				<hr></hr>
				<p class = "policy">Policy issued by &nbsp{$carrier}</p>
				<p class = "policy2">Policy #: {$policy_id}</p>
				<hr></hr>
			</div>
		</div>
		<div class="spacing">&nbsp</div>
		<hr class="hrtag"></hr>
    	<div class="i_type">
      		<div class="i_type1">
        		<div class = "in-type" style="width: 40%">
		           <b class = "ins_type">Type of Insurance</b>
		           <div class = "ins_data" style="margin-top: 10px;">
			           <p class = "ins_font"><b>COVERAGE:</b></p>
			           <p class = "ins_font"><b>COMBINED SINGLE LIMIT:</b></p>
			           <p class = "ins_font"><b class = "space">ANNUAL AGGREGATE:</b></p>
		       	   </div>
	        	</div>

		{if isset($previous_policy_data) && !empty($previous_policy_data)}
			{assign var=previousPolicyData value=$previous_policy_data|json_decode:true}
				{if isset($previousPolicyData) && !empty($previousPolicyData) && (count($previousPolicyData) > 0)}
					{assign var=policyIndex value=count($previousPolicyData) - 1}
					{assign var=liability value=$previousPolicyData.$policyIndex.prevSingleLimit}
					{assign var=annualA value=$previousPolicyData.$policyIndex.prevAnnualAggregate}
				{/if}
		{else}
			{assign var=liability value=$single_limit} 
		    {assign var=annualA value=$annual_aggregate} 
		{/if}

	        	<div class = "in-type1" style="width: 60%"> 
		            <p class = "ins_type"  style="margin-bottom: 10px;margin-left:1px;">Professional Liability - Claim s Made Form</p>
		            	{math assign="sl" equation='x/y' x=$liability y=1000000} 
		            	{math assign="aa" equation='x/y' x=$annualA y=1000000}
			            <p class = "ins_font">Insured's Status EFR Instructor ({$sl}M/{$aa}M)</p>
			            <p class = "ins_font">${$liability|number_format}&nbsp&nbsp&nbsp(per occurence)</p>
			            <p class = "ins_font">${$annualA|number_format}</p>						
		        </div>
	     	</div>
	     	<!-- <div class="i_type2_efr">
		       &nbsp
     		</div> -->
    	</div>
		<br/>
    	
    	<hr class="hrtag_efr"></hr>
    	<center><p class = "policy_notice1">Retro Date: {$start_date}, or the first day 		of uninterrupted coverage,whichever is earlier (refer to section VI of the 			   policy). However, in the event of a claim which invokes a Retroactive Date prior 	   to {$start_date}, the Certificate Holder must submit proof of uninterrupted 		   insurance coverage dating prior
			   to the date that the alleged negligent act, error, or omission occurred.
		</p></center>
		<hr class = "spacing1"></hr>
		<div class = "second_content">
			{if isset($previousPolicyData) && !empty($previousPolicyData)}
				<p class ="policy_update"><b>Endorsements & Upgrades:</b></p>
					{foreach from=$previousPolicyData item=$upgradeData}
						{if isset($upgradeData.upgraded_single_limit)}
							<p class = "policy_status">
	                            Liability Limits :  ${$upgradeData.upgraded_single_limit|number_format} Combined and ${$upgradeData.upgraded_annual_aggregate|number_format} Annual Aggregate as of {$upgradeData.update_date|date_format:"%m/%d/%Y"}
	                        </p>
	                    {/if}
	                 {/foreach}
	        {/if}

			<hr class = "hr_efr"></hr>
			<p class = "policy_notice">
				The insurance afforded by this policy is a master policy issued to PADI Worldwide Corporation, 30151 Tomas Street, Rancho Santa Margarita, CA 92688. The insurance is provided under terms and conditions of the master policy which is enclosed with this certificate. Please read the policy for a full description of the terms, conditions and exclusions of the policy. This certificate does not amend, alter or extend the coverage afforded by the policy referenced on this certificate.
			</p>
			<p class = "policy_notice">
				Notice of cancelation: The premium and any taxes or fees are fully earned upon inception and no refund is granted unless cancelled by the company.If the company cancels this policy, 45 days notice will be given to the certificate holder unless cancellation is for nonpayment of premium, then 10 days notice will be provided, and any premium not earned will be returned to the certificate holder.
			</p>

			{if $state == 'Alaska'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/AK.tpl"}</b>
				</p></center>
			{elseif $state == 'Alabama'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/AL.tpl"}</b>
				</p></center>
			{elseif $state == 'Arkansas'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/AR.tpl"}</b>
				</p></center>
			{elseif $state == 'Arizona'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/AZ.tpl"}</b>
				</p></center>
			{elseif $state == 'Colorado'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/CO.tpl"}</b>
				</p></center>
			{elseif $state == 'Connecticut'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/CT.tpl"}</b>
				</p></center>
			{elseif $state == 'District of Columbia'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/DC.tpl"}</b>
				</p></center>
			{elseif $state == 'Delaware'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/DE.tpl"}</b>
				</p></center>
			{elseif $state == 'Florida'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/FL.tpl"}</b>
				</p></center>
			{elseif $state == 'Micronesia'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/FM.tpl"}</b>
				</p></center>
			{elseif $state == 'Georgia'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/GA.tpl"}</b>
				</p></center>
			{elseif $state == 'Hawaii'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/HI.tpl"}</b>
				</p></center>
			{elseif $state == 'Iowa'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/IA.tpl"}</b>
				</p></center>
			{elseif $state == 'Idaho'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/ID.tpl"}</b>
				</p></center>
			{elseif $state == 'Illinois'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/IL.tpl"}</b>
				</p></center>
			{elseif $state == 'International'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/International.tpl"}</b>
				</p></center>
			{elseif $state == 'Kansas'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/KS.tpl"}</b>
				</p></center>
			{elseif $state == 'Kentucky'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/KY.tpl"}</b>
				</p></center>
			{elseif $state == 'Louisiana'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/LA.tpl"}</b>
				</p></center>
			{elseif $state == 'Massachusetts'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/MA.tpl"}</b>
				</p></center>
			{elseif $state == 'Maryland'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/MD.tpl"}</b>
				</p></center>
			{elseif $state == 'Maine'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/ME.tpl"}</b>
				</p></center>
			{elseif $state == 'Marshall Islands'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/MH.tpl"}</b>
				</p></center>
			{elseif $state == 'Michigan'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/MI.tpl"}</b>
				</p></center>
			{elseif $state == 'Minnesota'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/MN.tpl"}</b>
				</p></center>
			{elseif $state == 'Missouri'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/MO.tpl"}</b>
				</p></center>
			{elseif $state == 'Mississippi'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/MS.tpl"}</b>
				</p></center>
			{elseif $state == 'Montana'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/MT.tpl"}</b>
				</p></center>
			{elseif $state == 'North Carolina'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/NC.tpl"}</b>
				</p></center>
			{elseif $state == 'North Dakota'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/ND.tpl"}</b>
				</p></center>
			{elseif $state == 'Nebraska'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/NE.tpl"}</b>
				</p></center>
			{elseif $state == 'New Hampshire'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/NH.tpl"}</b>
				</p></center>
			{elseif $state == 'New Jersey'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/NJ.tpl"}</b>
				</p></center>
			{elseif $state == 'New Mexico'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/NM.tpl"}</b>
				</p></center>
			{elseif $state == 'Nevada'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/NV.tpl"}</b>
				</p></center>
			{elseif $state == 'New York'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/NY.tpl"}</b>
				</p></center>
			{elseif $state == 'Ohio'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/OH.tpl"}</b>
				</p></center>
			{elseif $state == 'Oklahoma'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/OK.tpl"}</b>
				</p></center>
			{elseif $state == 'Oregon'}
				<center><center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/OR.tpl"}</b>
				</p></center>
			{elseif $state == 'Pennsylvania'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/PA.tpl"}</b>
				</p></center>
			{elseif $state == 'Palau'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/PW.tpl"}</b>
				</p></center>
			{elseif $state == 'Rhode Island'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/RI.tpl"}</b>
				</p></center>
			{elseif $state == 'South Carolina'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/SC.tpl"}</b>
				</p></center>
			{elseif $state == 'South Dakota'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/SD.tpl"}</b>
				</p></center>
			{elseif $state == 'Tennessee'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/TN.tpl"}</b>
				</p></center>
			{elseif $state == 'Texas'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/TX.tpl"}</b>
				</p></center>
			{elseif $state == 'Utah'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/v/UT.tpl"}</b>
				</p></center>
			{elseif $state == 'Virginia'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/VA.tpl"}</b>
				</p></center>
			{elseif $state == 'Virgin Islands'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/VT.tpl"}</b>
				</p></center>
			{elseif $state == 'Washington'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/WA.tpl"}</b>
				</p></center>
			{elseif $state == 'Wisconsin'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/WI.tpl"}</b>
				</p></center>
			{elseif $state == 'West Virginias'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/WV.tpl"}</b>
				</p></center>
			{elseif $state == 'Wyoming'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/WY.tpl"}</b>
				</p></center>
			{else}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/EFR/{$surplusLineYear}/International.tpl"}</b>
				</p></center>
			{/if}
		</div>
	</div>
</body>
</html>

