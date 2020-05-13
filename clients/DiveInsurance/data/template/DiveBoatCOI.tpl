<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<link href= "{$smarty.current_dir}/css/divebtemplate_css.css" rel="stylesheet" type="text/css" />

</head>
<body>
	 <div class ="body_div">
		<center>
      <div>
		<b><p class = "title">{if $loss_payees == 'yes'}
			CERTIFICATE HAS LOSS PAYEES (SEE ATTACHED).
		{else}
			CERTIFICATE DOES NOT HAVE LOSS PAYEES.
		{/if}</p>

		<p class = "title">{if $additional_insured_select == 'newListOfAdditionalInsureds'}
			CERTIFICATE HAS ADDITIONAL INSURED (SEE ATTACHED).
		{else}
			CERTIFICATE DOES NOT HAVE ADDITIONAL INSURED.
		{/if}</p></b>

		<p class = "title">Coverage is provided only where an Amount of Insurance or Limit of Liability is shown</p>
		</div></center>
		<hr class = "hr_title"></hr>

  <b><p class = "sec_title">SECTION A - PROPERTY INSURED</p></b>
    <div class = "secA">
        <p class = "hull"><b>Hull</b></p>
        <div class = "section1">
          <div class = "sectiona">
            <p class="hull_title">Name of Vessel: {$vessel_name}</p>
            <p class="hull_title"><span>Year Built:&nbsp&nbsp&nbsp&nbsp {$built_year}&nbsp&nbsp&nbsp</span><span>Length:&nbsp&nbsp&nbsp&nbsp{$vessel_length}&nbsp&nbsp&nbsp     </span><span>HP:&nbsp&nbsp&nbsp&nbsp{$vessel_hp}</span></p>
            <p class="hull_title">S/N: &nbsp&nbsp{$vessel_sno}</p>
          </div>
          <div class = "sectionb">
            <p class="hull_title">Hull Type: {$hull_type}</p>
            <p class="hull_title">Mfg: &nbsp&nbsp{$hull_mfg}</p>  
          </div>
        </div>
        <div class = "section_div">
          <div class = "div_section">
                  <div class="sec1">
                      <p class="hull_title">Limit of Insurance:</p>
                      <p class="hull_title">Limit of Insurance - Tender/Dinghy:</p>
                  </div>
                  <div class="sec2"><p class="hull_title">US</p><p class="hull_title">US</p></div>
                  <div class="sec3">
                  	<p class="value_align">{if isset($HullPremium) && $HullPremium != '0.00'}
                              ${$HullPremium|number_format}
                              {else}
                              N/A
                            {/if}</p>
                   <p class="value_align">{if isset($DingyTenderPremium) && $DingyTenderPremium != '0.00'}
                              ${$DingyTenderPremium|number_format}
                              {else}
                              N/A
                            {/if}</p></div>
          </div>
          <div class = "div_section1">
                  <div class="sec4">
                      <p class="hull_title">Premium:&nbsp&nbsp&nbsp&nbspUS</p>
                      <p class="hull_title">Premium:&nbsp&nbsp&nbsp&nbspUS</p>
                  </div>
                   <div class="sec5"><p class="value_align">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp{if isset($HullPremium) && $HullPremium != '0.00'}
        Included
        {else}
        N/A
      {/if}</p><p class="value_align">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp{if isset($DingyTenderPremium) && $DingyTenderPremium != '0.00'}
        Included
        {else}
        N/A
      {/if}</p></div>
          </div>
        </div>
    </div>
  </div>
   
   <div class= "secA">
        <p class = "hull"><b>Trailer</b></p>
        <div class = "section_divi">
              <div class = "div_section">
                      <div class="sec1">
                          <p class="hull_title">Limit of Insurance:</p>
                      </div>
                      <div class="sec2"><p class="hull_title">US</p></div>
                     <div class="sec3"><p class="value_align">{if isset($TrailerPremium) && $TrailerPremium != '0.00'}
                            ${$TrailerPremium|number_format}
                            {else}
                            N/A
                          {/if}</p></div>
              </div>
              <div class = "div_section1">
                      <div class="sec4">
                          <p class="hull_title">Premium:&nbsp&nbsp&nbsp&nbspUS</p>
                    </div>
                     <div class="sec5"><p class="value_align">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp{if isset($TrailerPremium) && $TrailerPremium != '0.00'}
        Included
        {else}
        N/A
      {/if}</p></div>
              </div>
        </div>
    </div>

    <div class = "secA">
    <p class = "hull"><b>Personal Effects</b></p>
    <div class = "section_divi2">
          <div class = "div_section">
                  <div class="sec1">
                      <p class="hull_title">Limit per Item/per Occurence:</p>
                  </div>
                   <div class="sec2"><p class="hull_title">US</p></div>
                 <div class="sec3"><p class="value_align">$500.00/$5,000.00</p></div>
        </div>
        <div class="div_section1"></div>
    </div>
    </div>
    </div>
    <div>&nbsp</div>
    <hr class = "hr_secA"></hr>


