<?php
require __DIR__ .'/autoload.php';
use ElasticSearch;
class VA_ExternalLogic_ElasticIndex{

	public static function index($id, $entity){
		$indexer = self::getEntities($entity);
		if($indexer){
			$indexer->index(array('id'=>$id));
		}
	}
	public static function indexByParams($params, $entity){
		$indexer = self::getEntities($entity);
		if($indexer){
			$indexer->index($params);
		}
	}
	public static function delete($id, $entity){
		$indexer = self::getEntities($entity);
		if($indexer){
			$indexer->delete(array('id'=>$id));
		}
	}
	
	public static function getEntities($entity){
		switch($entity){
			case 'form':
			return (new ElasticSearch\FormIndexer());
			break;
			case 'message':
			return (new ElasticSearch\MessageIndexer());
			break;
			case 'ole':
			return (new ElasticSearch\OleIndexer());
			break;
			case 'comment':
			return (new ElasticSearch\FormCommentIndexer());
			break;
			case 'user':
			return (new ElasticSearch\UserIndexer());
			break;
			case 'timesheet':
			return (new ElasticSearch\TimesheetIndexer());
			break;
			case 'wizard':
			return (new ElasticSearch\WizardIndexer());
			break;
			case 'attachment':
			return (new ElasticSearch\AttachmentIndexer());
			break;
			default:
			return;
		}
	}

}