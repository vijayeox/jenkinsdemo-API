{assign var = 'licenseId'  value = array('AE' => '0757776','AK' => '18601','AL' => '75090','AP' => '0757776','AR' => '85796','AZ' => '381993','CA' => '0757776','CO' => '88018','CT' => '2428432','DC' => '197741','DE' => '147769','FL' => 'A273577','FM' => '0757776','GA' => '550396','GU' => '0757776','HI' => '345329','IA' => '230642','ID' => '442900','IL' => '230642','IN' => '873994','International' => '0757776','KS' => '230642','KY' => '345609','LA' => '249639','MA' => '1931743','MB' => '','MD' => '10127','ME' => 'PRN59470','MH' => '0757776','MI' => '202648','MN' => '40329255','MO' => '234899','MP' => '0757776','MS' => '9901467','MT' => '771760','NC' => '230642','ND' => '230642','NE' => '230642','NH' => '360870','NJ' => '9948948','NM' => '325128','NV' => '200636','NY' => 'EX999685-R','OH' => '671333','OK' => '185559','ON' => '','OR' => '230642','PA' => '415445','PR' => '0757776','PW' => '0757776','RI' => '1081343','SC' => '248507','SD' => '2802914','TN' => '803938','TX' => '620718','UT' => '410535','VA' => '677981','VI' => '0757776','VT' => '875974','WA' => '151305','WI' => '2612822','WV' => '230642','WY' => '241544')}

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<link href= "{$smarty.current_dir}/css/template_css.css" rel="stylesheet" type="text/css" />

