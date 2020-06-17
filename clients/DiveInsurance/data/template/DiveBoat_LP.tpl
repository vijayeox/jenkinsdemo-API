{assign var=list value=$lossPayees|json_decode:true}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<link href= "{$smarty.current_dir}/css/divebtemplate_css.css" rel="stylesheet" type="text/css" />

</head>
<body>
	<div class ="body_div_lp">
			<p style="margin-bottom:2%">&nbsp</p> 
			{foreach from=$list item=$additional}
	    		<p class = "ai_list">
	    			&nbsp&nbsp&nbsp{$additional.name},{$additional.address},{$additional.city},{$additional.state} &nbsp&nbsp{$additional.zip}
	    		</p>
    		{/foreach}
	</div>
</body>
</html>

