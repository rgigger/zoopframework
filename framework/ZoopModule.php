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
			{
				if($thisInclude[0] == '/')
					require($thisInclude);
				else
					require(zoop_dir . '/' . $this->name . '/' . $thisInclude);
			}
				
		
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
	
	/**
	 * Figures out the name of the module by removing the word "Module" from
	 * the class name and returning the result
	 *
	 * @return string
	 */
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
	
	/**
	 * Returns the configuration options using the Config class.
	 * Returns config options from "zoop.<modulename>.<path>"
	 * Path is optional and may be omitted.
	 *
	 * @param string $path
	 * @return array of configuration options
	 */
	function getConfig($path = '')
	{
		$config = Config::get('zoop.' . $this->getConfigPath() . $path);
		// echo_r($config);
		return $config;
	}
	
	/**
	 * This method should be overridden in the child class to return an array
	 * of filenames that should be included when the module is loaded
	 *
	 * @return array("key" => "value") or false;
	 */
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
