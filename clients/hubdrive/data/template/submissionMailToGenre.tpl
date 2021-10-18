{* Submission to Genre Mail Template *}
<html>
<head>
    <style>
        .signature_row{
            display:flex;
            width:100%;
        }
        .signature_column1{
            float:left;
            width:20%;
            border-right: 2px solid #808080;
            margin-right: 1%;
        }
        .signature_column2{
            float:left;
            width:60%;
        }
    </style>
</head>
<body>
{assign var=list value=$stateObj|json_decode:true}
<div style='line-height: 24px'>
	<p>Hi,</p>
    <p>{$avantNote}</p>
    <p>Please quote the attached submission.</p>
 	<br/>
    <p><b>Account Name :</b> {$insuredName} </p>
    <p><b>Account City and State :</b> {$city}, {$list.abbreviation}</p>
    <p><b>Effective Date :</b> {$desiredPolicyEffDateFormatted|date_format:"%m/%d/%Y"} </p>
 	<p><b>Hub Producer Name and location :</b> {$producerRegion} - {$producerName} </p>
    <p><b>Description of operations :</b> {$descriptionOfOperations} </p>
    <p><b>Limits needed in Excess Layer :</b> ${$limitsNeededExcess} </p>
    {if isset($ExcessCvrg)}
        <p><b>Excess Coverages(AL only,AL & GL or AL,GL & EL) :</b> {$ExcessCvrg} </p>
    {/if}
    <p><b>Need by date :</b>  {$quoteByDateFormatted|date_format:"%m/%d/%Y"}</p>
    <p><b>Target Permium (if avaliable) :</b> {$targetPremium|number_format:2} </p>
</div>
<br/>
<p>Please "replay all" when responding.</p>
<br/>
<p>Thank you,</p>

<br/><br/>
<div class = "signature_row">
    <div class = "signature_column1">
    <p><img alt="Avant" width = "200px" height = "200px" src = "{$avantImageUrl}"/></p>
    </div>
    <div class = "signature_column2">
    <p><span style = "font-color:#1F497D;"><b>{$AvantName} | {$AvantDesignation}</b></span></p>
    <p>Direct 615-866-5093  | | <span style = "font-color:blue;" ><u>{$AvantMailId}</u></span></p>
    <br/>
    <p><span style = "font-color : #d6e03d;"><b>Avant Brokerage</b></span>, Division of Specialty Program Group, LLC</p>
    <p>P.O. Box 1540 | Lee’s Summit, MO 64063 | <a href = "https://avantins.com/" rel="noopener noreferrer" target="_blank" style = "color:blue;"><u>AvantBrokerage.com</u></a></p>
    <br/>
    <br/>
    <p style = "font-color : #d6e03d;">Our office closes at 4:00 p.m. CST on Fridays.</p>
    </div>
</div>



<br/><br/>
<p>Confidentiality Notice: This electronic message, together with its attachments, if any, is intended to be viewed only by the individual to whom it is addressed. It may contain information that is privileged, confidential, protected health information and/or exempt from disclosure under applicable law. Any dissemination, distribution or copying of this communication is strictly prohibited without our prior permission. If the reader of this message is not the intended recipient or if you have received this communication in error, please notify us immediately by return e-mail and delete the original message and any copies of it from your computer system.</p>
</body>
</html>