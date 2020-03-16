<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<link href= "./css/divebtemplate_css.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="./AgentInfo.js"></script>
<script>
  function subst() {
      var vars = {};
      var data = {};
      var split = {};

      var query_strings_from_url = document.location.search.substring(1).split('&');
      for (var query_string in query_strings_from_url) {
          if (query_strings_from_url.hasOwnProperty(query_string)) {
              var temp_var = query_strings_from_url[query_string].split('=', 2);
              vars[temp_var[0]] = decodeURI(temp_var[1]);
          }
      }
       var css_selector_classes = ['page', 'frompage', 'topage', 'webpage', 'section', 'subsection', 'date', 'isodate', 'time', 'title', 'doctitle', 'sitepage', 'sitepages','start_date','license_number','firstname','lastname','city','state','country','zip','certificate_no','padi','end_date','policy_id','address1','address2','state_in_short','update_date','business_name'];
      for (var css_class in css_selector_classes) {
          if (css_selector_classes.hasOwnProperty(css_class)) {
              var element = document.getElementsByClassName(css_selector_classes[css_class]);
              for (var j = 0; j < element.length; ++j) {
                  element[j].textContent = vars[css_selector_classes[css_class]];
              }
          }
      }
      agentInfo();
  }
  </script>
</head>
<body onload="subst()" id = "doc_body">  
 <div class = "main_div">
 	       	  <hr class="line1"></hr>
      		<div class="spacer"></div>
      		<hr class="line2"></hr>

      		<div class="title1"><center><b>DIVE CENTER CERTIFICATE OF INSURANCE</b></center></div>

          <hr class="line1"></hr>
          <div class="spacer"></div>
          <hr class="line2"></hr>

    <div class = "content">
      <div class ="content1">
          <b class = "caption">Agent Information</b>
          <div class = "caption1">
            <p class ="info" id = "nameVal"></p>
            <p class ="info" id = "addressVal"></p>
            <p class ="info" style="margin-bottom:2px;"><span id= "phone1Val"></span>&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbspFAX <span id= "faxVal"></span></p>
            <p class ="info" id = "phone2Val" style="margin-bottom:2px;"></p>
            <p class = "info">License#: <span class = "license_number"></span></p>
          </div>
          <b class = "caption2">Insured's Name and Mailing Address:</b>
          <p class = "details"><span class ="business_name"></span></p>
          <p class = "details"><span class ="address1"></span></p>
          <p class = "details"><span class ="address2"></span></p>
          <p class = "details"><span class ="city"></span>,<span class ="state_in_short"></span></p>
          <p class = "details"><span class ="country"></span>&nbsp<span class ="zip"></span></p>
      </div>
      <div class ="content2">
        <div class = "certificate_data">
          <p class = "p_margin"><b>Certificate #:</b></p>
          <p class = "p_margin"><b>Member #:</b></p>
          <p class = "p_margin"><b>Effective Date:</b></p>
          <p class = "p_margin"><b>Expiration Date:</b></p>
        </div>
        <div class = "certificate_data1">
          <p class = "p_margin"><span class ="certificate_no"></span></p>
          <p class = "p_margin"><span class ="padi"></span></p>
          <p class = "p_margin"><span class ="update_date"></span></p>
          <p class = "p_margin"><span class ="end_date"></span></p>
        </div>
        <div>
          <hr></hr>
          <p class = "p_margin">Policy issued by Tokio Marine Specialty Insurance
Company</p>
          <p class = "p_margin">Policy #: <span class ="policy_id"></span></p>
          <hr></hr>
        </div>
      </div>
      </div>
    </div>
    <div class="margin_div">&nbsp</div>
    <div class="margin_div">&nbsp</div>
</body>
</html>
