<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
  <head>
    <link href="./css/template_css.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="./AgentInfo.js"></script>
    <script type="text/javascript" src="./js/moment.js"></script>
    <script>
      function subst() {
        var vars = {};
        var data = {};
        var split = {};

        var query_strings_from_url = document.location.search
          .substring(1)
          .split("&");
        for (var query_string in query_strings_from_url) {
          if (query_strings_from_url.hasOwnProperty(query_string)) {
            var temp_var = query_strings_from_url[query_string].split("=", 2);
            vars[temp_var[0]] = decodeURI(temp_var[1]);
            
            // vars[temp_var[0]] = decodeURIComponent(temp_var[1]);
            // vars[temp_var[0]] = decodeURIComponent(unescape(temp_var[1]));
          }
        }
        var css_selector_classes = [
          "page",
          "frompage",
          "topage",
          "webpage",
          "section",
          "subsection",
          "date",
          "isodate",
          "time",
          "title",
          "doctitle",
          "sitepage",
          "sitepages",
          "start_date",
          "license_number",
          "firstname",
          "lastname",
          "city",
          "state",
          "country",
          "zipcode",
          "certificate_no",
          "padi",
          "end_date",
          "policy_id",
          "address1",
          "address2"
        ];
        for (var css_class in css_selector_classes) {
          if (css_selector_classes.hasOwnProperty(css_class)) {
            var element = document.getElementsByClassName(
              css_selector_classes[css_class]
            );
            for (var j = 0; j < element.length; ++j) {
              element[j].textContent = vars[css_selector_classes[css_class]];
            }
            if (css_selector_classes[css_class] == "start_date") {
              document.getElementsByClassName(
                "start_date"
              )[0].textContent = moment(
                vars[css_selector_classes[css_class]]
              ).format("D MMMM YYYY");
            }
            if (css_selector_classes[css_class] == "end_date") {
              document.getElementsByClassName("end_date")[0].textContent =
                moment(vars[css_selector_classes[css_class]]).format(
                  "D MMMM YYYY"
                ) + " - 12:01:00 AM";
            }
          }
        }
        agentInfo();
      }
    </script>
  </head>
  <body onload="subst()" id="doc_body">
    <div class="main_div_ai">
      <hr class="line1" />
      <div class="spacer"></div>
      <hr class="line2" />
      <center>
        <div class="title1"><b>PADI PROFESSIONAL LIABILITY</b></div>
        <div class="title2"><b>ADDITIONAL INSURED(S) ENDORSEMENT</b></div>
      </center>
      <hr class="line1" />
      <div class="spacer"></div>
      <hr class="line2" />
      <div class="content">
        <div class="content1">
          <b class="caption">Contact Agent for information reporting Claims</b>
          <div class="caption1">
            <p class="info" id="nameVal"></p>
            <p class="info" id="addressLineVal"></p>
						<p class ="info" id = "addressLine2Val"></p>
            <p class="info" style="margin-bottom:2px;">
              <span id="phone1Val"></span>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspFAX
              <span id="faxVal"></span>
            </p>
            <p class="info" id="phone2Val" style="margin-bottom:2px;"></p>
            <p class="info">License#: <span class="license_number"></span></p>
          </div>
          <b class = "caption2">Insured's Name and Mailing Address:</b>
          <p class = "details">{$lastname},{$firstname} {if isset($initial)},{$initial}{/if}</p>
          <p class = "details">{$address1}</p>
          <p class = "details">{$address2}</p>
          <p class = "details">{$city},{$state_in_short} - {$zip}</p>
          <p class = "details">{$country}</p>
        </div>
        <div class="content2">
          <div class="certificate_data">
            <p class="p_margin"><b>Certificate #:</b></p>
            <p class="p_margin"><b>Member #:</b></p>
            <p class="p_margin"><b>Effective Date:</b></p>
            <p class="p_margin"><b>Expiration Date:</b></p>
          </div>
          <div class="certificate_data1">
            <p class="p_margin"><span class="certificate_no"></span></p>
            <p class="p_margin"><span class="padi"></span></p>
            <p class="p_margin"><span class="start_date"></span></p>
            <p class="p_margin"><span class="end_date"></span></p>
          </div>
          <div>
            <hr />
            <p class="p_margin">
              Policy issued by Tokio Marine Specialty Insurance Company
            </p>
            <p class="p_margin">Policy #: <span class="policy_id"></span></p>
            <hr />
            <p class="efr_bold"><b>EFR</b></p>
            <p class="efr_title2"><b>Emergency First Response Corporation</b></p>
            <p class="efr_title2">30151 Tomas Street</p>
            <p class="efr_title2">Rancho Santa Margarita, CA 92688</p>
          </div>
        </div>
      </div>
    </div>
    <hr class="hr_value" />
    <div>&nbsp</div>
  </body>
</html>