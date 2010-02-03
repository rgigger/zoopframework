<?php
abstract class ZoopLibrary
{
	private $mods, $path;
	
	/**
	 * Stuff about the constructor
	 *
	 * @return ZoopLibrary
	 */
	final function __construct($path)
	{
		$this->path = $path;
		$this->mods = array();
		$this->init();
	}
	
	protected function registerMod($name)
	{
		if(!isset($this->mods[$name]))
			$this->mods[$name] = false;
	}
	
	public function hasMod($name)
	{
		return isset($this->mods[$name]);
	}
	
	public function loadMod($name)
	{
		//	if this library doesn't have this module then Zoop will have to figure out which one does
		if(!$this->hasMod($name))
			return Zoop::loadMod($name);
		
		if(isset($this->mods[$name]) && $this->mods[$name])
			return;
		$modName = ucfirst($name) . 'Module';
		include("$this->path/$name/$modName.php");
		$this->mods[$name] = new $modName("$this->path/$name", $this);
	}
	
	public function loadMods()
	{
		foreach($this->mods as $name => $mod)
		{
			if(!$mod)
				$this->loadMod($name);
		}
	}
}