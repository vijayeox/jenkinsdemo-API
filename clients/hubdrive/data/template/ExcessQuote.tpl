<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<style>
.rowInfo:after {
  content: "";
  display: flex;
  clear: both;
  width:100%;
  font-size:15px;
}
.column1 {
  float: left;
  width: 30%;
}
.column2 {
  float: left;
  width: 70%;
}
.list{
    padding: 1px;
}
</style>
</head>
<body>
	<div class ="body_div">
        <div class="inline-img">
                <img alt="Avant" width = "250px" height = "200px" src = "{$avantImageSrc}"/>
        </div>
        <div class="mb-4">
                <p> &nbsp </p>
        </div>
        <b><center><p style = "font-size:25px;">Excess Liability Quotation</p></center></b>
        <div style = "margin-left:6%;">
            <div class = "rowInfo">
                <p class = "column1"><b>Insured:</b></p>
                <p class = "column2">{$insuredName} </br>{$city},US</p>
            </div>
            <div class = "rowInfo">
                <p class = "column1"><b>Carrier:</b></p>
                <p class = "column2">Trisura Specialty Insurance Company</p>
            </div>
            <div  class = "rowInfo">
                <p class = "column1"><b>Proposed Policy Period:</b></p>
                <p class = "column2">{$start_date|date_format:"%B %e, %Y"} to {$proposedPolicyEndDate|date_format:"%B %e, %Y"}</p>
            </div>
            <div class = "rowInfo">
                <p class = "column1"><b>Quotation is Valid Thru:</b></p>
                <p class = "column2">{$quotationValidThruDate|date_format:"%B %e, %Y"}<br/><p style = "margin-left:30%;font-size:12px;">Subject to no material changes to the information previously provided.</p></p>
            </div>
            <div class = "rowInfo">
                <p class = "column1"><b>Coverage:</b></p>
                <p class = "column2">Excess Liability</p>
            </div>
            <div class = "rowInfo">
                <p class = "column1"><b>Limits of Liability:</b></p>
                <p class = "column2">{$limitsOfLiability}</p>
            </div>
            <div class = "rowInfo">
                <p class = "column1"><b>Premium:</b></p>
                <p class = "column2">$ {$premium}</p>
            </div>
            <div class = "rowInfo">
                <p class = "column1"><b>Surplus Tax:</b></p>
                <p class = "column2">$ {$surplusTaxAmount}    {$surplusTaxPercentage}%</p>
            </div>
            <div class = "rowInfo">
                <p class = "column1"><b>Stamping fee:</b></p>
                <p class = "column2">$ {$stampingFeeAmount}    {$stampingFeePercentage}%</p>
            </div>
            <div class = "rowInfo">
                <p class = "column1"><b>Commission:</b></p>
                <p class = "column2">{$commission}%</p>
            </div>
        </div>
        <div style = "margin-left:6%;margin-top:5%;font-size:15px;">
        <div>&nbsp</div>
        <b><p style = "margin-top:2%;">Terms and Conditions:</p></b>
        <p style = "margin-top:4%;"><b>The following exclusions apply:</b></p>
        <ul>
            <li class = "list">Uninsured/Underinsured motorists exclusion</li>
            <li class = "list">Asbestos exclusion</li>
            <li class = "list">C\C\C</li>
            <li class = "list">EPLI</li>
            <li class = "list">Total Pollution</li>
            <li class = "list">Communicable Disease</li>
            <li class = "list">Transportation Brokerage</li>
            <li class = "list">Lead, Mold, Silica</li>
        </ul>
        </div>
        <div style = "margin-left:6%; font-size:15px;">
            <p><b>Surplus Lines Taxes requirements:</b></p>
            <p>Evidence of Due Diligent Effort - Only 1 declination Required</p>
            <p><b>Additional requirements: n/a</b></p>
        </div>
	</div>
</body>
</html>
