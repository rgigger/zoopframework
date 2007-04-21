<?php
class ZoneCreate
{
	function pageProject($p, $s)
	{
		$stationaryName = $p[3];
		if(isset($p[4]))
			$projectName = $p[4];
		else
			$projectName = $stationaryName;
		
		passthru("svn export http://zoop.googlecode.com/svn/trunk/stationary/$stationaryName $projectName");
	}
}