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
				<p class = "cancelFont">THE ABOVE POLICIES ARE HEREBY CANCELLED EFFECTIVE: {$cancelDate|date_format:"%m/%d/%Y"} </p>
			{else}
				<p class = "cancelFont">THE ABOVE POLICY IS HEREBY CANCELLED EFFECTIVE: {$cancelDate|date_format:"%m/%d/%Y"} </p>
			{/if}
		{else}
			<p class = "cancelFont">THE ABOVE POLICY IS HEREBY CANCELLED EFFECTIVE: {$cancelDate|date_format:"%m/%d/%Y"} </p>
		{/if}
			{if $reasonforCsrCancellation == 'nonPaymentOfPremium' && $product == 'Dive Store'}
				<p class = "cancelFont">DUE TO NON-PAYMENT OF PREMIUM.</p>
				<p class = "cancelFont">TO REINSTATE COVERAGE, PAYMENT OF ${$reinstateAmount} IS REQUIRED WITHIN
				10 DAYS OF THIS NOTICE TO THE FINANCE COMPANY.</p>
			{elseif $reasonforCsrCancellation == 'nonPaymentOfPremium' && $product != 'Dive Store'}
				<p class = "cancelFont">DUE TO NON-PAYMENT OF PREMIUM.</p>
				<p class = "cancelFont">TO REINSTATE COVERAGE, PAYMENT OF ${$reinstateAmount} IS REQUIRED WITHIN
				10 DAYS OF THIS NOTICE.</p>
			{elseif $reasonforCsrCancellation == 'padiMembershipNotCurrent'}
				<p class = "cancelFont">DUE TO PADI MEMBERSHIP NOT CURRENT.</p>
				<p class = "cancelFont">TO REINSTATE COVERAGE, MEMBERSHIP MUST BE RENEWED WITHIN 45 DAYS OF THIS NOTICE</p>
			{elseif $reasonforCsrCancellation == 'nonSufficientFunds' && $product == 'Dive Store'}
				<p class = "cancelFont">NON-PAYMENT OF PREMIUM DUE TO NON-SUFFICIENT FUNDS.</p>
				<p class = "cancelFont">TO REINSTATE COVERAGE, PAYMENT OF ${$reinstateAmount} IS REQUIRED WITHIN
				10 DAYS OF THIS NOTICE TO THE FINANCE COMPANY.</p>
			{elseif $reasonforCsrCancellation == 'nonSufficientFunds' && $product != 'Dive Store'}
				<p class = "cancelFont">NON-PAYMENT OF PREMIUM DUE TO NON-SUFFICIENT FUNDS.</p>
				<p class = "cancelFont">TO REINSTATE COVERAGE, PAYMENT OF ${$reinstateAmount} IS REQUIRED WITHIN
				10 DAYS OF THIS NOTICE.</p>
			{elseif $reasonforCsrCancellation == 'boatSold'}
				<p class = "cancelFont">DUE TO BOAT SOLD.</p>
			{elseif $reasonforCsrCancellation == 'storeSold'}
				<p class = "cancelFont">DUE TO STORE SOLD.</p>
			{elseif $reasonforCsrCancellation == 'businessClosed'}
				<p class = "cancelFont">DUE TO BUSINESS CLOSED.</p>
			{elseif $reasonforCsrCancellation == 'others'}
				<p class = "cancelFont">DUE TO {$othersCsr}</p>
			{/if}
		{elseif $cancellationStatus == 'notApproved'}
		<p class = "cancelFont">THE ABOVE POLICY IS NOT CANCELLED.</p>
		<p class = "cancelFont">DUE TO {$reasonforRejection}</p>
		{/if}
	</div>
</body>
</html>

