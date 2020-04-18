<?php

namespace Oxzion\Analytics;

use Oxzion\Service\TemplateService;
use Logger;


class AnalyticsAbstract
{

    protected $logger;
    protected $config;
    protected $appConfig;
    protected $appDBAdapter;

    public function __construct($config,$appDBAdapter,$appConfig) {
        $this->config = $config;  //This config is config for connection not the app config. 
        $this->appDBAdapter = $appDBAdapter;
        $this->appConfig = $appConfig;
        $this->logger = Logger::getLogger(get_class($this));
    }


    public function runQuery($app_name,$entity_name,$parameters){
        $finalResult = $this->getData($app_name,$entity_name,$parameters);
        if (isset($parameters['expression']) ||  isset($parameters['round']) ) {
            $finalResult['data'] = $this->postProcess($finalResult['data'],$parameters);
        }
        if (isset($parameters['target'])) {
            $finalResult['target'] = $parameters['target'];
        }
        if (isset($parameters['template'])) {
            $finalResult['data'] = $this->applyTemplate($finalResult,$parameters);
        }
        return $finalResult;
    }


    public function postProcess($resultData, $parameters)
    {

        $expression = "";
        $round = null;

        if (isset($parameters['expression'])) {
            $expression = $parameters['expression'];
        }
        if (isset($parameters['round'])) {
            $round = $parameters['round'];
        }
        $finalResults = $resultData;
        if (is_array($resultData)) {
            $field = isset($parameters['field']) ?$parameters['field']:'count';
            foreach ($resultData as $key => $data) {
                $value = $data[$field];
                eval("\$value=\$value" . $expression . ";");
                if ($round !== null && is_numeric($value)) {
                    $value = round($value, $round);
                }
                $finalResults[$key][$field] = $value;
            }
        } else {
            $value = $resultData;
            eval("\$value=\$value" . $expression . ";");
            if ($round !== null && is_numeric($value)) {
                $value = round($value, $round);
            }
            $finalResults = $value;
        }
        return $finalResults;
    }


    public function applyTemplate($resultData,$parameters) {
        $templateName = $parameters['template'];
        $templateEngine = new TemplateService($this->appConfig,$this->appDBAdapter);
        $templateEngine->init();
        print_r($resultData['data']);
        $result = $templateEngine->getContent($templateName,$resultData);
        return $result;
    }

}
