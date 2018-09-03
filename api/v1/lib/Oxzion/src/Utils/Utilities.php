<?php
namespace Oxzion\Utils;
class Utilities {

    public function __construct() {
        
    }

    public function CompareVariables($varaible1, $variable2, $expression) {
        switch ($expression) {
            case "==" : if ($varaible1 == $variable2) {
                    return 1;
                } else {
                    return 0;
                }
            case "===" : if ($varaible1 === $variable2) {
                    return 1;
                } else {
                    return 0;
                }
            case "!=" : if ($varaible1 != $variable2) {
                    return 1;
                } else {
                    return 0;
                }
            case "<>" : if ($varaible1 != $variable2) {
                    return 1;
                } else {
                    return 0;
                }
            case "<" : if ($varaible1 < $variable2) {
                    return 1;
                } else {
                    return 0;
                }
            case "<=" : if ($varaible1 <= $variable2) {
                    return 1;
                } else {
                    return 0;
                }
            case ">" : if ($varaible1 > $variable2) {
                    return 1;
                } else {
                    return 0;
                }
            case ">=" : if ($varaible1 >= $variable2) {
                    return 1;
                } else {
                    return 0;
                }
            default : return "Please add your case to this function";
        }
    }

    function calculateFiscalYearForDate($inputDate, $fyStart, $fyEnd) {
        $date = strtotime($inputDate);
        $inputyear = strftime('%Y', $date);

        $fystartdate = strtotime($fyStart . $inputyear);
        $fyenddate = strtotime($fyEnd . $inputyear);

        if ($date < $fyenddate) {
            $fy = intval($inputyear);
        } else {
            $fy = intval(intval($inputyear) + 1);
        }

        return $fy;
    }

    public function GeneratePDF($id, $object, $fieldname, $formname, $dbfieldname) {
        $value = $object->findById($id);
        $htmldivs = trim($value->$fieldname);

        // Include the main TCPDF library (search for installation path).
        require_once ROOT_PATH . '/library/VA/tcpdf/tcpdf.php';
        require_once ROOT_PATH . '/library/VA/tcpdf/examples/tcpdf_include.php';

        // create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('VA');
        $pdf->SetTitle('Vantage Agora');
        $pdf->SetSubject('Contract');
        $pdf->SetKeywords('VantageAgora', 'Contract', 'PDF');

        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING, array(0, 200, 255), array(0, 200, 128));
        $pdf->setFooterData(array(0, 64, 0), array(0, 64, 128));

        // set header and footer fonts
        $pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        // set some language-dependent strings (optional)
        if (ROOT_PATH . '/library/VA/tcpdf/examples/lang/eng.php') {
            require_once ROOT_PATH . '/library/VA/tcpdf/examples/lang/eng.php';
            $pdf->setLanguageArray($l);
        }

        // ---------------------------------------------------------
        // set default font subsetting mode
        $pdf->setFontSubsetting(true);

        // Set font
        // dejavusans is a UTF-8 Unicode font, if you only need to
        // print standard ASCII chars, you can use core fonts like
        // helvetica or times to reduce file size.
        $pdf->SetFont('dejavusans', '', 10, '', true);

        // Add a page
        // This method has several options, check the source code documentation for more information.
        $pdf->AddPage();
        $signblock = <<<EOD
<style>
.pullright{
float:right;
}
</style>
$htmldivs

EOD;
        $pdf->writeHTMLCell(0, 0, '', '', $signblock, 0, 1, 0, true, '', true);
        $agent_pdf = Date('Y-m-d') . "-" . $value->$dbfieldname;

