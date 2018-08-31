<?php
namespace Cache;
require __DIR__ .'/../autoload.php';
require_once __DIR__.'/../Common/Config.php';

use Exception;
	
class FileCache{
	function store($key, $content){
		$result = false;
		$file;
		try{
			$filename = $this->getFileName($key);
			
			@mkdir(CACHE_FOLDER, 0777, true);
		
			$file = fopen($filename,'a+');
			if (!$file) {
				throw new Exception('Could not write to cache');
			}
			
			if(flock($file, LOCK_EX)){
				ftruncate($file, 0);
				if(!is_resource($content)){
					$result = fwrite($file,serialize($content));
				}else{
					$result = true;
					while(!feof($content)){
						$chunk = fread($content, 1024);
						$size = fwrite($file, $chunk);
						if(!$size){
							$result = false;
						}
					}
				}
		    flock($file, LOCK_UN);
			}
		}catch (Exception $e){
			error_log("Exception occurred while saving in cache: ".$e);
			return false;
		}finally{
			if(isset($file)){
				fclose($file);
  			}
		}

		return $result;
	}

	private function getFileName($key) {
		return CACHE_FOLDER. md5($key);
  	}

  	function get($key, $stream = false){
  		$filename = $this->getFileName($key);
  		if (!file_exists($filename) || !is_readable($filename)){
  			return false;
  		}
  		$file;
  		$data = false;
  		try{
  			chmod($filename, 0777);
	  		$file = fopen($filename,'r');
	  		if (!$file){
	  			return false;
	  		}
	  		if(flock($file,LOCK_SH)){
	  			$data = file_get_contents($filename);	
	  			touch($filename);
	  			if(!$stream){
		  			$data = @unserialize($data);
			  		if (!$data) {
				        // If unserializing somehow didn't work out, we'll delete the file
				        unlink($filename);
				        return false;
				    }
				}else{
					//return the stream to the file
					$data = $file;
				}
			}
  		}catch(Exception $e){
  			error_log("Exception occurred while saving in cache: ".$e.getMessage());
			return false;	
  		}finally{
  			if(isset($file) && !$stream){
  				fclose($file);
  			}
  		}

  		return $data;
  	}

  	static function deleteByTime($hours = CACHE_TTL_HOURS){
  		$files = glob(CACHE_FOLDER."*");
  		print"Removing Cache contents older than $hours hrs\n";
  		foreach($files as $file)
        {
        	$fileatime=fileatime ($file);
            if ((time()-$fileatime) >= ($hours*60))
            {
                unlink($file);
            }
        }
  	}
  	function delete($key) {
        $filename = $this->getFileName($key);
        if (file_exists($filename)) {
            return unlink($filename);
        } else {
            return false;
        }
    }
}
?>