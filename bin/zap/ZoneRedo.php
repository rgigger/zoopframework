<?php
class ZoneRedo
{
	function subMigration($p, $s)
	{
		$version = $p[3];
		SqlBeginTransaction();
		$filename = Migration::filenameFromVersion($version);
		Migration::undo($filename, $version);
		Migration::apply($filename, $version);
		SqlCommitTransaction();
	}
	
	function subMigrations($p, $s)
	{
		SqlBeginTransaction();
		
		//	make sure the migrations table exists
		Migration::initDB();
		
		//	have it scan the migrations directory for all available migrations
		$versions = Migration::getAllMigrationNames();
		// print_r($versions);
		$flipped = array_flip($versions);
		
		//	query the db for applied migrations in reverse order
		$applied = Migration::getAllAppiedMigrationNames();
		// print_r($applied);
		
		print_r($versions);
		print_r($applied);
		
		//	undo all of the migrations
		foreach($applied as $key => $migration)
		{
			Migration::undo($flipped[$migration], $migration);
		}
		
		//	now, reapply all of them
		foreach($versions as $key => $migration)
		{
			Migration::apply($key, $migration);
		}
		
		SqlCommitTransaction();
		
		
		
		
		//	apply anything that hasn't been done yet, in the proper order
		// $unapplied = array_diff($versions, $applied);
		// print_r($unapplied);die();
		
		// foreach($unapplied as $key => $needsApplied)
		// {
		// 	Migration::apply($key, $needsApplied);
		// }
		
		/*
		//	make sure the migrations table exists
		$schema = SqlGetSchema();
		if(!$schema->tableExists('migrations'))
		{
			SqlAlterSchema("create table migrations (
						id serial primary key, 
						name text not null,
						applied int2 not null default 0)");
		}
		
		//	have it scan the migrations directory for all available migrations
		$filenames = ListDir(getcwd() . '/migrations', array('extentions' => array('php')));
		$versions = array();
		foreach($filenames as $thisFilename)
		{
			$parts = explode('_', $thisFilename);
			$version = $parts[0];
			$versions[$thisFilename] = $version;
		}
		
		//	query the db for applied migrations
		$applied = SqlFetchColumn("select name from migrations where applied = 1", array());
		
		//	apply anything that hasn't been done yet, in the proper order
		$unapplied = array_diff($versions, $applied);
		print_r($unapplied);
		foreach($unapplied as $key => $needsApplied)
		{
			//	apply the migration
			include_once(getcwd() . '/migrations/' . $key);
			$className = 'Migration_' . str_replace('.', '_', $needsApplied);
			$migration = new $className();
			$migration->up();
			
			//	mark it as applied
			SqlUpsertRow('migrations', array('name' => $needsApplied), array('applied' => 1));
			
			print_r($migration);
		}
		*/
	}
}