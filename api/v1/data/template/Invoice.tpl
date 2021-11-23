<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Example 1</title>
    <style>
    .clearfix:after {
        content: "";
        display: table;
        clear: both;
    }

    a {
    color: #5d6975;
    text-decoration: underline;
    }

    body {
    position: relative;
    width: 21cm;
    height: 29.7cm;
    margin: 0 auto;
    color: #001028;
    background: #ffffff;
    font-family: Arial, sans-serif;
    font-size: 12px;
    font-family: Arial;
    }

    header {
    padding: 10px 0;
    margin-bottom: 30px;
    }

    #logo {
    text-align: center;
    margin-bottom: 10px;
    }

    #logo img {
    width: 90px;
    }

    h1 {
    border-top: 1px solid #5d6975;
    border-bottom: 1px solid #5d6975;
    color: #5d6975;
    font-size: 2.4em;
    line-height: 1.4em;
    font-weight: normal;
    text-align: center;
    margin: 0 0 20px 0;
    background: url(dimension.png);
    }

    #project {
    display: flex;
    float: left;
    align-items: center;
    /* justify-content: space-between; */
    }

    #project span {
    color: #5d6975;
    text-align: right;
    width: 52px;
    margin-right: 10px;
    display: inline-block;
    font-size: 0.8em;
    }

    #project div {
    margin-left: 10px;
    }

    #company {
    float: right;
    text-align: right;
    }

    #project div,
    #company div {
    white-space: nowrap;
    }

    .header-table td {
    padding: 5px;
    text-align: right;
    }
    .header-table th,
    .header-table td {
    text-align: center;
    }

    table {
    width: 100%;
    border-collapse: collapse;
    border-spacing: 0;
    margin-bottom: 20px;
    }

    table tr:nth-child(2n-1) td {
    background: #f5f5f5;
    }

    table th,
    table td {
    text-align: left;
    }

    table th {
    padding: 5px 20px;
    color: #5d6975;
    border-bottom: 1px solid #c1ced9;
    white-space: nowrap;
    font-weight: normal;
    }

    table .service,
    table .desc {
    text-align: left;
    }

    table td {
    padding: 20px;
    text-align: right;
    }

    table td.service,
    table td.desc {
    vertical-align: top;
    }

    table td.unit,
    table td.qty,
    table td.total {
    font-size: 1.2em;
    }

    table td.grand {
    border-top: 1px solid #5d6975;
    }

    #notices .notice {
    color: #5d6975;
    font-size: 1.2em;
    }

    footer {
    color: #5d6975;
    width: 100%;
    height: 30px;
    position: absolute;
    bottom: 0;
    border-top: 1px solid #c1ced9;
    padding: 8px 0;
    text-align: center;
    }

/* new CSS */
    .align-left{
      text-align: left;
    }
    </style>
  </head>
  <body>
    <header class="clearfix">
      <!-- <h1>INVOICE</h1> -->
      <div id="company" class="clearfix">
      </div>
      <div id="project">
                <div class="invoice-tables">
          <table class="header-table">
            <thead>
              <tr>
                <th scope="col">{"Invoice: `$invoiceNumber`"}</th>
              </tr>
            </thead>
          </table>
          <table class="header-table">
            <thead>
              <tr>
                <th scope="col">Account Number </th>
                <th scope="col">Date</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>{$accountNumber}</td>
                <td>{$invoiceDate}</td>
              </tr>
            </tbody>
          </table>
          <table class="header-table">
            <thead>
              <tr>
                <th scope="col">Balance Due On</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>{$invoiceDueDate}</td>
              </tr>
            </tbody>
          </table>
          <table class="header-table">
            <thead>
              <tr>
                <th scope="col">Amount Paid </th>
                <th scope="col">Amount Due</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                {if isset($amountPaid)}
                <td>{"$`$amountPaid`"}</td>
                {else}
                <td>$0.0</td>
                {/if}
                <td>{"$`$total`"}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </header>
    <main>
      <table>
        <thead>
          <tr>
            <th class="service">#</th>
            <th class="desc">DESCRIPTION</th>
            <th>Effective Date</th>
            <th>Due Date</th>
            <th class="align_right">TOTAL</th>
          </tr>
        </thead>
        <tbody>
            {foreach from=$ledgerData item=lineItem key=key}
                <tr>
                    <td class="service align-left">{$key+1}</td>
                    <td class="desc align-left">{$lineItem['description']}</td>
                    
                    {if isset($lineItem['transactionEffectiveDate'])}
                    <td class="unit align-left">{$lineItem['transactionEffectiveDate']}</td>
                    {else}
                    <td class="unit align-left">--</td>
                    {/if}

                    {if isset($lineItem['transactionDueDate'])}
                    <td class="qty align-left">{$lineItem['transactionDueDate']}</td>
                    {else}
                    <td class="qty align-left">--</td>
                    {/if}
                    
                    <td class="total align-left">{"$`$lineItem['amount']`"}</td>
                </tr>
            {/foreach}
          <tr>
            <td colspan="4">SUBTOTAL</td>
            <td class="total align-left">{"$`$subtotal`"}</td>
          </tr>
          <tr>
            <td colspan="4">TAX</td>
            {if isset($tax)}
                <td class="total align-left">{"$`$tax`"}</td>
            {else}
                <td class="total align-left">--</td>
            {/if}

          </tr>
          <tr>
            <td colspan="4" class="grand total">GRAND TOTAL</td>
            <td class="grand total align-left">{"$`$total`"}</td>
          </tr>
        </tbody>
      </table>
    </main>
  </body>
</html>