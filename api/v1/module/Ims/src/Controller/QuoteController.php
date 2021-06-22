<?php
namespace Ims\Controller;

use Ims\Controller\AbstractController;
use Oxzion\Insurance\Ims\ImsService;

class QuoteController extends AbstractController
{
    public function __construct(ImsService $imsService)
    {
        parent::__construct($imsService, 'QuoteFunctions');
    }

}