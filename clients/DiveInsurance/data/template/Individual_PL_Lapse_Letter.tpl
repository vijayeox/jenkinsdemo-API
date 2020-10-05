<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<link href= "{$smarty.current_dir}/css/card_css.css" rel="stylesheet" type="text/css" />

</head>
<body>
	<div class="main_div">
	<div class ="main_div_space">
  	<div class = "details_div">
		<div class ="name_details">
			<p class = "name_title">NAME AND ADDRESS OF INSURED(S):</p>
			<p class="name">{$lastname},{$firstname}{if isset($initial)},{$initial}{/if}</p>
			<p class="name">{$address1}</p>
			<p class="name">{$address2}</p>
			<p class = "name">{$city},{$state} - {$zip}</p>
			<p class = "name">{$country}</p>
		</div>
		<div class = "certi_details">
			<div class = "certi_title">
				<b><p class="certi">Date:</p>
				<p class="certi">Certificate #:</p>
				<p class="certi">Member #:</p>
				<p class="certi">Effective Date:</p>
				<p class="certi">Exp. Date</p></b>
			</div>
			<div class = "certi_value">
				<p class="certi">{$smarty.now|date_format:"%d %B %Y"}</p>
				<p class="certi">{$certificate_no}</p>
				<p class="certi">{$padi}</p>
				<p class="certi">{$start_date}</p>
				<p class = "certi">{$end_date|date_format:"%d %B %Y"}&nbsp12:01:00 AM</p>
			</div>
		</div>
	</div>
	<div>&nbsp</div>
	<div class = "space">
		<p class = "lapse_body2">&nbsp&nbsp&nbsp&nbsp&nbsp&nbspEnclosed are your certificate and policy booklet for your PADI Professional Liability Insurance coverage.Our records indicate that you have let your coverage lapse. As an Instructor you should keep your policy in force even if you are not teaching. Having a lapse in coverage will cause you to lose all prior acts coverage. If you are not teaching we recommend you purchase the non-teaching instructor policy in June. This coverage will allow you to maintain your prior acts coverage and can be renewed at a discounted rate.</p>

		<p class = "lapse_body2">&nbsp&nbsp&nbsp&nbsp&nbsp&nbspTo help prevent your coverage having a lapse in the future you may signup to have your coverage automatically renewed. If you would like to do this please call or send me a signed request to do so, with the credit card or checking account information you would like payment for the automatic renewal to be made.</p>

		<p class = "lapse_body1">
			If you have any questions, please contact our office at (800) 223-9998.
		</p>

		<p class = "sign">
			Sincerely,
			<br/><br/>
			PADI Department<br/>
			Vicencia & Buckley, a Division of HUB International Insurance Services Inc.<br/>
			6 Centerpointe Dr. #350<br/>
			La Palma, CA - 90623<br/>
			Email: padi-professional@hubinternational.com<br/>
		</p>
	</div>
  </div>
</div>
</body>
</html>

