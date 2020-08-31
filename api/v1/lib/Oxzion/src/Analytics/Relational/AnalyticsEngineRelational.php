<?php
namespace Oxzion\Analytics\Relational;

use Oxzion\Analytics\AnalyticsAbstract;
use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Sql;
use Zend\DB;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\Analytics\AnalyticsPostProcessing;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Where;
use Oxzion\Utils;
use Oxzion\Utils\AnalyticsUtils;

abstract class AnalyticsEngineRelational extends AnalyticsAbstract {
	protected $dbAdapter;
	protected $dbConfig;

    public function __construct($appDBAdapter,$appConfig)  {
		parent::__construct($appDBAdapter,$appConfig);
    }

    public function setConfig($config){
        $dbConfig['driver'] = 'Pdo';
        $dbConfig['database'] = $config['database'];
        $dbConfig['host'] = $config['host'];
        $dbConfig['username'] = $config['username'];
        $dbConfig['password'] = $config['password'];
        $dbConfig['dsn'] = 'mysql:dbname=' . $config['database'] . ';host=' . $config['host'] . ';charset=utf8;username=' . $config["username"] . ';password=' . $config["password"] . '';
        $this->dbConfig = $dbConfig;
        $this->dbAdapter = new Adapter($dbConfig);
		parent::setConfig($dbConfig);
    }

    public function getData($app_name,$entity_name,$parameters)
    {
        try {
			
			$orgId = AuthContext::get(AuthConstants::ORG_ID);
			if (isset($parameters['view'])) {
			//	$result = $this->getResultsFromView($orgId,$parameters['view']);	
				$formatedPara = $this->formatQuery($parameters);
		//	print_r($formatedPara);exit;
				$result = $this->getResultsFromPara($orgId,$parameters['view'],$formatedPara);
				$finalResult['meta']['type'] = 'view';
				foreach(array_keys($result) as $key) {
					if ($result[$key]['org_id']!=$orgId) {  //Temp solution to protect with org id instead the where clause
						$result = [];break;
					}
					unset($result[$key]['org_id']);
				 }
				 $finalResult['data'] = $result;
			} else {
				$formatedPara = $this->formatQuery($parameters);
				$result = $this->getResultsFromPara($orgId,$app_name,$entity_name,$formatedPara);
				$finalResult['data'] = $result;
			}
			return $finalResult;
			
        } catch (Exception $e) {
            throw new Exception("Error running MySQL Analytics", 0, $e);
        }
    }

	private function formatQuery($parameters) {
		$range=null;
		$field = null;
		$filter =array();
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
		$operation = strtolower($parameters['operation']);
		$group = array();

		if (!empty($parameters['group'])) {
			$parameters['frequency'] = null;  //frequency 4 is to override time frequecy by group
			if (is_array($parameters['group'])) {
				$group = $parameters['group'];
			} else {
				$group = explode(',',$parameters['group']);
			}
		} 
		$select = array();
		

		if (!empty($parameters['frequency'])) {
			switch ($parameters['frequency']) {
				case 1:
					$group[] = "$datetype";
					break;
				case 2:
					$group[] = "MONTH($datetype)";
					break;
				case 3:
					$group[] = "QUARTER($datetype)";
					break;
				case 4:
					$group[] = "YEAR($datetype)";
					break;
			}
		}
		if ($field) { 
			if (!empty($group)) {
				$select=$group;
				$select[] = 'org_id';
				$select[$field] = new \Zend\Db\Sql\Expression("$operation($field)");
			} else {
				$select = ['org_id',$field=>new \Zend\Db\Sql\Expression("$operation($field)")];
			}
		} 
		else {
			if (!empty($group)) {
				$select=$group;
				$select[] = 'org_id';
				$select[] = new \Zend\Db\Sql\Expression("count(*)");;
			} 
		}
		if ($datetype)
			$range = "$datetype between '$startdate' and '$enddate'";
		if (isset($parameters['filter'])) {
			$filter = $parameters['filter']; 
		}
		$returnarray = array('group' => $group, 'range' => $range, 'select' => $select,'filter'=>$filter);
		if (isset($parameters['pagesize'])) {
			$returnarray['limit'] = $parameters['limit'];
		}
		if (isset($parameters['list'])) {
			$listConfig=explode(",", $parameters['list']);
			foreach ($listConfig as $k => $v) {
				if(strpos($v, "=")!==false){
					$listitem = explode("=", $v);
					$returnarray['select'][$listitem[1]] = $listitem[0];
					$returnarray['displaylist'][] = $listitem[1];
				} else {
					$returnarray['select'][] = $v;
					$returnarray['displaylist'][] = $v;
				}
			}
		}	
		if (isset($parameters['sort'])) {
			$returnarray['sort'] = $parameters['sort'];
		}
		return $returnarray;
	}

