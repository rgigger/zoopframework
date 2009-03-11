<?php
class AppModule extends ZoopModule
{
	function getIncludes()
	{
		//return array(zoop_dir . '/krumo/class.krumo.php', 'Globals.php');
		return array('Globals.php');
	}
	
	function getClasses()
	{
		return array('Application', 'Object');
	}
}
