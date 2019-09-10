<?php
namespace Oxzion\Service;

use Zend\Cache\StorageFactory;

class CacheService
{
    protected $cache;
    private $supportedDatatypes;
    private $ttl=3600;
    private $adapter = 'filesystem';
    private static $instance;

    private function __construct()
    {
        $this->cache = StorageFactory::factory(
            array(
                'adapter'=>array(
                    'name'=>'filesystem',
                    'options'=>array(
                        'ttl'=>$this->ttl
                    )
                )
            )
        );
        $this->supportedDatatypes = $this->cache->getCapabilities()->getSupportedDatatypes();
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            self::$instance = new CacheService();
        }

        return self::$instance;
    }

    public function set($key, $object)
    {
        $key = $this->massageKey($key);
        if ($this->supportedDatatypes['object']) {
            $this->cache->setItem($key, $object);
        } else {
            $this->cache->setItem($key, serialize($object));
        }
    }
    public function get($key)
    {   
        $key = $this->massageKey($key);
        if ($this->supportedDatatypes['object']) {
            return $this->cache->getItem($key);
        } else {
            return unserialize($this->cache->getItem($key));
        }
    }
    public function clear($key)
    {
        $key = $this->massageKey($key);
        $this->cache->removeItem($key);
    }
    protected function massageKey($key){
        $string = [".","@"];
        $replaced_string = ["__","__a__"];
        $key = str_ireplace($string,$replaced_string,$key);
        return $key;
    }
}
