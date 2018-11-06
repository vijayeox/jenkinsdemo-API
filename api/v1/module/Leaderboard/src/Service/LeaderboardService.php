<?php
namespace Leaderboard\Service;

use Oxzion\Service\AbstractService;
use Leaderboard\Model\LeaderboardTable;
use Leaderboard\Model\Leaderboard;
use Oxzion\Auth\AuthContext;
use Oxzion\Auth\AuthConstants;
use Oxzion\ValidationException;
use Zend\Db\ResultSet\ResultSet;
use Oxzion\Service\UserService;
use Exception;

class LeaderboardService extends AbstractService{

    private $table;

    public function __construct($config, $dbAdapter, LeaderboardTable $table){
        parent::__construct($config, $dbAdapter);
        $this->table = $table;
    }

    
}
?>