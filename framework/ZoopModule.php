<?php
abstract class ZoopModule
{
	private $name;
	protected $hasConfig = false;
	
	final function __construct()
	{
		//	second stage (module specific) construction
		$this->init();
		
		//	get the module name
		$this->name = $this->createName();
		
		//	register any class files
		if($this->getDepends())
			foreach($this->getDepends() as $thisDepends)
				Zoop::loadLib($thisDepends);
		
		//	include any normal files that need to be included
		if($this->getIncludes())
			foreach($this->getIncludes() as $thisInclude)
				require(zoop_dir . '/' . $this->name . '/' . $thisInclude);
		
		//	register any class files
		if($this->getClasses())
			foreach($this->getClasses() as $thisClass)
				Zoop::registerClass($thisClass, zoop_dir . '/' . $this->name . '/' . $thisClass . '.php');
		
		if($this->hasConfig)
			$this->loadConfig();
		
		//	handle configuration
		$this->configure();		
	}
	
	protected function init() {}
	protected function configure() {}
	
	function createName()
	{
		return strtolower(str_replace('Module', '', get_class($this)));
	}
	
	function getConfigPath()
	{
		return $this->name;
	}
	
	private function loadConfig()
	{
		Config::suggest(zoop_dir . '/' . $this->name . '/' . 'config.yaml', 'zoop.' . $this->getConfigPath());
	}
	
	function getConfig($path = '')
	{
		$config = Config::get('zoop.' . $this->getConfigPath() . $path);
		// echo_r($config);
		return $config;
	}
	
	protected function getIncludes()
	{
		return false;
	}
	
	function getClasses()
	{
		return false;
	}
	
	function getDepends()
	{
		return false;
	}
}
