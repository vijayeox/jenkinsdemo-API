<?php
	include_once __DIR__.'/AsyncIndexer.php';

	date_default_timezone_set('UTC');
	
	$options = getIndexDateParam();
	

	batchIndex($options);
	
	function getIndexDateParam(){
		$options = array();
		if(count($GLOBALS['argv']) > 1) {
			if($GLOBALS['argv'][1] == '-h'){
				print "Usage : php batchIndexer.php <Options>\n";
				print " If no options are specified then complete indexing will be done.\n";
				print " Options when specified can be the following -\n";
				print " 	1. -h will show this message\n";
				print " 	2. INDEX_FROM=<Date (yyyy-mm-dd) From which indexing needs to be performed in >\n";
				print " 	3. INDEX_ENTITIES=<comma separated list of form,comment,message,ole,user,attachment>\n";
				print " E.g., to index instance forms and comments only since 01/01/2017\n";
				print " 	php batchIndexer.php \"INDEX_FROM=2017-01-01&INDEX_ENTITIES=form,comment\"\n";
				exit;
			}else{
				$params = explode('&', $GLOBALS['argv'][1]);
				foreach($params as $idx => $val){
					$param = explode('=', $val);
					if(count($param) == 2 && $param[0] == 'INDEX_FROM' && validateDate($param[1])){
						$options['date'] = $param[1];
						print ("Indexing data saved on or after : $param[1]\n");
					}
					if(count($param) == 2 && $param[0] == 'INDEX_ENTITIES'){
						$options['index_entities'] = explode(',', $param[1]);
						print ("Indexing only these entities : $param[1]\n");
					}
				}

			}
		}else{
			print ("Indexing all the data \n");
		}
		if(!isset($options['date'])){$options['date'] = date('Y-m-d H:i:s',strtotime('-1 hours'));}
		return $options;
	}

	function validateDate($date)
	{
	    $d = DateTime::createFromFormat('Y-m-d', $date);
	    return $d && $d->format('Y-m-d') === $date;
	}
?>