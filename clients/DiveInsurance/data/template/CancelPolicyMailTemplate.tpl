  {* Cancel Policy Mail Template *}

            <div style='width:100%;background:#452767;color:#fff;height:35px;margin-bottom:2px'>
            </div>
            <div style='line-height: 24px'>Dear {$firstname} {$lastname}, <br/><br/>
			<p>Your PADI Endorsed Professional Liability insurance has been canceled effective 12:01 a.m. {$CancelDate|date_format:"%m/%d/%Y"} due to {if $reasonforCsrCancellation == 'nonPaymentOfPremium'}
				Non-payment of installment premium. 
			{elseif $reasonforCsrCancellation == 'padiMembershipNotCurrent'}
				PADI Membership is not current.
			{elseif $reasonforCsrCancellation == 'nonSufficientFunds'}
				Non-Payment of premium,due yo non-sufficient funds.
			{elseif $reasonforCsrCancellation == 'boatSold'}
				Boat Sold.
			{elseif $reasonforCsrCancellation == 'storeSold'}
				Store Sold.
			{elseif $reasonforCsrCancellation == 'others'}
				{$othersCsr}
			{/if}
			</p>To reinstate your coverage, with no lapse, we need the following within 10days.<br/>
				<center>{assign var=reasons value=$reinstatementCriteria|json_decode:true}
				{assign var=count value=$reasons|@sizeof}
				{assign var=count1 value=$count}
				{for $foo=1 to $count1}
					<p>{$foo}.{$reasons[$foo - 1]['criteria']}</p><br/>
				{/for}</center>	
				<br/><br/>
				<p>If you have any questions feel free to give us a call or send us an email.</p>
			 <br/>

            </div>

<br/>
Sincerely,
<br/><br/>
PADI Department<br/>
Vicencia & Buckley, a Division of HUB International Insurance Services Inc.<br/>
A division of HUB International<br/>
6 Centerpointe Dr. #350<br/>
La Palma, CA  90623<br/>
Email: padi-professional@hubinternational.com<br/>
Phone: 800-223-9998 or 714-739-3177<br/>
Fax: 714-739-3188<br/>
License #0757776
<br/><br/>
PLEASE ADD OUR DOMAINS <a href = 'hubinternational.com'>hubinternational.com</a> and <a href = 'www.diveinsurance.com'>www.diveinsurance.com</a> TO YOUR SAFE SENDERS LIST!
<br/><br/>
You can find us at <a href = 'www.diveinsurance.com'>www.diveinsurance.com</a>
<br/><br/><br/><br/>
This message, together with any attachments, is intended only for the use of the individual or entity to which it is addressed. It may contain information that is confidential and prohibited from disclosure. If you are not the intended recipient, you are hereby notified that any dissemination or copying of this message or any attachment is strictly prohibited. If you have received this message in error, please notify the original sender immediately by telephone or by return e-mail and delete this message, along with any attachments, from your computer. Thank You!

