<?php
namespace Oxzion\AppDelegate;

use Oxzion\Service\FtpService;

abstract class FtpDelegate extends AbstractAppDelegate
{

    private $ftpService;

    protected function __construct(array $config)
    {
        parent::__construct();
        $this->ftpService = FtpService::getInstance($config['host'], $config['user'], $config['password'], $config['ssl'], $config['port']);
    }

    protected function uploadFile(string $source_file_path, string $target_file_path)
    {
        return $this->ftpService->uploadFile($source_file_path, $target_file_path);
    }

    protected function download(string $source_directory, string $pattern, string $target_directory, $mode = FTP_BINARY)
    {
        return $this->ftpService->downLoad($source_directory, $pattern, $target_directory, $mode);
    }
}
