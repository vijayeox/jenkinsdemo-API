{assign var=list value=$additionalInsured|json_decode:true}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<link href= "{$smarty.current_dir}/css/divebtemplate_css.css" rel="stylesheet" type="text/css" />

</head>
<body>
	<div class ="body_div_lp">
			<div class = "ai_spacing"></div>
			<p style="margin-bottom:2%">&nbsp</p> 
	    	{foreach from=$list item=$additional}
	    		<p class = "ai_list">
	    			{$additional.name},{$additional.address},{$additional.city},{$additional.state_in_short} &nbsp&nbsp{$additional.zip}
	    		</p>
    		{/foreach}
	</div>
</body>
</html>

