<?php
namespace Oxzion;

use Exception;

class ServiceException extends \Exception
{
    private $messageCode;
    public function __construct(string $message, string $messageCode, int $codeValue = 0)
    {
        parent::__construct($message, $codeValue);
        $this->messageCode = $messageCode;
    }

    public function getMessageCode()
    {
        return $this->messageCode;
    }
}
