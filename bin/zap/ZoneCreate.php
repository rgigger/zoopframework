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
		
		passthru("svn export http://zoop.googlecode.com/svn/trunk/stationary/$stationaryName $projectName");
	}
	
	function subMigration($p, $s)
	{
		$version = $p[3];
		$name = $p[4];
		
		$gui = new Gui();
		$gui->left_delimiter = '[[';
		$gui->right_delimiter = ']]';
		$stationaryFile = 'file:' . getcwd() . '/stationary/migration.tpl';
		$gui->assign('version', $version);
		$contents = $gui->fetch($stationaryFile);
		
		$newFilename = getcwd() . '/migrations/' . $version . '_' . $name . '.php';
		file_put_contents($newFilename, $contents);
	}
}