<?php
	namespace Search;

	require_once __DIR__.'/solrInit.php';
	
	use Solarium\Plugin\BufferedAdd\Event\Events;
	use Solarium\Plugin\BufferedAdd\Event\PreFlush as PreFlushEvent;
	use Solarium\Plugin\BufferedAdd\Event\PostFlush as PostFlushEvent;
	use Solarium\Client;
	use Solarium\QueryType\Update\Query\Document\Document as UpdateDocument;
	use Exception;

	class SolrIndexer implements DocumentIndexer {
		private $isBuffered;
		private $client;
		private $update;
		private $bufferSize;
		
		public function __construct($core, $buffered = True, $bufferSize = 10)
		{
			$this->isBuffered = $buffered;
			$this->bufferSize = $bufferSize;

			$this->client = new Client($GLOBALS['config']);
			if($this->isBuffered){
				$this->update = $this->client->getPlugin('bufferedadd');
				$this->update->setBufferSize($this->bufferSize);
				$this->client->getEventDispatcher()->addListener(
				    Events::PRE_FLUSH,
				    function (PreFlushEvent $event) {
				        //print('Flushing buffer (' . count($event->getBuffer()) . "docs)\n");
				    }
				);
				$this->client->getEventDispatcher()->addListener(
				    Events::POST_FLUSH,
				    function (PostFlushEvent $event) {
				    	$result = $event->getResult();
				        //print('Flushed buffer Status - ' . $result->getStatus() . ', time taken ' .$result->getQueryTime()."\n");
				    }
				);
			}	
			
		}

		public function index(array $document){
			$this->sanitizeDocument($document);
			if($this->isBuffered){
					$this->update->createDocument($document);
			}else{
				$doc = new UpdateDocument($document);
				$update = $this->client->createUpdate();
				$update->addDocument($doc);
				$update->addCommit();
				$result = $this->client->update($update);
				print('Update Query Status - ' . $result->getStatus() . ', time taken ' .$result->getQueryTime() . "\n");
				return $result;
			}
		}

		private function sanitizeDocument(&$doc) { 
			foreach($doc as $key => $value){
				if(is_string($value)){
					$doc[$key] = preg_replace('@[\x00-\x08\x0B\x0C\x0E-\x1F]@', ' ', $value); 
				}
			}
		  	
		} 
		public function extract(array $fields, $fileLocation){
				$id = ($fields['instanceform_id'])?$fields['instanceform_id']:$fields['message_id'];
				$type = ($fields['instanceform_id'])?'instanceform':'message';
			try{
				$query = $this->client->createExtract();
				$query->addFieldMapping('content', 'text');
				$query->setUprefix('attr_');
				$query->setFile($fileLocation);
				//$query->setCommit(true);
				$query->setOmitHeader(false);

				// add document
				$doc = new UpdateDocument($fields);
				$query->setDocument($doc);

				// this executes the query and returns the result
				$result = $this->client->extract($query);
			}catch(Exception $e){
				$this->update->createDocument($fields);
				// adds the index without the document, if there is any problem with the document
				return;
			}
			return $result;
		}

		public function flush(){
			if($this->isBuffered){
				$this->update->flush();
			}
		}

		public function commit(){
			if($this->isBuffered){
				$this->flush();
				$this->update->commit();
			}
		} 

		public function deleteById($id){
			$update = $this->client->createUpdate();
			$update->addDeleteById($id);
				
			if(!$this->isBuffered){
				$update->addCommit();
			}
			
			return $this->client->update($update);
			
		}
	}
?>