</head>
<body>
	<div class ="body_div">
		<div>&nbsp</div>
		<div class = "content">
			<div class ="content1">
					<b class = "caption">Agent Information</b>
					<div class = "caption1">
						<p class ="info">Vicencia & Buckley A Division of HUB International</p>
						<p class ="info" style="margin-bottom:2px;">Insurance Services</p>
						<p class ="info">6 Centerpointe Drive, #350</p>
						<p class ="info">La Palma, CA 90623-2538</p>
						<p class ="info" style="margin-bottom:2px;">(714) 739-3177&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspFAX (714) 739-3188</p>
						<p class ="info" style="margin-bottom:2px;">(800) 223-9998</p>
						<p class = "info">License#: {$licenseId[{$state_id}]}</p>
					</div>
					<b class = "caption2">Insured's Name and Mailing Address:</b>
					<p class = "details">{$firstname},{$middlename},{$lastname}</p>
					<p class = "details">{$address1}</p>
					<p class = "details">{$address2}</p>
					<p class = "details">{$city},{$state}</p>
					<p class = "details">{$country},{$zipcode}</p>
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
					<p class = "p_margin">{$member_no}</p>
					<p class = "p_margin">{$effective_date}</p>
					<p class = "p_margin">{$expiry_date}</p>
					<p class = "p_margin">90 DAY DISCOVERY PERIOD</p>
				</div>
				<hr></hr>
				<p class = "policy">Policy issued by Tokio Marine Specialty Insurance
				Company</p>
				<p class = "policy2">Policy #: {$policy_id}</p>
				<hr></hr>
			</div>
		</div>
		<div class="spacing">&nbsp</div>
		<hr class="hrtag"></hr>
    	<div class="i_type">
      		<div class="i_type1">
        		<div class = "in-type">
		           <b class = "ins_type">Type of Insurance</b>
		           <div class = "ins_data">
			           <p class = "ins_font"><b>COVERAGE:</b></p>
			           <p class = "ins_font"><b>COMBINED SINGLE LIMIT:</b></p>
			           <p class = "ins_font"><b class = "space">ANNUAL AGGREGATE:</b></p>
		       	   </div>
	        	</div>
	        	<div class = "in-type1"> 
		            <p class = "ins_type"  style="margin-bottom: 0px;margin-left:1px;">Professional Liability - Claims Made Form</p>
		            <p class = "ins_font">Insured's Status: {$insured_status}</p>
		            <p class = "ins_font">${$single_limit}&nbsp&nbsp&nbsp(per occurrence)</p>
		            <p class = "ins_font">${$annual_aggregate}</p>
	        	</div>
	     	</div>
	     	<div class="i_type2">
		       <div class="i-type">
		           <p class = "ins_font">&nbsp</p>
		           <p class = "ins_font">&nbsp</p>
		           <p class = "ins_font"><b>Equipment Liability:</b></p>
		           <p class = "ins_font"><b>Cylinder Coverage:</b></p>
		       </div>
		       <div class="i-type">
		          <p class = "ins_font">&nbsp</p>
		          <p class = "ins_font">&nbsp</p>
		          <p class = "ins_font">{$equipment_liability}</p>
		          <p class = "ins_font">{$cylinder_coverage}</p>
		       </div>
     		</div>
    	</div>
    	
    	<hr class="hrtag"></hr>
    	<center><p class = "policy_notice1">Retroactive Date: {$effective_date}, or the first day 		of uninterrupted coverage,whichever is earlier (refer to section VI of the 			   policy). However, in the event of a claim which invokes a Retroactive Date prior 	   to {$effective_date}, the Certificate Holder must submit proof of uninterrupted 		   insurance coverage dating prior
			   to the date that the alleged negligent act, error, or omission occurred.
		</p></center>
		<hr class = "spacing1"></hr>
		<b><center><p class = "phy_add">Physical Address {if isset($physical_address)} 
										 : {$physical_address}
									  {else}
										is the same as the mailing address
									{/if}
			</p></center></b>
		<hr class="hrtag1"></hr>
		<div class = "second_content">
			{if isset($update)}
				<p class ="policy_update"><b>Endorsements & Upgrades:</b></p>
				<p class = "policy_status">Status of Insured : Instructor as of {$update_date}</p>
			{/if}

			<hr></hr>
			<p class = "policy_notice">
				The insurance afforded by this policy is a master policy issued to PADI Worldwide Corporation, 30151 Tomas Street, Rancho Santa Margarita, CA 92688. The insurance is provided under terms and conditions of the master policy which is enclosed with this certificate. Please read the policy for a full description of the terms, conditions and exclusions of the policy. This certificate does not amend, alter or extend the coverage afforded by the policy referenced on this certificate.
			</p>
			<p class = "policy_notice">
				Notice of cancelation: The premium and any taxes or fees are fully earned upon inception and no refund is granted unless cancelled by the company.If the company cancels this policy, 45 days notice will be given to the certificate holder unless cancellation is for nonpayment of premium, then 10 days notice will be provided, and any premium not earned will be returned to the certificate holder.
			</p>

			{if $state_id == 'AK'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/AK_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'AL'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/AL_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'AR'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/AR_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'AZ'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/AZ_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'CO'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/CO_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'CT'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/CT_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'DC'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/DC_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'DE'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/DE_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'FL'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/FL_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'FM'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/FM_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'GA'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/GA_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'HI'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/HI_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'IA'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/IA_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'ID'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file = "{$smarty.current_dir}/notice/ID_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'IL'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/IL_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'International'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/International_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'KS'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/KS_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'KY'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/KY_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'LA'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file = "{$smarty.current_dir}/notice/LA_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'MA'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/MA_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'MD'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/MD_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'ME'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/ME_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'MH'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/MH_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'MI'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file = "{$smarty.current_dir}/notice/MI_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'MN'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file = "{$smarty.current_dir}/notice/MN_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'MO'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/MO_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'MS'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/MS_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'MT'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/MT_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'NC'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/NC_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'ND'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/ND_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'NE'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/NE_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'NH'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/NH_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'NJ'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/NJ_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'NM'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/NM_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'NV'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/NV_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'NY'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file = "{$smarty.current_dir}/notice/NY_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'OH'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/OH_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'OK'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/OK_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'OR'}
				<center><center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/OR_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'PA'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/PA_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'PW'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/PW_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'RI'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/RI_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'SC'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/SC_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'SD'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/SD_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'TN'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/TN_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'TX'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/TX_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'UT'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/UT_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'VA'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/VA_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'VT'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file = "{$smarty.current_dir}/notice/VT_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'WA'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/WA_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'WI'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/WI_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'WV'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/WV_Notice.tpl"}</b>
				</p></center>
			{elseif $state_id == 'WY'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/notice/WY_Notice.tpl"}</b>
				</p></center>
			{/if}
		</div>
	</div>
</body>
</html>