        // ---------------------------------------------------------
        // Close and output PDF document
        // This method has several options, check the source code documentation for more information.
        $pdf->output(APPLICATION_PATH . '/../data/' . $formname . '/' . $agent_pdf . '.pdf', 'F');
        //============================================================+
        // END OF FILE
        //============================================================+
    }

    public function getMatrixDateRange($daterange) {
        $date_range = Array();
        switch ($daterange) {
            case "monthly" :
                $date_range['startdate'] = Date("Y-m-01 00:00:00");
                $date_range['enddate'] = Date("Y-m-d 23:59:59");
                return $date_range;
                break;
            case "daily" :
                $date_range['startdate'] = Date("Y-m-d 00:00:00");
                $date_range['enddate'] = Date("Y-m-d 23:59:59");
                return $date_range;
                break;
            case "yearly" :
                $lastyear1 = strtotime("-1 year", strtotime(Date("y-m-d")));
                $lastyear = date("Y-m-d 00:00:00", $lastyear1);
                $date_range['startdate'] = $lastyear;
                $date_range['enddate'] = Date("Y-m-d 23:59:59");
                return $date_range;
                break;
            case "starttoend" :
                $date_range['startdate'] = Date("Y-01-01 00:00:00");
                $date_range['enddate'] = Date("Y-12-31 23:59:59");
                return $date_range;
                break;
            case "starttotoday" :
                $date_range['startdate'] = Date("Y-01-01 00:00:00");
                $date_range['enddate'] = Date("Y-m-d 23:59:59");
                return $date_range;
                break;
                break;
        }
    }

    public function appendAjaxtoFields($formula, $destid) {                                   //@BUG 56644 
        $nmatches = preg_match_all('/\$\[(.*)\]/U', $formula, $matchesarray);
        $javascript = '';
        if ($nmatches) {
            foreach ($matchesarray[1] as $fieldname) {
                $javascript .= "$('input[name^=$fieldname]').blur(function() {ajaxFormulaCalculate('$formula','$destid');});\n";
            }
        }
        return $javascript;
    }

    public function updateSequence($data, $orgid, $tablename) {      //@BUG 56346
        $sequencesid = $data['sequence'];
        $generic = new VA_Model_Generic("timesheet_clients");
        $data = $generic->enlistbySequence($data['id'], $orgid, $sequencesid);
        foreach ($data as $key => $value) {
            $value['sequence'] = ++$sequencesid;
            $generic->update($value);
        }
    }

    public function GetTimezoneOffset($avatar) {
        $this_tz = new DateTimeZone($avatar->timezone);
        $now = new DateTime("now", $this_tz);
        $offset = $this_tz->getOffset($now);
        $datediff = gmdate("H:i", abs($offset));
        if ($offset < 0)
            $datediff = '-' . $datediff;
        else
            $datediff = '+' . $datediff;
        return $datediff;
    }

    public function getYearMonthJoinQuery($view_type, $select) {

        switch ($view_type) {
            case "monthly" :
                $select2->setIntegrityCheck(false)
                        ->from(array('m' => 'matrix_months'), array('matrix_years'))
                        ->joinLeft(array('t1' => $select), "m.matrix_month = t1.year"
                );
                $select2->order('m.matrix_month');
                break;
            case "yearly" :
                $select2->setIntegrityCheck(false)
                        ->from(array('m' => 'matrix_years'), array('matrix_years'))
                        ->joinLeft(array('t1' => $select), "m.matrix_years = t1.year"
                );
                $select2->where("matrix_years <= ?", Date('Y'));
                $select2->where("matrix_years >= ?", Date('Y') - 5);
                $select2->order('m.matrix_years');
                break;
                return $select2;
        }
    }

    function get_financial_year($year) {
        $date = $year . "-" . Date("m-d");
        $financial_date = $year . "-" . Date("04-01");
        if (($date > $financial_date)) {
            $start_date = $financial_date;
            $end_date = date('Y-m-d', strtotime($financial_date . " + 365 day"));
        } else {
            $year = $year - 1;
            $date = $year . "-" . Date("m-d");
            $financial_date = $year . "-" . Date("04-01");
            $start_date = $financial_date;
            $end_date = date('Y-m-d', strtotime($financial_date . " + 365 day"));
        }
        // get the first Thursday before 2 Jan of $year
        return Array("startdate" => $start_date, "enddate" => $end_date);
    }

    public function searchAssocArray(&$array, $key, $value){
        $return_array = array();
        if (is_array($array)) {
            if (isset($array[$key]) && $array[$key] == $value) {
                $return_array[] = $array;
            }
            foreach ($array as $subarray) {
                $return_array = array_merge($return_array, self::searchAssocArray($subarray, $key, $value));
            }
        }
        return $return_array;
    }

    public function customCalc($param1, $param2, $calc_type){
        switch ($calc_type) {
            case "sum" :
                return $param1 +$param2;
            break;
            case "times":
                return $param1 * ($param2);
            break;
            case "diff":
                return $param1 - $param2;
            break;
            case "div":
                return $param1 / ($param2)?$param2:1;
            break;
            case "avg":
                return ($param1 + $param2)/100;
            break;
            default:
                return $param1;
        }
    }

    public static function VersionFile($filename){
        $modifiedtime = date('YmdHis', filemtime(ROOT_PATH.'/public/'.$filename));
        $path = VA_Logic_Session::translate('CDN_URL');
        return ($path?$path.'/public':VA_Logic_Session::getBaseUrl()).'/'.$filename.'?'.$modifiedtime;
    }

    public static function DymanicDebugMode($instanceformid, &$avatar, $trace){
        $tracearray = $trace->getTrace();
        foreach ($tracearray as $key => $value) {
            $string .= '#'.$key.' '.$value['file'].' ('.$value['line'].') '.$value['class'].$value['type'].$value['function'].'(arguments)'.PHP_EOL;
            if($value['args'])
                $string .= 'Arguments'.PHP_EOL.self::convertArrayToString($value['args']).PHP_EOL;
        }

       $ddm_path = ROOT_PATH . "\data\ddm/".Date('Y-m-d');
       (is_dir($ddm_path)?'':mkdir($ddm_path));
       $ddm_filename = $ddm_path.'/'.$instanceformid.'.txt';
       $handle = fopen($ddm_filename, 'a');
       $start = PHP_EOL.'------------Modified '.$instanceformid.' at '.Date('H:i:s').' UTC-----------';
       $body = PHP_EOL.str_replace('/path/to/code/', '', $string);
       $mailbody = PHP_EOL.str_replace('/path/to/code/', '', $trace->getTraceAsString());
       $end = PHP_EOL.'---------------------------------------------------------'.PHP_EOL;
       fwrite($handle, $start.$body.$end);
       fclose($handle);
       $subject = "Restricted field(s) changed in #".$instanceformid." by ".$avatar->firstname.' '.$avatar->lastname.'-'.$avatar->id;
       $body = $start.$body.$end.'<br><br>File Path: '.$ddm_filename;
       self::sendDDMMail($body, $subject);
    }

    public static function sendDDMMail($content, $subject){
        $mailarray['fromid'] = 1;
        $mailarray['avatarlist'] = explode(',',VA_Logic_Session::translate('MOD_DYNAMIC_DEGUG_MODE_AVATAR_RECIPIENTS_ID'));
        $mailarray['subject'] = $subject;
        $mailarray['message'] = $content;
        if(VA_Logic_Session::translate('MOD_DYNAMIC_DEGUG_MODE_CONFIG')){
            $check_array = VA_Service_Messages::sendMessage($mailarray);
        }
    }

    public static function convertArrayToString($array){
        $string = '';
        foreach ($array as $key => $value) {
            $string .= '#'.$key.' ';
            if(is_array($value)){
                $out = '';
                foreach( $value as $k => $v ) {
                    $out .= $sep . $k . ':' . $v;
                    $sep = ';';
                }
                $string .= $out;
            }elseif(is_object($value)){
                $string .= get_class($value).'->'.$value->id;
            }elseif(is_string($value)){
                $string .= $value;
            }
            $string .= PHP_EOL;
        }
        return $string;
    }

    public static function sendHeartBeat($type){
        $configs = new VA_Model_Configuration();
        $wf_obj = new VA_Workflow_SentinelWorkflow();
        if($type == 1){
            $array = $configs->getDataByParams(array('orgid','groupid'=>'value'),'parameter = "sentinel1"');
        }elseif($type == 2){
            $array = $configs->getDataByParams(array('orgid','groupid'=>'value'),'parameter = "sentinel2"');
        }
        if($array){
            foreach($array as $value){
                $wf_obj->rule2($value);
            }
        }
    }

    /**
     * [sendOTP description]
     * @param  [number] $to_number [called number along with country code]
     * @param  string $type      [sendSMS or call]
     * @return [array]            [otp number, status and response]
     */
    public static function sendOTP($to_number, $type = "sendSMS", $otp = null) {
        if (empty($otp)) { $otp = rand(111111,999999); }
        if ($type == "call")
            $message = "Your Activation Code is ".implode(",", str_split($otp)).", Please verify your Activation Code is ".implode(",", str_split($otp));
        else
            $message = "Your Activation Code is ".$otp;
        return VA_API_ExternalAPICall::twilio("alert", array("method"=>$type, "to"=>$to_number, "message"=>$message, "otp"=>$otp));
    }

    /**
     * [verifyOTP description]
     * @param  [number] $to_number [called number along with country code]
     * @param  [number] $otp       [otp entered by user]
     * @return [array]            [status and message]
     */
    public function verifyOTP($to_number, $otp) {
        $key = md5(sha1($to_number));
        $cache = VA_Logic_Session::getCoreCache();
        $cache_data = $cache->load("twilio_call_queue");
        if (isset($cache_data[$key])) {
            $data = $cache_data[$key];
        } else {
            return array("status"=>404, "message"=>"Activation Code has expired please re-verify!");
        }
        if ($data["otp"] == $otp) {
            unset($cache_data[$key]);
            $cache->remove("twilio_call_queue");
            $cache->save($cache_data, "twilio_call_queue", array("twilio_call_queue"));
            return array("status"=>200, "message"=>"Thank you! Your phone number is verified!");
        } else {
            return array("status"=>401, "message"=>"Entered Activation Code is wrong!");
        }
    }

    /**
     * [callStatus identify current call status - available only before verifyOTP function is triggered]
     * @param [number] $to_number [called number along with country code]
     * @return [array]            [status and message]
     */
    public static function callStatus($to_number) {
        $key = md5(sha1($to_number));
        $cache = VA_Logic_Session::getCoreCache();
        $cache_data = $cache->load("twilio_call_queue");
        if (!isset($cache_data[$key])) {
            return array("status"=>404, "message"=>"failed");
        } else {
            $data = $cache_data[$key];
            if (preg_match('/^4(\d{2})$/', $data['status'])) {
                unset($cache_data[$key]);
                $cache->remove("twilio_call_queue");
                $cache->save($cache_data, "twilio_call_queue", array("twilio_call_queue"));
            }
            return array("status"=>$data['status'], "message"=>$data['response_message']);
        }
    }

    public static function object_to_array($data){
        if (is_array($data) || is_object($data)) {
            $result = array();
            foreach ($data as $key => $value){
                $result[$key] = self::object_to_array($value);
            }
            return $result;
        }
        return $data;
    }

    public static function keyedArrayToList($data) {
        foreach ($data as $key => $value)
            $return_data .= $key.'=>'.$value.'|';
        return trim($return_data, '|');
    }

    public static function listToKeyedArray($list) {
        if (!$list)
            return null;
        $listoptions = explode('|', $list);
        $listarray = array();
        $i=0;
        foreach ($listoptions as $option) {
            $keyandoption = explode('=>', $option);
            $listarray[$i]['key'] = trim($keyandoption[1]);
            $listarray[$i]['value'] = trim($keyandoption[0]);
            $i++;
        }
        return($listarray);
    }
    public static function arrayToKeyedArray($array) {
        $i=0;
        $listarray = array();
        foreach ($array as $key => $option) {
            $listarray[$i]['key'] = $option;
            $listarray[$i]['value'] = (string) $key;
            $i++;
        }
        return($listarray);
    }
    
    public static function listOfKeyandValueArray($list){
        if (!$list)
            return null;
        $listoptions = explode('|', $list);
        $listarray = array();
        $i=0;
        foreach ($listoptions as $option) {
            $keyandoption = explode('=>', $option);            
            $listarray[trim($keyandoption[0])] = trim($keyandoption[1]);
        }
        return($listarray);
    }
    
    public static function getDefaultFieldsWithTitle(){
        $instfields = array(
            array('field'=>'id','name'=>'id','title'=>'ID'),
            array('field'=>'name','name'=>'name','title'=>'Name'),
            array('field'=>'status','name'=>'status','title'=>'Status'),
            array('field'=>'createdby','name'=>'createdby','title'=>'Created By'),
            array('field'=>'assignedto','name'=>'assignedto','title'=>'Assigned To'),
            array('field'=>'assignedgroup','name'=>'assignedgroup','title'=>'Assigned Group'),
            array('field'=>'ownergroup','name'=>'ownergroup','title'=>'Owner Group'),
            array('field'=>'parentname','name'=>'parentname','title'=>'Parent'),
            array('field'=>'startdate','name'=>'startdate','title'=>'Start Date'),
            // array('field'=>'startdatey','name'=>'startdatey','title'=>'Start Date Year'),
            // array('field'=>'startdatem','name'=>'startdatem','title'=>'Start Date Month'),
            array('field'=>'nextactiondate','name'=>'nextactiondate','title'=>'Next Action Date'),
            // array('field'=>'nextactiondatey','name'=>'nextactiondatey','title'=>'Next Action Date Year'),
            // array('field'=>'nextactiondatem','name'=>'nextactiondatem','title'=>'Next Action Date Month'),
            array('field'=>'enddate','name'=>'enddate','title'=>'End Date'),
            // array('field'=>'enddatey','name'=>'enddatey','title'=>'End Date Year'),
            // array('field'=>'enddatem','name'=>'enddatem','title'=>'End Date Month'),
            array('field'=>'date_created','name'=>'date_created','title'=>'Date Created'),
            array('field'=>'date_modified','name'=>'date_modified','title'=>'Date Modified'));
        return $instfields;
    }
    public static function addColorFilters($colorid,$filter_string=null){
        $time = VA_Service_Utils::dateToUTC(date('Y-m-d H:i:s'));
        $yellowLimit = VA_Service_Utils::dateToUTC(date("Y-m-d H:i:s", strtotime($time)) . " -2 days");
        $greenlimit = VA_Service_Utils::dateToUTC(date("Y-m-d H:i:s", strtotime($time)) . " +2 days");
        if($filter_string){
            $filter_string .=" AND ";
        } else {
            $filter_string = "";
        }
        if ($colorid == 1) {
            $filter_string .= " (a.nextactiondate >= '$greenlimit' AND a.enddate >= '$greenlimit')";
        } elseif ($colorid == 2) {
            $filter_string .= " (a.nextactiondate > '$time' AND a.nextactiondate < '$greenlimit')";
        } elseif ($colorid == 3) {
            $filter_string .= " (a.nextactiondate <= '$time' OR a.enddate <= '$time' OR a.enddate is Null)";
        }
        return $filter_string;
    }
    /**
     * Get the OLE Automation Date epoch
     *
     * @return DateTimeImmutable
     */
    public static function BaseDate()
    {
        static $baseDate = null;
        if ($baseDate == null) {
            $baseDate = new DateTimeImmutable('1899-12-30 00:00:00');
        }
        return $baseDate;
    }
    /**
     * Convert a DateTime object to a float representing an OLE Automation Date
     *
     * @param DateTimeInterface $dateTime
     * @return float
     */
    public static function DateTimeToOADate(DateTimeInterface $dateTime)
    {
        $interval = self::BaseDate()->diff($dateTime);
        $mSecs = ($interval->h * 3600000)
            + ($interval->i * 60000)
            + ($interval->s * 1000)
            + floor($dateTime->format('u') / 1000);
        return $interval->days + ($mSecs / 86400000);
    }
    /**
     * Convert a float representing an OLE Automation Date to a DateTime object
     *
     * The returned value has a microsecond component, but resolution is millisecond and even
     * this should not be relied upon as it is subject to floating point precision errors
     *
     * @param float $oaDate
     * @return DateTime
     */
    public static function OADateToDateTime($oaDate)
    {
        $days = floor($oaDate);
        $msecsFloat = ($oaDate - $days) * 86400000;
        $msecs = floor($msecsFloat);
        $hours = floor($msecs / 3600000);
        $msecs %= 3600000;
        $mins = floor($msecs / 60000);
        $msecs %= 60000;
        $secs = floor($msecs / 1000);
        $msecs %= 1000;
        return self::BaseDate()->add(new DateInterval(sprintf('P%sDT%sH%sM%sS', $days, $hours, $mins, $secs)))->format('Y-m-d H:i:s');
    }

    public static function getMailTime($date,$avatar){
        $today_date = VA_Service_Utils::dateFromUTC(date('Y-m-d'), $avatar->getTimeZone());
        if((strtotime($today_date)-(60*60*24)) < strtotime($date)){
            return date("H:i",strtotime($date));
        } else if($date == date("l, F d")-1){
            return "Yesterday";
        } else {
            return date("M d",strtotime($date));
        }
    }

    public static function getSourceOptionsForXflat(){
        $returndata = array();
        $project = array();
        $forms = array();
        $orgid = VA_Logic_Session::getAvatar()->orgid;
        $metafields = new VA_Model_MetaFields();
        $allfields = $metafields->enlistXFlatFieldsbyOrgIDandFormid($orgid);
        foreach($allfields as $value){
            $forms[$value['formid']] = $value['formname'];
        }
        $timesheetclients = new VA_Model_TimesheetClients();
        $clients = $timesheetclients->enlistByOrgid($orgid)->toArray();
        foreach($clients as $client){
            $project[$client['id']] = str_replace("'",'',$client['client_name']);
        }
        $project[0] = 'All';
        asort($forms);
        asort($project);
        return array('forms'=>json_encode($forms),'project'=>json_encode($project));
    }

    public static function validateemail($email) {
        try {
            list($username,$domain) = split('@',$email);
            $regex = "/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/";
            if (filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match($regex, $email) && checkdnsrr($domain) && $domain != 'va.com')
                return 1;
            else
                return 0;
        } catch (Exception $e) {
            return 0;
        }
        
    }

    public static function isValidData($type, $data) {
        if(strpos($type, 'float')) {
            $pattern = "/^([\d]+)((?:\.{1})[\d]{1,2})?$/";
            if (!preg_match($pattern, $data)) {
                return 'Enter decimal number!';
            }
        } elseif(strpos($type, 'email')) {
            $pattern = "/^([\w]+[\_\.\-\w]*)(\+[\d]{1})?@([\w]*)[\.]+([\w]{2,4})([+]{1}[0-9]{1})?$/";
            if (strpos($data, ',')) {
                if (strpos($data, ',') < 7) {
                    return 'Enter valid email!';
                }
                else {
                    foreach (explode(',', $data) as $value) {
                        if (trim($value) != '' && !preg_match($pattern, trim($value)))
                            return 'Enter valid email!';
                    }
                }
            } elseif (!preg_match($pattern, $data)) {
                return 'Enter valid email!';
            }
        } elseif(strpos($type, 'hyperlink')) {
            $pattern = "/^((https?|ftp):\/\/|www\.)([\w]{2,}\.)[\w][-\w+&@#\/%?=~_|!:,.;]*[-\w+&@#\/%=~_|]/i";
            if (!preg_match($pattern, $data)) {
                return 'Enter valid url! (eg. https://example.com, www.example.com)';
            }
        } elseif(strpos($type, 'us-mobno')) {
            $pattern = "/^((?:[\d]{3})-(?:[\d]{3})-(?:[\d]{4}))$/";
            if (!preg_match($pattern, $data)) {
                return 'Invalid! (eg. xxx-xxx-xxxx)';
            }
        } elseif(strpos($type, 'us-ssn')) {
            $pattern = "/^((?:[\d]{3})-?(?:[\d]{2})-?(?:[\d]{4}))$/";
            if (!preg_match($pattern, $data)) {
                return 'Invalid! (eg. xxxxxxxxx [9 digits])';
            }
        }
    }

    public Static function sortArrayByFieldName($array, $fieldname, $sort_type = SORT_NATURAL) {
        if (is_object($array)) {
            $array = (array) $array;
        }
        foreach ($array as $key => $value) {
            $fieldname_array[$key] = trim($value[$fieldname]);
        }
        array_multisort($fieldname_array, $sort_type, $array);
        return $array;
    }

    public static function getFormFields($formid, $condition){
        $form = new VA_Logic_MetaForm($formid);
        $fields = $form->getFieldsVal($condition);
        $fieldtype = array();
        $fieldtype['other']['field']['id'] = 'ID';
        $fieldtype['other']['field']['status'] = 'Status';
        $fieldtype['select']['field']['status'] = 'Status';
        $fieldtype['select']['optionlist']['status'] = $form->statuslist;

        $avatar_logic = VA_Logic_Session::getAvatar();
        $orgid = $avatar_logic->orgid;
        $org = new VA_Logic_Organization(array('id' => $orgid));
        $model = new VA_Model_Avatars();
        $avatars = $model->findArrayByorgid($orgid);
        $avatararray = array();
        foreach($avatars as $avatar){
            $avatararray[] = $avatar['id'].'=>'.$avatar['firstname'].' '.$avatar['lastname'];
        }
        $avatarastring = implode('|',$avatararray);

        $fieldtype['select']['field']['assignedto'] = 'Assigned To';
        $fieldtype['select']['optionlist']['assignedto'] = $avatarastring;
        $fieldtype['select']['field']['createdid'] = 'Owner';
        $fieldtype['select']['optionlist']['createdid'] = $avatarastring;

        $fieldtype['date']['startdate'] = VA_Logic_Session::Translate('FORM_QUICKEDITFORM_START_DATE');
        $fieldtype['date']['enddate'] = VA_Logic_Session::Translate('FORM_QUICKEDITFORM_END_DATE');
        $fieldtype['date']['nextactiondate'] = VA_Logic_Session::Translate('FORM_QUICKEDITFORM_NEXT_ACTION_DATE');
        $fieldtype['date']['date_created'] = 'Date Created';
        $fieldtype['date']['date_modified'] = 'Date Modified';
        foreach($fields as $field){
            $fieldtype['other']['field'][$field['name']] = $field['field'];
            if($field['type'] == 'select'){
                $fieldtype['select']['field'][$field['name']] = $field['title'];
                $fieldtype['select']['optionlist'][$field['name']] = $field['options_list'];
            }elseif($field['type'] == 'date'){
                $fieldtype['date'][$field['name']] = $field['title'];
                unset($fieldtype['select']['field'][$field['name']]);
            }
        }
        $fieldtype['other']['calc'] = VA_Service_MetaOptions::getArrayFromList(VA_Logic_Session::translate('MOD_EMPLOYEE_XFLAT_MATH'));

        return $fieldtype;
    }

    public static function getOrgGroupsandAvatars($string){

        $string = trim(end(explode(',',$string)));
        if($string == '') return;


        $avatar_logic = VA_Logic_Session::getAvatar();
        $avatar_model = new VA_Model_Avatars();
        $groups_model = new VA_Model_Groups();
        $orgid = $avatar_logic->orgid;
        $org = new VA_Logic_Organization(array('id' => $orgid));

        $grouparray = array();
        $avatars = array();
        $avatars = $org->getAvatarListByName($string, 0);
        if($avatars){
            array_walk($avatars, function($value,$key) use(&$avatars){
                $avatars[$key]['class'] = 'x_avatars'; // can have different background to the list
            });
        }

        $groups = $groups_model->enlistexcept(null, $orgid,$string)->toArray();
        if($groups){
            array_walk($groups, function($value, $key) use(&$grouparray){
                $grouparray[$value['id']]['groupid'] = $value['id'];
                $grouparray[$value['id']]['value'] = $value['name'];
                $grouparray[$value['id']]['img'] = $value['logo'];
                $grouparray[$value['id']]['class'] = $value['x_groups']; // can have different background to the list
            });
        }

        return array_merge($avatars,$grouparray);
    }

    /**
     * [downloadfile download a particular file]
     * @param  [array] $data [you can pass file id, instanceform or message id along with filename]
     * @param  boolean $getpath [pass true if you want to return file path]
     * @return [file]       [file will be downloaded through browser]
     */
    public static function downloadfile($data, $getpath = false) {
        $orgid = VA_Logic_Session::getAvatar()->orgid;
        if ($data['id']) {
            $data_set = (new VA_Model_InstformsFiles())->getDatabyParams(array('instanceformid', 'messageid', 'filename'), 'id = '.$data['id']);
            if (!$data_set) return;
            $data = array_merge($data, $data_set[0]);
        }
        if ($data['instanceformid']) {
            $filepath = APPLICATION_DATA . '/uploads/organization/' . $orgid . '/' . $data['instanceformid'] . '/' . $data['filename'];
        } elseif ($data['messageid']) {
            $filepath = APPLICATION_DATA . '/uploads/organization/' . $orgid . '/messages/' . $data['messageid'] . '/' . $data['filename'];
        }
        if (!file_exists($filepath)) return;
        elseif ($getpath) return $filepath;
        self::downloadFileByPath($filepath);
    }

    /**
     * [downloadzip adds file to a zip and download]
     * @param  [array]  $data      [split array of filenames]
     * @param  [string]  $zip_path     [specify path where you want to store the zip file along with zip name]
     * @param  integer $deletezip [pass 0 if you want the zip to to stay in server]
     * @return [file]             [zip file will be downloaded through browser]
     */
    public static function downloadzip($data, $zip_path = null, $deletezip = 1) {
        $id = $data['id'];
        $org_folder = APPLICATION_DATA."/uploads/organization/".VA_Logic_Session::getAvatar()->orgid;
        if(strpos($data['type'], 'instanceform') > -1) {
            $attachmentsobject = new VA_Logic_Attachments(VA_Logic_Session::getAvatar(),$id);
            $attachedfiles = $attachmentsobject->getFiles();
            $folder = $org_folder ."/". $id;
        } elseif(strpos($data['type'], 'message') > -1) {
            if (!empty($data['attachments'])) {
                foreach ($data['attachments'] as $value) {
                    $attachedfiles[] = array("filename" => $value->name);
                    $folder = $value->filepath;
                }
            } else {
                $instfile_mapper = new VA_Model_InstformsFiles();
                $attachedfiles = $instfile_mapper->enlistByMessageId($id);
                $folder = $org_folder . "/messages/" . $id;
            }
        }
        if (empty($attachedfiles)) { return; }
        if (empty($zip_path)) {
            $zip_path = $org_folder."/temp/".$id.'_attachments.zip';
        }
        self::downloadFileByPath(self::attachmentsToZip($attachedfiles, $zip_path, $folder), $deletezip);
    }

    /**
     * [splitAndZip create multiple zip with specific number of files]
     * @param  [array] $attachments [array of filenames]
     * @param  [string] $zip_folder        [specify path where you want to store the zip file]
     * @param  [string] $zipname     [zip name]
     * @param  [integer] $limit       [number of attachments in one zip file]
     * @return [array]              [zip file full path names are returned]
     */
    public static function splitAndZip($attachments, $zip_folder, $zipname, $limit) {
        $i = 0;
        $start = 0;
        $zipname = trim($zipname,'.zip');
        $batches = ceil(count($attachments) / $limit);
        while ($i < $batches) {
            $zipname_multi = ($i) ? ($zipname.'_'.$i.'.zip') : ($zipname.'.zip');
            $attachment_batch = array_slice($attachments, $start, $limit);
            $zippath[] = self::attachmentsToZip($attachment_batch, $zip_folder."/".$zipname_multi);
            $start = $start+$limit;
            $i++;
        }
        return $zippath;
    }

    /**
     * [attachmentsToZip adds files and folders to a zip]
     * @param  [array]  $attachments     [array of filenames]
     * @param  [string]  $zip_path        [specify path where you want to store the zip file along with zip name]
     * @param  [string]  $attachment_path [folder where files are located]
     * @param  integer $unlink_files    [pass 1 if you want to delete the file after ziping it]
     * @param  string  $action          [overwrite to discard old data if zip exists else pass append to keep old files]
     * @return [string]                   [path of the zip is returned]
     */
    public static function attachmentsToZip($attachments, $zip_path, $attachment_path = null, $unlink_files = 0, $action = 'overwrite') {
        self::createFilePath($zip_path);
        $zip = new ZipArchive();
        if ($action = 'overwrite' && file_exists($zip_path))
            unlink($zip_path);
        $zip->open($zip_path, ZipArchive::CREATE || ZipArchive::OVERWRITE);
        $unlink_attachments = array();
        foreach ($attachments as $attachment) {
            if (is_array($attachment) && isset($attachment['filename'])) {
                if (empty($attachment_path))
                    $attachment = $attachment['filename'];
                else
                    $attachment = $attachment_path."/".$attachment['filename'];
            }
            if (basename($attachment) == '.' || basename($attachment) == '..') { continue; }
            if (is_dir($attachment)) {
                self::addFolderToZip($zip, $attachment);
                if ($unlink_files || (strpos($attachment, '/temp/') != 0))
                    $unlink_attachments[] = $attachment;
            } elseif(file_exists($attachment)) {
                $zip->addFile($attachment, basename($attachment));
                if ($unlink_files || (strpos($attachment, '/temp/') != 0))
                    $unlink_attachments[] = $attachment;
            }
        }
        $zip->close();
        foreach ($unlink_attachments as $attachment) {
            if (is_dir($attachment)) self::deleteDirectory($attachment);
            else unlink($attachment);
        }
        return $zip_path;
    }

    /**
     * [addFolderToZip recursive function used by above function downloadzip inorder to add folders to zip]
     * @param [object] &$zip   [ZipArchive object]
     * @param [String] $folder [folder path from where files have to extracted]
     * @param [String] $zip_path [used for recursive purpose (do not use this)]
     */
    public static function addFolderToZip(&$zip, $folder, $zip_path = null) {
        $zip_path = $zip_path.basename($folder);
        $zip->addEmptyDir($zip_path);
        foreach (glob($folder."/*") as $file) {
            if (basename($file) == '.' || basename($file) == '..') { continue; }
            if (is_dir($file)) {
                self::addFolderToZip($zip, $file, $zip_path."/");
            } elseif(file_exists($file)) {
                $zip->addFile($file, $zip_path."/".basename($file));
            }
        }
    }

    /**
     * [downloadFile send path to download a file]
     * @param  [string]  $path   [full path]
     * @param  integer $unlink [pass 1 to delete the file after downloading]
     * @param  [string]  $data       [output data]
     * @param  string  $contentype [output file type]
     * @return [file]          [file will be downloaded to your system]
     */
    public static function downloadFileByPath($path, $unlink = 0, $data = null, $contentype = 'application/octet-stream') {
        // header('Expires: 0');
        header("Pragma: public");
        header("Cache-Control: must-revalidate");
        header('Content-Transfer-Encoding: binary');
        header('Content-Description: File Transfer');
        header('Content-Type: '.$contentype);
        header('Content-Disposition: attachment; filename="' .basename($path). '"');
        ob_clean();
        if ($data) { echo $data; }
        else { readfile($path); }
        if ($unlink && $data == null) { unlink($path); }
        return ob_flush();
    }

    /**
     * [dataToFile write or append data to a file]
     * @param  [string] $filepath [full file path including file name]
     * @param  [data] $data     [data to be written in the file]
     * @param  string $action   [action to be performed]
     * @return [string]           [filepath]
     */
    public static function dataToFile($filepath, $data, $action='append') {
        self::createFilePath($filepath);
        if(file_exists($filepath) && $action == 'append'){
            $fh = fopen($filepath, 'a') or die('Could not open the file.');
        } else {
            $fh = fopen($filepath, 'w') or die('Could not write the file.');
        }
        flock($fh, LOCK_EX);
        fwrite($fh, $data) or die("Could not write file!");
        flock($fh, LOCK_UN);
        fclose($fh);
        return $filepath;
    }

    /**
     * [createFilePath makes directory path]
     * @param  [string] $fullpath [real server path]
     * @param  boolean $filename_included [pass true if file name is included in the fullpath]
     * @return [fullpath]   [fullpath which was passed]
     */
    public static function createFilePath($fullpath, $filename_included = true) {
        $filename = '';
        if ($filename_included) {
            $filename = basename($fullpath);
            if (strpos($filename, '.'))
                $fullpath = str_replace($filename, "", $fullpath);
            else
                $filename = '';
        }
        if (file_exists($fullpath)) return $fullpath.$filename;
        if (strrpos(explode('/', $fullpath)[0], ':') == false) {
            $path = '/';
        } else {
            $path = '';
        }
        foreach (explode('/', $fullpath) as $dir) {
            if ($dir) {
                $path .= $dir.'/';
                if (!file_exists($path)) mkdir($path);
            }
        }
        return $path.$filename;
    }

    /**
     * [deleteDirectory deletes the folder and all files in it.]
     * @param  [string] $dirname [folder path to be deleted]
     * @return [bool]          [true if complete]
     */
    public static function deleteDirectory($dirname) {
        if (!is_dir($dirname))
            return false;
        foreach (glob($dirname."/*") as $file) {
            if (basename($file) == "." && basename($file) == "..") { continue; }
            if (is_dir($file))
                self::deleteDirectory($file);
            else
                unlink($file);
        }
        rmdir($dirname);
        return true;
    }

    /**
     * [copyFileToPath copy file or dir from one path to another]
     * @param  [string] $source      [source path]
     * @param  [string] $destination [destination path]
     */
    public static function copyFileToPath($source, $destination) {
        $source = trim(trim($source, '/'), '\\');
        $destination = trim(trim($destination, '/'), '\\');
        if (strrpos(explode('/', $source)[0], ':') == false) {
            $source = '/'.$source;
            $destination = '/'.$destination;
        }
        if (is_dir($source)) {
            foreach (array_diff(scandir($source), array('.', '..')) as $filename)
                self::copyFileToPath($source.'/'.$filename, $destination.'/'.$filename);
        } else {
            copy($source, VA_Logic_Utilities::createFilePath($destination));
        }
    }

    /**
     * [copyFileToFtpPath copy a file to the ftp server]
     * @param  string $filepath   [from directory realpath including filename]
     * @param  string $serverpath [to directory without filename]
     * @param  string $servername [server host name]
     * @param  string $username   [server username]
     * @param  string $password   [server password]
     * @return bool               [result]
     */
    public static function copyFileToFtpPath($filepath, $serverpath = '', $servername, $username, $password) {
        $conn_id = ftp_connect($servername);
        $login_result = ftp_login($conn_id, $username, $password);
        ftp_pasv($conn_id, true);
        ftp_chdir($conn_id, $serverpath);
        $result = ftp_put($conn_id, basename($filepath), $filepath, FTP_ASCII);
        ftp_close($conn_id);
        return $result;
    }

    /**
     * [arrayIndexDataId description]
     * @param  [array]  $data           [full data]
     * @param  [array or string]    $values [values you want in an array]
     * @param  [boolean]    $desc_or_name   [if you want data from description or name with description as higher priority]
     * @param  [string] $indexname      [which value has to become idex of array]
     * @param  [boolean] $index_array_append      [if index is repeated make it multi dimensional]
     * @return [array]                  [array index will be id with selected data]
     */
    public static function arrayIndexDataId($data, $values = null, $desc_or_name = false, $indexname = 'id', $index_array_append = 0) {
        $returnarray = array();
        if ($desc_or_name) {
            foreach ($data as $datarow) {
                $name = strtolower(trim(strip_tags($datarow['description'])));
                if(strpos($name, 'please enter the description here..') !== false || $name == '')
                    $name = $datarow['name'];
                $returnarray[$datarow[$indexname]] = ucwords($name);
            }
            return $returnarray;
        }

        if ($values == null)
            $values = array_keys($data[key($data)]);

        if (is_array($values)) {
            foreach ($data as $datarow) {
                $index_array = array();
                foreach ($values as $value) {
                    $index_array[$value] = $datarow[$value];
                    // if (isset($datarow["value"])) {
                    //     if (strpos($datarow[$value], "|") && strpos($datarow[$value], "=>")) {
                    //         $val = 'array("'.str_replace("|", '","', (str_replace("=>", '"=>"', $datarow[$value]))).'")';
                    //         $returnarray[$datarow[$indexname]]["option_array"] = eval("return $val;");
                    //         $returnarray[$datarow[$indexname]]["option_value"] = $returnarray[$datarow[$indexname]]["option_array"][$datarow["value"]];
                    //     } elseif (strpos($datarow[$value], "$") > -1) {
                    //         $multioptions = VA_Service_MetaOptions::getArrayFromList($datarow[$value]);
                    //         if (empty($multioptions)) {
                    //             $returnarray[$datarow[$indexname]][$value] = $datarow[$value];
                    //             break;
                    //         }
                    //         $returnarray[$datarow[$indexname]]["option_array"] = $multioptions;
                    //         $returnarray[$datarow[$indexname]]["option_value"] = $returnarray[$datarow[$indexname]]["option_array"][$datarow["value"]];
                    //     }
                    // }
                }
                if ($index_array_append)
                    $returnarray[$datarow[$indexname]][] = $index_array;
                else
                    $returnarray[$datarow[$indexname]] = $index_array;
            }
        } else {
            foreach ($data as $datarow) {
                if ($index_array_append)
                    $returnarray[$datarow[$indexname]][] = $datarow[$values];
                else
                    $returnarray[$datarow[$indexname]] = $datarow[$values];
            }
        }
        return $returnarray;
    }

    public static function objectIndexDataId($data, $values = null, $desc_or_name = false, $indexname = 'id') {
        if ($desc_or_name) {
            foreach ($data as $datarow) {
                $name = strtolower(trim(strip_tags($datarow->description)));
                if(strpos($name, 'please enter the description here..') !== false || $name == '')
                    $name = $datarow->name;
                $returnarray[$datarow->$indexname] = ucwords($name);
            }
            return $returnarray;
        }

        if (empty($values))
            return;

        if (is_array($values)) {
            foreach ($data as $datarow) {
                foreach ($values as $value)
                    $returnarray[$datarow->$indexname][$value] = $datarow->$value;
            }
        } else {
            foreach ($data as $datarow)
                $returnarray[$datarow->$indexname] = $datarow->$values;
        }
        return $returnarray;
    }

    public static function sendEmail($from, $to, $cc, $bcc, $subject, $html, $attachments = null) {
        $mail = new Zend_Mail();

        $mail->setFrom($from['email'], (($from['name']) ? $from['name'] : ($from['firstname'] . ' ' . $from['lastname'])));

        if (!is_array($to))
            $to = explode(',', $to);
        $mail->addTo(array_filter($to));
        if (!is_array($cc))
            $cc = explode(",", $cc);
        $mail->addCc(array_filter($cc));
        if (!is_array($bcc))
            $bcc = explode(",", $bcc);
        $mail->addBcc(array_filter($bcc));

        $mail->setSubject($subject);
        $mail->setBodyHtml($html);

        if ($attachments != null && !empty($attachments)) {
            if (count($attachments) > 15) {
                $zipname = $subject.date("_Y-m-d_H_i_s").".zip";
                $zipped_attachments = VA_Logic_Utilities::splitAndZip($attachments, str_replace(basename(current($attachments)), "", current($attachments)), $zipname, count($attachments));
                foreach ($zipped_attachments as $zip_path) {
                    $file = $mail->createAttachment(file_get_contents($zip_path) . "\r\n");
                    $file->type = "application/octet-stream";
                    $file->filename = basename($zip_path);
                    unlink($zip_path);
                }
            }else{
                foreach ($attachments as $attachment) {
                    $file = $mail->createAttachment(file_get_contents($attachment) . "\r\n");
                    $file->type = "application/octet-stream";
                    $file->filename = basename($attachment);
                }
            }
        }
        // echo "<pre>";print_r($mail);exit();
        try{
            $mail->send();
        } catch (Exception $e) {
            print_r($e);//exit();
        }
    }

    public static function getTimesheetFields($client){
        $avatar_logic = VA_Logic_Session::getAvatar();
        $orgid = $avatar_logic->orgid;

        $timesheet = new VA_Logic_ClubTimesheet(array());
        $timesheet_model = new VA_Model_ClubTimesheet();
        $fields = new VA_Model_TimesheetFields();
        // echo '<pre>';print_r($fields_val);exit;
        $fieldtype = array();
        $fieldtype['date']['start_time'] = 'Start Date';
        $fieldtype['date']['end_time'] = 'End Date';
        $classArray = array();

        if($client && count(explode(',',$client)) == 1){
            $fields_val = $fields->enlistByOrgidClientID($orgid, $client)->toArray();
            foreach($fields_val as $key=>$value){
                if($value['field_type'] == 'select'){
                    if (strpos($value['field_id'], 'dropdown') !== false) {
                        $field_name_temp = "timesheet_" . $value['field_id'];
                        if($field_mod = $classArray[$field_name_temp] == ''){
                            $field_mod = new VA_Model_TimesheetGenericFields($field_name_temp);
                            $classArray[$field_name_temp] = $field_mod;
                        }

                    } else{
                        $field_name_temp = "VA_Model_Timesheet" . $value['field_id'];
                         if($field_mod = $classArray[$field_name_temp] == ''){
                            $field_mod = new $field_name_temp();
                            $classArray[$field_name_temp] = $field_mod;
                        }
                    }
                    $val = $field_mod->enlistByOrgClientid($orgid, $client)->toArray();
                    if($val){
                        $fieldtype['select']['field'][$value['field_id']] = $value['field_name'];
                        $fieldtype['select']['optionlist'][$value['field_id']] = VA_Service_MetaOptions::getListFromArray(array_combine(array_column($val,'id'),array_column($val,'field_name')));
                    }
                }
                elseif($value['field_type'] == 'number'){
                        $fieldtype['other']['field'][$value['field_id']] = $value['field_name'];
                }
                elseif($value['field_type'] == 'error'){
                    $error = array('No')+array_filter(VA_Service_MetaOptions::getArrayFromList($value->field_format));
                }
                elseif($value['field_type'] == 'datetime' || $value['field_type'] == 'date'){
                    $fieldtype['date'][$value['field_id']] = $value['field_name'];
                }
            }
            $errors = $timesheet->getTimesheetErrors($error);
            $fieldtype['select']['field']['error'] = 'Error';
            $fieldtype['select']['optionlist']['error'] = str_replace(';','|',(str_replace(':','=>',$errors)));
            $avatars = $timesheet_model->enlistAvatarByClient($client);
            $avatarlist = array();
            foreach($avatars as $value){
                $avatarlist[] = $value['avatar_id'].'=>'.$value['firstname'].' '.$value['lastname'];
            }
            $fieldtype['select']['field']['avatar_id'] = 'User';
            $fieldtype['select']['optionlist']['avatar_id'] = implode('|',$avatarlist);
        }else{
            $timesheetStatus = new VA_Model_TimesheetStatus();
            $status = $timesheetStatus->getDatabyParams(array('status'=>'DISTINCT(TRIM(field_name))'),"client_id in ($client)",null,'field_name');
            $statusarray = array();
            foreach($status as $value){
                $statusarray[] = strtolower(str_replace(' ','_',$value['status'])).'=>'.$value['status'];      
                  }
                $fieldtype['select']['field']['multistatus'] = 'Status';
                $fieldtype['select']['optionlist']['multistatus'] = implode($statusarray,'|');
            }       
            $fieldtype['other']['calc'] = VA_Service_MetaOptions::getArrayFromList(VA_Logic_Session::translate('MOD_EMPLOYEE_XFLAT_MATH'));
            $fieldtype['other']['field']['avatar_id'] ='Users';
            $fieldtype['other']['field']['id'] ='Record';
            $fieldtype['other']['field']['duration'] ='Duration';
            return $fieldtype;
        }
     public static function convertMonthNumbertoMonthName($monthnum, $format = 'F'){ //This code will convert the Month number to Month Name format
        $monthnum; // 3
        $dateObj   = DateTime::createFromFormat('!m', $monthnum);
        return $monthName = $dateObj->format($format); // March
    }

    public static function getquarterDates($date,$format='Y-m-d'){
        $days = date('d',strtotime($date));
        $month = date('m',strtotime($date));
        $year = date('Y',strtotime($date));
        if($month >='1' && $month <= '3'){
            $returnarray = array('startdate'=>date($year.'-01-01'),'enddate'=>date($year.'-03-t'),'quarter'=>1);
        }elseif($month >='4' && $month <= '6'){
            $returnarray = array('startdate'=>date($year.'-04-01'),'enddate'=>date($year.'-06-t'),'quarter'=>2);
        }elseif($month >='7' && $month <= '9'){
            $returnarray = array('startdate'=>date($year.'-07-01'),'enddate'=>date($year.'-09-t'),'quarter'=>3);
        }elseif($month >='10' && $month <= '12'){
            $returnarray = array('startdate'=>date($year.'-10-01'),'enddate'=>date($year.'-12-t'),'quarter'=>4);
        }
        $returnarray['daysfinished'] = floor(($date - $returnarray['startdate'])/ (60 * 60 * 24));
        return $returnarray;
    }

    public static function slug($z){
        $z = strtolower($z);
        $z = preg_replace('/[^a-z0-9 -_.]+/', '', $z);
        $z = str_replace(' ', '-', $z);
        return trim($z, '-');
    }

    public static function statustolabel($status){
        switch ($status) {
            case 'Active':
                $label = '<span class="label label-success">'.$status.'</span>';
                break;
            
            case 'Inactive':
                $label = '<span class="label label-default">'.$status.'</span>';
                break;
        }
        return $label;
    }

    /**
    * Removes  utf-8 characters from the string
    *
    *https://stackoverflow.com/questions/1176904/php-how-to-remove-all-non-printable-characters-in-a-string
    *
    * @param       string  $string    Input string
    * @return      string
    */

    public static function remove_utf8($string){
        return preg_replace('/[\x00-\x1F\x7F-\xFF]/', '', $string);
    }

    public static function validateDate($date, $format = 'Y-m-d H:i:s')
    {
        $d = DateTime::createFromFormat($format, $date);
        return $d && $d->format($format) == $date;
    }

    public static function checkForSpecialCharacters($string){
        if (preg_match('/[\'^£$%&*()}{@#~?>.<>,|=_+¬-]/', $string))
        {
            return 1;
        }
        return 0;
    }

    public static function checkForCharacters($string){
        if (preg_match('/[A-Za-z]/', $string))
        {
            return 1;
        }
        return 0;
    }

     public static function rand_color() {
        return '#' . str_pad(dechex(mt_rand(0, 0xFFFFFF)), 6, '0', STR_PAD_LEFT);
    }
    public static function calculations($value, $paidlsd, $incurredlsd, $policy_premium, $select_options,$checkultimate = true){
        $earnedpremium = number_format($value['earnedpremium'], 3, '.', '');
        $writtenpremium = number_format($value['writtenpremium'], 3, '.', '');
        $effective_year = date('Y', strtotime($value['mr_effectivedate']));

        $paid_effect_value = (isset($paidlsd[$effective_year]))?$paidlsd[$effective_year]:1;
        $incurred_effect_value = (isset($incurredlsd[$effective_year]))?$incurredlsd[$effective_year]:1;
        $value['frequency'] = number_format($value['claimcount']/($earnedpremium/100000), 3, '.', '');
        $value['paid_loss_ratio'] = number_format($value['totalpaid']/$earnedpremium, 3, '.', '');
        $value['incurred_loss_ratio'] = number_format($value['totalincurred']/$earnedpremium, 3, '.', '');
        $value['incurred_loss_severity'] = number_format($value['totalincurred']/$value['claimcount'], 3, '.', '');
        $value['ultimate_loss_paid'] = number_format(($value['totalpaid']*$paid_effect_value), 3, '.', '');
        $value['ultimate_loss_ratio_paid'] = number_format($value['ultimate_loss_paid']/$earnedpremium, 3, '.', '');
        $value['ultimate_loss_severity_paid'] = number_format($value['ultimate_loss_paid']/$value['claimcount'], 3, '.', '');
        $value['ultimate_loss_incurred'] = number_format(($value['totalincurred']*$incurred_effect_value), 3, '.', '');
        $value['ultimate_loss_ratio_incurred'] = number_format($value['ultimate_loss_incurred']/$earnedpremium, 3, '.', '');
        $value['ultimate_loss_severity_incurred'] = number_format($value['ultimate_loss_incurred']/$value['claimcount'], 3, '.', '');
        //$value['policy_count'] = 1;
        $value['earnedpremium'] = $earnedpremium;
        $value['writtenpremium'] = $writtenpremium;
        if($checkultimate){
            $value['basic_limit_premium_100'] = number_format((1.00/(isset($policy_premium[$value['mr_yearclaimlimit']][$value['mr_yearaggregatelimit']])?$policy_premium[$value['mr_yearclaimlimit']][$value['mr_yearaggregatelimit']]:1)*$earnedpremium), 3, '.', '');
            $value['basic_limit_premium_250'] = number_format((1.55/(isset($policy_premium[$value['mr_yearclaimlimit']][$value['mr_yearaggregatelimit']])?$policy_premium[$value['mr_yearclaimlimit']][$value['mr_yearaggregatelimit']]:1)*$earnedpremium), 3, '.', '');
            $value['limited_developed_loss_100'] = number_format((($value['ultimate_loss_incurred'] < 100000)?$value['ultimate_loss_incurred']:100000), 3, '.', '');
            $value['limited_developed_loss_250'] = number_format((($value['ultimate_loss_incurred'] < 250000)?$value['ultimate_loss_incurred']:250000), 3, '.', '');
        }
        $value['limited_unlimited_loss_ratio_100'] = number_format(($value['limited_developed_loss_100']/$value['basic_limit_premium_100']), 3, '.', '');
        $value['limited_unlimited_loss_ratio_250'] = number_format(($value['limited_developed_loss_250']/$value['basic_limit_premium_250']), 3, '.', '');
        $value['mr_effectivedate'] = ($effective_year != '1970')?$effective_year:'';
        //$value['mr_state'] = $select_options['mr_state'][$value['mr_state']];

        foreach ($select_options['writtenpremium'] as $range => $text) {
            $range_value = explode('-', $range);
            $count = count($range_value);
            if(($count == 2 && ($writtenpremium >= $range_value[0] && $writtenpremium <= $range_value[1])) || ($count == 1 && ($writtenpremium >= '75001' || $writtenpremium <= '5000'))){
                $value['premium_tier'] = $text;
                break;
            }
        }
        return $value;
    }

    static public function getMimeType($filetype,$baseurl,$url){
        $filetype = strtolower($filetype);
        if(in_array($filetype, array("png"))){
            return array("previewimage"=>$baseurl.'/assets/img/file_png.png',"type"=>"image");
        } else if(in_array($filetype, array("jpg","jpeg"))){
            return array("previewimage"=>$baseurl.'/assets/img/file_jpg.png',"type"=>"image");
        } else if(in_array($filetype, array("bmp"))){
            return array("previewimage"=>$baseurl.'/assets/img/file_image.png',"type"=>"image");
        } else if(in_array($filetype, array("gif"))){
            return array("previewimage"=>$baseurl.'/assets/img/file_gif.png',"type"=>"image");
        } else if (in_array($filetype, array("pdf"))){
            return array("previewimage"=>$baseurl.'/assets/img/file_pdf.png',"type"=>"pdf");
        } else if (in_array($filetype, array("xls", "xlsx", "csv"))){
            return array("previewimage"=>$baseurl.'/assets/img/file_xlsx.png',"type"=>"spreadsheet");
        } else if (in_array($filetype, array("docx"))){
            return array("previewimage"=>$baseurl.'/assets/img/file_docx.png',"type"=>"document");
        }  else if (in_array($filetype, array("ods", "fods"))){
            return array("previewimage"=>$baseurl.'/assets/img/file_ods.png',"type"=>"spreadsheet");
        }  else if (in_array($filetype, array("csv"))){
            return array("previewimage"=>$baseurl.'/assets/img/file_csv.png',"type"=>"spreadsheet");
        } else if (in_array($filetype, array("doc","mht", "djvu","fb2", "epub", "xps"))){
            return array("previewimage"=>$baseurl.'/assets/img/file_doc.png',"type"=>"document");
        } else if (in_array($filetype, array( "odt", "fodt"))){
            return array("previewimage"=>$baseurl.'/assets/img/file_odt.png',"type"=>"document");
        } else if (in_array($filetype, array( "rtf"))){
            return array("previewimage"=>$baseurl.'/assets/img/file_rtf.png',"type"=>"document");
        } else if (in_array($filetype, array("txt"))){
            return array("previewimage"=>$baseurl.'/assets/img/file_txt.png',"type"=>"document");
        } else if (in_array($filetype, array("html","htm"))){
            return array("previewimage"=>$baseurl.'/assets/img/file_html.png',"type"=>"document");
        } else if (in_array($filetype, array("pps", "ppsx","ppt", "pptx"))){
            return array("previewimage"=>$baseurl.'/assets/img/file_pptx.png',"type"=>"presentation");
        } else if (in_array($filetype, array("odp", "fodp"))){
            return array("previewimage"=>$baseurl.'/assets/img/file_odp.png',"type"=>"presentation");
        } else{
         return array("previewimage"=>$baseurl.'/assets/img/file.png',"type"=>"file");
        }
    }
    /**
     * Make an internal curl request
     *
     * In some cases we may need to make a curl to within data elements within the current codebase ,in this scenario we need to get existing user's access details while making curl request
     *
     *  @param url The url of the request to be made 
          *  @return response of the request made
     */
    static public function makeInternalCurlRequest($url){
        $crypto = new VA_Logic_Crypto();
        $username = $crypto->encryption(VA_Logic_Session::getAvatar()->username);
        $password = $crypto->encryption(VA_Logic_Session::getAvatar()->password);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, VA_Logic_Session::getFullBaseUrl().$url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_ENCODING, '');
        curl_setopt($ch, CURLOPT_HTTPHEADER, array("username:".$username,"password:".$password,"auth:1"));
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_VERBOSE, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $result = curl_exec ($ch);
        curl_close ($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        return $result;
    }

    static public function getFileSize($bytes) {
        if ($bytes >= 1073741824) {
            $bytes = number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            $bytes = number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            $bytes = number_format($bytes / 1024, 2) . ' KB';
        } elseif ($bytes > 1) {
            $bytes = $bytes . ' bytes';
        } elseif ($bytes == 1) {
            $bytes = $bytes . ' byte';
        } else {
            $bytes = '0 bytes';
        }
        return $bytes;
    }
    static function dateToUTC($datetime, $timezone = null) {
        if (!$datetime)
            return "";
        if ($timezone == null) {
            $timezone = date_default_timezone_get();   //If timezone is null, get default(server timezone).
        }
        $date = new DateTime($datetime, new DateTimeZone($timezone));
        $date->setTimezone(new DateTimeZone('UTC'));
        return $date->format('Y-m-d H:i:s');
    }

    static function dateFromUTC($datetime, $timezone = null, $format = null) {
        if (!$datetime)
            return "";
        if ($timezone == null) {
            $timezone = date_default_timezone_get();   //If timezone is null, get default(server timezone).
        }
        if($format == null){
            $format = 'Y-m-d H:i:s';
        }
        $date = new DateTime($datetime, new DateTimeZone('UTC'));
        $date->setTimezone(new DateTimeZone($timezone));
        return $date->format($format);
    }
    static function expandArray($list){
        if($list){
            $list = explode('|', $list);
            $options = array();
            foreach ($list as $option) {
                $option = explode('=>', $option);
                $options[$option[0]] = $option[1];
            }
            return $options;
        }
    }

}