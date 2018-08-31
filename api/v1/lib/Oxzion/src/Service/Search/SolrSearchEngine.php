<?php
	namespace Search;
	
	use Solarium\Client;
	
	require(__DIR__.'/solrInit.php');

	class SolrSearchEngine implements SearchEngine{
		public $client;
		public $query;
		function __construct($core = '')
		{
			if(isset($core) && $core != ''){
				$GLOBALS['config']['endpoint']['localhost']['core'] = VA_Logic_Session::getSolrCore();
			}
			$this->client = new Client($GLOBALS['config']);
			$this->query = $this->client->createSelect();
		}

		/**
		*	$options can have the following structure
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
		public function search($q, array $options = array()){
			$this->query->setQuery($q);
			$this->query->clearSorts();
			$this->query->clearFields();
			$fields = NULL;
			if(isset($options['fields'])){
				$this->query->addFields($options['fields']);
			}
			if(isset($options['sort'])){
				$this->query->addSorts($options['sort']);
			}
			if(isset($options['debug'])){
				$debug = $this->query->getDebug();
				$debug->setExplainOther('*');
			}
			if(!isset($options['start'])){
				$options['start'] = 0;
			}
			if(!isset($options['rows'])){
				$options['rows'] = 10;
			}
			if(isset($options['component'])){

				foreach ($options['component'] as $type => $config) {
					$component = $this->query->getComponent($type, true, $config);
		        	if($type == 'highlighting'){
						$component->setSimplePrefix("|&|");
						$component->setSimplePostfix('|&*|');
					}
					if($type == 'grouping'){
						$component->setLimit($options['rows']);
						// get a group count
						$component->setNumberOfGroups(true);
					}
		        }
			}

			if(isset($options['filterquery'])){
				$this->query->addFilterQueries($options['filterquery']);
			}

			// following boosts the fields
			if(isset($options['boostfield'])){
				$boostfields ='exact^5 entity_id^1.5 '.$options['boostfield'];
			}
			$dismax = $this->query->getDisMax();
			$dismax->setQueryFields($boostfields);

			$this->query->setStart($options['start'])->setRows($options['rows']);
			$resultset = $this->client->select($this->query);
			return $resultset;
		}


	}

?>