<?php
namespace Ims\Controller;

use Exception;
use Oxzion\Service\ImsService;
use Zend\Db\Adapter\AdapterInterface;
use Ims\Controller\AbstractController;

class InsuredController extends AbstractController
{
    public function __construct(ImsService $imsService)
    {
        parent::__construct($imsService, 'InsuredFunctions');
    }
}
