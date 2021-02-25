<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="width=device-width" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Arrowhead AutoDealer</title>
    <link href= "{$smarty.current_dir}/css/mail.css" rel="stylesheet" type="text/css" />
  </head>
  <body class="">
    <span class="preheader">Arrowhead AutoDealer New Policy Submision</span>
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
                        <p>Hi there,</p>
                        <br />
                        <p>Producer Name : {$producername}</p>
                        <p>Insured Name : {$namedInsured}</p>
                        <p>Phone : {$businessPhone}</p>
                        <p>Email : {$businessEmail}</p>
                        <br />
                        {if $mailTemplateFlag == 0}
                          <p>
                            The above referenced account has been submitted on the
                            ArrowHead portal. Please find the attached carrier
                            documents.
                          </p>
                        {else}
                          <p>
                            The above referenced account has been submitted on the
                            ArrowHead portal. Since the attachment size is too big please
                            download the files from the portal.
                          </p>
                        {/if}
                        <br />
                        Thank you
                        <br />
                        <br />
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
              <!-- END MAIN CONTENT AREA -->
            </table>
            <!-- END CENTERED WHITE CONTAINER -->
            <!-- START FOOTER -->
            <div class="footer">
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
            </div>
            <!-- END FOOTER -->
          </div>
        </td>
        <td>&nbsp;</td>
      </tr>
    </table>
  </body>
</html>
