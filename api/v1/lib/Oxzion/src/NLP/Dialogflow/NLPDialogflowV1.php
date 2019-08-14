<?php
namespace Oxzion\NLP\Dialogflow;

use Oxzion\Utils\RestClient;
use Oxzion\NLP\NLPEngine;
use function GuzzleHttp\json_encode;

class NLPDialogflowV1 implements NLPEngine
{
    public static $apiKey = 'ba466a6e36d24de895838ce4dc05b172'; //this has to be moved to org settings
    public static $url = "https://api.api.ai/api/query?v=20150910";

    public function processText($text)
    {
        $responseJSON =  $this->callAPI($text);
        $returnJSON = $this->formatResponse($responseJSON);
        return $returnJSON;
    }

    public function callAPI($text)
    {
        $querytext = urlencode($text);
        $client = new RestClient('');
        $headers = ['Authorization'=>'Bearer '.self::$apiKey];
        $data = $client->get(self::$url."&query=$querytext&lang=en&sessionId=123456789", null, $headers);
        return $data;
    }

    public function formatResponse($responseJSON)
    {
        $data = json_decode($responseJSON, true);
        $result = $data['result'];
        $parameters = $result['parameters'];
        $parameters = $this->cleanParameters($parameters);
        $action = $result['action'];
        $response = '';
        if ($action=='error') {
            $response = "Sorry I did not understand the question";
        } else {
            if (!$action) {
                if (isset($result['fulfillment']['speech'])) {
                    $response = $result['fulfillment']['speech'];
                }
            }
        }
        $returnResponse = json_encode(['parameters'=>$parameters,'action'=>$action,'response'=>$response]);
        return($returnResponse);
    }



    public function cleanParameters($parameters)
    {
        if (isset($parameters['date-period']) && $parameters['date-period']) {
            $datepara =  $parameters['date-period'];
        } elseif (isset($parameters['date']) && $parameters['date']) {
            $datepara =  $parameters['date'];
        } else {
            $datepara = date('Y-01-01').'/'.date('Y-12-31');
        }
        $pos = strpos($datepara, '/');
        if ($pos===false) {
            $datepara=date('Y-m-d', strtotime($datepara));
            $datepara = $datepara.'/'.$datepara;
        }
        $parameters['date-period'] = $datepara;
        return($parameters);
    }
}
