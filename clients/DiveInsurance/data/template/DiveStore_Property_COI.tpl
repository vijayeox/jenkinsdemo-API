<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<link href= "{$smarty.current_dir}/css/divestemplate_css.css" rel="stylesheet" type="text/css" />

</head>
<body>
	<div class ="body_div">
	  <hr class = "s_hrtag"></hr>
      <center><b>Store Location:&nbsp<span class ="store_location"> {$address1},{$address2},{$city},{$state},{$country},{$zip}</span></b></center>
      <hr class = "s_hrtag"></hr>
      <br/>
		<div class="section_col">
			<div class ="sec1">
				<p class ="title">Propery Coverages</p>
			</div>
			<div class ="sec2">
				<p class ="title">Limits</p>
			</div>
		</div>

			<div class = "sec_content">
				<div class = "sec3">
					<p class = "sec_title">Contents Limit:</p>
					<p class = "sec_title">&nbsp&nbsp&nbsp&nbsp(Sign limited to : $25,000)</p>
					<p class = "sec_title">Business Income:</p>
					<p class = "sec_title">Building Coverage:</p>
					<p class = "sec_title">Equipment Breakdown: (Included in Contents Limit)</p>
					<p class = "sec_title">Business Income from dependant properties:</p>
					<p class = "sec_title">Robbery (per Occurrence - Inside):</p>
					<p class = "sec_title">Robbery (per Occurrence - Outside):</p>
					<p class = "sec_title">Transit Coverage (Locked Vehicle):</p>
					<p class = "sec_title">Employee Theft Limit:</p>
					<p class = "sec_title">Property of Others:</p>
					<p class = "sec_title">Off premises:</p>
					<p class = "sec_title">Glass:</p>
				</div>
				<b><div class = "sec4">
					<p class = "sec_title2">
						${$dspropTotal|number_format}
					</p>
					<p></p>
					<p class = "sec_title2">                        
						{if $additionalLossofBusinessIncomePL != "false"}
                            ${$lossOfBusIncome|number_format}
                        {else}
                            $0
                        {/if}</p>
					<p class = "sec_title2">
						{if isset($dspropreplacementvalue) && $dspropownbuilding == "yes"}
                            ${$dspropreplacementvalue|number_format}
                        {else}
                            $0
                        {/if}
					</p>
					<p class = "sec_title2">
						{if isset($dspropFurniturefixturesandequip) && (int)$dspropFurniturefixturesandequip != 0}
                            <td>Included</td>
                        {else}
                            <td>Not Included</td>
                        {/if}
					</p>
					<p class = "sec_title2">$5,000</p>
					<p class = "sec_title2">$2,500</p>
					<p class = "sec_title2">$2,500</p>
					<p class = "sec_title2">$10,000</p>
					<p class = "sec_title2">$5,000</p>
					<p class = "sec_title2">$25,000</p>
					<p class = "sec_title2">$10,000</p>
					<p class = "sec_title2">$5,000</p>
				</div></b>
			</div>
			<div class="spacing1">&nbsp</div>
			<div class="deduct"><b>Deductible:</b><br/>
				<p class ="deduct1">Wind/Hail is 5% of Insured Values per location, $5000 minimum, for Florida, Hawaii, Puerto Rico, USVI, Guam and all Tier 1
locations (coastal Counties) in Texas, Louisiana, Mississippi, Alabama, Georgia, South Carolina, North Carolina and all Harris
County Texas locations. Mechanical breakdown is $2500. All other perils is $1000.</p></div>
		<hr></hr>
		<b><center><p class="addIn">{if !empty($lossPayees)}
			Certificate has Loss Payees (See Attached)
			{else}
			Certificate Does Not Have Loss Payees.
			{/if}</p></center></b>
		<hr></hr>

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
				<hr class="hr_nw"></hr>
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
					<b>{include file = "{$smarty.current_dir}/v/UT.tpl"}</b>
				</p></center>
			{elseif $state == 'Virginia'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/SurplusLines/DiveStore/{$surplusLineYear}/VA.tpl"}</b>
				</p></center>
			{elseif $state == 'Virgin Islands'}
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

