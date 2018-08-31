<?php
namespace Leaderboard\Controller;

use Zend\Log\Logger;
use Oxzion\Model\Entity\Leaderboard;
use Oxzion\Model\Table\LeaderboardTable;
use Oxzion\Controller\AbstractApiController;

class LeaderboardController extends AbstractApiController {

    public function __construct(Logger $log){
        parent::__construct( $log, __CLASS__, Leaderboard::class);
        $this->setIdentifierName('leaderboardId');
    }
}