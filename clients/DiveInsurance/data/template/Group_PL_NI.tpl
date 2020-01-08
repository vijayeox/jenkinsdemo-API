{assign var=list value=$namedInsured|json_decode:true}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<link href= "{$smarty.current_dir}/css/template_css.css" rel="stylesheet" type="text/css" />

</head>
<body>
	<div class ="body_div_lp">
	    	{foreach from=$list item=$value}
	    	{assign var=upgrade value={$value['upgrade']}}
	  		<div class = "named_div">
			      <div class = "named_div1"><p class ="t_title">{$value['padi']}</p></div>

			      <div class = "named_div2"><p class ="t_title" style="text-tranform:uppercase;">{$value['name']}</p></div>

			      <div class = "named_div3"><p class ="t_title">{$value['status']}</p></div>

			      <div class = "named_div4"><p class ="t_title">{$value['effective_date']|date_format:"%m/%d/%Y"}</p></div>

			      <div class = "named_div5"><p class ="t_title">{if upgrade}
			      							Yes
			      							{else}
			      							No
			      							{/if}</p>
			      </div>

			      <div class = "named_div6"><p class ="t_title">{if isset($value['upgrade_effective_date'])}
										      		$value['upgrade_effective_date']
										      {else}
										      	&nbsp
										      {/if}</p>
			      </div>

			      <div class = "named_div7"><p class ="t_title">{if isset($value['cancel_date'])}
										         $value['cancel_date']
										      {else}
										      	&nbsp
										      {/if}</p>
				  </div>
    		</div>
			{/foreach}
	</div>
</body>
</html>

