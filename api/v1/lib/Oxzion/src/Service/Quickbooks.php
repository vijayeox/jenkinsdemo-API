<?php
require __DIR__ .'/autoload.php';
use QuickBooksOnline\API\Core\ServiceContext;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\ReportService\ReportService;
use QuickBooksOnline\API\ReportService\ReportName;
use QuickBooksOnline\API\Core\CoreConstants;
use QuickBooksOnline\API\PlatformService\PlatformService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Facades\Purchase;
use QuickBooksOnline\API\Data\IPPPurchase;
use QuickBooksOnline\API\QueryFilter\QueryMessage;

class VA_ExternalLogic_Quickbooks {

	private $dataService;
	private $loginHelper;
	private $data;
	private $config;

	public function __construct($data=null,$dataService=null){
		if(!$dataService){
			$this->data = $data;
			$this->dataService = DataService::Configure($this->data);
		} else {
			$this->dataService = DataService::Configure($dataService);
		}
		$this->setLoginHelper();
	}
	public function getData(){
		return $this->data;
	}
	public static function initWithdataService($dataService){
		return new self(null,$dataService);
	}
	public function getAccessToken(){
		return $this->refreshToken();
		// return $this->dataService;
	}
	public function setLoginHelper(){
		$this->loginHelper = $this->dataService->getOAuth2LoginHelper();
	}
	public function getConfigArray(){
		$this->loginHelper = $this->dataService->getOAuth2LoginHelper();
	}
	public function getUrl(){
		$authorizationCodeUrl = $this->loginHelper->getAuthorizationCodeURL();
		return $authorizationCodeUrl;
	}
	public function getInvoices(){
		// $authorizationCodeUrl = $OAuth2LoginHelper->getAuthorizationCodeURL();
		return $authorizationCodeUrl;
	}
	public function updateToken($accessTokenObj){
		$this->dataService->updateOAuth2Token($accessTokenObj);
	}
	public function authorize($code,$realmid){
		$this->loginHelper = $this->dataService->getOAuth2LoginHelper();
		return $this->loginHelper->exchangeAuthorizationCodeForToken($code,$realmid);
	}
	public function refreshToken(){
		$accessTokenObj = $this->loginHelper->refreshToken();
		return $accessTokenObj->getAccessToken();
	}
	public function getConfig($params){
		$string = "$[QUICKBOOKREPORT!";
		foreach ($params as $key => $value) {
			if($key=='startdate'){
				$parameters[] = "startdate:".$value;
			}
			if($key == 'enddate'){
				$parameters[] = "enddate:".$value;
			}
			if($key == 'type'){
				$parameters[] = "type:".$value;
			}
			if($key == 'reporttype'){
				$parameters[] = "reporttype:".$value;
			}
		}
		$string .= implode(",", $parameters);
		$string .= "]";
		return $string;
	}
	public function getReport($params){
		$data = explode(",", $params);
		if(is_array($data)){
			foreach ($data as $key => $value) {
				$config = explode(":", $value);
				$data[$config[0]] = $config[1];
			}
		} else {
			$data = $params;
		}
		$startdate = $data['startdate'];
		$enddate = $data['enddate'];
		$method = $data['type'];
		$reporttype = $data['reporttype'];
		$this->refreshToken();
		$this->dataService->useJson();
		$serviceContext = $this->dataService->getServiceContext();
		$reportService = new ReportService($serviceContext);
		if (!$reportService) {
			return "Problem while initializing ReportService.\n";
		}
		if($startdate){
			$startdate = date('Y-m-d', strtotime($startdate));
			$reportService->setStartDate($startdate);
		}
		if($enddate){
			$enddate = date('Y-m-d', strtotime($enddate));
			$reportService->setEndDate($enddate);
		}
		if($method){
			$reportService->setAccountingMethod($method);
		}
		$reflector = new ReflectionClass('QuickBooksOnline\API\ReportService\ReportName');
		$classobj = $reflector->getConstants();
		$report_type = $classobj[$reporttype];
		$report = json_decode(json_encode($reportService->executeReport($report_type)), true);
		return $this->constructReport($report);
	}
	function constructReport($report){
		$string .='<div id="reportcontentsection">'.$report['Header']['ReportName'].'</h1><h1 class="page-title">	<small> Report Basis :'.$report['Header']['ReportBasis']." Period ".$report['Header']['StartPeriod']." to ".$report['Header']['EndPeriod'].'</small><small class="pull-right">Currency : '.$report['Header']['Currency'].'</small></h1><hr><div class="invoice"><div class="row"><div class="col-xs-12">';
		$string .= '<table class="table table-striped table-hover"><thead class="ox-table"><tr>';
		foreach ($report['Columns'] as $key => $value) {
			foreach ($value as $k => $v) {
				$string  .= "<th style='text-align:left'>".$v['ColTitle']."</th>";
			}
		}
		$string.="</tr></thead><tbody>";
		$string.= $this->createRows($report['Rows'],null,"");
		$string.="</tbody></table>";
		$string.='</div></div></div></div>';
		return $string;
	}
	function createRows($itemlist,$tabspace=null){
		foreach ($itemlist as $key => $value) {
		// if($value['type']=='Section'){
			$string .= $this->loopRow($value,null,$string);
		// }
		}
		return $string;
	}
	function loopRow($rows,$tabspace=null,$string){
		foreach ($rows as $key => $value) {
			if($value['type']=='Section'){
				// break;
				$string = $this->loopRow($value,$tabspace,$string);
			} else {
				if($value['type']=="Data"){
					$string .= "<tr>";
					foreach ($value['ColData'] as $k => $v) {
						if($k==0){
							$string .= "<td>".$tabspace.$v['value']."</td>";
						} else {
							$string .= "<td>".$v['value']."</td>";
						}
					}
					$string .= "</tr>";
				} else {
					if($key=='Header'){
						$string .= "<tr style='border-bottom:1px solid #452767'>";
						foreach ($value['ColData'] as $k => $v) {
							if($k==0){
								$string .= "<td>".$tabspace.$v['value']."</td>";
							} else {
								$string .= "<td>".$v['value']."</td>";
							}
						}
						$string .= "</tr>";
					} else if($key=='Rows'){
						$string .= $this->loopRow($value['Row'],$tabspace.'&emsp;');
					} else if($key=='Summary'){
						$string .= "<tr'>";
						foreach ($value['ColData'] as $k2 => $v2) {
							if($k2==0){
								$string .= "<td><b>".$tabspace.$v2['value']."</b></td>";
							} else {
								$string .= "<td><b>".$v2['value']."</b></td>";
							}
						}
						$string .= "</tr>";
					}
				}
			}
		}
		return $string;
	}
}
?>