  {* CSR Rejection Mail Template *}

            <div style='width:100%;background:#452767;color:#fff;height:35px;margin-bottom:2px'>
            </div>
            <div style='line-height: 24px'>Dear {$firstname} {$lastname}, <br/><br/>
                 <p>{$lastname},{$firstname} {if isset($initial)},{$initial}{/if}<br/>
					{$address1}<br/>
					{$address2}<br/>
					{$city},{$state_in_short} - {$zip}<br/>
					{$country}<br/></p>
				<br/>
				<p>This mail is regarding the rejection of your policy. Your policy is rejected because of the following reason:<br/>{$rejectionReason}</p><br/>
				
				<p>We thank you for your long-standing relationship with PADI and Vicencia & Buckley, a Division of HUB International Insurance Services Inc. If you have any questions feel free to give us a call or send us an email.</p>
			 <br/>

            </div>

Regards,
<br/><br/>
Vicencia & Buckley A Division of HUB International
