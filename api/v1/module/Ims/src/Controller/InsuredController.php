<?php
namespace Ims\Controller;

use Ims\Controller\AbstractController;

class InsuredController extends AbstractController
{
    public function __construct($imsService)
    {
        parent::__construct($imsService, 'InsuredFunctions');
    }

}