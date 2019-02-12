<?php

namespace Leaderboard\Controller;

use Zend\Log\Logger;
use Leaderboard\Model\LeaderboardTable;
use Leaderboard\Model\Leaderboard;
use Leaderboard\Service\LeaderboardService;
use Oxzion\Controller\AbstractApiController;
use Zend\Db\Adapter\AdapterInterface;
use Bos\ValidationException;

class LeaderboardController extends AbstractApiController {

    private $appService;
    /**
    * @ignore __construct
    */
    public function __construct(LeaderboardTable $table, LeaderboardService $appService, Logger $log, AdapterInterface $dbAdapter) {
        parent::__construct($table, $log, __CLASS__, Leaderboard::class);
        $this->setIdentifierName('leaderboardId');
        $this->appService = $appService;
    }

}
