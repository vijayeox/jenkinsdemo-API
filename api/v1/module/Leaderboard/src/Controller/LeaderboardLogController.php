<?php
namespace Leaderboard\Controller;

use Zend\Log\Logger;
use Oxzion\Model\Table\LeaderboardLog;
use Oxzion\Model\Entity\LeaderboardLogTable;
use Oxzion\Controller\AbstractApiController;

class LeaderboardLogController extends AbstractApiController {

    public function __construct(LeaderboardLogTable $table, Logger $log){
        parent::__construct($table, $log, __CLASS__, LeaderboardLog::class);
        $this->setIdentifierName('leaderboardlogId');
    }
}