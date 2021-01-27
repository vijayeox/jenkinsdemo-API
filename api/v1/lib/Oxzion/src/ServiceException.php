<?php
namespace Oxzion;

use Exception;

class ServiceException extends OxServiceException
{
    private $messageCode;
    public function __construct(string $message, string $messageCode, int $codeValue = OxServiceException::ERR_CODE_INTERNAL_SERVER_ERROR)
    {
        parent::__construct($message, NULL, $codeValue);
        $this->messageCode = $messageCode;
    }

    public function getMessageCode()
    {
        return $this->messageCode;
    }
}
