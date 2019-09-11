{* COI Auto Renewal Notification Template *}

            <div style='width:100%;background:#452767;color:#fff;height:35px;margin-bottom:2px'>
            </div>
            <div style='line-height: 24px'>Dear {$firstname} {$lastname}, <br/><br/>
                <p>{$firstname} {$lastname}<br/>
                {$address1}<br/>
				{$address2}<br/>
				{$city},{$state},{$zip}<br/></p>


				<p>Coverage Level: {$coverage}<br/>
				Equipment Liability: {if $isequipmentliability}
											Selected
									 {else} 
									  		Not Selected
									 {/if}<br/>
				Excess Liability: {if $isexcessliability}
											Selected
								  {else} 
									  		Not Selected
							      {/if}</p>

				<p>Payment Type: {$credit_card_type}<br/>
				Last 4 Digits: {$card_no}<br/>
				Exp Date: {$card_expiry_date}<br/>
				</p>
				<br/>
				<p>It's that time of the year to think about insurance. Fortunately, you are currently signed up for automatic renewal under the PADI Endorsed Professional Liability Program.</p><br/>

				<p>The rates for the {$policy_period} policy period have changed. Please review the attached renewal rates for the {$policy_period} policy period.</p><br/>

				<p style = 'font-size:13px;text-transform: uppercase;'><b>Please reply to this email with your request to be taken off auto renewal by May 29, {$expiry_year}. Not responding is confirmation you want to stay on auto renewal.</b></p><br/>

				<p>PADI Members, such as yourself, on automatic renewal are given priority over other members. Therefore, in order to beat the June 30th rush, we will be charging your credit card on or around 06/01/{$expiry_year}.</p><br/>

				<p>Please note that if your payment is declined this will cause a delay in your renewal or lapse in coverage. Please take a moment to update your account by calling our office. Above is the current information we have for you. Please let us know if there have been any changes to your billing information; including, credit card number, expiration date, or address. Along with updating your payment information, you should let us know of any changes to your Additional Insured list, Excess Liability, Equipment Liability or Upgrades.</p>
				<br/>
				<p>We thank you for your long-standing relationship with PADI and Vicencia & Buckley, a Division of HUB International Insurance Services Inc. If you have any questions regarding your automatic renewal or anything else feel free to give us a call or send us an email.</p>
			 <br/>

            </div>

Regards,
<br/><br/>
Vicencia & Buckley A Division of HUB International
