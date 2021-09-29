<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <style>
            body{
                margin-top:20px;
                color: #484b51;
            }
            .text-secondary-d1 {
                color: #728299!important;
            }
            .page-header {
                margin: 0 0 1rem;
                padding-bottom: 1rem;
                padding-top: .5rem;
                border-bottom: 1px dotted #e2e2e2;
                display: -ms-flexbox;
                display: flex;
                -ms-flex-pack: justify;
                justify-content: space-between;
                -ms-flex-align: center;
                align-items: center;
            }
            .page-title {
                padding: 0;
                margin: 0;
                font-size: 1.75rem;
                font-weight: 300;
            }
            .brc-default-l1 {
                border-color: #dce9f0!important;
            }

            .ml-n1, .mx-n1 {
                margin-left: -.25rem!important;
            }
            .mr-n1, .mx-n1 {
                margin-right: -.25rem!important;
            }
            .mb-4, .my-4 {
                margin-bottom: 1.5rem!important;
            }

            hr {
                margin-top: 1rem;
                margin-bottom: 1rem;
                border: 0;
                border-top: 1px solid rgba(0,0,0,.1);
            }

            .text-grey-m2 {
                color: #888a8d!important;
            }

            .text-success-m2 {
                color: #86bd68!important;
            }

            .font-bolder, .text-600 {
                font-weight: 600!important;
            }

            .text-110 {
                font-size: 110%!important;
            }
            .text-blue {
                color: #478fcc!important;
            }
            .pb-25, .py-25 {
                padding-bottom: .75rem!important;
            }

            .pt-25, .py-25 {
                padding-top: .75rem!important;
            }
            .bgc-default-tp1 {
                background-color: rgba(121,169,197,.92)!important;
            }
            .bgc-default-l4, .bgc-h-default-l4:hover {
                background-color: #f3f8fa!important;
            }
            .page-header .page-tools {
                -ms-flex-item-align: end;
                align-self: flex-end;
            }

            .btn-light {
                color: #757984;
                background-color: #f5f6f9;
                border-color: #dddfe4;
            }
            .w-2 {
                width: 1rem;
            }

            .text-120 {
                font-size: 120%!important;
            }
            .text-primary-m1 {
                color: #4087d4!important;
            }

            .text-danger-m1 {
                color: #dd4949!important;
            }
            .text-blue-m2 {
                color: #68a3d5!important;
            }
            .text-150 {
                font-size: 150%!important;
            }
            .text-60 {
                font-size: 60%!important;
            }
            .text-grey-m1 {
                color: #7b7d81!important;
            }
            .align-bottom {
                vertical-align: bottom!important;
            }
        </style>
    </head>

    <body>
        <div class="page-content container">
            <div class="container px-0">
                <div class="row mt-4">
                    <div class="col-12 col-lg-10 offset-lg-1">

                    <div class="row">
                        <div class="col-sm-6 d-sm-flex justify-content-between">
                            <div class="img-container">
                                <img src="./image/hubLogo.jpg" width="100px"/>
                            </div>
                            <div class="text-grey-m2">
                                <div class="my-2"><span class="text-600 text-90"/>Hub International Transportation Insurance</div>
                                <div class="my-2"><span class="text-600 text-90"/>Phone: 800-880-0975</div>
                                <div class="my-2"><span class="text-600 text-90"/>Fax: 801-943-3889</div>
                            </div>

                        </div>        
                        <div class="text-95 col-sm-6 align-self-start d-sm-flex justify-content-end">
                            <hr class="d-sm-none" />
                            <div class="invoice-tables">
                                <table  class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                        <th scope="col">Invoice #2222344 </th>
                                        <th scope="col">Page 1 of 1</th>
                                        </tr>
                                    </thead>
                                    </table>
                                    <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                        <th scope="col">Account Number </th>
                                        <th scope="col">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>123456</td>
                                            <td>9/27/2021</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                        <th scope="col">Balance Due On</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>9/30/2021</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    <table  class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                        <th scope="col">Amount Paid </th>
                                        <th scope="col">Amount Due</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>100$</td>
                                            <td>300$</td>
                                        </tr>
                                        </tbody>
                                    </table>
                            </div>

                        </div>
                        
                    </div>

                        <div class="mt-4">
                            <div class="row text-600 text-white bgc-default-tp1 py-25">
                                <div class="d-none d-sm-block col-1">#</div>
                                <div class="col-9 col-sm-5">Description</div>
                                <div class="d-none d-sm-block col-4 col-sm-2">Qty</div>
                                <div class="d-none d-sm-block col-sm-2">Unit Price</div>
                                <div class="col-2">Amount</div>
                            </div>

                            <div class="text-95 text-secondary-d3">
                                {foreach from=$data['ledgerData'] item=lineItem key=key}
                                    <div class="row mb-2 mb-sm-0 py-25">
                                        <div class="d-none d-sm-block col-1">{$key+1}</div>
                                        <div class="col-9 col-sm-5">{$lineItem['description']}</div>
                                        <div class="d-none d-sm-block col-2">{$lineItem['quantity']?$lineItem['quantity']:"--"}</div>
                                        <div class="d-none d-sm-block col-2 text-95">{"$".$lineItem['unitCost']}</div>
                                        <div class="col-2 text-secondary-d2">{"$".$lineItem['amount']}</div>
                                    </div>
                                {/foreach}
                            </div>

                            <div class="row border-b-2 brc-default-l2"></div>

                            <div class="row mt-3">
                                <div class="col-12 col-sm-7 text-grey-d2 text-95 mt-2 mt-lg-0">
                                </div>

                                <div class="col-12 col-sm-5 text-grey text-90 order-first order-sm-last">
                                    <div class="row my-2">
                                        <div class="col-7 text-right">
                                            SubTotal
                                        </div>
                                        <div class="col-5">
                                            <span class="text-120 text-secondary-d1">{"$".$data['subtotal']}</span>
                                        </div>
                                    </div>

                                    <div class="row my-2">
                                        <div class="col-7 text-right">
                                            Tax
                                        </div>
                                        <div class="col-5">
                                            <span class="text-110 text-secondary-d1">{$data['tax']?("$".$data['tax']):"--"}</span>
                                        </div>
                                    </div>

                                    <div class="row my-2 align-items-center bgc-primary-l3 p-2">
                                        <div class="col-7 text-right">
                                            Total Amount
                                        </div>
                                        <div class="col-5">
                                            <span class="text-150 text-success-d3 opacity-2">{"$".$data['total']}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <hr />

                        </div>
                    </div>
                </div>
            </div>
        </div>
    
    </body>
</html>