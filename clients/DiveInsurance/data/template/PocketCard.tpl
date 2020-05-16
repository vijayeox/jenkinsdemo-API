<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <link href= "{$smarty.current_dir}/css/card_css.css" rel="stylesheet" type="text/css" />
</head>
<body>
	{assign var=decode value=$data|json_decode:true}
	{assign var=val value=0}
	{foreach from=$decode item=$individual}
		{assign var=val value=$val+1}
		{if $val%1 == 0}
			<p class="break"></p>
		{/if}
		<div class="main_div">
			<div class = "details">
				{if !(isset($individual.product) && ($individual.product == 'Dive Boat')) && !(isset($individual.product) && ($individual.product == 'Dive Store'))}
				<p class = "email"><b>Your certificate and insurance policy <br>were emailed to :<br><u>{$individual.email}</u></b></p><br>
				<p class = "email" align="left"><b>{$individual.lastname},&nbsp;{$individual.firstname}<br>{$individual.address1}<br>
					{if isset($individual.address2) && !empty($individual.address2)}
						{$individual.address2}<br>
					{/if}
					{$individual.city},&nbsp;{$individual.state},&nbsp;{$individual.zip},<br>{$individual.country} </b></p>
				{/if}
			</div>
			<div class = "insure">
				<div class = "header">
					<p class = "heading1"><b>UNDERWATER INSTRUCTOR'S LIABILITY INSURANCE</b></p>
					<p class = "heading2"><b>Insured's Pocket Card</b></p>
				</div>
				<div class = "main_section">
					<p class = "card_holder_name">{if isset($individual.business_name)}Business Name:&nbsp;
						{$individual.business_name}<br> Insured: &nbsp;{$individual.firstname}&nbsp{$individual.lastname}
					{else}Cardholder Name: &nbsp;{$individual.firstname}&nbsp{$individual.lastname}</p>{/if}
					<div class = "section">
						<div class = "sec1">
							<p class = "info">Certificate #: &nbsp{$individual.certificate_no}</p>
							<p class = "info">Valid FROM #: {$individual.start_date|date_format:"%m/%d/%Y"}</p>
						</div>
						<div class = "sec2">
							<p class = "info">PADI #: &nbsp{$individual.padi}</p>							
							<p class = "info">Valid THRU #: {$individual.end_date|date_format:"%m/%d/%Y"}</p><br>
						</div>
					</div>
					<p><br>The insurance offered is subject to all the terms of the policy, including endorsement, applicable thereto Additional details of the insurance are found on the accompanying cerificate of insurance</p>
					<div class = "end">
						<p class= "padi"><b>padi</b></p>
						<p class = "address">30151 Thomas Street, Rancho Santa Margarita, CA 92688</p>
					</div>
				</div>
			</div><br><br><br><br>
		</div>
		<div class="main_div">
			<div class = "insure">
				<p class = "heading3">Important Telephone Numbers</p>
				<div class = "t1">
					<table style="width:100%">
		              <tr>
		                <td class = "classname">PADI Incident Reports:</td>
		                <td>(800)&nbsp;729-7234; ext. 2413 or 2540 (PADI)</td>
		              </tr>
		              <tr>
		                <td></td>
		                <td>(949)&nbsp;858-7234; ext. 2413 or 2540 (PADI)</td>
		              </tr>
		              <tr>
		                <td class = "classname">Fax:</td>
		                <td>(after 5 p.m. Monday-Friday; weekends & holidays)</td>
		              </tr>
		              <tr>
		                <td></td>
		                <td>(800)&nbsp;729-7234; ext. 2726 (PADI)</td>
		              </tr>
		              <tr>
		                <td></td>
		                <td>(949)&nbsp;858-7234; ext. 2726 (PADI)</td>
		              </tr>
		            </table>
					<hr>
					<table style="width:100%">
			            <tr>
			              <td class = "classname">Insurance Questions:</td>
			              <td>(800)&nbsp;223-9998&nbsp;(Vincencia & Buckley Insurance)</td>
			            </tr>
			            <tr>
			              <td></td>
			              <td>(714)&nbsp;739-3177</td>
			            </tr>
			            <tr>
			              <td class = "classname">Fax:</td>
			              <td>(714)&nbsp;739-3188</td>
			            </tr>
			        </table>
					<hr>
					<table style="width:100%">
			            <tr>
			              <td class = "classname">Legal Questions:</td>
			              <td>(800)&nbsp;729-7234; ext. 4010 - Toll-free U.S & Canada</td>
			            </tr>
			            <tr>
			              <td>(7am - 5pm Mon - Thur</td>
			              <td>(949)&nbsp;858-7234; ext. 4010 - all other areas</td>
			            </tr>
			            <tr>
			              <td>& 7am - 4pm Fri)</td>
			              <td>Potential Liability, Legal Questions, Attorney Referral</td>
			            </tr>
		          	</table>
				</div>
			</div>
			<div class = "details">
				<p class = "email"><b>If you have any questions or need any additional information,<br>Please contact us:<br>Email&nbsp;:&nbsp;{$individual.product_email_id}<br>
				Phone:&nbsp;(800)&nbsp;223-9998<br>or (714)&nbsp;739-3177</b></p>
			</div>
		</div>
	{/foreach}
</body>
</html>
