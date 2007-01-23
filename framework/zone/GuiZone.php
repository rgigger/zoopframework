<?php
class GuiZone extends Zone
{
	function chooseGui($type)
	{
		assert($guiType === NULL);	//	if they want something different they need to extend this class
		$tmp = new gui();
		return $tmp;
	}
	
	function getTemplateDir()
	{
		$className = get_class($this);
		$zoneName = substr($className, 4));
		return $zoneName;
	}
	
	function assign($name, $value)
	{
		gui::assign($name, $value);
	}
	
	function display($templateName, $guiType)
	{
		$guiAssigns = gui::getAssigns();
		
		$gui = &$this->guiChoose($guiType);
		
		foreach($guiAssigns as $name => $value)
		{
			$gui->assign($name, $value);
		}
		
		$gui->assign();
		//	we may want to do some assigns here
		//	script_url
		//	virtual_url
		//	zone_url
		
		$dirName = $this->getTemplateDir();
		
		$gui->display($dirName . '/'. $templateName);
	}
}