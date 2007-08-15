<?php
abstract class ZoopModule
{
	private $name;
	
	function __construct()
	{
		//	get the module name
		$this->name = $this->createName();
		
		//	include any normal files that need to be included
		if($this->getIncludes())
			foreach($this->getIncludes() as $thisInclude)
				require(zoop_dir . '/' . $this->name . '/' . $thisInclude);
		
		//	register any class files
		if($this->getClasses())
			foreach($this->getClasses() as $thisClass)
				Zoop::registerClass($thisClass, zoop_dir . '/' . $this->name . '/' . $thisClass . '.php');
		
		//	handle configuration
		$this->configure();		
	}
	
	function createName()
	{
		return strtolower(str_replace('Module', '', get_class($this)));
	}
	
	function getConfigPath()
	{
		return $this->name;
	}
	
	function getConfigInfo()
	{
		return Config::get('zoop.' . $this->getConfigPath());
	}
	
	function getIncludes()
	{
		return false;
	}
	
	function getClasses()
	{
		return false;
	}
}
