<?php
namespace Oxzion\Analytics\Elastic;

use Oxzion\Analytics\AnalyticsEngine;
use Elasticsearch\ClientBuilder;
use Oxzion\Service\ElasticService;
use Bos\Auth\AuthContext;
use Bos\Auth\AuthConstants;

class AnalyticsEngineImpl implements AnalyticsEngine {
	private $config;
	private $hasGroup;
    public function __construct($config) {
        $this->config = $config;
    }

    public function runQuery($appId,$type,$parameters)
    {
        try {
			if ($type) {
				$parameters['Filter-type']=$type;
			}
            $query = $this->formatQuery($parameters);
            $elasticService = new ElasticService($this->config);
            $data = $elasticService->getQueryResults($appId,$query);
            return $data;
        } catch (Exception $e) {
            throw new Exception("Error performing Elastic Search", 0, $e);
        }
    }


    private function formatQuery($parameters) {
		
		$datetype = $parameters['date_type'];
		if ($parameters['date-period']) {
			$period = explode('/', $parameters['date-period']);
			$startdate = $period[0];
			$enddate = $period[1];
		} else {
			$startdate = date('Y').'-01-01';
			$enddate = date('Y').'-12-31';
		}
		if (substr(strtolower($parameters['field']), 0, 5) == 'date(') {
			$parameters['field'] = substr($parameters['field'], 5, -1);
		}
		if (!isset($parameters['operation'])) {
			$parameters['operation'] = 'count';
		}
		$field = $parameters['field'];
		$parameters['operation'] = strtolower($parameters['operation']);
		if ($parameters['operation'] != 'count_distinct') {
			$operation = explode('_', $parameters['operation']);
		} else {
			$operation[0] = $parameters['operation'];
		}

		$group = array();
		$aggregates = array();
		if ($parameters['group']) {
			$parameters['frequency'] = 4;  //frequency 4 is to override time frequecy by group
			$group = $parameters['group'];
		}
		$aggregates[$operation[0]] = strtolower($field);
		if ($parameters['frequency'] != 4) {
			switch ($parameters['frequency']) {
				case 1:
					$group[] = 'period-month';
					break;
				case 2:
					$group[] = 'period-quarter';
					break;
				case 3:
					$group[] = 'period-day';
					break;
				case 5:
					$group[] = 'period-year';
					break;
			}
		}
		if (!isset($parameters['skipdate']) && $datetype)
			$range[$datetype] = $startdate . "/" . $enddate;

		$timesheetStatus = new VA_Model_TimesheetStatus();
		foreach ($parameters as $key => $value) {
			if (strstr($key, 'Filter')) {

				list($k1, $keycolumn) = explode('-', $key);
				list($temp, $type) = explode('_', $k1);
				if (strtolower($type) == 'value') {
					$filtertype = 'value';
				} else {
					$filtertype = 'key';
				}

				$value = rtrim($value, ',');
				$options = explode(',', $value);

				asort($options);
				if ($keycolumn) $keycolumn = strtolower($keycolumn);
				if ($options[0] !== null) {
					if (count($options) > 1) {
						$filter[$keycolumn] = $options;
					} else {
						if ($options[0] != 'all') {  
							if ($filtertype == 'value') {
								$filter[$keycolumn . '__value'] = $options[0];
							} else {
								$filter[$keycolumn] = $options[0];
							}
						}
					}
				}
			}
		}
		$this->hasGroup = (empty($group)) ? 0 : 1;
		if (!empty($group)) $group = array_map('strtolower', $group);

		$returnarray = array('filter' => $filter, 'group' => $group, 'range' => $range, 'aggregates' => $aggregates);
		if (isset($parameters['pagesize'])) {
			$returnarray['pagesize'] = $parameters['pagesize'];
		}
		if (isset($parameters['list'])) {
				if($parameters['list']=="*"){
					$returnarray['select'] = ['display_id', 'name', 'status', 'created_by', 'date_created','modified_by','date_modified'];
				} else {
					$listConfig=explode(",", $parameters['list']);
					foreach ($listConfig as $k => $v) {
						if(strpos($v, "=")!==false){
							$listitem = explode("=", $v);
							$returnarray['select'][] = $listitem[0];
							$returnarray['listfields'][] = $listitem[1];
						} else {
							$returnarray['select'][] = $v;
							$returnarray['listfields'][] = $v;
						}
					}
				}	
		}
		if (isset($parameters['sort'])) {
			$returnarray['sort'] = $parameters['sort'];
		}
		return $returnarray;
    }

}
?>