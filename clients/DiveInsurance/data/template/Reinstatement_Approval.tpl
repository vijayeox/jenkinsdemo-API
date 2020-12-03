<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<link href= "{$smarty.current_dir}/css/divestemplate_css.css" rel="stylesheet" type="text/css" />

</head>
<body>
	<div class ="body_div_ai">
		<p style="margin-bottom:2%">&nbsp</p>
		{if $product == 'Dive Store'}
			{if $multiplePolicy == "yes"}
				<p>THE ABOVE POLICIES ARE HEREBY REINSTATED EFFECTIVE: {$reinstateDate|date_format:"%m/%d/%Y"} </p>
			{else}
				<p>THE ABOVE POLICY IS HEREBY REINSTATED EFFECTIVE: {$reinstateDate|date_format:"%m/%d/%Y"} </p>
			{/if}
		{else}
			<p>THE ABOVE POLICY IS HEREBY REINSTATED EFFECTIVE: {$reinstateDate|date_format:"%m/%d/%Y"} </p>
		{/if}
	</div>
</body>
</html>

