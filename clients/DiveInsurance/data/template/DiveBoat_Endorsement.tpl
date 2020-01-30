<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<link href= "{$smarty.current_dir}/css/divebtemplate_css.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<div class ="body_div_endo">
	     <div class = "section1">
          <div class = "sectiona">
            <p class="hull_title">Name of Vessel: {$vessel_name}</p>
            <p class="hull_title"><span>Year Built: &nbsp&nbsp&nbsp&nbsp {$vessel_year}&nbsp&nbsp&nbsp</span><span>Length:&nbsp&nbsp&nbsp&nbsp{$vessel_length}&nbsp&nbsp&nbsp</span><span>HP:&nbsp&nbsp&nbsp&nbsp{$vessel_hp}</span></p>
            <p class="hull_title">S/N: &nbsp&nbsp{$vessel_sno}</p>
          </div>
          <div class = "sectionb">
            <p class="hull_title">Hull Type: {$hull_type}</p>
            <p class="hull_title">Mfg: &nbsp&nbsp{$hull_mfg}</p>  
          </div>
          <hr></hr>
          <p>
          {if isset($layup_period)}
              {assign var=layup value=$layup_period|json_decode:true}
              Layup Period is from {$layup.startdate|date_format:"%m/%d/%Y"} to {$layup.enddate|date_format:"%m/%d/%Y"}
          {/if}</p>
          <hr></hr>
          <p>*** SECTION A - PROPERTY INSURED - CHANGE(S)</p>
          <p>{if isset($section_a)}
              {assign var=section_a value=$section_a|json_decode:true}
                {if isset($section_a.vessel)}
                    {if isset($section_a.vessel.agreed_value)}
                      *** The Agreed Valuation of said Vessel has increased by ${$section_a.vessel.agreed_value}
                    {/if}

                    {if isset($section_a.vessel.deductible)}
                      {if isset($section_a.vessel.agreed_value)}
                        ,the deductible has increased by ${$section_a.vessel.deductible}
                      {else}
                        *** The deductible has increased by ${$section_a.vessel.deductible}
                      {/if}
                    {/if}

                    {if isset($section_a.vessel.premium)}
                      {if isset($section_a.vessel.agreed_value) || isset($section_a.vessel.deductible)}
                        ,the premium has increased by ${$section_a.vessel.premium}
                      {else}
                        *** The premium has increased by ${$section_a.vessel.premium}
                      {/if}
                    {/if}
                {/if}

     
                {if isset($section_a.dinghy)}
                    {if isset($section_a.dinghy.amount)}
                      *** The amount of DinghyTender Insurance has increased by ${$section_a.dinghy.amount}
                    {/if}

                    {if isset($section_a.dinghy.premium)}
                      {if isset($section_a.dinghy.amount)}
                        ,the premium has increased by ${$section_a.dinghy.premium}
                      {else}
                        *** The Dinghy premium has increased by ${$section_a.dinghy.premium}
                      {/if}
                    {/if}
                {/if}


                {if isset($section_a.trailer)}
                    {if isset($section_a.trailer.amount)}
                      *** The amount of Trailer Insurance has increased by ${$section_a.trailer.amount}
                    {/if}

                    {if isset($section_a.trailer.premium)}
                      {if isset($section_a.trailer.amount)}
                         and the premium has increased by ${$section_a.trailer.premium}
                      {else}
                        *** The Trailer premium has increased by ${$section_a.trailer.premium}
                      {/if}
                    {/if}
                {/if}
            {/if}
          </p>
          <p>*** SECTION B - LIABILITY INSURED - CHANGE(S)</p>
          <p>
            {if isset($section_b)}
                {assign var=section_b value=$section_b|json_decode:true}

                {if isset($section_b.liability_limit)}
                    {if isset($section_b.liability.amount)}
                      *** The Liability limit has now been increased by ${$section_b.liability.amount}
                    {/if}

                    {if isset($section_.liability.deductible)}
                        ,with the deductible has increased by ${$section_b.liability.deductible}
                    {/if}

                    {if isset($section.liability.premium)}
                      {if isset($section_b.liability.amount) || isset($section.liability.deductible)}
                         and the premium has increased by ${$section.liability.premium}
                      {else}
                        *** The Liability premium has increased by ${$section.liability.premium}
                      {/if}
                    {/if}
                {/if}

                {if isset($section_b.crew_liability)}
                    {if isset($section_b.crew_liability.amount)}
                      *** The Liability for Crew Liabitity for setting/removing is now ${$section_b.crew_liability.amount}
                    {/if}

                    {if isset($section_.crew_liability.deductible)}
                        ,with the deductible of ${$section_b.crew_liability.deductible}
                    {/if}

                    {if isset($section.crew_liability.premium)}
                      {if isset($section_b.crew_liability.amount) || isset($section.crew_liability.deductible)}
                         and the additional premium of ${$section.crew_liability.premium}
                      {else}
                        *** The Crew Liability additional premium is ${$section.crew_liability.premium}
                      {/if}
                    {/if}
                {/if}


                {if isset($section_b.water_crew_liability)}
                    {if isset($section_b.water_crew_liability.amount)}
                      *** The Liability for in Water Crew Liabitity is now Covered with a deductible of ${$section_b.water_crew_liability.amount}
                    {/if}

                    {if isset($section.water_crew_liability.premium)}
                      {if isset($section_b.water_crew_liability.amount)}
                         and the additional premium of ${$section.water_crew_liability.premium}
                      {else}
                        *** The Crew Liability additional premium is ${$section.water_crew_liability.premium}
                      {/if}
                    {/if}
                {/if}
            {/if}
          </p>


            {if isset($additionalInsured)}
            {assign var=list value=$additionalInsured|json_decode:true}

            <p>Name & Address</p>
                <center> 
                    <p>
                    Not withstanding the fact that such parties as advised are hereby named in their capacity as advised as Co-Assured in this Policy, this cover will only extend insofar as they may be found liable to pay in the first instance for liabilities which are properly the responsibility of the Assured, and nothing herein contained shall be construed as extending cover in respect of any amount which would not have been recoverable hereunder by the Assured had such claim been made or enforced against him. Once indemnification hereunder has been made there shall be no further liability hereunder to make any further payment to any person or company whatsoever, including the Assured, in respect of that claim.
                    </p>

                    <p>
                    All rights granted to us together with all duties of an assured under the original insuring agreement shall also apply to any other named co-assured jointly.
                    </p>
                </center>

                {foreach from=$list item=$additional}
                   {$additional.name},
                {/foreach}
            {/if}
          </p>
        </div>
	</div>
</body>
</html>

