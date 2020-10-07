{assign var=list value=$additionalNamedInsured|json_decode:true}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<link href= "{$smarty.current_dir}/css/divestemplate_css.css" rel="stylesheet" type="text/css" />

</head>
<body>
	<div class ="body_div_ai" style = "margin-top:3%;">
	    	{foreach from=$list item=$additional}
	    		<p class = "ai_list" style = "text-transform:uppercase;">
	    			&nbsp&nbsp&nbsp {if isset($additional.name) && is_string($additional.name) &&  $additional.name != ""}{$additional.name}{/if},{if isset($additional.address) && is_string($additional.address)  && $additional.address != ""},{$additional.address}{/if}{if isset($additional.city) && is_string($additional.city) && $additional.city != ""},{$additional.city}{/if}{if isset($additional.state) && is_string($additional.state) && $additional.state != ""},{$additional.state}{/if}{if isset($additional.zip) && is_string($additional.zip) && $additional.zip != ""},{$additional.zip}{/if}
	    		</p>
    		{/foreach}
	</div>
</body>
</html>
