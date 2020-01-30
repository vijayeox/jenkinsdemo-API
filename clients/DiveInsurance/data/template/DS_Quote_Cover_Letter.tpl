<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<link href= "{$smarty.current_dir}/css/card_css.css" rel="stylesheet" type="text/css" />

</head>
<body>
	<div class ="div_cover">
		<p>{$smarty.now|date_format:"%m/%d/%Y"}</p>
		<div class ="info_cover">
			<p class="name1">{$orgname}</p>
			<p class="name1">{$address1}</p>
			<p class="name1">{$address2}</p>
			<p class="name1">{$city},{$state}</p>
			<p class="name1">{$country},{$zip}</p>
		</div>

		<p>Dear <span class ="rgaard1">{$firstname}&nbsp{$lastname}</span></p>
		<div class = "line_space">
		<p>We are pleased to enclose your PADI Endorsed Dive Center proposal. Our program includes:</p>
		<ul class ="order">
			<li>Damage to Premise you rent includes water damage and smoke and explosion in addition to fire damage.</li>
			<li>Contingent Professional Liability - See policy for coverage terms.</li>
			<li>No coinsurance penalty on property coverage.</li>
			<li>Accidental Compressor Breakdown (excluding wear and tear and normal maintenance).</li>
			<li>Optional Group Professional Liability.</li>
			<li>U.S. A XV rated insurer.</li>
			<li>Unlimited defense costs for covered claims.</li>
			<li>Non-motorized watercraft less than 20 feet in length.</li>
			<li>No liability deductible.</li>
			<li>Worldwide liability coverage - except where not allowed by law.</li>
			<li>Optional Travel Agents Liability.</li>
			<li>Optional higher Property Deductible of $2,500.00 or $5,000.00.</li>
			<li>Please note: Your Dive Center general liability insurance does not cover the supervision and instruction of swimmers. This can only be covered by an individual or group professional liability policy.</li>
		</ul>

		<p>To purchase your insurance coverage, please provide us with the following items:</p>
		
			{foreach from=$list.name item=$value}
	  			<p class = "ai_list">
	    			&nbsp&nbsp&nbsp{$value}
	  			</p>
			{/foreach}
		
		<p class = "line_end">Thank you for your support of the PADI Endorsed Insurance Program, if you have any questions, please call or email me if you have any questions.</p>
</div>
		<p>Sincerely,</p>
		<p>Vicencia & Buckley A Division of HUB International</p>
		<p class="acc_name">{$manager_name},CISR, Account Manager</p>
		<p class ="footer_line">Vicencia & Buckley A Division of HUB International</p>
		<p class ="footer_line">(800) 223-9998 or (714) 739-3176</p>
		<p class ="footer_line">{$manager_email}</p>

	</div>
</body>
</html>

