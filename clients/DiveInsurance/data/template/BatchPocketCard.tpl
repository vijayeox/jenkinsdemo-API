<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <link href= "{$smarty.current_dir}/css/card_css.css" rel="stylesheet" type="text/css" />
</head>
<body>
	<div style = "margin-top: 10%;">
	{assign var=decode value=$data|json_decode:true}
	{assign var=val value=0}
	{foreach from=$decode item=$individual}
		{if $val%3 == 0}
			<p class="break"></p>
		{/if}
		<div class="main_div"><br/><br/><br/><br/><br/><br/><br/>
			<div class = "details">
				{if !(isset($individual.product) && ($individual.product == 'Dive Boat')) && !(isset($individual.product) && ($individual.product == 'Dive Store'))}
				<p class = "email1" style="padding-bottom: 10px;" >{$individual.email}</p>
				<p class = "email1" align="left">{$individual.lastname},&nbsp;{$individual.firstname}<br>{$individual.address1}<br>
					{if isset($individual.address2) && !empty($individual.address2)}
						{$individual.address2}<br>
					{/if}
					{$individual.city},&nbsp;{$individual.state},&nbsp;{$individual.zip},<br>{$individual.country}</p>
				{/if}
			</div>
			<div class = "insure1" style="padding-left=5px;"><br/>
				<div class = "main_section1" style = "font-size: 15px;">
					<p class = "card_holder_name" style = "margin-bottom: 0px;font-size:15px">{if isset($individual.business_name)}Business Name:&nbsp;
						<span style = "text-transform: uppercase;">{$individual.business_name}</span><br> Insured: &nbsp;<span style = "text-transform: uppercase;">{$individual.firstname}&nbsp{$individual.lastname}</span>
					{else}Insured: &nbsp;<span style = "text-transform: uppercase;">{$individual.firstname}&nbsp{$individual.lastname}</span></p>{/if}
					<br>
					<div class = "section" style = "font-size: 15px;">
						<div class = "sec1">
							<p class = "info">Certificate #: &nbsp{$individual.certificate_no}</p>
							<p class = "info">PADI #: &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp{$individual.padi}</p>
						</div>
						<div class = "sec2" style = "font-size: 15px;">
							<p class = "info">Effective Date:&nbsp&nbsp&nbsp{$individual.start_date|date_format:"%m/%d/%Y"}</p>
							<p class = "info">Expiration Date: {$individual.end_date|date_format:"%m/%d/%Y"}</p>
						</div>
					</div>
				</div>
			</div><br><br><br><br><br><br><br><br>
			{if $individual.product == "Dive Store"}
				<br><br><br><br>
			{/if}
		</div>
		{assign var=val value=$val+1}
	{/foreach}
</div>
</body>
</html>
