<?php
namespace Oxzion\AppDelegate;

use Oxzion\Service\CommentService;
use Logger;

trait CommentTrait
{
    protected $logger;
    private $commentService;
    
    public function __construct(){
        $this->logger = Logger::getLogger(__CLASS__);
    }
    
    public function setCommentService(CommentService $commentService){
        $this->logger->info("SET COMMENT SERVICE");
        $this->commentService = $commentService;
    }

    protected function createComment($data,$fileId){
      return $this->commentService->createComment($data,$fileId);
    }
}
