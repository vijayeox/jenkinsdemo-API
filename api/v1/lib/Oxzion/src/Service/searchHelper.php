<?php
	include_once __DIR__.'/autoload.php';
	use Search\SolrSearchEngine;

	date_default_timezone_set('UTC');
	
	$options = parseArguments();
	//var_dump($options);
	
	$query = $options['q'];
	unset($options['q']);

	$result = search($query, $options);
	print "$result\n";
	/**
	 arguments of format q=text&start=0&row=10&fields=id1,name,price&sort=price->asc&filterquery=maxprice->price:[1 TO 300]&highlighting=*&grouping=fields->type,queries->price:[0 TO 99.99];price:[100 TO *]
	*/
	function parseArguments(){
		$options = array('component' => array());
		if(count($GLOBALS['argv']) > 1) {
			
			$params = explode('&', $GLOBALS['argv'][1]);
			foreach ($params as $index => $val) {
				$iParam = explode('=', $val);
				$key = $iParam[0];
				$value = $iParam[1];
				
				switch ($key) {
					case 'fields':
						$options[$key]=array_map('trim', explode(',', $value));
						break;
					case 'sort':
					case 'filterquery':
						$temp = extractMap($value);
						$fQuery = array();
						foreach ($temp as $k => $v) {
							$fQuery[$k] = array('query' => $v);
						}
						$options[$key] = $fQuery;
						break;	
					case 'highlighting':
						$options['component'][$key] = array('field' => array_map('trim', explode(',', $value)));
						break;	
					case 'grouping':
						$temp = extractMap($value);
						$options['component'][$key] = array();
						if(isset($temp['fields'])){
							$options['component'][$key]['fields'] = explode(';', $temp['fields']);
						}
						if(isset($temp['queries'])){
							$options['component'][$key]['queries'] = explode(';', $temp['queries']);
						}
						break;	
					default:
						$options[$key] = $value;
						break;
				}
				
				
			}
		}else{
			print "Usage\n";
			print "php searchHelper.php key=val&key2=val2..\n";
			print "key values can be \n";
			print "q : the search string\n";
			print "start : the start row number to fetch in the results\n";
			print "row : the number of rows to fetch\n";
			print "fields : fields to fetch in the result, comma separated solr schema field names\n";
			print "sort : fields to sort either in asc or desc as in price->asc,name->desc\n";
			print "filterquery : how to filter the results with a unique key name and a corresponding filter criteria e.g., maxPrice->price:[1 to 300],entity->entity_type:MESSAGES \n";
			print "highlighting : comma separated field names to include in the highlighting section. * for matching on all fields\n";
			print "grouping : map of fields and queries, fields is list of semicolon separated fields to group on and queries can be semicolon separated query criteria\n";
			print "E.g., \n";
			print "php searchHelper.php \"q=text&start=0&row=10&fields=id1,name,price&sort=price->asc&filterquery=maxprice->price:[1 TO 300]&highlighting=*&grouping=fields->type,queries->price:[0 TO 99.99];price:[100 TO *]\"\n";
			exit;
		}

		return $options;
	}

	function extractMap($data, $separator = ','){
		$map = array();
		if(isset($data)){
			$val = explode($separator, $data);
			array_walk($val, function($a, $key) use(&$map){
								$ele = explode('->', $a);
								if(array_key_exists(1, $ele)){
									$map[$ele[0]] = $ele[1];
								}
							});
		}
		return $map;
	}
	/*
	*	search options are in the following format
	*	array(
	*	    'start'         => 2,
	*	    'rows'          => 20,
	*	    'fields'        => array('id','name','price'),
	*	    'sort'          => array('price' => 'asc'),
	*		'debug'			=> true,
	*	    'filterquery' => array(
	*	        'maxprice' => array(
	*	            'query' => 'price:[1 TO 300]'
	*	        ),
	*	    ),
	*	    'component' => array(
	*	        'facetset' => array(
	*	            'facet' => array(
	*	                // notice this config uses an inline key value, instead of array key like the filterquery
	*	                array('type' => 'field', 'key' => 'stock', 'field' => 'inStock'),
	*	            )
	*	        ),
	*			'grouping' => array(
	*				'queries' => array('price:[0 TO 99.99]', 'price:[100 TO *]'),
	*				'fields' => array('type')
	*			),
	*			'highlighting' => array(
	*				'field' => array('name', 'description')
	*			)
	*	    ),
	*	); 
	*/
	function search($q, $options = array()){
		$searchEngine = new SolrSearchEngine();
		$result = $searchEngine->search($q, $options);
		return $result->getResponse()->getBody();
	;
	}
?>