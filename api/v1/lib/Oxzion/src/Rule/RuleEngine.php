<?php
namespace Oxzion\Rule;
use Oxzion\Db\Persistence\Persistence;

interface RuleEngine
{
	public function runRule(array $data,Persistence $persistenceService=null);
}
?>