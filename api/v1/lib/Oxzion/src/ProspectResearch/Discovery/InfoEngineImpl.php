<?php
namespace Oxzion\ProspectResearch\Discovery;

use Oxzion\ProspectResearch\InfoEngine;
use Zend\Http\Client;
use Zend\Http\Request;
use Oxzion\Utils\RestClient;
// use function GuzzleHttp\json_decode;
// use function GuzzleHttp\json_encode;
use Exception;

class InfoEngineImpl implements InfoEngine
{
    const PARTNER_KEY = 'bfYDYcPmkBYFYTbl2KnJavLR7vt9wsZR56H4rwxYFD0_j7GsF2oKSQN1OF5uN0Lk';
    private $config;
    public function __construct($config)
    {
        $this->config = $config;
    }
    // orgchart test input json
    // {
	// 	"searchtype":"orgchart",
	// 	"searchpara":{
	// 		"companyId":"1448",
	// 		"departmentId": 10
	// 	}
    // }
    // scoop test input json
    // {
    //     "searchtype":"scoop",
    //     "searchpara":{
    //         "companyCriteria": {
    //             "websiteUrls": ["www.keybank.com"]
    //         }
    //     }
    // }

    // companies test input json
    // {
    //     "searchtype":"companies",
    //     "searchpara":{
    //         "companyCriteria": {
    //             "websiteUrls": ["www.keybank.com"]
    //         }
    //     }
    // }

    public function GetCompanyInfo($parameters)
    {
        try {
            $authtoken = $this->getAuthCode();
            $searchtype = $parameters['searchtype'];
            $jsondata = json_encode($parameters['searchpara']);
            $outputData = $this->getInfo($searchtype,$jsondata,$authtoken);
            return $outputData;
        } catch (Exception $e) {
            throw new Exception($e->getMessage(), 0, $e);
        }
    }




    public function getAuthCode()
    {
        $jsonArray = ["username"=>"yuvraj@vantageagora.com","password"=>"Pkq0124!","partnerKey"=>self::PARTNER_KEY];
        $client = new RestClient('');
        try{
            $response = $client->postWithHeader('https://papi.discoverydb.com/papi/login',$jsonArray,['Content-Type' => 'application/json']);
        }
        catch (Exception $e) {
            // $this->log->info("Getting Authorizaion Failed");    
            throw new Exception("Getting Authorizaion Failed.", 0, $e);
        }
        $authtoken = $response['headers']['X-AUTH-TOKEN'][0];
        return $authtoken;
    }


    public function getInfo($searchtype,$jsondata,$authtoken)
    {
        switch ($searchtype) {
            case 'orgchart': /* for example with departmentID of 8 and 10 */
                $data = $this->getOrgInfo($searchtype,$jsondata,$authtoken);
                break;
            case 'scoop':
                $data = $this->getScoopInfo($jsondata,$authtoken);
                break;
            case 'companies':
                $data = $this->getSearchInfo($searchtype,$jsondata,$authtoken);
                break;
            default: /* for example 'companies' the basic info */
                throw new Exception("Invalid searchtype of '$searchtype' given.");
                // $data = $this->getSearchInfo($searchtype,$jsondata,$authtoken);
                // break;
        }
        return $data;
    }


    public function getScoopInfo($jsondata,$authtoken)
    {
        $client = new RestClient('');
        $headersArray=['X-AUTH-TOKEN' => "$authtoken", 'Content-Type' => 'application/json', 'X-PARTNER-KEY' => self::PARTNER_KEY];
        $jsonArray = json_decode($jsondata,true);
        
        try{
            $responseArray = $client->postWithHeader("https://papi.discoverydb.com/papi/v1/search/scoops",$jsonArray,$headersArray);
        }
        catch(Exception $e){
            throw new Exception("Getting Company Scoop Info Failed.", 0, $e);
        } 
        foreach ($responseArray as $key => $result){
            if (is_string($result)){
                $responseArray[$key] = json_decode($result,true);
            }
        }
        return $responseArray; 
    }

    public function getOrgInfo($searchtype,$jsondata,$authtoken)
    {
        $client = new RestClient('');
        
        $headersArray=['X-AUTH-TOKEN' => "$authtoken", 'Content-Type' => 'application/json', 'X-PARTNER-KEY' => self::PARTNER_KEY];
        $jsonArray = json_decode($jsondata,true);
        $companyid = $jsonArray['companyId'];
        $deptid = $jsonArray['departmentId'];
        try{
            $response = $client->get("https://papi.discoverydb.com/papi/v1/companies/" . $companyid . "/orgchart/" . $deptid,$jsonArray,$headersArray);
        }
        catch(Exception $e){
            throw new Exception("Getting Company Org Chart Info Failed.", 0, $e);
        }
        return $response;
    }
        
    public function getSearchInfo($searchtype,$jsondata,$authtoken)
    {
        $client = new RestClient('');
        $headersArray=['X-AUTH-TOKEN' => "$authtoken", 'Content-Type' => 'application/json', 'X-PARTNER-KEY' => self::PARTNER_KEY];
        $jsonArray = json_decode($jsondata,true);
        try{
            $response = $client->postWithHeader("https://papi.discoverydb.com/papi/v1/search/" . $searchtype, $jsonArray, $headersArray);
        }
        catch(Exception $e){
            // $this->log->info("Getting Scoop Info Failed");  
            throw new Exception("Getting Company Info Failed.", 0, $e);
        }
        return $response;
    }
}
