<?php
namespace Ims\Controller;

use Ims\Controller\AbstractController;

class QuoteController extends AbstractController
{
    public function __construct($imsService)
    {
        parent::__construct($imsService, 'QuoteFunctions');
    }

}