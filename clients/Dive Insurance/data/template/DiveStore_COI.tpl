<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<link href= "{$smarty.current_dir}/css/divestemplate_css.css" rel="stylesheet" type="text/css" />

</head>
<body>
	<div class ="body_div_lp">
	    	{if isset($property)}
	    	 	{include file = 'DiveStore_Property_COI.tpl'}
	    	{else if isset($liability)}
	    		{include file = 'DiveStore_Liability_COI.tpl'}
	    	{/if}
	</div>
</body>
</html>