<p class = "sec_title"><b>SECTION B - LIABILITY INSURANCE </b>(Including Defense Costs) </p>
<div class ="secB">
<p class = "sec_titl"><span>Maximum Number:       &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp    </span><span>Passengers:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp{$certified_for_max_number_of_passengers}</span><span>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp{if isset($CrewInBoatCount)}Crew on Boat:   &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp {$CrewInBoatCount}</span><span>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp{/if}{if isset($CrewInWaterCount)}Crew in Water:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp{$CrewInWaterCount}{/if}</span></p>  
      <div class = "section_divi">
          <div class = "div_section">
                  <div class="sec1">
                      <p class="hull_title">Limit of Insurance - Protection & Indemnity:</p>
                      <p class="hull_title">Limit of Insurance - Crew Liability:</p>
                      <p class="hull_title">Limit of Insurance - Crew in the Water:</p>
                  </div>
                  <div class="sec2"><p class="hull_title">US</p><p class="hull_title">US</p><p class="hull_title">US</p></div>
                  <div class="sec3"><p class="value_align">{$totalLiabilityLimit|number_format}</p>
                  	<p class="value_align">{if isset($CrewOnBoatPremium) && $CrewOnBoatPremium != '0.00'}
                                {$totalLiabilityLimit|number_format}
                             {else}
                                NotCovered
                            {/if}</p>
                  	<p class="value_align">{if isset($CrewMembersinWaterPremium) && $CrewMembersinWaterPremium != '0.00'}
                                {$totalLiabilityLimit|number_format}
                             {else}
                                NotCovered
                             {/if}</p></div>
          </div>
          <div class = "div_section1">
                  <div class="sec4">
                      <p class="hull_title">Premium:&nbsp&nbsp&nbsp&nbspUS</p>
                      <p class="hull_title">Premium:&nbsp&nbsp&nbsp&nbspUS</p>
                      <p class="hull_title">Premium:&nbsp&nbsp&nbsp&nbspUS</p>
                  </div>
                  <div class="sec5"><p class="value_align">&nbsp&nbsp&nbsp&nbsp&nbsp&nbspIncluded</p><p class="value_align">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp{if isset($CrewOnBoatPremium) && $CrewOnBoatPremium != '0.00'}
        Included
        {else}
        N/A
      {/if}</p><p class="value_align">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp{if isset($CrewMembersinWaterPremium) && $CrewMembersinWaterPremium != '0.00'}
        Included
        {else}
        N/A
      {/if}</p></div>
    </div>
</div>
</div>
<div>&nbsp</div>
<div>&nbsp</div>
<div>&nbsp</div>
<div style = "margin-top: 0px;">&nbsp</div>
<hr class = "hr_secA"></hr>
<p class = "sec_title_med"><b>SECTION C - MEDICAL PAYMENTS </b></p>
<div class = "section_divi">
          <div class = "div_section">
                  <div class="sec9">
                      <p class="hull_title">Limit of Insurance - Protection & Indemnity:</p>
                  </div>
                  <div class="sec2"><p class="hull_title">&nbsp&nbsp&nbsp&nbsp&nbsp&nbspUS</p></div>
                  <div class="sec3"><p class="value_align">$5,000.00</p></div>
          </div>
          <div class = "div_section1">
                  <div class="sec4">
                      <p class="hull_title">Premium: US</p>
                  </div>
                  <div class="sec5"><p class="hull_title">&nbsp&nbsp&nbsp&nbsp&nbsp&nbspIncluded</p></div>
          </div>
    </div>
