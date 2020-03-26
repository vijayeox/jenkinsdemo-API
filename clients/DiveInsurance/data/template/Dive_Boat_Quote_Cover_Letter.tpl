{assign var=list value=$quoteRequirement|json_decode:true}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<link href= "{$smarty.current_dir}/css/card_css.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="{$smarty.current_dir}/AgentInfo.js"></script>
</head>
<body onload = "agentInfo()">
	<span id ="cat"></span>
	<div class ="div_cover">
		<p>{$smarty.now|date_format:"%m/%d/%Y"}</p>
		<div class ="info_cover">
			<p class="name1"><span>#{$business_padi}</span>&nbsp&nbsp&nbsp&nbsp&nbsp<span>{$business_name}</span></p>
		</div>
		<div class = "line_space">
		<p>We are pleased to enclose your PADI Endorsed Dive Boat insurance proposal. The coverage includes:</p>
		<ul class ="order">
			<li>Worldwide coverage (except where not permitted by law)</li>
			<li>$1,000,000 passenger liability (while on board the vessel) and Third Party Liability</li>
			<li>Optional $1,000,000 liability including Jones Act Coverage (Crew Coverage while on board the vessel - Optional)</li>
			<li>US and International vessels are eligible</li>
			<li>No deductible increase for named storms</li>
			<li>Pollution coverage for sudden and accidental fuel or oil spills, up to $500,000</li>
			<li>Collision liability coverage</li>
			<li>Higher liability limits available - Call us for a quote!</li>
			<li>Optional In Water Coverage for passengers and paid crew (Optional – Supplemental application required)</li>
		</ul>

		<p>(This is a summary of coverage and does not change the policy language. The policy is what determines the coverage and
a policy will be sent to you.)</p>

	<p><b>To purchase your insurance coverage, please provide us with the following items prior to {$quote_due_date|date_format:"%m/%d/%Y"}</b></p>
		{foreach from=$list item=$quoteData}
				<p class = "ai_list">
					[X]{if !isset($quoteData['quoteInfoOther'])}
	    			 	    {$quoteData['quoteInfo']}
	    			   	{else}
	    			   		{$quoteData['quoteInfoOther']}
	    			   	{/if}
	    		</p>
    	{/foreach}


		<p class = "line_end">Thank you for your support of the PADI Endorsed Dive Boat program. Please call or email me if you have any questions.</p>
</div>
		<p>Sincerely,</p>
		<p class ="footer_line">Vicencia & Buckley A Division of HUB International</p>
		<p class ="footer_line">{$approverName},CISR, Account Manager</p>
		<p class ="footer_line"><span id="phone1Val"></span> or <span id="phone2Val"></span></p>
		<p class ="footer_line">{$approverEmailId}</p>

	</div>
</body>
</html>

