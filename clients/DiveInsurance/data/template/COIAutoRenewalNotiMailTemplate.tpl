 {assign var=year value={'Y'|date}}
  {* COI Auto Renewal Notification Template *}

            <div style='width:100%;background:#452767;color:#fff;height:35px;margin-bottom:2px'>
            </div>
            <div style='line-height: 24px'>Dear {$lastname}, {$firstname}{if isset($initial)}, {$initial}{/if}, <br/><br/>
                 <p>{$lastname},{$firstname} {if isset($initial)},{$initial}{/if}<br/>
					{$address1}<br/>
					{$address2}<br/>
					{$city},{$state_in_short} - {$zip}<br/>
					{$country}<br/>
</p>

				Coverage Level: {$careerCoverageVal}<br/><br/>
				Equipment Liability: {if $equipment == "equipmentLiabilityCoverage"}
											Selected
									 {else} 
									  		Not Selected
									 {/if}<br/>
				Excess Liability: {if $excessLiability  == "excessLiabilityCoverage9000000" || $excessLiability  == "excessLiabilityCoverage4000000" || $excessLiability  == "excessLiabilityCoverage3000000" || $excessLiability  == "excessLiabilityCoverage2000000" || $excessLiability  == "excessLiabilityCoverage1000000"}
											Selected
								  {else} 
									  		Not Selected
							      {/if}

				<br/><br/>
				<br/>
				<p>It's that time of the year to think about insurance. Fortunately, you are currently signed up for automatic renewal under the PADI Endorsed Professional Liability Program.</p><br/>

				<p>The final amount that will be deducted from your account for the policy period June 30,{$year} to June 30,{$year+1} is ${$amount}.</p>

				<p style = 'font-size:13px;text-transform: uppercase;'><b>Please reply to this email with your request to be taken off auto renewal by June 20, {$year+1}. Not responding is confirmation you want to stay on auto renewal.</b></p><br/>

				<p>PADI Members, such as yourself, on automatic renewal are given priority over other members. Therefore, to beat the June 30th rush, we will be charging your credit card on or around June 20, {$year+1}.</p><br/>

                <p>Please note that if your payment is declined this will cause a delay in your renewal or lapse in coverage. Please take a moment to update your account by calling our office. Above is the current information we have for you. Please let us know if there are any changes to your billing information; Including, credit card details, expiration date, or address. Along with updating your payment information, you should let us know of any changes to your Additional Insured list, Excess Liability, Equipment Liability or Upgrades.</p>
                <br/>
                <p>We thank you for your long-standing relationship with PADI and Vicencia & Buckley, a Division of HUB International Insurance Services Inc. If you have any questions regarding your automatic renewal or anything else feel free to give us a call or send us an email.</p>
			 <br/>

            </div>
<br/>
Sincerely,
<br/><br/>
PADI Department<br/>
Vicencia & Buckley, a Division of HUB International Insurance Services Inc.<br/>
A division of HUB International<br/>
6 Centerpointe Dr. #350<br/>
La Palma, CA?? 90623<br/>
Email: padi-professional@hubinternational.com<br/>
Phone: 800-223-9998 or 714-739-3177<br/>
Fax: 714-739-3188<br/>
License #0757776
<br/><br/>
PLEASE ADD OUR DOMAINS <a href = "https://www.hubinternational.com/" rel="noopener noreferrer" target="_blank" style = "color:blue;"><u>hubinternational.com</u></a> and <a href = "https://www.diveinsurance.com/" rel="noopener noreferrer" target="_blank" style = "color:blue;"><u>diveinsurance.com</u></a> TO YOUR SAFE SENDERS LIST!
<br/><br/>
You can find us at <a href = "https://www.diveinsurance.com/" rel="noopener noreferrer" target="_blank" style = "color:blue;"><u>www.diveinsurance.com</u></a>
<br/><br/><br/><br/>
This message, together with any attachments, is intended only for the use of the individual or entity to which it is addressed. It may contain information that is confidential and prohibited from disclosure. If you are not the intended recipient, you are hereby notified that any dissemination or copying of this message or any attachment is strictly prohibited. If you have received this message in error, please notify the original sender immediately by telephone or by return e-mail and delete this message, along with any attachments, from your computer. Thank You!

