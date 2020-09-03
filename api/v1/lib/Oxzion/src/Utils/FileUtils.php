<?php
namespace Oxzion\Utils;
use Oxzion\Utils\StringUtils;

use Exception;

class FileUtils
{
    public static function getFileExtension($file){
        return pathinfo($file, PATHINFO_EXTENSION);
    }

    public static function createDirectory($directory)
    {
        // Check whether the directory already exists, and if not,
        // create the directory.
        if (!is_dir($directory)) {
            if (!mkdir($directory, 0777, true)) {
                throw new Exception("Could not create directory $directory: " . print_r(error_get_last(), true));
            }
        }
    }
    public static function truepath($path)
    {
        // whether $path is unix or not
        $unipath=strlen($path)==0 || $path{0}!='/';
        // attempts to detect if path is relative in which case, add cwd
        if (strpos($path, ':')===false && $unipath) {
            $path=getcwd().DIRECTORY_SEPARATOR.$path;
        }
        // resolve path parts (single dot, double dot and double delimiters)
        $path = str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $path);
        $parts = array_filter(explode(DIRECTORY_SEPARATOR, $path), 'strlen');
        $absolutes = array();
        foreach ($parts as $part) {
            if ('.'  == $part) {
                continue;
            }
            if ('..' == $part) {
                array_pop($absolutes);
            } else {
                $absolutes[] = $part;
            }
        }
        $path=implode(DIRECTORY_SEPARATOR, $absolutes);
        // resolve any symlinks
        if (file_exists($path) && linkinfo($path)>0) {
            $path=readlink($path);
        }
        // put initial separator that could have been lost
        $path=!$unipath ? '/'.$path : $path;
        return $path;
    }
    public static function storeFile($file, $directory)
    {
        self::createDirectory($directory);
        // Check if file is OK!
        try {
            if (is_array($file)) {
                if (isset($file['tmp_name'])) {
                    move_uploaded_file($file['tmp_name'], $directory.$file['name']);
                    if (!file_exists($directory.$file['name'])) {
                        file_put_contents($directory.$file['name'], file_get_contents($file['tmp_name']));
                    }
                    chmod($directory.$file['name'], 0777);
                }
                if (isset($file['body'])) {
                    file_put_contents($directory.$file['filename'], $file['body']);
                    return $file['filename'];
                }
            } else {
                move_uploaded_file($file, $directory.$file);
                chmod($directory.$file, 0777);
            }
        } catch (Exception $e) {
            throw new Exception('Could not save file. Error:' . print_r(error_get_last(), true));
        }
        return $file['name'];
    }

    public static function copy($src, $destFile, $destDirectory)
    {
        self::createDirectory($destDirectory);
        copy($src, $destDirectory.$destFile);
    }

    public static function copyDir($src, $dest) {
        if (!file_exists($dest)) self::createDirectory($dest);
        foreach (scandir($src) as $file) {
            if ($file == '.' || $file == '..') continue;
            $srcCheck = self::joinPath($src);
            $destCheck = self::joinPath($dest);
            if (is_dir($srcCheck.$file))
                self::copyDir($srcCheck.$file, $destCheck.$file);
            elseif (!file_exists($destCheck.$file))
                copy($srcCheck.$file, $destCheck.$file);
        }
    }

    public static function renameFile($source, $destination)
    {
        return rename($source, $destination);
    }

    public static function rmDir($fsObj)
    {
        if(is_link($fsObj)){
            if (!unlink($fsObj)) {
                throw new Exception("Failed to delete symlink ${fsObj}:" . print_r(error_get_last(), true));
            }
            return;
        }
        if (is_file($fsObj)) {
            if (!unlink($fsObj)) {
                throw new Exception("Failed to delete file ${fsObj}:" . print_r(error_get_last(), true));
            }
            return;
        }
        if(is_dir($fsObj)){
            if (DIRECTORY_SEPARATOR != $fsObj[strlen($fsObj)-1]) {
                $fsObj = $fsObj . DIRECTORY_SEPARATOR;
            }
            $dirList = scandir( $fsObj );
            foreach( $dirList as $item ) {
                if (('.' == $item) || ('..' == $item)) {
                    continue;
                }
                self::rmDir( $fsObj . $item );
            }
            if (!rmdir( $fsObj )) {
                throw new Exception("Failed to delete directory ${fsObj}:" . print_r(error_get_last(), true));
            }
            return;
        }
        throw new Exception("Unexpected file system object type : ${fsObj}.");
    }

    public static function getFiles($directory)
    {
        // Scan the directory and create the list of uploaded files.
        $files = [];
        $handle  = opendir($directory);
        while (false !== ($entry = readdir($handle))) {
            if ($entry=='.' || $entry=='..') {
                continue;
            } // Skip current dir and parent dir.
            $files[] = $entry;
        }
        // Return the list of uploaded files.
        return $files;
    }
    public static function getFileSize($fileName, $directory)
    {
        return filesize($directory.$fileName);
    }

    public static function deleteFile($fileName, $directory)
    {
        if (!unlink($directory.$fileName)) {
            throw new Exception("Could not Delete File: ${fileName} under directory ${directory}." . 
                print_r(error_get_last(), true));
        }
    }

    public static function fileExists($fileName)
    {
        return file_exists($fileName);
    }

    public static function symlink($target, $link)
    {
        if (!symlink($target, $link)) {
            throw new Exception("Failed to create symlink ${link} -> ${target}." . 
                'Error:' . print_r(error_get_last(), true));
        }
    }

    public static function unlink($link)
    {
        if(is_link($link))
        {
            if (!unlink($link)) {
                throw new Exception("Failed to unlink ${link}." . 
                'Error:' . print_r(error_get_last(), true));
            }
        }
    }

    public static function convetImageTypetoPNG($file)
    {
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        switch ($extension) {
            case 'jpg':
            case 'jpeg':
            case 'JPG':
            case 'JPEG':
            $image = imagecreatefromjpeg($file['tmp_name']);
            break;
            case 'gif':
            case 'GIF':
            $image = imageCreateFromGIF($file['tmp_name']);
            break;
            case 'png':
            case 'PNG':
            $image = imageCreateFromPNG($file['tmp_name']);
            break;
        }
        return $image;
    }

    public static function getUniqueFile($baseLocation,$file){
        $baseLocation = self::joinPath($baseLocation);
        $counter = 0;
        while(true){
            $file = ($counter == 0) ? $file : $file.$counter;
            if(!file_exists($baseLocation.$file)){
                return $file;
            }
            $counter++;
        }
    }

    public static function joinPath($baseLocation){        
        if(!(StringUtils::endsWith($baseLocation,'/'))){
            $baseLocation .= "/";
        }
        return $baseLocation;
    }

    public static function createTempDir($dirNameLength = 10) {
        $tempDir = sys_get_temp_dir();
        for ($i=0; $i<100; $i++) {
            $dirName = StringUtils::randomString($dirNameLength);
            $targetDir = $tempDir . DIRECTORY_SEPARATOR . $dirName;
            if (!file_exists($targetDir)) {
                if (!mkdir($targetDir)) {
                    throw new Exception('Failed to create temp directory.');
                }
                return $targetDir;
            }
        }
		throw new Exception('Failed to create unique temporary directory in 100 attempts!.');
    }

    public static function createTempFileName($fileNameLength = 10) {
        $tempDir = sys_get_temp_dir();
        for ($i=0; $i<100; $i++) {
            $fileName = StringUtils::randomString($fileNameLength);
            $targetFile = $tempDir . DIRECTORY_SEPARATOR . $fileName;
            if (!file_exists($targetFile)) {
                return $targetFile;
            }
        }
        throw new Exception('Failed to create unique temporary file name in 100 attempts!.');
    }
}
