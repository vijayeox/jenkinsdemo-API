<?php
	spl_autoload_register(function($className){
			$className = ltrim($className, '\\');

		$className = str_replace('\\','\/',$className);
		$className = str_replace ( '_', '\/', $className );
		    $fileName  = __DIR__;
		    $namespace = '';
		    if ($lastNsPos = strrpos($className, '\/')) {
		        $namespace = substr($className, 0, $lastNsPos);
		        $className = substr($className, $lastNsPos + 2);
		        $fileName  .= '/'.$namespace.'/';
		    }
		    $fileName .= $className . '.php';

		    //echo "Loading file - ".$fileName;
		    if (file_exists($fileName)) {
		    	require_once $fileName;
		    } 
		});
	
	require __DIR__.'/../../vendor/autoload.php';
	
        
?>