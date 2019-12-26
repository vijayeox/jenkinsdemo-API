<!DOCTYPE html>
<html>

<head>
    <!-- <link href="{$smarty.current_dir}/css/divestemplate_css.css" rel="stylesheet" type="text/css" /> -->
    <link href="./css/divestemplate_css.css" rel="stylesheet" type="text/css" />
    <!-- <script type="text/javascript" src="{$smarty.current_dir}/AgentInfo.js"></script> -->
</head>

<body>  

    <div class="checkbox_sec">
        <p>We are pleased to enclose your PADI Endorsed Dive Center proposal. Our program includes:</p>
        <ul>
            <li>Damage to Premise you rent includes water damage and smoke and explosion in addition to fire damage.
            </li>
            <li>Contingent Professional Liability - See policy for coverage terms.</li>
            <li>No coinsurance penalty on property coverage.</li>
            <li>Accidental Compressor Breakdown (excluding wear and tear and normal maintenance).</li>
            <li>Optional Group Professional Liability.</li>
            <li>U.S. A XV rated insurer.</li>
            <li>Unlimited defense costs for covered claims.</li>
            <li>Non-motorized watercraft less than 20 feet in length.</li>
            <li>No liability deductible.</li>
            <li>Worldwide liability coverage - except where not allowed by law.</li>
            <li>Optional Travel Agents Liability.</li>
            <li>Optional higher Property Deductible of $2,500.00 or $5,000.00.</li>
            <li>Please note: Your Dive Center general liability insurance does not cover the supervision and instruction
                of
                swimmers. This can only be covered by an individual or group professional liability policy.</li>
        </ul>
        <b>
            <p>To purchase your insurance coverage, please provide us with the following items:</p>
        </b>
        <!-- QuoteReqmtCalc -->
        <div style="margin-left: 1%;">
        <p class="p_margin">
            <span>[X]</span>&nbsp;
            {if $installments >=1 || $installments <=6}
            <span>A deposit of {$TotalDueCalc} or full payment of {$FullPayment}</span>   
            {else}
            <span>Full payment of ${$QuoteReqmt1calc}</span>
            {/if}
        </p>
        <p class="p_margin">
            <span>[X]</span>&nbsp;
            <span>Signed Proposal.</span>
        </p>
        <p class="p_margin">
            <span>[X]</span>&nbsp;
            <span>have also attached a Business Income Worksheet. This coverage is if you have a covered property loss and your
                    business is forced to close temporarily. It will help you pay for lost net income and continuing expenses. Our quote gives
                    you $200,000 in coverage, so this is the most the insurance company will pay for a covered loss. This form will help you
                    determine how much Business Income Coverage you need to cover your net income and continuing expenses. We
                    encourage you to complete the attached form and return it to me for a quote.</span>
        </p>
        <p class="p_margin">
            <span>[X]</span>&nbsp;
            <span>Financing available if not paying in full.</span>
        </p>
        </div>        


        <p>Thank you for your support of the PADI Endorsed Dive Center insurance program. Please call or email me if you
            have any questions.</p>
        <p>Sincerely,</p>
        

        <!-- Lisa Cossey, CISR, Customer Service -->
        <p><span>{$AcctRep}</span>,&nbsp;<span>{$title}</span></p>
        <p class="p_margin">{$ProducerBusinessName}</p>
        <p class="p_margin"><span>{$ProducerPhone2}<span> or <span>{$RepPhone}</span></p>
        <p class="p_margin">{$RepEmail}</p>

    </div>
</body>

</html>