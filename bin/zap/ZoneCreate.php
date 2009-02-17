<?php
class ZoneCreate
{
	function subProject($p, $s)
	{
		$stationaryName = $p[3];
		if(isset($p[4]))
			$projectName = $p[4];
		else
			$projectName = $stationaryName;
		
		$command = "svn export http://zoop.googlecode.com/svn/trunk/stationary/$stationaryName $projectName";
		echo $command . "\n";
		passthru($command);
	}
	
	function subMigration($p, $s)
	{
		if(!isset($s['v']))
			trigger_error("no version passed in.  use -v migration_version");
		else
			$version = $s['v'];
		
		if(!isset($s['n']))
			trigger_error("no name passed in.  use -n migration_name");
		else
			$name = $s['n'];
		
		if(!isset($s['s']))
			$stationaryFilename = 'migration.tpl';
		else
			$stationaryFilename = $s['s'];
		
		if(isset($s['m']))
			$moduleName = $s['m'];
		
		$gui = new Gui();
		$gui->left_delimiter = '[[';
		$gui->right_delimiter = ']]';
		
		if(isset($s['m']))
			$stationaryFilename = 'file:' . zoop_dir . "/$moduleName/stationary/$stationaryFilename";
		else if(strpos($stationaryFilename, ':') === false)
			$stationaryFilename = 'file:' . getcwd() . "/stationary/$stationaryFilename";
		else
		{
			$parts = explode(':', $stationaryFilename);
			$modName = $parts[0];
			$filename = $parts[1];
			$stationaryFilename = 'file:' . zoop_dir . "/$modName/stationary/$filename";
		}
			
		$gui->assign('version', str_replace('.', '_', $version));
		$contents = $gui->fetch($stationaryFilename);
		
		$dir = getcwd() . '/migrations';
		$newFilename = $dir . '/' . $version . '_' . $name . '.php';
		if(!file_exists($dir))
			mkdir($dir, 0775, true);
		file_put_contents($newFilename, $contents);
	}
}