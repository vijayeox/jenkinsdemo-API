 {assign var=year value={'Y'|date}}
  {* EFR COI Auto Renewal Notification Template *}

            <div style='width:100%;background:#452767;color:#fff;height:35px;margin-bottom:2px'>
            </div>
            <div style='line-height: 24px'>Dear {$lastname},{$firstname} {if isset($initial)},{$initial}{/if}, <br/><br/>
                {$lastname},{$firstname} {if isset($initial)},{$initial}{/if}<br/>
                    {$address1}<br/>
                    {$address2}<br/>
                    {$city},{$state_in_short} - {$zip}<br/>
                    {$country}<br/>


				<br/>
				<p>It's that time of the year to think about insurance. Fortunately, you are currently signed up for automatic renewal under the Emergency First Response Program.</p><br/>

				<p>The final amount that will be deducted from your account for the policy period July 01,{$year} to June 30,{$year+1} is ${$amount}.</p>

				<p style = 'font-size:13px;text-transform: uppercase;'><b>Please reply to this email with your request to be taken off auto renewal by May 29, {$year+1}. Not responding is confirmation you want to stay on auto renewal.</b></p><br/>

				<p>PADI Members, such as yourself, on automatic renewal are given priority over other members. Therefore, to beat the June 30th rush, we will be charging your credit card on or around 06/01/{$year+1}.</p><br/>

                <p>Please note that if your payment is declined this will cause a delay in your renewal or lapse in coverage. Please take a moment to update your account by calling our office. Above is the current information we have for you. Please let us know if there are any changes to your billing information; Including, credit card details, expiration date, or address. Along with updating your payment information, you should let us know of any changes to your Additional Insured list or Upgrades.</p>
                <br/>
                <p>We thank you for your long-standing relationship with PADI and Vicencia & Buckley, a Division of HUB International Insurance Services Inc. If you have any questions regarding your automatic renewal or anything else feel free to give us a call or send us an email.</p>
			 <br/>

            </div>

Regards,
<br/><br/>
Vicencia & Buckley A Division of HUB International
