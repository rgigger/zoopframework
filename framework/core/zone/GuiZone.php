<?php
class GuiZone extends Zone
{
	protected $displayed = false;
	protected $baseDir = NULL;
	
	public function init($requestInfo = NULL, $params = array())
	{
		parent::init($requestInfo, $params);
	}
	
	public function setBaseDir($dir)
	{
		$this->baseDir = $dir;
	}
	
	public function getBaseDir()
	{
		return $this->baseDir;
	}
	
	function chooseGui($type)
	{
		assert($type === NULL);	//	if they want something different they need to extend this class
		$tmp = new gui();
		return $tmp;
	}
	
	protected function getTemplateDir()
	{
		$className = get_class($this);
		$zoneName = strtolower(substr($className, 4));
		return $zoneName;
	}
	
	function assign($key, $value)
	{
		GuiAssign($key, $value);
	}
	
	function displayed()
	{
		return $this->displayed;
	}
	
	function display($templateName, $guiType = NULL)
	{
		$gui = $this->chooseGui($guiType);
		
		foreach(GuiGetAssigns() as $name => $value)
			$gui->assign($name, $value);
		
		foreach(GetTemplateDirs() as $thisDir)
			$gui->addTemplateDir($thisDir);
		
		if(defined('script_url'))
			$gui->assign('scriptUrl', script_url);
		if(defined('virtual_url'))
			$gui->assign('virtualUrl', virtual_url);
		$gui->assign('zoneUrl', $this->getUrl());
 		
		if(!$this->baseDir)
			$dirName = $this->getTemplateDir();
		else
			$dirName = $this->baseDir;
		$gui->display($dirName . '/'. $templateName . '.tpl');
		$this->displayed = true;
	}
}
