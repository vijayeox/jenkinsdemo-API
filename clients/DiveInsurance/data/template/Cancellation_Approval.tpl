<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<link href= "{$smarty.current_dir}/css/divestemplate_css.css" rel="stylesheet" type="text/css" />

</head>
<body>
	<div class ="body_div_ai">
		{if $cancellationStatus == 'approved'}
		<p style="margin-bottom:2%">&nbsp</p>
		{if $product == 'Dive Store'}
			{if $multiplePolicy == "yes"}
				<p>THE ABOVE POLICIES ARE HEREBY CANCELLED EFFECTIVE: {$cancelDate|date_format:"%m/%d/%Y"} </p>
			{else}
				<p>THE ABOVE POLICY IS HEREBY CANCELLED EFFECTIVE: {$cancelDate|date_format:"%m/%d/%Y"} </p>
			{/if}
		{else}
			<p>THE ABOVE POLICY IS HEREBY CANCELLED EFFECTIVE: {$cancelDate|date_format:"%m/%d/%Y"} </p>
		{/if}
			{if $reasonforCsrCancellation == 'nonPaymentOfPremium' && $product == 'Dive Store'}
				<p>DUE TO NON-PAYMENT OF PREMIUM.</p>
				<p>TO REINSTATE COVERAGE, PAYMENT OF ${$reinstateAmount} IS REQUIRED WITHIN
				10 DAYS OF THIS NOTICE TO THE FINANCE COMPANY.</p>
			{elseif $reasonforCsrCancellation == 'nonPaymentOfPremium' && $product != 'Dive Store'}
				<p>DUE TO NON-PAYMENT OF PREMIUM.</p>
				<p>TO REINSTATE COVERAGE, PAYMENT OF ${$reinstateAmount} IS REQUIRED WITHIN
				10 DAYS OF THIS NOTICE.</p>
			{elseif $reasonforCsrCancellation == 'padiMembershipNotCurrent'}
				<p>DUE TO PADI MEMBERSHIP NOT CURRENT.</p>
				<p>TO REINSTATE COVERAGE, MEMBERSHIP MUST BE RENEWED WITHIN 45 DAYS OF THIS NOTICE</p>
			{elseif $reasonforCsrCancellation == 'nonSufficientFunds' && $product == 'Dive Store'}
				<p>NON-PAYMENT OF PREMIUM DUE TO NON-SUFFICIENT FUNDS.</p>
				<p>TO REINSTATE COVERAGE, PAYMENT OF ${$reinstateAmount} IS REQUIRED WITHIN
				10 DAYS OF THIS NOTICE TO THE FINANCE COMPANY.</p>
			{elseif $reasonforCsrCancellation == 'nonSufficientFunds' && $product != 'Dive Store'}
				<p>NON-PAYMENT OF PREMIUM DUE TO NON-SUFFICIENT FUNDS.</p>
				<p>TO REINSTATE COVERAGE, PAYMENT OF ${$reinstateAmount} IS REQUIRED WITHIN
				10 DAYS OF THIS NOTICE.</p>
			{elseif $reasonforCsrCancellation == 'boatSold'}
				<p>DUE TO BOAT SOLD.</p>
			{elseif $reasonforCsrCancellation == 'storeSold'}
				<p>DUE TO STORE SOLD.</p>
			{elseif $reasonforCsrCancellation == 'businessClosed'}
				<p>DUE TO BUSINESS CLOSED.</p>
			{elseif $reasonforCsrCancellation == 'others'}
				<p>DUE TO {$othersCsr}</p>
			{/if}
		{elseif $cancellationStatus == 'notApproved'}
		<p>THE ABOVE POLICY IS NOT CANCELLED.</p>
		<p>DUE TO {$reasonforRejection}</p>
		{/if}
	</div>
</body>
</html>

