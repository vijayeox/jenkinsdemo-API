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

		<p>Dear <span class ="rgaard1">{if $product == 'Group Professional Liability'} {$lastname}, {$firstname}{if isset($initial)}, {$initial}{/if} {else} {$business_name} {if isset($dba) && $dba !=""} - {$dba} {/if}{/if}</span></p>
		<div class = "line_space">
		<p>Enclosed is the endorsement proposal to your PADI Endorsed {if $product == 'Group Professional Liability'} Professional Liability {else} Dive Store {/if}policy.{if isset($notes) && $notes != ""}  This endorsement makes the following change:</p>
		<p>{$notes}</p>
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

