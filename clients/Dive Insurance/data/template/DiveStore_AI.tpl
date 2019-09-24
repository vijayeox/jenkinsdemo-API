{assign var=list value=$aiList|json_decode:true}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<link href= "{$smarty.current_dir}/css/divestemplate_css.css" rel="stylesheet" type="text/css" />

</head>
<body>
	<div class ="body_div_ai">
	    	{foreach from=$list.name item=$value}
	  			<p class = "ai_list">
	    			*&nbsp{$value}
	  			</p>
			{/foreach}
	</div>
</body>
</html>

