<?php
namespace Leaderboard\Model;

use Oxzion\Db\ModelTable;
use Oxzion\Db\Config;
use Oxzion\Model\Model;
use Oxzion\Model\Entity\Leaderboard;

class LeaderboardTable extends ModelTable {
    public function __construct() {
		$this->tablename = 'leaderboard'; leaderboard_log
        parent::__construct(new Leaderboard());
    }
    public function save(Model $data){
        return $this->internalSave($data->toArray());
    }
}