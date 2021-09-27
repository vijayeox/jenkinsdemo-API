<!DOCTYPE html>
<html>
    <head>
        <style>
        img {
            border: none;
            -ms-interpolation-mode: bicubic;
            max-width: 100%;
        }

        body {
            background-color: #f6f6f6;
            font-family: sans-serif;
            -webkit-font-smoothing: antialiased;
            font-size: 14px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        table {
            border-collapse: separate;
            mso-table-lspace: 0pt;
            mso-table-rspace: 0pt;
            width: 100%;
        }

        table td {
            font-family: sans-serif;
            font-size: 14px;
            vertical-align: top;
        }


        /* -------------------------------------
        BODY & CONTAINER
        ------------------------------------- */

        .body {
            background-color: #f6f6f6;
            width: 100%;
        }


        /* Set a max-width, and make it display as block so it will automatically stretch to that width, but will also shrink down on a phone or something */

        .container {
            display: block;
            margin: 0 auto !important;
            /* makes it centered */
            max-width: 580px;
            padding: 10px;
            width: 580px;
        }


        /* This should also be a block element, so that it will fill 100% of the .container */

        .content {
            box-sizing: border-box;
            display: block;
            margin: 0 auto;
            max-width: 580px;
            padding: 10px;
        }


        /* -------------------------------------
        HEADER, FOOTER, MAIN
        ------------------------------------- */

        .main {
            background: #ffffff;
            border-radius: 3px;
            width: 100%;
        }

        .wrapper {
            box-sizing: border-box;
            padding: 20px;
        }

        .content-block {
            padding-bottom: 10px;
            padding-top: 10px;
        }

        .footer {
            clear: both;
            margin-top: 10px;
            text-align: center;
            width: 100%;
        }

        .footer td,
        .footer p,
        .footer span,
        .footer a {
            color: #999999;
            font-size: 12px;
            text-align: center;
        }


        /* -------------------------------------
        TYPOGRAPHY
        ------------------------------------- */

        h1,
        h2,
        h3,
        h4 {
            color: #000000;
            font-family: sans-serif;
            font-weight: 400;
            line-height: 1.4;
            margin: 0;
            margin-bottom: 30px;
        }

        h1 {
            font-size: 35px;
            font-weight: 300;
            text-align: center;
            text-transform: capitalize;
        }

        p,
        ul,
        ol {
            font-family: sans-serif;
            font-size: 14px;
            font-weight: normal;
            margin: 0;
            margin-bottom: 15px;
        }

        p li,
        ul li,
        ol li {
            list-style-position: inside;
            margin-left: 5px;
        }

        a {
            color: #3498db;
            text-decoration: underline;
        }


        /* -------------------------------------
        BUTTONS
        ------------------------------------- */

        .btn {
            box-sizing: border-box;
            width: 100%;
        }

        .btn>tbody>tr>td {
            padding-bottom: 15px;
        }

        .btn table {
            width: auto;
        }

        .btn table td {
            background-color: #ffffff;
            border-radius: 5px;
            text-align: center;
        }

        .btn a {
            background-color: #ffffff;
            border: solid 1px #3498db;
            border-radius: 5px;
            box-sizing: border-box;
            color: #3498db;
            cursor: pointer;
            display: inline-block;
            font-size: 14px;
            font-weight: bold;
            margin: 0;
            padding: 12px 25px;
            text-decoration: none;
            text-transform: capitalize;
        }

        .btn-primary table td {
            background-color: #3498db;
        }

        .btn-primary a {
            background-color: #3498db;
            border-color: #3498db;
            color: #ffffff;
        }


        /* -------------------------------------
        OTHER STYLES THAT MIGHT BE USEFUL
        ------------------------------------- */

        .last {
            margin-bottom: 0;
        }

        .first {
            margin-top: 0;
        }

        .align-center {
            text-align: center;
        }

        .align-right {
            text-align: right;
        }

        .align-left {
            text-align: left;
        }

        .clear {
            clear: both;
        }

        .mt0 {
            margin-top: 0;
        }

        .mb0 {
            margin-bottom: 0;
        }

        .preheader {
            color: transparent;
            display: none;
            height: 0;
            max-height: 0;
            max-width: 0;
            opacity: 0;
            overflow: hidden;
            mso-hide: all;
            visibility: hidden;
            width: 0;
        }

        .powered-by a {
            text-decoration: none;
        }

        hr {
            border: 0;
            border-bottom: 1px solid #f6f6f6;
            margin: 20px 0;
        }


        /* -------------------------------------
        RESPONSIVE AND MOBILE FRIENDLY STYLES
        ------------------------------------- */

        @media only screen and (max-width: 620px) {
            table[class="body"] h1 {
                font-size: 28px !important;
                margin-bottom: 10px !important;
            }
            table[class="body"] p,
            table[class="body"] ul,
            table[class="body"] ol,
            table[class="body"] td,
            table[class="body"] span,
            table[class="body"] a {
                font-size: 16px !important;
            }
            table[class="body"] .wrapper,
            table[class="body"] .article {
                padding: 10px !important;
            }
            table[class="body"] .content {
                padding: 0 !important;
            }
            table[class="body"] .container {
                padding: 0 !important;
                width: 100% !important;
            }
            table[class="body"] .main {
                border-left-width: 0 !important;
                border-radius: 0 !important;
                border-right-width: 0 !important;
            }
            table[class="body"] .btn table {
                width: 100% !important;
            }
            table[class="body"] .btn a {
                width: 100% !important;
            }
            table[class="body"] .img-responsive {
                height: auto !important;
                max-width: 100% !important;
                width: auto !important;
            }
        }


        /* -------------------------------------
        PRESERVE THESE STYLES IN THE HEAD
        ------------------------------------- */

        @media all {
            .ExternalClass {
                width: 100%;
            }
            .ExternalClass,
            .ExternalClass p,
            .ExternalClass span,
            .ExternalClass font,
            .ExternalClass td,
            .ExternalClass div {
                line-height: 100%;
            }
            .apple-link a {
                color: inherit !important;
                font-family: inherit !important;
                font-size: inherit !important;
                font-weight: inherit !important;
                line-height: inherit !important;
                text-decoration: none !important;
            }
            #MessageViewBody a {
                color: inherit;
                text-decoration: none;
                font-size: inherit;
                font-family: inherit;
                font-weight: inherit;
                line-height: inherit;
            }
            .btn-primary table td:hover {
                background-color: #34495e !important;
            }
            .btn-primary a:hover {
                background-color: #34495e !important;
                border-color: #34495e !important;
            }
        }
        </style>
    </head>
    <body>
        <span class="preheader">HubDrive Online Compliance Submision</span>
        <table
        role="presentation"
        border="0"
        cellpadding="0"
        cellspacing="0"
        class="body"
        >
        <tr>
            <td>&nbsp;</td>
            <td class="container">
            <div class="content">
                <!-- START CENTERED WHITE CONTAINER -->
                <table role="presentation" class="main">
                <!-- START MAIN CONTENT AREA -->
                <tr>
                    <td class="wrapper">
                    <table
                        role="presentation"
                        border="0"
                        cellpadding="0"
                        cellspacing="0"
                    >
                        <tr>
                        <td>
                            <p> RSP Name : {if ((isset($name) && $name != ""))}<span>{$name}</span>{else}<span>____</span>{/if}</p>
                            <p> Business Name : {if ((isset($companyName) && $companyName != ""))}<span>{$companyName}</span>{else}<span>____</span>{/if}</p>
                            <p> Driver # : {if ((isset($vendorNumber) && $vendorNumber != ""))}<span>{$vendorNumber}</span>{else}<span>____</span>{/if}</p>
                            <p> Facility : {if ((isset($pleaseSelectTheFacility) && $pleaseSelectTheFacility != ""))}<span>{$pleaseSelectTheFacility}</span>{else}<span>____</span>{/if}</p>

                            <p>Welcome to OnTrac Compliance,</p>
                            <p>Please see the attached checklist regarding the status of your Certificate of Insurance for OnTrac.</p>
                            
                            <p>If you need to supply a Revised Certificate of Insurance (COI), please send to <a href='HDOLsupport@hubinternational.com'>HDOLsupport@hubinternational.com</a>.</p>

                            <p>If you have any questions about this mail, please contact <a href='VendorRelations@ontrac.com'>VendorRelations@ontrac.com</a> or your OnTrac VR Specialist</p>
                            <br />
                            <br />
                        </td>
                        </tr>
                        <tr>
                        <td><span
                            style="
                                font-size: 10pt;
                                font-family: 'Times New Roman', serif;
                                color: #1155cc;
                                border: none windowtext 1pt;
                                padding: 0in;
                                mso-fareast-language: EN-IN;
                                text-decoration: none;
                            "
                            ><img
                                border="0"
                                width="100"
                                height="50"
                                id="_x0000_i1028"
                                src="./image/hub.jpg" /></span
                        ></td></tr>
                        
                        <tr><td><p style= "font-size: 10pt; font-family: 'Times New Roman', serif;margin-bottom: 0px;"> Risk & Insurance | Employee Benefits | Retirement & Private Wealth </p></td></tr>
                        <tr><td><p style= "font-size: 12pt; font-family: 'Times New Roman', serif;color:#0678d5;font-weight:bold">Ready for tomorrow</p></td></tr>
                        <br>
                        <tr><td><p style= "font-size: 12pt; font-family: 'Times New Roman', serif;font-weight:bold">HUB Compliance</p>
                        
                        </td>
                        </tr>
                    </table>
                    </td>
                </tr>
                <!-- END MAIN CONTENT AREA -->
                </table>
                <!-- END CENTERED WHITE CONTAINER -->
                <!-- START FOOTER -->
                <!-----<div class="footer">
                <table
                    class="MsoNormalTable"
                    border="0"
                    cellspacing="0"
                    cellpadding="0"
                    style="border-collapse: collapse"
                >
                    <tbody>
                    <tr style="height: 13pt">
                        <p class="MsoNormal">
                        <a
                            href="http://www.eoxvantage.com/"
                            target="_blank"
                            title="http://www.eoxvantage.com/"
                            ><span
                            style="
                                font-size: 10pt;
                                font-family: 'Times New Roman', serif;
                                color: #1155cc;
                                border: none windowtext 1pt;
                                padding: 0in;
                                mso-fareast-language: EN-IN;
                                text-decoration: none;
                            "
                            ><img
                                border="0"
                                width="100"
                                height="100"
                                style="
                                width: 1.1137in;
                                height: 1.1145in;
                                max-width: 100vw;
                                max-height: 101.305vw;
                                "
                                id="_x0000_i1028"
                                src="https://lh5.googleusercontent.com/Aj3VBqHWUKzF7oXEnV2cCHND8uL0b8xTci2Y0gL8vy0UiOW5231RHh9wUy8mRlEp-P2mIRYh0kjIyzRm39TYL_VVVjVk9fcvxdXO-nv7khbHjCuU0NO5FqKYJgMEggwBkyZkKJt3" /></span
                        ></a>
                        </p>
                    </tr>
                    </tbody>
                </table>
                <table
                    role="presentation"
                    border="0"
                    cellpadding="0"
                    cellspacing="0"
                >
                    <tr>
                    <td class="content-block">
                        <span class="apple-link"
                        >23611 Chagrin Blvd. Suite 320
                        </span>
                        <br />
                        <span class="apple-link"> Beachwood, OH 44122 </span>
                    </td>
                    </tr>
                    <tr>
                    <td class="content-block powered-by">
                        Powered by <a href="http://htmlemail.io">EOS</a>
                    </td>
                    </tr>
                </table>
                </div>----->
                <!-- END FOOTER -->
            </div>
            </td>
            <td>&nbsp;</td>
        </tr>
        </table>
    </body>
</html>