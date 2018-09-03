<?php
	namespace Search;
	
	interface DocumentIndexer{
		public function index(array $document);
		public function extract(array $fields, $fileLocation);
		public function flush();
		public function commit();
		public function deleteById($id);
	}
?>