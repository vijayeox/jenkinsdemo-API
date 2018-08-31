<?php
namespace Bookmark\Controller;

use Zend\Log\Logger;
use Bookmark\Model\Links;
use Bookmark\Model\LinksTable;
use Oxzion\Controller\AbstractApiController;

class BookmarkController extends AbstractApiController {

    public function __construct(Logger $log){
        parent::__construct($log, __CLASS__, Links::class);
        $this->setIdentifierName('linksId');
    }
}