<div>&nbsp</div>
    <hr></hr>
    <div class = "total">
      <p class="hull_title"><span>TOTAL PREMIUM&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</span><span>US</span><span class="totalp" >${$amount|number_format}</span></p>
      <hr class="total_hr"></hr>
      <p class="hull_title"><span>PADI Administrative Fee&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</span><span>US</span><span class="totalp">${$padiFee|number_format}</span></p>
    </div>
<hr class = "sec_title"></hr>
{if isset($navigation_limit_note) && $navigation_limit_note != ""}
<p class="nav"><b>Navigation Limits:</b></p>
<p class="nav_title">While the Vessel is afloat, this policy covers only losses which occur within the navigation limits specified below:</p>
<p class="nav_title2">{$navigation_limit_note}</p>
{/if}
{if isset($layup_period_from_date_time) && $layup_period_from_date_time != ""}
<hr></hr>
<p class = "layup"><b>Layup Period is from {$layup_period_from_date_time|date_format:"%d %B %Y"} to {$layup_period_to_date_time|date_format:"%d %B %Y"}</b></p>
<hr></hr>
{/if}
<p class = "layup"  ><b>Deductibles:</b></p>
<div>
  <p style = "margin:0px;"><span class = "sec_title">SECTION A - HULL INSURANCE:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</span><span>Hull Deductibles - 1.5% of value up to 25 years. 2.5% of value over 25 years.</span></p>
  <div class = "main_sec">
    <div class ="sector">
        <div class = "sector1">
          <p class="value_align1">Dinghy/Tender:</p>
          <p class="value_align1">Trailer:</p>
          <p class="value_align1">Personal Effects:</p>
        </div>
        <div class ="sector2">
          <p  class="value_align">
                            {if isset($DingyTenderPremium) && $DingyTenderPremium != '0.00'}
                            $1000.00
                            {else}
                            N/A
                          {/if}
                  					</p>
          <p class="value_align">
                  						{if isset($TrailerPremium) && $TrailerPremium != '0.00'}
                              $1000.00
                              {else}
                              N/A
                            {/if}
                  					</p>
          <p class="value_align">$500.00</p>
        </div>
    </div>
    <div class = "sector3">
      <div class = "sector4">
         <p class="value_align1">SECTION B - LIABILITY INSURANCE:</p>
          <p class="value_align1">&nbsp</p>
          <p class="value_align1">SECTION C - MEDICAL PAYMENTS</p>
      </div>
        <div class = "sector5">
           <p class="value_align">$1,000.00</p>
          <p class="value_align">&nbsp</p>
          <p class="value_align">$100.00</p>
        </div>
    </div>
  </div>

