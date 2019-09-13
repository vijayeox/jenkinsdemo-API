<?php

namespace Oxzion\Utils;

class ZipUtils {
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
}