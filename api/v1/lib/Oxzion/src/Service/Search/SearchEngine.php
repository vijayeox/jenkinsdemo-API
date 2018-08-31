<?php
	namespace Search;
	
	interface SearchEngine{
		public function search($q, array $options);
	}
?>