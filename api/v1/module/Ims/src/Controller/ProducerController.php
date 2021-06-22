<?php
namespace Ims\Controller;

use Ims\Controller\AbstractController;

class ProducerController extends AbstractController
{
    public function __construct($imsService)
    {
        parent::__construct($imsService, 'ProducerFunctions');
    }

}