</div>
<p style = "font-size: 12px;margin-top: 10%;text-align:justify;margin-bottom: 0px;padding-bottom: 0px;">The insurance afforded by this policy is a master policy issued to PADI Worldwide Corporation, 30151 Tomas Street, Rancho Santa Margarita, CA 92668.
The insurance is provided under terms and conditions of the master policy which is enclosed with this certificate. Please read the policy for a full
description of the terms, conditions and exclusions of the policy. This certificate does not amend, alter or extend the coverage afforded by the policy
referenced on this certificate.
Notice of cancelation: If the company cancels this policy, 45 days notice will be given to the certificate holder unless cancellation is for nonpayment of
premium, then 10 days notice will be provided, and any premium not earned will be returned to the certificate holder.</p>


      {if $business_state == 'Alaska'}
        <hr></hr>
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/AK.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Alabama'}
				<hr></hr>
        <center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/AL.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Arkansas'}
				<hr></hr>
        <center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/AR.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Arizona'}
				<hr></hr>
        <center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/AZ.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Colorado'}
				<hr></hr>
        <center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/CO.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Connecticut'}
				<hr></hr>
        <center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/CT.tpl"}</b>
				</p></center>
			{elseif $business_state == 'District of Columbia'}
				<hr></hr>
        <center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/DC.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Delaware'}
				<hr></hr>
        <center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/DE.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Florida'}
				<hr></hr>
        <center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/FL.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Micronesia'}
				<hr></hr>
        <center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/FM.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Georgia'}
				<hr></hr>
        <center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/GA.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Hawaii'}
				<hr></hr>
        <center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/HI.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Iowa'}
				<hr></hr>
        <center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/IA.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Idaho'}
				<hr></hr>
        <center><p class = "notice" style = "color:red;">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/ID.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Illinois'}
				<hr></hr>
        <center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/IL.tpl"}</b>
				</p></center>
			{elseif $business_state == 'International'}
				<hr></hr>
        <center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/International.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Kansas'}
				<hr></hr>
        <center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/KS.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Kentucky'}
				<hr></hr>
        <center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/KY.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Louisiana'}
				<hr></hr>
        <center><p class = "notice" style = "color:red;">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/LA.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Massachusetts'}
				<hr></hr>
        <center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/MA.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Maryland'}
				<hr></hr>
        <center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/MD.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Maine'}
				<hr></hr>
        <center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/ME.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Marshall Islands'}
				<hr></hr>
        <center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/MH.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Michigan'}
				<hr></hr>
        <center><p class = "notice" style = "color:red;">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/MI.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Minnesota'}
				<hr></hr>
        <center><p class = "notice" style = "color:red;">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/MN.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Missouri'}
				<hr></hr>
        <center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/MO.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Mississippi'}
				<hr></hr>
        <center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/MS.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Montana'}
				<hr></hr>
        <center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/MT.tpl"}</b>
				</p></center>
			{elseif $business_state == 'North Carolina'}
				<hr></hr>
        <center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/NC.tpl"}</b>
				</p></center>
			{elseif $business_state == 'North Dakota'}
				<hr></hr>
        <center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/ND.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Nebraska'}
				<hr></hr>
        <center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/NE.tpl"}</b>
				</p></center>
			{elseif $business_state == 'New Hampshire'}
				<hr></hr>
        <center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/NH.tpl"}</b>
				</p></center>
			{elseif $business_state == 'New Jersey'}
				<hr></hr>
        <center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/NJ.tpl"}</b>
				</p></center>
			{elseif $business_state == 'New Mexico'}
				<hr></hr>
        <center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/NM.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Nevada'}
				<hr></hr>
        <center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/NV.tpl"}</b>
				</p></center>
			{elseif $business_state == 'New York'}
				<hr></hr>
        <center><p class = "notice" style = "color:red;">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/NY.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Ohio'}
				<hr></hr>
        <center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/OH.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Oklahoma'}
				<hr></hr>
        <center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/OK.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Oregon'}
				<hr></hr>
        <center><center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/OR.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Pennsylvania'}
				<hr></hr>
        <center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/PA.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Palau'}
				<hr></hr>
        <center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/PW.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Rhode Island'}
				<hr></hr>
        <center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/RI.tpl"}</b>
				</p></center>
			{elseif $business_state == 'South Carolina'}
				<hr></hr>
        <center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/SC.tpl"}</b>
				</p></center>
			{elseif $business_state == 'South Dakota'}
				<hr></hr>
        <center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/SD.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Tennessee'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/TN.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Texas'}
				<hr></hr>
        <center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/TX.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Utah'}
				<hr></hr>
        <center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/UT.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Virginia'}
				<hr></hr>
        <center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/VA.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Virgin Islands'}
				<hr></hr>
        <center><p class = "notice" style = "color:red;">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/VT.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Washington'}
				<hr></hr>
        <center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/WA.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Wisconsin'}
				<hr></hr>
        <center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/WI.tpl"}</b>
				</p></center>
			{elseif $business_state == 'West Virginias'}
				<hr></hr>
        <center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/WV.tpl"}</b>
				</p></center>
			{elseif $business_state == 'Wyoming'}
				<hr></hr>
        <center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/WY.tpl"}</b>
				</p></center>
			{/if}
  </div>
</body>
</html>

