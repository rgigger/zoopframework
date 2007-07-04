<?php
class ZoneApply
{
	function subMigrations($p, $s)
	{
		//	make sure the migrations table exists
		Migration::initDB();
		
		//	have it scan the migrations directory for all available migrations
		$versions = Migration::getAllMigrationNames();
		
		//	query the db for applied migrations
		$applied = Migration::getAllAppiedMigrationNames();
		
		//	apply anything that hasn't been done yet, in the proper order
		$unapplied = array_diff($versions, $applied);
		
		print_r($unapplied);
		
		foreach($unapplied as $key => $needsApplied)
		{
			Migration::apply($needsApplied);
		}
	}
}