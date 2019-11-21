{assign var=list value=$additionalInsured|json_decode:true}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<link href= "{$smarty.current_dir}/css/divebtemplate_css.css" rel="stylesheet" type="text/css" />

</head>
<body>
	<div class ="body_div_lp">
			<div class = "ai_spacing"></div>
	    	{foreach from=$list item=$additional}
	    		{assign var=datalist value=$additional|json_decode:true}
	    		<p class = "ai_list">
	    			{$datalist.name},{$datalist.address},{$datalist.city},{$datalist.state},{$datalist.zip}
	    		</p>
    		{/foreach}
	</div>
</body>
</html>

