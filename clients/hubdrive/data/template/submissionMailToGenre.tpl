{* Submission to Genre Mail Template *}
{assign var=list value=$stateObj|json_decode:true}
<div style='line-height: 24px'>
	<p>Good Morning,</p>
    <p>Please quote the attached submission.</p>
 	<br/>
    <p><b>Account Name :</b> {$insuredName} </p>
    <p><b>Account City and State :</b> {$city}, {$list.abbreviation}</p>
    <p><b>Effective Date :</b> {$quoteByDateFormatted|date_format:"%m/%d/%Y"} </p>
 	<p><b>Hub Producer Name and location :</b> {$producerRegion} - {$producerName} </p>
    <p><b>Description of operations :</b> {$descriptionOfOperations} </p>
    <p><b>Limits needed in Excess Layer :</b> ${$limitsNeededExcess} </p>
    {if isset($ExcessCvrg)}
        <p><b>Excess Coverages(AL only,AL & GL or AL,GL & EL) :</b> {$ExcessCvrg} </p>
    {/if}
    <p><b>Need by date :</b> {$desiredPolicyEffDateFormatted|date_format:"%m/%d/%Y"} </p>
    <p><b>Target Permium (if avaliable) :</b> {$targetPremium|number_formt:2} </p>
</div>
<br/>
<p>Please "replay all" when responding.</p>
<br/>
<p>Thank you,</p>

<br/><br/>
<img alt="Avant" width = "250px" height = "200px" src = "{$avantImageUrl}"/>
AccountManager Department<br/>

<br/><br/>
<p>Confidentiality Notice: This electronic message, together with its attachments, if any, is intended to be viewed only by the individual to whom it is addressed. It may contain information that is privileged, confidential, protected health information and/or exempt from disclosure under applicable law. Any dissemination, distribution or copying of this communication is strictly prohibited without our prior permission. If the reader of this message is not the intended recipient or if you have received this communication in error, please notify us immediately by return e-mail and delete the original message and any copies of it from your computer system.</p>
