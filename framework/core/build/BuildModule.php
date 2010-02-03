<?php
class BuildModule extends ZoopModule
{
	function getIncludes()
	{
		return array('functions.php');
	}
	
	function getClasses()
	{
		return array('BuildProject');
	}	
}
