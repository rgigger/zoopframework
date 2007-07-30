<?php
class Migration
{
	//	static
	function initDb()
	{
		//	create the migration table if it does not exist
		$schema = SqlGetSchema();
		
		if(!$schema->tableExists('migration'))
		{
			$sql = "create table migration (
						id serial primary key, 
						name text not null,
						applied int2 not null default 0)";
			SqlAlterSchema($sql);
		}
	}
	
	//	static
	function getAllMigrationNames()
	{
		$filenames = ListDir(getcwd() . '/migrations', array('extentions' => array('php')));
		$versions = array();
		foreach($filenames as $thisFilename)
		{
			$parts = explode('_', $thisFilename);
			$version = $parts[0];
			$versions[$thisFilename] = $version;
		}
		
		return $versions;
	}
	
	//	static
	function getAllAppiedMigrationNames()
	{
		return SqlFetchColumn("select name from migration where applied = 1", array());
	}
	
	//	static
	function apply($key, $name)
	{
		include_once(getcwd() . '/migrations/' . $key);
		$className = 'Migration_' . str_replace('.', '_', $name);
		$migration = new $className();
		$migration->up();
		
		//	mark it as applied
		SqlUpsertRow('migration', array('name' => $name), array('applied' => 1));
		
		print_r($migration);
	}
	
	//	static
	function remove($name)
	{
		include_once(getcwd() . '/migrations/' . $key);
		$className = 'Migration_' . str_replace('.', '_', $name);
		$migration = new $className();
		$migration->down();
		
		//	mark it as applied
		SqlUpsertRow('migration', array('name' => $name), array('applied' => 0));
		
		print_r($migration);
	}
}