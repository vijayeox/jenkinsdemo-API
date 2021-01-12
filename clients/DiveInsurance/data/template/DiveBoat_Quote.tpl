<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
  <link href= "{$smarty.current_dir}/css/divebtemplate_css.css" rel="stylesheet" type="text/css" />
  <script type="text/javascript" src="{$smarty.current_dir}/AgentInfo.js"></script>
</head>
<body onload = "agentInfo()">
 <div class = "m_div">
    <div class = "agent">
      <div class = "agent_info">
        <b class = "agent_info_title">Agent Information</b>
        <div class = "agent_1">
         <p class ="info_margin1" id ="nameVal"></p>
         <p class ="add_margin" id = "addressLineVal"></p>
				 <p class ="add_margin" id = "addressLine2Val"></p>
         <p></p>
         <p class = "p_info">License#: {$license_number}</p>
       </div>
     </div>
     <div class ="agent_info1">
      <b class = "agent_info2">Agent Contact Information</b>
      <div class = "agent_1">
       <p class ="p_info"><span id= "phone1Val"></span>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspFAX <span id= "faxVal"></span></p>
       <p class ="p_info" id = "phone2Val"></p>
       <p class ="p_info">www.diveinsurance.com</p>
       <p class ="p_info">diveboat@diveinsurance.com</p>
       <p class ="p_info">Policy period: {$start_date} thru {$end_date}</p>
     </div>
   </div>
 </div>
 <div>&nbsp</div>
 <hr class = "hr_margin"></hr>
 <div class="agent">
  <div class = "agent_info">
    <b >Insured's Name and Mailing Address:</b>
    <p class = "c_details">{$business_name}</p>
    <p class = "c_details">{$business_address1}</p>
    <p class = "c_details">{$business_address2}</p>
    <p class = "c_details">{$business_city}, {$state_in_short} {$business_zip}</p>
    <p class = "c_details">{$business_country}</p>
  </div>
  <div class = "agent_info1">
    <p class ="padi_margin"><b>Member#:</b> {$padi}
    </div>
  </div>
  <div class = "spacing_div">&nbsp</div>
  <hr class = "footer_line1"></hr>
  <b><center><p class ="title_value">{if $lossPayeesSelect == 'yes'}
   CERTIFICATE HAS LOSS PAYEES (SEE ATTACHED).
   {else}
   CERTIFICATE DOES NOT HAVE LOSS PAYEES.
 {/if}</p>
 <p class ="title_value">{if $additional_insured_select == 'addAdditionalInsureds'}
   CERTIFICATE HAS ADDITIONAL INSURED (SEE ATTACHED).
   {else}
   CERTIFICATE DOES NOT HAVE ADDITIONAL INSURED.
 {/if}</p></center></b>

 <hr class = "footer_line2"></hr>
 <p class = "sec_title"><b>PROPERTY INSURANCE</b></p>
 <div class = "secA">
  <p class = "hull"><b>Hull</b></p>
  <div class = "section1">
    <div class = "sectiona">
      <p class="hull_title">Name of Vessel: {$vessel_name}</p>
      <p class="hull_title"><span>Year Built: &nbsp&nbsp&nbsp&nbsp {$built_year}&nbsp&nbsp&nbsp</span><span>Length:&nbsp&nbsp&nbsp&nbsp{$vessel_length}&nbsp&nbsp&nbsp     </span><span>HP:&nbsp&nbsp&nbsp&nbsp{$vessel_hp}</span></p>
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
  <p class = "hull"><b>Personal Effects</b><span style = "font-size:13px;">(Items valued over $500 must be scheduled)</span></p>
  <div class = "section_divi2">
    <div class = "div_section">
      <div class="sec1">
        <p class="hull_title">Limit per Item/per Occurence:</p>
      </div>
      <div class="sec2"><p class="hull_title">US</p></div>
      <div class="sec3"><p class="value_align">$500.00/$5,000.00</p></div>
    </div>
    <div class="div_section1">
      
    </div>
  </div>
