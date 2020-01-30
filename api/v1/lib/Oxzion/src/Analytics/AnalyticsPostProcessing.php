<?php

namespace Oxzion\Analytics;

use Hamcrest\Type\IsNumeric;

class AnalyticsPostProcessing
{

    public static function postProcess($resultData, $parameters)
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

}
