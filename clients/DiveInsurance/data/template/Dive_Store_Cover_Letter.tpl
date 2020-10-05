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
			{if isset($dba) && $dba != ""}<p class="name">DBA : {$dba}</p>{/if}
			<p class="name">{$address1}</p>
			<p class="name">{$address2}</p>
			<p class="name">{$city}, {$state} {$zip}</p>
			<p class="name">{$country}</p>
		</div>

		<p class = "rgard">RE: PADI SPONSORED DIVE CENTER INSURANCE</p>

		<p>Dear <span class ="rgard1">{$lastname}, {$firstname}{if isset($initial)}, {$initial}{/if},</span></p>
		<div class = "line_space">
		<p>We are pleased to enclose the certificate of insurance and policy for your dive center operation. The certificate lists the coverage and policy limits applicable to your business.</p>
		<ul class ="order">
			<li>Please read these documents and advise us of any discrepancies or necessary changes.</li>
			<li>If you carry the Group Professional Liability Policy please be sure your roster is up to date, including anyone no
longer working for you.</li>
			<li>If financing, your payments are due to AFS/IBEX Premium Finance Company. You will receive a monthly invoice.</li>
			<li>Please note: Your Dive Center general liability insurance does not cover the supervision and instruction of swimmers.
This can only be covered by an individual or group professional liability policy.</li>
		</ul>
		{if isset($notes) && $notes != ''}
			{$notes}
     	{/if}
		<p class = "line_end">Thank you for your support of the PADI Endorsed Dive Center insurance program. Please call or email me if you have any
questions.</p>
</div>
		<p>Sincerely,</p>
		<p class ="footer_line">Vicencia & Buckley A Division of HUB International</p>
		<p class ="footer_line">{$approverName}, {$approverDesignation}</p>
		<p class ="footer_line">(800) 223-9998 or (714) 739-3176</p>
		<p class ="footer_line">{$approverEmailId}</p>

	</div>
</body>
</html>

