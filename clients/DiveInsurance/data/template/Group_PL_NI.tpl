{assign var=list value=$namedInsureds|json_decode:true}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<link href= "{$smarty.current_dir}/css/template_css.css" rel="stylesheet" type="text/css" />

</head>
<body>
	<div class ="body_div_lp">
		{foreach from=$list item=$value}
			<div class = "named_div">
			      <div class = "named_div1"><p class ="t_title">{$value.padi}</p></div>

			      <div class = "named_div2"><p class ="t_title" style="text-tranform:uppercase;">{$value.name}</p></div>

			      <div class = "named_div3"><p class ="t_title">{$value.status}</p></div>

			      <div class = "named_div4"><p class ="t_title">{$start_date|date_format:"%m/%d/%Y"}</p></div>
			      <div class = "named_div5"><p class ="t_title">No</p>
			      </div>

			      <div class = "named_div6"><p class ="t_title">&nbsp</p>
			      </div>

			      <div class = "named_div7"><p class ="t_title">&nbsp</p>
				  </div>
			</div>
	    {/foreach}
	</div>
</body>
</html>

