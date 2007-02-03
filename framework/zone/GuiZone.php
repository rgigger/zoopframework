<?php
class GuiZone extends Zone
{
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
	
	function assign($name, $value)
	{
		gui::assign($name, $value);
	}
	
	function display($templateName, $guiType = NULL)
	{
		$gui = $this->chooseGui($guiType);
		
		$guiAssigns = gui::getAssigns();
		foreach($guiAssigns as $name => $value)
		{
			$gui->assign($name, $value);
		}
		
		//	we may want to do some assigns here
		//	virtual_url
		//	zone_url
		//	request_uri
		if(defined('script_url'))
			$this->assign('scriptUrl', script_url);
		
		$dirName = $this->getTemplateDir();
		$gui->display($dirName . '/'. $templateName . '.tpl');
	}
}