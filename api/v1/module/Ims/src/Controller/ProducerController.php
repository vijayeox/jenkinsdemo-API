<?php
namespace Ims\Controller;

use Ims\Controller\AbstractController;
use Oxzion\Insurance\Ims\Service as ImsService;

class ProducerController extends AbstractController
{
    public function __construct(ImsService $imsService)
    {
        parent::__construct($imsService, 'ProducerFunctions');
    }
}
