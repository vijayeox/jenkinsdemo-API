<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<link href= "{$smarty.current_dir}/css/divebtemplate_css.css" rel="stylesheet" type="text/css" />

</head>
<body>
	 <div class ="body_div">
		<center>
      <div>
		<b><p class = "title">{if !empty($lossPayees)}
			CERTIFICATE HAS LOSS PAYEES (SEE ATTACHED).
		{else}
			CERTIFICATE DOES NOT HAVE LOSS PAYEES.
		{/if}</p>

		<p class = "title">{if !empty($additionalInsured)}
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
            <p class="hull_title">Name of Vessel: </p>
            <p class="hull_title"><span>Year Built:&nbsp&nbsp&nbsp&nbsp 12345&nbsp&nbsp&nbsp    </span><span>Length:&nbsp&nbsp&nbsp&nbsp23&nbsp&nbsp&nbsp     </span><span>HP:&nbsp&nbsp&nbsp&nbsp78</span></p>
            <p class="hull_title">S/N: &nbsp&nbsp234546464675</p>
          </div>
          <div class = "sectionb">
            <p class="hull_title">Hull Type: </p>
            <p class="hull_title">Mfg: &nbsp&nbspjhfjhfv75</p>  
          </div>
        </div>
        <div class = "section_div">
          <div class = "div_section">
                  <div class="sec1">
                      <p class="hull_title">Limit of Insurance:</p>
                      <p class="hull_title">Limit of Insurance - Tender/Dinghy:</p>
                  </div>
                  <div class="sec2"><p class="hull_title">US</p><p class="hull_title">US</p></div>
                  <div class="sec3"><p class="hull_title">$280,000.00</p><p class="hull_title">N/A</p></div>
          </div>
          <div class = "div_section1">
                  <div class="sec4">
                      <p class="hull_title">Premium:&nbsp&nbsp&nbsp&nbspUS</p>
                      <p class="hull_title">Premium:&nbsp&nbsp&nbsp&nbspUS</p>
                  </div>
                  <div class="sec5"><p class="hull_title">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp$280,000.00</p><p class="hull_title">&nbsp&nbsp&nbsp&nbsp&nbsp&nbspN/A</p></div>
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
                      <div class="sec3"><p class="hull_title">$280,000.00</p></div>
              </div>
              <div class = "div_section1">
                      <div class="sec4">
                          <p class="hull_title">Premium:&nbsp&nbsp&nbsp&nbspUS</p>
                    </div>
                      <div class="sec5"><p class="hull_title">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp$280,000.00</p></div>
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
                  <div class="sec3"><p class="hull_title">$500.00/$5,000.00</p></div>
        </div>
        <div class="div_section1"></div>
    </div>
    </div>
    </div>
    <div>&nbsp</div>
    <hr class = "hr_secA"></hr>


<p class = "sec_title"><b>SECTION B - LIABILITY INSURANCE </b>(Including Defense Costs) </p>
<div class ="secB">
<p class = "sec_titl"><span>Maximum Number:           </span><span>Passengers:     49</span><span>Crew on Boat:    2</span><span>Crew in Water:     0</span></p>    
      <div class = "section_divi">
          <div class = "div_section">
                  <div class="sec1">
                      <p class="hull_title">Limit of Insurance - Protection & Indemnity:</p>
                      <p class="hull_title">Limit of Insurance - Crew Liability:</p>
                      <p class="hull_title">Limit of Insurance - Crew in the Water:</p>
                  </div>
                  <div class="sec2"><p class="hull_title">US</p><p class="hull_title">US</p><p class="hull_title">US</p></div>
                  <div class="sec3"><p class="hull_title">$1,000,000.00</p><p class="hull_title">$1,000,000.00</p><p class="hull_title">Not Covered</p></div>
          </div>
          <div class = "div_section1">
                  <div class="sec4">
                      <p class="hull_title">Premium:&nbsp&nbsp&nbsp&nbspUS</p>
                      <p class="hull_title">Premium:&nbsp&nbsp&nbsp&nbspUS</p>
                      <p class="hull_title">Premium:&nbsp&nbsp&nbsp&nbspUS</p>
                  </div>
                  <div class="sec5"><p class="hull_title">&nbsp&nbsp&nbsp&nbsp&nbsp&nbspIncluded</p><p class="hull_title">&nbsp&nbsp&nbsp&nbsp&nbsp&nbspIncluded</p><p class="hull_title">&nbsp&nbsp&nbsp&nbsp&nbsp&nbspN/A</p></div>
          </div>
    </div>
