<?php
namespace Ims\Controller;

use Ims\Controller\AbstractController;
use Oxzion\Insurance\Ims\ImsService;

class InsuredController extends AbstractController
{
    public function __construct(ImsService $imsService)
    {
        parent::__construct($imsService, 'InsuredFunctions');
    }

}