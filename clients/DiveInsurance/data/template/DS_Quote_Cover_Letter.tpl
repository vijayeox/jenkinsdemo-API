<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<link href= "{$smarty.current_dir}/css/card_css.css" rel="stylesheet" type="text/css" />

</head>
<body>
	<div class ="div_cover">
		<p>{$smarty.now|date_format:"%m/%d/%Y"}</p>
		<div class ="info_cover">
			<p class="name">{$business_name}</p>
			<p class="name">{if isset($dba) && $dba !=""}
								DBA : {$dba} 
							{/if}</p>
			<p class="name">{$address1}</p>
			<p class="name">{$address2}</p>
			<p class="name">{$city}, {$state} {$zip}</p>
			<p class="name">{$country}</p>
		</div>

		<p>Dear <span class ="rgaard1">{$lastname}, {$firstname}{if isset($initial)}, {$initial}{/if}</span></p>
		<div class = "line_space">
		<p>We are pleased to enclose your PADI Endorsed Dive Center proposal. Our program includes:</p>
		<ul class ="order">
			<li>Damage to Premise you rent includes water damage and smoke and explosion in addition to fire damage.</li>
			<li>Contingent Professional Liability - See policy for coverage terms.</li>
			<li>No coinsurance penalty on property coverage.</li>
			<li>Accidental Compressor Breakdown (excluding wear and tear and normal maintenance).</li>
			<li>Optional Group Professional Liability.</li>
			<li>U.S. A++ XV rated insurer.</li>
			<li>Unlimited defense costs for covered claims.</li>
			<li>Non-motorized watercraft less than 20 feet in length.</li>
			<li>No liability deductible.</li>
			<li>Worldwide liability coverage - except where not allowed by law.</li>
			<li>Optional Travel Agents Liability.</li>
			<li>Optional higher Property Deductible of $2,500.00 or $5,000.00.</li>
			<li>Please note: Your Dive Center general liability insurance does not cover the supervision and instruction of swimmers. This can only be covered by an individual or group professional liability policy.</li>
		</ul>

		{if isset($quoteInfo) && $quoteInfo != '[]'}
		<p>To purchase your insurance coverage, please provide us with the following items:</p>
			{assign var=list value=$quoteInfo|json_decode:true}
			{foreach from=$list item=$quoteData key = key}
					{if $key == 'Other' &&  ($quoteData == true || $quoteData == 'true')}
						 {assign var=other value=$quoteInfoOther|json_decode:true}
						 {foreach from=$other item=$otherInfo}
						  <p class = "quote_list">[X]{$otherInfo.info}</p>
						 {/foreach}
					{else if $key == 'Marine Survey (completed within the past 12 months) Vessels five (5) years or older are required to provide current condition and valuation survey with confirmation that all recommendations are completed: XXX' && ($quoteData == true || $quoteData == 'true')}
	    			   	  {assign var=marineInfo value=str_replace('XXX',$marineX,$key)}
	    			   	  <p class = "quote_list">[X]{$marineInfo}</p>
	    			{else if $quoteData == true || $quoteData == 'true'}
						  <p class = "quote_list">[X]{$key}</p>	    			   		
	    			{/if}
    	{/foreach}
     	{/if}
		
		<p class = "line_end">Thank you for your support of the PADI Endorsed Insurance Program, if you have any questions, please call or email me if you have any questions.</p>
</div>
<div>
		<p>Sincerely,</p>
		<p class ="footer_line">Vicencia & Buckley A Division of HUB International</p>
		<p class ="footer_line">{$approverName}, {$approverDesignation}</p>
		<p class ="footer_line">(800) 223-9998 or (714) 739-3176</p>
		<p class ="footer_line">{$approverEmailId}</p>
</div>
	</div>
</body>
</html>