</div>
<p class = "sec_title"><b>SECTION C - MEDICAL PAYMENTS </b></p>
<div class = "section_divi">
          <div class = "div_section">
                  <div class="sec9">
                      <p class="hull_title">Limit of Insurance - Protection & Indemnity:</p>
                  </div>
                  <div class="sec2"><p class="hull_title">&nbsp&nbsp&nbsp&nbsp&nbsp&nbspUS</p></div>
                  <div class="sec3"><p class="hull_title">&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp$1,000,000.00</p></div>
          </div>
          <div class = "div_section1">
                  <div class="sec4">
                      <p class="hull_title">Premium: US</p>
                  </div>
                  <div class="sec5"><p class="hull_title">&nbsp&nbsp&nbsp&nbsp&nbsp&nbspIncluded</p></div>
          </div>
    </div>
<div>&nbsp</div>
<div>&nbsp</div>
    <hr></hr>
    <div class = "total">
      <p class="hull_title"><span>TOTAL PREMIUM&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</span><span>US</span><span class="totalp" >$10,965.00</span></p>
      <hr class="total_hr"></hr>
      <p class="hull_title"><span>PADI Administrative Fee&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</span><span>US</span><span class="totalp">$75.00</span></p>
    </div>
<hr class = "sec_title"></hr>
<p class="nav"><b>Navigation Limits:</b></p>
<p class="nav_title">While the Vessel is afloat, this policy covers only losses which occur within the navigation limits specified below:</p>
<p class="nav_title">PHILIPPINE SEA; ISLAND OF GUAM; NOT MORE THAN 20 MILES FROM A HARBOR OF SAFE REFUGE, WITHIN 3 NAUTICAL MILES FROM
SHORE, FRON RITIDIAN POINT IN THE NORTH, THENCE WESTWARD, TO TANGON ROCK, COCOS LAGOON, IN THE SOUTH.</p>

<p><b>Deductibles:</b></p>
<div>
  <p><span class = "sec_title">SECTION A - HULL INSURANCE:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp</span><span>Hull Deductibles - 1.5% of value up to 25 years. 2.5% of value over 25 years.</span></p>
  <div class = "main_sec">
    <div class ="sector">
        <div class = "sector1">
          <p>Dinghy/Tender:</p>
          <p>Trailer:</p>
          <p>Personal Effects:</p>
        </div>
        <div class ="sector2">
          <p>N/A</p>
          <p>N/A</p>
          <p>$500.00</p>
        </div>
    </div>
    <div class = "sector3">
      <div class = "sector4">
         <p>SECTION B - LIABILITY INSURANCE:</p>
          <p>&nbsp</p>
          <p>SECTION C - MEDICAL PAYMENTS</p>
      </div>
        <div class = "sector5">
          <p>$1,000.00</p>
          <p>&nbsp</p>
          <p>$100.00</p>
        </div>
    </div>
  </div>

</div>

