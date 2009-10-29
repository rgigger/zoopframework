<?php
class ZoneApply
{
	function subMigrations($p, $s)
	{
		SqlBeginTransaction();
		
		//	make sure the migrations table exists
		Migration::initDB();
		
		//	have it scan the migrations directory for all available migrations
		$versions = Migration::getAllMigrationNames();
		
		//	query the db for applied migrations
		$applied = Migration::getAllAppiedMigrationNames();
		
		//	apply anything that hasn't been done yet, in the proper order
		$unapplied = array_diff($versions, $applied);
		
		foreach($unapplied as $key => $needsApplied)
		{
			Migration::apply($key, $needsApplied);
		}
		
		SqlCommitTransaction();
	}
	
	function subMigration($p, $s)
	{
		$version = $p[3];
		SqlBeginTransaction();
		$filename = Migration::filenameFromVersion($version);
		Migration::apply($filename, $version);
		SqlCommitTransaction();
	}
	
}