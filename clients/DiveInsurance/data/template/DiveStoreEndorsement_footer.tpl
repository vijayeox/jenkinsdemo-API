<!DOCTYPE html>
<html>
<head>
	<link href= "./css/divebtemplate_css.css" rel="stylesheet" type="text/css" />
  <script>
  function subst() {
        var vars = {};
        var css_selector_classes = [];
        var query_strings_from_url = document.location.search.substring(1).split('&');
        for (var query_string in query_strings_from_url) {
            if (query_strings_from_url.hasOwnProperty(query_string)) {
                var temp_var = query_strings_from_url[query_string].split('=', 2);
                vars[temp_var[0]] = decodeURI(temp_var[1]);
                css_selector_classes.push(temp_var[0]);
            }
        }

        // var css_selector_classes = ['page', 'frompage', 'topage', 'webpage', 'section', 'subsection', 'date', 'isodate', 'time', 'title', 'doctitle', 'sitepage', 'sitepages', 'reference','start_date'];
        for (var css_class in css_selector_classes) {
            if (css_selector_classes.hasOwnProperty(css_class)) {
                var element = document.getElementsByClassName(css_selector_classes[css_class]);
                for (var j = 0; j < element.length; ++j) {
                    element[j].textContent = vars[css_selector_classes[css_class]];
                }
            }
        }
    }
  </script>
</head>
<body class="footer_html" onload="subst()">
  <hr class = "hrtag1"></hr>
  <div class="box">
     <p class = "policy_notice">
          The insurance afforded by this policy is a master policy issued to PADI Worldwide Corporation, 30151 Tomas Street, Rancho Santa Margarita, CA 92688. The insurance is provided under terms and conditions of the master policy which is enclosed with this certificate. Please read the policy for a full description of the terms, conditions and exclusions of the policy. This certificate does not amend, alter or extend the coverage afforded by the policy referenced on this certificate.
        </p>
        <p class = "policy_notice">
          Notice of cancelation: The premium and any taxes or fees are fully earned upon inception and no refund is granted unless cancelled by the company.If the company cancels this policy, 45 days notice will be given to the certificate holder unless cancellation is for nonpayment of premium, then 10 days notice will be provided, and any premium not earned will be returned to the certificate holder.
      </p>
  </div>
	<div class= "footer" id="footer">
			<span class = "footer1">
				Issued on behalf of:
				<p>Tokio Marine Specialty Insurance Company</p>
       <p style = "font-size: 13px;">Page <span class="page"></span> of <span class="topage"></span></p>
			</span>
      <span class = "footer2">
        Date
        <p><span class="start_date"></span></p>
      </span>
			<span class = "footer3">
				<center>
					<p class = "footer_title">Authorized Representative</p>
					<img class="img" height = "40" width ="200" src = "./image/steve_vicencia.png"/>
					<hr class = "hrtag2"></hr>
					Steve Vicencia CPCU
				</center>
			</span>
	</div>
</body>
</html>
