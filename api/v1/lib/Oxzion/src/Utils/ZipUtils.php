<?php

namespace Oxzion\Utils;

use Exception;
use ZipArchive;

class ZipUtils
{
    /**
     * Extract the zip file from the upload form
     * @method extract
     * @param array $target Target File, $destination Destination Folder
     * <code>
     * </code>
     * @return array Returns the file object.</br>
     * <code> status : "success|error",
     *        data : File Object
     * </code>
     */
    public function extract($target, $destination)
    {
        try {
            $zipFile = new \PhpZip\ZipFile();
            $zipFile->openFile($target); // open archive from file
            $extract = $zipFile->extractTo($destination); // extract files to the specified directory
        } catch (Exception $e) {
            return 0;
        }
        return $extract;
    }

    /*
     * Begin - based on https://www.php.net/manual/en/class.ziparchive.php
     */
    /**
     * Add files and sub-directories in a directory to zip file.
     * @param string $directory
     * @param ZipArchive instance $zipFile
     * @param int $stripPathLength Number of text to be exclusived from the file path.
     */
    private static function directoryToZip($directory, &$za, $stripPathLength) {
        $handle = opendir($directory);
        if (FALSE === $handle) {
            $za->close(); //Ignore any errors while closing.
            throw new ZipException('Failed to open directory to be added to zip archive.', 
                ['directory' => $directory]);
        }
        while (false !== ($file = readdir($handle))) {
            if (('.' === $file) || ('..' === $file)) {
                continue;
            }
            $filePath = $directory . DIRECTORY_SEPARATOR . $file;
            $exclusions=array('dist','node_modules');
            if(!in_array($file,$exclusions)){
                // Remove prefix from file path before adding to zip.
                $localPath = substr($filePath, $stripPathLength);
                if (is_file($filePath)) {
                    if (!$za->addFile($filePath, $localPath)) {
                        $za->close(); //Ignore any errors while closing.
                        throw new ZipException('Failed to add file to zip archive.', ['file' => $localPath]);
                    }
                    continue;
                }
                if (is_dir($filePath)) {
                    // Add sub-directory.
                    if (!$za->addEmptyDir($localPath)) {
                        $za->close(); //Ignore any errors while closing.
                        throw new ZipException('Failed to add sub-directory to zip archive.', ['directory' => $localPath]);
                    }
                    self::directoryToZip($filePath, $za, $stripPathLength);
                    continue;
                }

            }
        }
        closedir($handle);
    }

	/**
     * Zip a directory (include itself).
     * Usage:
     *   ZipUtil::zipDir('/path/to/sourceDir', '/path/to/out.zip');
     *
     * @param string $sourcePath Path of directory to be zipped.
     * @param string $outZipPath Path of output zip file.
     */
    public static function zipDir($sourcePath, $outZipPath, $retainTopLevelDirectoryName = false) {
        $za = new ZipArchive();
        if (TRUE !== $za->open($outZipPath, ZipArchive::CREATE)) {
            throw new ZipException('Failed to open zip archive.', ['file' => $outZipPath]);
        }
        $stripPathLength = 0;
        if ($retainTopLevelDirectoryName) {
            $pathInfo = pathinfo($sourcePath);
            $parentPath = $pathInfo['dirname'];
            $dirName = $pathInfo['basename'];
            if (!$za->addEmptyDir($dirName)) {
                throw new ZipException('Failed to add top level directory to zip archive.', ['directory' => $dirName]);
            }
            $stripPathLength = strlen($parentPath . DIRECTORY_SEPARATOR);
        }
        else {
            $stripPathLength = strlen($sourcePath . DIRECTORY_SEPARATOR);
        }
        self::directoryToZip($sourcePath, $za, $stripPathLength);
        if (!$za->close()) {
            throw new ZipException('Failed to close zip archive.');
        }
    }
    /*
     * End - based on https://www.php.net/manual/en/class.ziparchive.php
     */

    public static function unzip($zipFile, $directory, $entries = null) {
        $za = new ZipArchive();
        //if (TRUE !== $za->open($zipFile, ZipArchive::RDONLY)) {
        if (TRUE !== $za->open($zipFile)) {
            throw new ZipException('Failed to open zip archive.', ['file' => $zipFile]);
        }
        if (isset($entries)) {
            if (!$za->extractTo($directory, $entries)) {
                $za->close(); //Ignore any errors while closing.
                throw new ZipException('Failed to extract zip archive.', 
                    ['file' => $zipFile, 'entries' => $entries]);
            }
        }
        else {
            if (!$za->extractTo($directory)) {
                $za->close(); //Ignore any errors while closing.
                throw new ZipException('Failed to extract zip archive.', ['file' => $zipFile]);
            }
        }
        if (!$za->close()) {
            throw new ZipException('Failed to close zip archive.');
        }
    }
}
