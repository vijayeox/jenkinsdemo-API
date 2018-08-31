<?php

require __DIR__ .'/autoload.php';
include_once __DIR__.'/Search/solrInit.php';
include_once __DIR__.'/AsyncIndexer.php';

use Oxzion\OxzionIndexer;
use Messaging\MessageProducer;

class VA_ExternalLogic_SolrIndex{

	public static function index($id, $entity){
		// $core = VA_Logic_Session::getSolrCore();
		// if($GLOBALS['config']['endpoint']['localhost']['core']){
			$oxzionIndexer = OxzionIndexer::getInstance('');
			$oxzionIndexer->index(array('id'=>$id,'entity_type'=>self::getEntities($entity)));
		// }
	}

	public static function delete($id, $entity){
		// $core = VA_Logic_Session::getSolrCore();
			$oxzionIndexer = OxzionIndexer::getInstance('');
			$oxzionIndexer->delete(array('id'=>$id,'entity_type'=>self::getEntities($entity)));
	}

	public static function getEntities($entity){
		switch($entity){
			case 'form':
				return OxzionIndexer::FORM;
				break;
			case 'message':
				return OxzionIndexer::MESSAGE;
				break;
			case 'ole':
				return OxzionIndexer::OLE;
				break;
			case 'comment':
				return OxzionIndexer::COMMENT;
				break;
			case 'user':
				return OxzionIndexer::USER;
				break;
			case 'attachment':
				return OxzionIndexer::ATTACHMENT;
				break;
			default:
				echo '<pre>';print_r('invalid entity');
		}
	}

}