</div>
</div>
</div>
<hr class = "hr_db"></hr>
<p class = "sec_title"><b>LIABILITY INSURANCE </b><span style = "font-size:13px;">(Including Defense Costs)</span></p>
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
      <div class="sec3"><p class="value_align">{$totalLiabilityLimit}</p>
      <p class="value_align">{if isset($CrewOnBoatPremium) && $CrewOnBoatPremium != '0.00'}
                                {$totalLiabilityLimit}
                             {else}
                                NotCovered
                            {/if}
                             </p>
      <p class="value_align">{if isset($CrewMembersinWaterPremium) && $CrewMembersinWaterPremium != '0.00'}
                                {$totalLiabilityLimit}
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
<hr class = "hr_tg"></hr>
<p class = "sec_title"><b>MEDICAL PAYMENTS </b></p>
<div class = "section_divi">
  <div class = "div_section">
    <div class="sec9">
      <p class="hull_title">Limit of Insurance - Protection & Indemnity:</p>
    </div>
    <div class="sec2"><p class="hull_title">US</p></div>
    <div class="sec3"><p class="value_align">$5,000.00</p></div>
  </div>
  <div class = "div_section1">
    <div class="sec4">
      <p class="hull_title">&nbspPremium:&nbsp&nbsp&nbsp&nbspUS</p>
    </div>
    <div class="sec5"><p class="value_align">&nbsp&nbsp&nbsp&nbsp&nbsp&nbspIncluded</p></div>
  </div>
</div>
<div>&nbsp</div>
<hr></hr>
<div class = "total">
  <p class="hull_title"><span>TOTAL PREMIUM&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</span><span>US</span><span class="totalp" >${$amount|number_format}</span></p>
  <hr class="total_hr"></hr>
  <p class="hull_title"><span>PADI Administrative Fee&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</span><span>US</span><span class="totalp">${$padiFee|number_format}</span></p>
</div>
{if isset($navigation_limit_note) && $navigation_limit_note != ""}
<hr class = "sec_title"></hr>
<p class="nav"><b>Navigation Limits:</b></p>
<p class="nav_title">While the Vessel is afloat, this policy covers only losses which occur within the navigation limits specified below:</p>
<p class="nav_title2">{$navigation_limit_note}</p>
{/if}
{if isset($layup_period_from_date_time) && $layup_period_from_date_time != ''} 
<hr></hr>
<p class = "layup"><b>Layup Period is from {$layup_period_from_date_time|date_format:"%d %B %Y"} to {$layup_period_to_date_time|date_format:"%d %B %Y"}</b></p>
<hr></hr>
{/if}
<p class = "layup"><b>Deductibles:</b></p>
<div>
  <p><span class = "sec_title">SECTION A - HULL INSURANCE:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</span><span>Hull Deductibles - 1.5% of value up to 25 years. 2.5% of value over 25 years.</span></p>
  <div class = "main_sec">
    <div class ="sector">
      <div class = "sector1">
        <p class =" deduct">Dinghy/Tender:</p>
        <p class =" deduct">Trailer:</p>
        <p class =" deduct">Personal Effects:</p>
      </div>
      <div class ="sector2">
        <p  class="value_align">{if isset($DingyTenderPremium) && $DingyTenderPremium != '0.00'}
        $1000.00
        {else}
        N/A
      {/if}</p>
        <p class="value_align">{if isset($TrailerPremium) && $TrailerPremium != '0.00'}
        $1000.00
        {else}
        N/A
      {/if}</p>
        <p class="value_align">$500.00</p>
      </div>
    </div>
    <div class = "sector3">
      <div class = "sector4">
       <p class =" deduct">SECTION B - LIABILITY INSURANCE:</p>
       <p class =" deduct">&nbsp</p>
       <p class =" deduct">SECTION C - MEDICAL PAYMENTS</p>
     </div>
     <div class = "sector5">
      <p class="value_align">$1,000.00</p>
      <p class="value_align">&nbsp</p>
      <p class="value_align">$100.00</p>
    </div>
  </div>
</div>

</div>

</body>
</html>

