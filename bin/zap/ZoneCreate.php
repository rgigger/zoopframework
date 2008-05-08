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
		if(!isset($p[3]))
			trigger_error("no version passed in");
		else
			$version = $p[3];
		
		if(!isset($p[4]))
			trigger_error("no name passed in");
		else
			$name = $p[4];
		
		$gui = new Gui();
		$gui->left_delimiter = '[[';
		$gui->right_delimiter = ']]';
		$stationaryFile = 'file:' . getcwd() . '/stationary/migration.tpl';
		$gui->assign('version', str_replace('.', '_', $version));
		$contents = $gui->fetch($stationaryFile);
		
		$dir = getcwd() . '/migrations';
		$newFilename = $dir . '/' . $version . '_' . $name . '.php';
		if(!file_exists($dir))
			mkdir($dir, 0775, true);
		file_put_contents($newFilename, $contents);
	}
}