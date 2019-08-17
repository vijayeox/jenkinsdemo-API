<?php
namespace Oxzion\Analytics\Elastic;

use Elasticsearch\ClientBuilder;
use Oxzion\Service\ElasticService;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;

class AnalyticsEngineImpl {
	private $config;
	private $hasGroup;
    public function __construct($config) {
        $this->config = $config;
    }

    public function runQuery($appId,$type,$query,$parameters)
    {
        try {
            $orgId = AuthContext::get(AuthConstants::ORG_ID);
            if ($type) {
                $parameters['Filter-type']=$type;
            }
            $query = $this->formatQuery($parameters);
            $elasticService = new ElasticService($this->config);
			$result = $elasticService->getQueryResults($orgId,$appId,$query);
			if ($result['type']=='group') {
				$result['data'] = $this->flattenResult($result,$query);
			} else {
				$result['list'] = $query['select'];
				$result['displaylist'] = $query['displaylist'];
			}
			return $result;
        } catch (Exception $e) {
            throw new Exception("Error performing Elastic Search", 0, $e);
        }
    }


    private function formatQuery($parameters) {
		$range=null;
		$filter=null;
		$datetype = (!empty($parameters['date_type']))?$parameters['date_type']:null;
		if (!empty($parameters['date-period'])) {
			$period = explode('/', $parameters['date-period']);
			$startdate = date('Y-m-d', strtotime($period[0]));
			$enddate =  date('Y-m-d', strtotime($period[1]));
		} else {
			$startdate = date('Y').'-01-01';
			$enddate = date('Y').'-12-31';
		}
		if (!empty($parameters['field'])) {
			if (substr(strtolower($parameters['field']), 0, 5) == 'date(') {
				$parameters['field'] = substr($parameters['field'], 5, -1);
			}
			$field = $parameters['field'];
		}

		if (!isset($parameters['operation'])) {
			$parameters['operation'] = 'count';
		}

		$parameters['operation'] = strtolower($parameters['operation']);
		if ($parameters['operation'] != 'count_distinct') {
			$operation = explode('_', $parameters['operation']);
		} else {
			$operation[0] = $parameters['operation'];
		}

		$group = array();
		$aggregates = array();
		if (!empty($parameters['group'])) {
			$parameters['frequency'] = null;  //frequency 4 is to override time frequecy by group
			if (is_array($parameters['group'])) {
				$group = $parameters['group'];
			} else {
				$group = explode(',',$parameters['group']);
			}
		}
		if ($field) {
			$aggregates[$operation[0]] = strtolower($field);
		}
		else {
				if (!isset($parameters['list'])) {
					if (!empty($group)) {
						$aggregates[$operation[0]] = strtolower($group[0]);
				} else {
						$aggregates[$operation[0]] = '_id';
				}
			}
		}
		if (!empty($parameters['frequency'])) {
			switch ($parameters['frequency']) {
				case 1:
					$group[] = 'period-day';
					break;
				case 2:
					$group[] = 'period-month';
					break;
				case 3:
					$group[] = 'period-quarter';
					break;
				case 4:
					$group[] = 'period-year';
					break;
			}
		}
		if (!isset($parameters['skipdate']) && $datetype)
			$range[$datetype] = $startdate . "/" . $enddate;
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
							$returnarray['displaylist'][] = $listitem[1];
						} else {
							$returnarray['select'][] = $v;
							$returnarray['displaylist'][] = $v;
						}
					}
				}
		}
		if (isset($parameters['sort'])) {
			$returnarray['sort'] = $parameters['sort'];
		}
		return $returnarray;
	}



		$operation = key($config['aggregates']);
		if ($index==$count) {
			foreach($result as $data) {
				if ($operation=='count') {
					$value = $data['doc_count'];
				} else {
					$value = $data['value']['value'];
				}
				$finalresult[] = array('name'=>$key.' - '.$data['key'],'value'=>$value,'grouplist'=>array_merge ($grouplist,array($data['key'])));
			}
		} else {
			foreach($result as $data) {
				$groupname = 'groupdata'.$index;
				$keytemp = ($key) ? $key.' - '.$data['key']:$data['key'];
				$grouplisttemp = array_merge($grouplist,array($data['key']));
				$this->flattenmultigroups($finalresult,$data['groupdata'.(string)$index]['buckets'],$config,$count,$index+1,$keytemp,$grouplisttemp);
			}

		}
	}

	public function flattenResult($result,$config) {
		$finalresult = array();
		$operation = key($config['aggregates']);
		$qtrtranslate = array('Jan'=>'Q1','Apr'=>'Q2','Jul'=>'Q3','Oct'=>'Q4');
		if (isset($config['group'])) {
			if (count($config['group'])==1) {
				foreach($result['data'] as $data ) {
					if (substr($config['group'][0],0,7)=='period-') {
						$name = $data['key_as_string'];
						if ($config['group'][0]=='period-quarter') {
						   $month = substr($name,0,3);
						   $name=$qtrtranslate[$month].substr($name,3);
						}

					} else {
						$name = $data['key'];
					}
					if ($operation=='count') {
						$value = $data['doc_count'];
					} else {
						$value = $data['value']['value'];
					}
					$finalresult[]=array('name'=>$name,'value'=>$value,'grouplist'=>$config['group'][0]);
				}
			} else {
				$this->flattenmultigroups($finalresult,$result['data'],$config,count($config['group'])-1,0);
			}
		} else {
			$finalresult[] = array('name'=>$config['field'],'value'=>$result['data']);
		}
		return $finalresult;
	}

}
?>