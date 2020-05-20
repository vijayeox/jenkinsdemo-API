<?php
namespace Oxzion\Utils;

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
                throw new \Exception("Could not create directory $directory: " . error_get_last());
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
            throw new \Exception('Could not upload File: ' . error_get_last());
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
            if (is_dir($src.'/'.$file))
                self::copyDir($src.'/'.$file, $dest.'/'.$file);
            elseif (!file_exists($dest.'/'.$file))
                copy($src.'/'.$file, $dest.'/'.$file);
        }
    }

    public static function renameFile($source, $destination)
    {
        return rename($source, $destination);
    }

    public static function deleteDirectoryContents($dir)
    {
        if(is_dir($dir)){
            $files = scandir( $dir );
            foreach( $files as $file ) {
                if ($file !== '.' && $file !== '..')
                self::deleteDirectoryContents( $dir.'/'.$file );
            }
            rmdir( $dir );
        } else {
            unlink( $dir );
        }
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
    public static function rmDir($dirPath)
    {
        if(is_link($dirPath)){
            unlink($dirPath);
        }else if(is_dir($dirPath)){
            if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
                $dirPath .= '/';
            }
            $files = glob($dirPath . '*', GLOB_MARK);
            foreach ($files as $file) {
                if (is_dir($file)) {
                    self::rmDir($file);
                } else {
                    unlink($file);
                }
            }
            rmDir($dirPath);
        }
    }
    public static function deleteFile($fileName, $directory)
    {
        if (unlink($directory.$fileName)) {
            return 1;
        } else {
            throw new \Exception('Could not Delete File: ' . error_get_last());
        }
    }

    public static function fileExists($fileName)
    {
        return file_exists($fileName);
    }

    public static function symlink($target, $link)
    {
        return symlink($target, $link);
    }

    public static function unlink($link)
    {
        if(is_link($link))
        {
            unlink($link);
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
        if(!endsWith($baseLocation,'/')){
            $baseLocation .= "/";
        }
        $counter = 0;
        while(true){
            $file = ($counter == 0) ? $file : $file.$counter;
            if(!file_exists($baseLocation.$file)){
                return $file;
            }
            $counter++;
        }
    }
}
