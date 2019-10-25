<?php

namespace Oxzion\Analytics;

use Hamcrest\Type\IsNumeric;

class AnalyticsPostProcessing
{

    public static function postProcess($results, $parameters)
    {

        $expression = "";
        $round = null;
        if (isset($parameters['expression'])) {
            $expression = $parameters['expression'];
        }
        if (isset($parameters['round'])) {
            $round = $parameters['round'];
        }

        if (is_array($results['data'])) {
            foreach ($results['data'] as $key => $data) {
                $value = $data['value'];
                eval("\$value=\$value" . $expression . ";");
                if ($round !== null && is_numeric($value)) {
                    $value = round($value, $round);
                }
                $results['data'][$key]['value'] = $value;
            }
        } else {
            $value = $results['data'];
            eval("\$value=\$value" . $expression . ";");
            if ($round !== null && is_numeric($value)) {
                $value = round($value, $round);
            }
            $results['data'] = $value;
        }
        return ($results);
    }

}
