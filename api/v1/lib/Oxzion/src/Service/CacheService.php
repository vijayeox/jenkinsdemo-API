<?php
namespace Oxzion\Service;
use Zend\Cache\StorageFactory;
class CacheService{
	protected $cache;
	private $supportedDatatypes;
	private $ttl=3600;
	private $adapter = 'filesystem';
	public function __construct(){
		$this->cache = StorageFactory::factory(
			array(
				'adapter'=>array(
					'name'=>'filesystem',
					'options'=>array(
						'ttl'=>$this->ttl
					)
				)
			));
		$this->supportedDatatypes = $this->cache->getCapabilities()->getSupportedDatatypes();
	}
	public function set($key,$object){
		if($this->supportedDatatypes['object']){
			$this->cache->setItem($key, $object);
		} else {
			$this->cache->setItem($key, serialize($object));
		}
	}
	public function get($key){
		if($this->supportedDatatypes['object']){
			return $this->cache->getItem($key);
		} else {
			return unserialize($this->cache->getItem($key));
		}
	}
	public function clear($key){
		$this->cache->removeItem($key);
	}
}