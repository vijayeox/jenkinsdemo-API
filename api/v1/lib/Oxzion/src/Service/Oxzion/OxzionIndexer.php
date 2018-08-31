<?php
	namespace Oxzion;
	include_once __DIR__.'/../Common/Config.php';
	use Search\SolrIndexer;
	Use Exception;
	
	class OxzionIndexer{
		private static $instance = null;
		const FORM = 'form';
		const COMMENT = 'comment';
		const MESSAGE = 'message';
		const OLE = 'ole';
		const USER = 'user';
		const ATTACHMENT = 'attachment';
		
		private $indexers;
		private $batchIndexer;
		
		private function __construct($core){
			$this->batchIndexer = new SolrIndexer($core, true, SOLR_BUFFER_SIZE);
			$this->indexers = array();
			$this->indexers[OxzionIndexer::FORM] = new FormIndexer($core);
			$this->indexers[OxzionIndexer::COMMENT] = new FormCommentIndexer($core);
			$this->indexers[OxzionIndexer::MESSAGE] = new MessageIndexer($core);
			$this->indexers[OxzionIndexer::OLE] = new OleIndexer($core);
			$this->indexers[OxzionIndexer::USER] = new UserIndexer($core);
			$this->indexers[OxzionIndexer::ATTACHMENT] = new AttachmentIndexer($core);
			register_shutdown_function(function($oxzionIndexer){
				$oxzionIndexer->cleanup();
		
			}, $this);
		}

		public static function getInstance($core){
			if (!isset(static::$instance))
	        {
	            self::$instance = new OxzionIndexer($core);
	        }
	        return static::$instance;
		}
		public function cleanup(){
			$this->indexers = null;
			self::$instance = null;
			$this->batchIndexer = null;
		}

		public function batchIndex($params = array()){
			$ret = array();
			$batchIndexer = &$this->batchIndexer;
			try{
				$result = null;
				$indexerList = $this->indexers;
				if(array_key_exists('index_entities', $params)){
					$indexerList = array();
					foreach($params['index_entities'] as $idx => $val){
						if(array_key_exists($val, $this->indexers)){
							$indexerList[$val] = $this->indexers[$val];
						}else{
							print("----------------------------------------------------------------\n");
							print("WARNING: Ignoring Invalid entity in request - $val \n");
							print("----------------------------------------------------------------\n");
						}
					}
					unset($params['index_entities']);
				}
				foreach($indexerList as $key => $indexer){
					print("---------------------------------------------\n");
					print("Starting Indexing for $key\n");
					print("---------------------------------------------\n");
					if($key != OxzionIndexer::ATTACHMENT){
						$result = $indexer->index($params, function($doc) use(&$batchIndexer){
							$batchIndexer->index($doc);
						});
						$batchIndexer->flush();
					}else{
						$result = $indexer->index($params, function($doc, $fileLocation) use(&$batchIndexer){
							$batchIndexer->extract($doc, $fileLocation);
						});
					}
					print("Total number of documents found : ".$result['total']."\n");
					print("Number of documents Failed indexing : ".$result['fails']."\n\n");
					if($result){
						$ret[$key] = $result;
					}
					
				}

			$this->indexDeleted($params);// remove deleted entries
			}finally{
				// $this->performCommit();
			}
			return $ret;
		}

		public function index($params = array()){
			if(array_key_exists('entity_type', $params) && array_key_exists('id', $params)){
				try{
					$entity = $params['entity_type'];
					$id = $params['id'];
					// print("---------------------------------------------\n");
					// print("Starting Indexing for $entity with id : $id\n");
					// print("---------------------------------------------\n");
					$appIndexer = $this->indexers[$entity];
					$indexer = &$this->batchIndexer;
					if($entity != OxzionIndexer::ATTACHMENT){
						$result = $appIndexer->index(array('id'=>$id), function($doc) use(&$indexer){
								$indexer->index($doc);
							});
					}else{
						$result = $appIndexer->index(array('id'=>$id), function($doc, $fileLocation) use(&$indexer){
							$indexer->extract($doc, $fileLocation);
						});
					}
					$indexer->flush();
					// print("Completed indexing\n\n");
				}finally{
					$this->performCommit();
				}
			}
		}

		public function delete($params = array()){
			if(array_key_exists('entity_type', $params) && array_key_exists('id', $params)){
				try{
					$entity = $params['entity_type'];
					$id = $params['id'];
					// print("---------------------------------------------\n");
					// print("Deleting Index for $entity with id : $id\n");
					// print("---------------------------------------------\n");
					$appIndexer = $this->indexers[$entity];
					$id = $appIndexer->computeId($id);
					$this->batchIndexer->deleteById($id);
					// print("Completed operation\n\n");
				}finally{
					$this->performCommit();
				}
			}
		}

		private function performCommit(){
			try{
					sleep(15);
					$this->batchIndexer->commit();
				}catch(Exception $e){
					print("Exception occurred while committing : ".$e->getMessage());
					/*if($cnt <= 3){
						performCommit($cnt++);
					}*/
				}
		}

		private function indexDeleted($params){
			$appIndexer = $this->indexers[OxzionIndexer::FORM];
			$ids = $appIndexer->deletedForms($params);
			if(!empty($ids)){
				foreach ($ids as $id){
					$this->batchIndexer->deleteById($id);
				}
			}
		}
	}
?>