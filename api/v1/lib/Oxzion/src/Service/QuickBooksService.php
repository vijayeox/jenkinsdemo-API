<?php
namespace Oxzion\Service;

use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\ReportService\ReportService;


class QuickBooksService {

	private $dataService;
	private $loginHelper;
	private $data;
	private $config;

    public function __construct() {
    }

    public function setConfig($config){
    	$this->config = $config;
        $this->dataService = DataService::Configure($config);
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

	public function updateToken($accessTokenObj){
		$this->dataService->updateOAuth2Token($accessTokenObj);
	}
	public function authorize($code,$realmid){
		$this->loginHelper = $this->dataService->getOAuth2LoginHelper();
		return $this->loginHelper->exchangeAuthorizationCodeForToken($code,$realmid);
	}
	public function refreshToken(){
		$this->setLoginHelper();
		$refreshedAccessTokenObj = $this->dataService->getOAuth2LoginHelper()->refreshToken();
		$error = $this->loginHelper->getLastError();
		if(!$error){
			$this->dataService->updateOAuth2Token($refreshedAccessTokenObj);
		}
		return $refreshedAccessTokenObj->getRefreshToken();
	}
	
	public function getUpdatedConfig(){
		$config = $this->config;
		$config['refreshTokenKey'] = $this->refreshToken();
		return $config;
	}

	public function getReport($params){
		$report  = array();
		$updatedConfig = $this->getUpdatedConfig();
		$report['config']=$updatedConfig;
		$startdate = $params['startdate'];
		$enddate = $params['enddate'];
		$method = $params['type'];
		$reporttype = $params['reporttype'];
		$summarizeColumn = isset($params['summarize_column_by'])?$params['summarize_column_by']:null;
		$this->dataService->useJson();
		$serviceContext = $this->dataService->getServiceContext();
		$reportService = new ReportService($serviceContext);
		if (!$reportService) {
			return "Problem while initializing ReportService.\n";
		}
		if(isset($startdate)){
			$reportService->setStartDate($startdate);
		}
		if(isset($enddate)){
			$reportService->setEndDate($enddate);
		}
		if(isset($method)){
			$reportService->setAccountingMethod($method);
		}
		if(isset($summarizeColumn)){
			$reportService->setSummarizeColumnBy($summarizeColumn);
		}
		$reflector = new \ReflectionClass('QuickBooksOnline\API\ReportService\ReportName');
		$classobj = $reflector->getConstants();
		$report_type = $classobj[$reporttype];
	//	$error = $this->dataService->getLastError();
		$report['data'] = json_decode(json_encode($reportService->executeReport($report_type)), true);
		return $report;
	}




}
?>