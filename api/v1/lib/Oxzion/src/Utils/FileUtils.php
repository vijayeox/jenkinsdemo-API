<?php
namespace Oxzion\Utils;

class FileUtils{

	public function checkDirectoryIfExists($directory){
		
	}
	static public function createDirectory($directory){
		// Check whether the directory already exists, and if not,
        // create the directory.
        if(!is_dir($directory)) {
            if(!mkdir($directory,0777,true)) {
                throw new \Exception('Could not create directory for uploads: ' . error_get_last());
            }
        }
	}
	static public function storeFile($file,$directory){
		self::createDirectory($directory);
		 // Check if file is OK!
		try {
			if(is_array($file)){
				if(isset($file['tmp_name'])){
					move_uploaded_file($file['tmp_name'], $directory.$file['name']);
				}
				if(isset($file['body'])){
					file_put_contents($directory.$file['filename'],$file['body']);
					return $file['filename'];
				}
			} else {
				move_uploaded_file($file, $directory.$file);
			}
		} catch(Exception $e){
            throw new \Exception('Could not upload File: ' . error_get_last());
		}
        return $file['name'];
	}

	static public function copy($src, $destFile, $destDirectory){
		self::createDirectory($destDirectory);
		copy($src, $destDirectory.$destFile);
	}
	
	static public function renameFile($source,$destination){
		self::createDirectory(str_replace(basename($destination),"",$destination));
		return rename($source, $destination);
	}
	static public function deleteDirectoryContents($dir) {
		if (is_file($dir)) {
			return unlink($dir);
		} elseif (is_dir($dir)) {
			$scan = glob(rtrim($dir,'/').'/*');
			foreach($scan as $index=>$path) {
				self::deleteDirectoryContents($path);
			}
			return @rmdir($dir);
		}
	}
	static public function getFiles($directory){
		// Scan the directory and create the list of uploaded files.
        $files = [];        
        $handle  = opendir($directory);
        while (false !== ($entry = readdir($handle))) {       
            if($entry=='.' || $entry=='..')
                continue; // Skip current dir and parent dir.
            $files[] = $entry;
        }
        // Return the list of uploaded files.
        return $files;
	}
	static public function getFileSize($fileName,$directory){
		return filesize($directory.$fileName);
	}
	static public function deleteFile($fileName,$directory){
		if (unlink($directory.$fileName)) {
			return 1;
		} else {
            throw new \Exception('Could not Delete File: ' . error_get_last());
		}
	}

	static public function fileExists($fileName){
		return file_exists($fileName);
	} 
}
?>