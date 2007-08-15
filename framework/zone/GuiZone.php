<?php
class GuiZone extends Zone
{
	var $displayed = false;
	
	function chooseGui($type)
	{
		assert($type === NULL);	//	if they want something different they need to extend this class
		$tmp = new gui();
		return $tmp;
	}
	
	function getTemplateDir()
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
		
		$guiAssigns = GuiGetAssigns();
		foreach($guiAssigns as $name => $value)
		{
			$gui->assign($name, $value);
		}
		
		if(defined('script_url'))
			$gui->assign('scriptUrl', script_url);
		if(defined('virtual_url'))
			$gui->assign('virtualUrl', virtual_url);
		$gui->assign('zoneUrl', $this->getUrl());
 		
		$dirName = $this->getTemplateDir();
		$gui->display($dirName . '/'. $templateName . '.tpl');
		$this->displayed = true;
	}
}