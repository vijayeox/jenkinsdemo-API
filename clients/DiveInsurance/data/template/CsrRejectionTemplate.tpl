  {* CSR Rejection Mail Template *}

            <div style='width:100%;background:#452767;color:#fff;height:35px;margin-bottom:2px'>
            </div>
            <div style='line-height: 24px'>Dear {$lastname}, {$firstname}{if isset($initial)}, {$initial}{/if}, <br/><br/>
                 <p>{$lastname}, {$firstname}{if isset($initial)}, {$initial}{/if}<br/>
					{$address1}<br/>
					{$address2}<br/>
					{$city},{$state_in_short} - {$zip}<br/>
					{$country}<br/></p>
				<br/>
				<p>Your application for the PADI {$productType} Insurance is on hold, pending additional information from you.  Please provide us with the following additional information.<br/></p>
				<br/>
				{if isset($rejectionReason)}
					<center>{assign var=reasons value=$rejectionReason|json_decode:true}
					{assign var=count value=$reasons|@sizeof}
					{assign var=count1 value=$count}
					{for $foo=1 to $count1}
					<p>{$foo}.{$reasons[$foo - 1]['reason']}</p><br/>
					{/for}</center>		
				{/if}
				<p>Please provide the additional requested information so we may finalize your coverage.  If you have any questions please call or e-mail us.</p>
			 <br/>

            </div>
<br/>
Sincerely,
<br/><br/>
{if $productType == 'Dive Store' || $productType == 'Dive Boat'}
{$approverName}<br/>
{$approverDesignation}<br/>
{else}
PADI Department<br/>
{/if}
Vicencia & Buckley, a Division of HUB International Insurance Services Inc.<br/>
A division of HUB International<br/>
6 Centerpointe Dr. #350<br/>
La Palma, CA  90623<br/>
{if $productType == 'Dive Store' || $productType == 'Dive Boat'}
Email: {$approverEmailId}<br/>
{else}
Email: padi-professional@hubinternational.com<br/>
{/if}
Phone: 800-223-9998 or 714-739-3177<br/>
Fax: 714-739-3188<br/>
License #0757776
<br/><br/>
PLEASE ADD OUR DOMAINS <a href = "https://www.hubinternational.com/" rel="noopener noreferrer" target="_blank" style = "color:blue;"><u>hubinternational.com</u></a> and <a href = "https://www.diveinsurance.com/" rel="noopener noreferrer" target="_blank" style = "color:blue;"><u>diveinsurance.com</u></a> TO YOUR SAFE SENDERS LIST!
<br/><br/>
You can find us at <a href = "https://www.diveinsurance.com/" rel="noopener noreferrer" target="_blank" style = "color:blue;"><u>www.diveinsurance.com</u></a>
<br/><br/><br/><br/>
This message, together with any attachments, is intended only for the use of the individual or entity to which it is addressed. It may contain information that is confidential and prohibited from disclosure. If you are not the intended recipient, you are hereby notified that any dissemination or copying of this message or any attachment is strictly prohibited. If you have received this message in error, please notify the original sender immediately by telephone or by return e-mail and delete this message, along with any attachments, from your computer. Thank You!

