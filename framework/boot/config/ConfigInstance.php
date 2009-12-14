<?php
die('I dont think that this file is actually being used at all')
class ConfigInstance
{
	private $info = array();
	private $file;
	private $loaded = false;
	
	function __construct($file = null)
	{
		if($file)
			$this->file = $file;
	}
	
	//	these two functions should probably use the pattern
	//	one uses a multiline assign the other single
	//	one uses the references and the other doesn't
	//	one assigns to the root reference, the other assigns directly to $this->info
	//	do it one way or the other
	public function suggest($file, $prefix = NULL)
	{
		if($prefix)
			$root = &$this->getReference($prefix);
		else
			$root = &$this->info;
		$root = array_merge(Yaml::read($file), $root);
	}

	public function insist($file, $prefix = NULL)
	{
		$root = $prefix ? $this->getReference($prefix) : $this->info;
		$this->info = array_merge($root, Yaml::read($file));
	}

	/**
	 * Specify configuration file to use
	 *
	 * @param string $file Path and filename of the config file to use
	 */
	private function setConfigFile($file)
	{
		$this->file = $file;
	}

	/**
	 * Loads the config file specified by the $file member
	 *
	 */
	private function load()
	{
		$this->insist($this->file);
		$this->loaded = true;
	}

	/**
	 * Returns configuration options based on a path (i.e. zoop.db or zoop.application.info)
	 *
	 * @param string $path Path for which to fetch options
	 * @return array of configuration values
	 */
	public function get($path = '')
	{
		if(!$this->loaded)
			$this->load();
		
		$cur = $this->info;		
		if(!$path)
			return $cur;
		
		$parts = explode('.', $path);
		foreach($parts as $thisPart)
			if(isset($cur[$thisPart]))
				$cur = $cur[$thisPart];
			else
				return false;
		
		return $cur;
	}	

	private function &getReference($path)
	{
		$parts = explode('.', $path);
		$cur = &$this->info;

		foreach($parts as $thisPart)
		{
			if(isset($cur[$thisPart]))
				$cur = &$cur[$thisPart];
			else
			{
				$cur[$thisPart] = array();
				$cur = &$cur[$thisPart];
			}
		}

		return $cur;
	}
	
	public function expandInternalReferences($ldelim, $rdelim)
	{
		$this->_expand($ldelim, $rdelim, '');
	}
	
	private function _expand($ldelim, $rdelim, $cur)
	{
		$curNode = $this->getReference($cur);
		if(is_string($curNode))
		{
			echo $curNode;
			return;
		}
		
		assert(is_array($curNode));
		foreach(array_keys($curNode) as $key)
			$this->_expand($ldelim, $rdelim, "$cur.$key");
	}
}
