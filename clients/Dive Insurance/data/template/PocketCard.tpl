<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <link href= "{$smarty.current_dir}/css/card_css.css" rel="stylesheet" type="text/css" />
</head>
<body>         
	<div class="main_div">
		<div class = "details">
			<p class = "info">{$lastname},{$firstname}</p>
			<p class = "info">{$address1},{$address2}</p>
			<p class = "info">{$city},{$state}</p>
			<p class = "info">{$country},{$zip}</p>
		</div>
		<div class = "insure">
			<p>Insured: &nbsp&nbsp&nbsp{$firstname}&nbsp{$lastname}</p>
			<div class = "section">
				<div class = "sec1">
					<p class = "info">Certificate #:&nbsp&nbsp{$certificate_no}</p>
					<p class = "info">PADI #:&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp{$padi}</p>
				</div>
				<div class = "sec2">
					<p class = "info">Effective Date:&nbsp&nbsp&nbsp{$start_date|date_format:"%m/%d/%Y"}</p>
					<p class = "info">Expiration Date:&nbsp{$end_date|date_format:"%m/%d/%Y"}</p>
				</div>
			</div>
		</div>
	</div>
</body>
</html>

