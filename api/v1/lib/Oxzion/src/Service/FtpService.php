<?php
namespace Oxzion\Service;
use Oxzion\ServiceException;
use Exception;
use Logger;
class FtpService extends AbstractService
{
    //check this https://github.com/Nicolab/php-ftp-client for detailed api

    //
    private static $instance;
    private $ftp;
    /**
    * Constructor.
    * @param string host
    * @param boolean user   
    * @param int password
    * @param boolean ssl   
    * @param int port
    */
    private function __construct($host, $user, $password, $ssl = true, $port = 21)
    {
        $this->ftp = new \FtpClient\FtpClient();
        $this->ftp->connect($host, $ssl, $port);
        $this->ftp->login($user, $password);
        $this->logger = Logger::getLogger(get_class($this));
    }

    /**
    * Returns the FtpService, If it does not exist, it will be created.
    * @param string host
    * @param boolean user   
    * @param int password
    * @param boolean ssl   
    * @param int port
    * @return FtpService instance
    */
    public static function getInstance($host, $user, $password, $ssl = true, $port = 21)
    {
        try { 
            // if (self::$instance === null) {
                self::$instance = new FtpService($host, $user, $password, $ssl, $port);
            // }
        } catch (Exception $e) {
            throw new ServiceException($e->getMessage(), "could.not.coonect.to.ftp");
        }    
        return self::$instance;
    }

    /**
     * Upload folder to the Ftp with given source_directory to target directory
     * @util
     * @param string source_directory
     * @param string target_directory
     * @return mixed folderUpload
     */
    public function uploadFolder($source_directory, $target_directory) {
        $folderUpload;
        $this->logger->info("Entered ");
        try { 
             $folderUpload = $this->ftp->putAll($source_directory, $target_directory);
        } catch (Exception $e) {
                $this->logger->error($e->getMessage(), $e);
                throw new ServiceException($e->getMessage(), "could.not.upload.to.ftp");
        }
        $this->logger->info("Exit ");      
        return $folderUpload;     
    }
    
    /**
     * Upload file to the Ftp with given source_file_path to target target_file_path
     * @util
     * @param string source_file_path
     * @param string target_file_path
     * @return boolean fileUpload
     */
    public function uploadFile($source_file_path, $target_file_path) {
        $this->logger->info("Entered ");
        $fileUpload;
        try { 
            $fileUpload = $this->ftp->put($target_file_path, $source_file_path, FTP_BINARY);
            if(!$fileUpload) {
                throw new ServiceException("could not upload the file to ftp server", "could.not.upload.to.ftp");
            }
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            throw new ServiceException($e->getMessage(), "could.not.upload.to.ftp");
        } 
        $this->logger->info("Exit "); 
        return $fileUpload;  
    }

    /**
     * Download all the file from the Ftp with given source_directory, pattern to target directory
     * @util
     * @param string source_directory
     * @param string target_directory
     * @param string mode
     * @return array fileList
     */
    public function downLoad($source_directory, $pattern, $target_directory, $mode = FTP_BINARY) {
        $this->logger->info("Entered ");
        $fileList = array();
        try { 
            $contents = $this->ftp->nlist($source_directory);
            foreach ($contents as $file) { 
                if ($file == '.' || $file == '..') {
                    continue;
                } 
                else if(fnmatch ($pattern, $file) || basename($file) == $pattern) {
                    ftp_get($this->ftp->getConnection(), $target_directory.'/'.(basename($file)), $file, FTP_BINARY);
                    array_push($fileList, $file);
                }
            }
        } catch (Exception $e) {
            $this->logger->error($e->getMessage(), $e);
            throw new ServiceException($e->getMessage(),"could.not.download");
        } 
        $this->logger->info("Exit "); 
        return $fileList;
    }
}
