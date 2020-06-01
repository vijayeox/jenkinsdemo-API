<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<link href= "{$smarty.current_dir}/css/card_css.css" rel="stylesheet" type="text/css"/>
<script type="text/javascript" src="{$smarty.current_dir}/AgentInfo.js"></script>
<script>
function subst() {
      agentInfo();
  }	
</script>
</head>
<body onload = "subst()">
	<span id ="cat"></span>
	<div class ="div_cover">
		<p>{$smarty.now|date_format:"%m/%d/%Y"}</p>
		<div class ="info_cover">
			<p class="name1"><span>#{$padi}</span>&nbsp&nbsp&nbsp&nbsp&nbsp<span>{$business_name}</span></p>
		</div>
		<div class = "line_space">
		<p>We are pleased to enclose your PADI Endorsed Dive Boat insurance proposal. The coverage includes:</p>
		<ul class ="order">
			<li class = "quote_list">Worldwide coverage (except where not permitted by law)</li>
			<li class = "quote_list">$1,000,000 passenger liability (while on board the vessel) and Third Party Liability</li>
			<li class = "quote_list">Optional $1,000,000 liability including Jones Act Coverage (Crew Coverage while on board the vessel - Optional)</li>
			<li class = "quote_list">US and International vessels are eligible</li>
			<li class = "quote_list">No deductible increase for named storms</li>
			<li class = "quote_list">Pollution coverage for sudden and accidental fuel or oil spills, up to $500,000</li>
			<li class = "quote_list">Collision liability coverage</li>
			<li class = "quote_list">Higher liability limits available - Call us for a quote!</li>
			<li class = "quote_list">Optional In Water Coverage for passengers and paid crew (Optional - Supplemental application required)</li>
		</ul>

		<p>(This is a summary of coverage and does not change the policy language. The policy is what determines the coverage and
a policy will be sent to you.)</p>

	{if isset($quote_due_date) && $quote_due_date != '' && isset($quoteInfo) && $quoteInfo != '[]'}
	{assign var=list value=$quoteInfo|json_decode:true}
	<p><b>To purchase your insurance coverage, please provide us with the following items prior to {$quote_due_date|date_format:"%m/%d/%Y"}</b></p>
		{foreach from=$list item=$quoteData key = key}
					{if $key == 'Other' &&  ($quoteData == true || $quoteData == 'true')}
						 {assign var=other value=$quoteInfoOther|json_decode:true}
						 {foreach from=$other item=$otherInfo}
						 {assign var=other1 value=urldecode($otherInfo.info)}
						  <p class = "quote_list">[X]{$other1}</p>
						 {/foreach}
					{else if $key == 'Copy of Captain’s License (front & back) for XXX' && ($quoteData == true || $quoteData == 'true')}
	    			   	  {assign var=captainInfo value=str_replace('XXX',$captainX,$key)}
	    			   	  {assign var=captainInfo1 value=urldecode($captainInfo)}
	    			   	  <p class = "quote_list">[X]{$captainInfo1}</p>
	    			{else if $key == 'Marine Survey (completed within the past 12 months) Vessels five (5) years or older are required to provide current condition and valuation survey with confirmation that all recommendations are completed: XXX' && ($quoteData == true || $quoteData == 'true')}
	    			   	  {assign var=marineInfo value=str_replace('XXX',$marineX,$key)}
	    			   	  {assign var=marine value=urldecode($marineInfo)}
	    			   	  <p class = "quote_list">[X]{$marine}</p>
	    			{else if $quoteData == true || $quoteData == 'true'}
	    				  {assign var=keyData value=urldecode($key)}
						  <p class = "quote_list">[X]{$keyData}</p>	    			   		
	    			{/if}
    	{/foreach}
     {/if}
		<p class = "line_end">Thank you for your support of the PADI Endorsed Dive Boat program. Please call or email me if you have any questions.</p>
</div>
		<p>Sincerely,</p>
		<p class ="footer_line">Vicencia & Buckley A Division of HUB International</p>
		<p class ="footer_line">{$approverName},CISR, {$approverDesignation}</p>
		<p class ="footer_line">(800) 223-9998 or (714) 739-3176</p>
		<p class ="footer_line">{$approverEmailId}</p>

	</div>
</body>
</html>

