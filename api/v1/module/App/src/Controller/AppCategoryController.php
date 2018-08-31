<?php
namespace App\Controller;

use Zend\Log\Logger;
use App\Model\Modulecategories;
use App\Model\ModulecategoriesTable;
use Oxzion\Controller\AbstractApiController;

class AppCategoryController extends AbstractApiController {

    public function __construct(Logger $log){
        parent::__construct( $log, __CLASS__, Modulecategories::class);
        $this->setIdentifierName('modulecategoriesId');
    }
}