<p>The insurance afforded by this policy is a master policy issued to PADI Worldwide Corporation, 30151 Tomas Street, Rancho Santa Margarita, CA 92668.
The insurance is provided under terms and conditions of the master policy which is enclosed with this certificate. Please read the policy for a full
description of the terms, conditions and exclusions of the policy. This certificate does not amend, alter or extend the coverage afforded by the policy
referenced on this certificate.
Notice of cancelation: If the company cancels this policy, 45 days notice will be given to the certificate holder unless cancellation is for nonpayment of
premium, then 10 days notice will be provided, and any premium not earned will be returned to the certificate holder.</p>
<hr></hr>


            {if $state == 'Alaska'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/AK.tpl"}</b>
				</p></center>
			{elseif $state == 'Alabama'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/AL.tpl"}</b>
				</p></center>
			{elseif $state == 'Arkansas'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/AR.tpl"}</b>
				</p></center>
			{elseif $state == 'Arizona'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/AZ.tpl"}</b>
				</p></center>
			{elseif $state == 'Colorado'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/CO.tpl"}</b>
				</p></center>
			{elseif $state == 'Connecticut'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/CT.tpl"}</b>
				</p></center>
			{elseif $state == 'District of Columbia'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/DC.tpl"}</b>
				</p></center>
			{elseif $state == 'Delaware'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/DE.tpl"}</b>
				</p></center>
			{elseif $state == 'Florida'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/FL.tpl"}</b>
				</p></center>
			{elseif $state == 'Micronesia'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/FM.tpl"}</b>
				</p></center>
			{elseif $state == 'Georgia'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/GA.tpl"}</b>
				</p></center>
			{elseif $state == 'Hawaii'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/HI.tpl"}</b>
				</p></center>
			{elseif $state == 'Iowa'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/IA.tpl"}</b>
				</p></center>
			{elseif $state == 'Idaho'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/ID.tpl"}</b>
				</p></center>
			{elseif $state == 'Illinois'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/IL.tpl"}</b>
				</p></center>
			{elseif $state == 'International'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/International.tpl"}</b>
				</p></center>
			{elseif $state == 'Kansas'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/KS.tpl"}</b>
				</p></center>
			{elseif $state == 'Kentucky'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/KY.tpl"}</b>
				</p></center>
			{elseif $state == 'Louisiana'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/LA.tpl"}</b>
				</p></center>
			{elseif $state == 'Massachusetts'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/MA.tpl"}</b>
				</p></center>
			{elseif $state == 'Maryland'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/MD.tpl"}</b>
				</p></center>
			{elseif $state == 'Maine'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/ME.tpl"}</b>
				</p></center>
			{elseif $state == 'Marshall Islands'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/MH.tpl"}</b>
				</p></center>
			{elseif $state == 'Michigan'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/MI.tpl"}</b>
				</p></center>
			{elseif $state == 'Minnesota'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/MN.tpl"}</b>
				</p></center>
			{elseif $state == 'Missouri'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/MO.tpl"}</b>
				</p></center>
			{elseif $state == 'Mississippi'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/MS.tpl"}</b>
				</p></center>
			{elseif $state == 'Montana'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/MT.tpl"}</b>
				</p></center>
			{elseif $state == 'North Carolina'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/NC.tpl"}</b>
				</p></center>
			{elseif $state == 'North Dakota'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/ND.tpl"}</b>
				</p></center>
			{elseif $state == 'Nebraska'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/NE.tpl"}</b>
				</p></center>
			{elseif $state == 'New Hampshire'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/NH.tpl"}</b>
				</p></center>
			{elseif $state == 'New Jersey'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/NJ.tpl"}</b>
				</p></center>
			{elseif $state == 'New Mexico'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/NM.tpl"}</b>
				</p></center>
			{elseif $state == 'Nevada'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/NV.tpl"}</b>
				</p></center>
			{elseif $state == 'New York'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/NY.tpl"}</b>
				</p></center>
			{elseif $state == 'Ohio'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/OH.tpl"}</b>
				</p></center>
			{elseif $state == 'Oklahoma'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/OK.tpl"}</b>
				</p></center>
			{elseif $state == 'Oregon'}
				<center><center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/OR.tpl"}</b>
				</p></center>
			{elseif $state == 'Pennsylvania'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/PA.tpl"}</b>
				</p></center>
			{elseif $state == 'Palau'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/PW.tpl"}</b>
				</p></center>
			{elseif $state == 'Rhode Island'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/RI.tpl"}</b>
				</p></center>
			{elseif $state == 'South Carolina'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/SC.tpl"}</b>
				</p></center>
			{elseif $state == 'South Dakota'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/SD.tpl"}</b>
				</p></center>
			{elseif $state == 'Tennessee'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/TN.tpl"}</b>
				</p></center>
			{elseif $state == 'Texas'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/TX.tpl"}</b>
				</p></center>
			{elseif $state == 'Utah'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/UT.tpl"}</b>
				</p></center>
			{elseif $state == 'Virginia'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/VA.tpl"}</b>
				</p></center>
			{elseif $state == 'Virgin Islands'}
				<center><p class = "notice" style = "color:red;">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/VT.tpl"}</b>
				</p></center>
			{elseif $state == 'Washington'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/WA.tpl"}</b>
				</p></center>
			{elseif $state == 'Wisconsin'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/WI.tpl"}</b>
				</p></center>
			{elseif $state == 'West Virginias'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/WV.tpl"}</b>
				</p></center>
			{elseif $state == 'Wyoming'}
				<center><p class = "notice">
					<b>{include file = "{$smarty.current_dir}/DiveBoatSurplus/WY.tpl"}</b>
				</p></center>
			{/if}

  </div>
</body>
</html>