	public function getResultsFromView($orgId,$view) {
		$sql    = new Sql($this->dbAdapter);
		$select = $sql->select();
		$select->from($view);
	//	$select->where(['org_id' => $orgId]);   //commented it out for now since it is clearning out the order. 
	//We will need this in the future so cross org by mistake is not possible
		$statement = $sql->prepareStatementForSqlObject($select);
		$result = $statement->execute();
		$resultSet = new ResultSet();
        return $resultSet->initialize($result)->toArray();
		
	}

	public function getResultsFromPara($orgId,$entity_name,$para) {
		$sql    = new Sql($this->dbAdapter);
		$select = $sql->select();
//		print_r($para['select']);exit;
		if (!empty($para['select'])) {
			$select->columns($para['select']);
		}
		$select->from($entity_name);
	//	$select->where(['org_id' => $orgId]);
		if (!empty($para['range'])) {
			$select->where($para['range']);
		}
		if (!empty($para['filter'])) {
			$where = new Where();
			$this->createFilter($para['filter'],$where);
			$select->where($where);
		}
		if (!empty($para['group'])) {
			$select->group($para['group']);
		}
		if (!empty($para['limit'])) {
			$select->group($para['limit']);
		}
	//	echo $select->getSqlString();exit;
		$statement = $sql->prepareStatementForSqlObject($select);

		$result = $statement->execute();
		$resultSet = new ResultSet();
        return $resultSet->initialize($result)->toArray();

	}



    protected function createFilter($filter,$where) {
		$symMapping = ['>'=>'greaterThan','>='=>'greaterThanOrEqualTo','<'=>'lessThan',
		'<='=>'lessThanOrEqualTo','!='=>'notEqualTo','LIKE'=>'like','NOT LIKE'=>'notLike'];
        $boolMapping = ['OR'=>'or','AND'=>'and'];
        if (!isset($filter[1]) && is_array($filter)) {
            $filter = $filter[0];
        }
        $column = $filter[0];
        if (isset($filter[2])) {
            $value = $filter[2];
            $condition = $filter[1];
        } else {
            $condition = "==";
            $value = $filter[1];
        }
        if (strtoupper($condition)=='OR' OR strtoupper($condition)=='AND') {
				$whereNest = $where->nest();
				$this->createFilter($column,$whereNest);
				if (strtoupper($condition)=='OR') {
					$whereNest->or;
				 } else {
					$whereNest->and;
				 }
				 $this->createFilter($value,$whereNest);
				 $whereNest->unnest();				                  
        } else {
           // echo $column.' '.$condition.' '.$value;exit;
				$value = AnalyticsUtils::checkSessionValue($value);
				if (strtolower(substr($value,0,5))=="date:") {
					$value = date("Y-m-d",strtotime(substr($value,5)));
				} 
                if ($condition=="=="){                
                        if (!is_array($value)) {
							$where->equalTo($column,$value);
                        } else {
                            $where->in($column,$value);
                        }    

                }  else {
						$functionName = $symMapping[$condition];
						$where->$functionName($column,$value);
                }
                        
               
		 }
    }

}
?>