<?php
abstract class ZoopModule
{
	private $name, $path, $lib;
	private $depends = array(), $includes = array(), $classes = array();
	protected $hasConfig = false;
	
	final function __construct($path, $lib)
	{
		//	second stage (module specific) construction
		$this->init();
		
		//	assign in the paramamters
		$this->path = $path;
		$this->lib = $lib;
		$this->name = strtolower(str_replace('Module', '', get_class($this)));
		
		$classname = get_class($this);
		//	load any dependant modules
		if($this->getDepends())
			foreach($this->getDepends() as $thisDepends)
				$this->lib->loadMod($thisDepends);
		
		//	include any normal files that need to be included
		if($this->getIncludes())
		{
			foreach($this->getIncludes() as $thisInclude)
			{
				if($thisInclude[0] == '/')
					require($thisInclude);
				else
					require($this->path . '/' . $thisInclude);
			}
		}
		
		//	register any class files
		if($classes = $this->getClasses())
			foreach($classes as $thisClass)
				ZoopLoader::addClass($thisClass, $this->path . '/' . $thisClass . '.php');
		
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
		Config::suggest($this->path . '/' . 'config.yaml', 'zoop.' . $this->getConfigPath());
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
		return $config;
	}
	
	/**
	 * stuff about this function
	 *
	 * @return array(list of files to include) or false;
	 */
	protected function addClass($className)
	{
		$this->classes[] = $className;
	}
	
	protected function getClasses()
	{
		return $this->classes;
	}
	
	protected function addInclude($include)
	{
		$this->includes[] = $include;
	}
	
	protected function getIncludes()
	{
		return $this->includes;
	}
	
	protected function depend($module)
	{
		$this->depends[] = $module;
	}
	
	private function getDepends()
	{
		return $this->depends;
	}
}
