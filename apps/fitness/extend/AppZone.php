<?php
class AppZone extends GuiZone
{
	/*
	function chooseGui($guiType)
	{
		$guiVars = GuiGetAssigns();
		
		switch($guiType)
		{
			case 'main':
				$gui = new AppMainGui();
				break;
			
			default:
				trigger_error("unknown gui type: $guiType");
				break;
		}
		
		return $gui;
	}
	*/
	
	function closePages($p, $z)
	{
		if(!$this->displayed())
			$this->display($p[0]);
	}
	
	function closePosts($p, $z)
	{
		Redirect(virtual_url);
	}
	
	//	there's probably a better way to do this.  this is just to change the default for guiType
	//	we could just make NULL mean 'main' in chooseGui above
	/*
	function display($templateName, $guiType = 'main')
	{
		parent::display($templateName, $guiType);
	}
	*/
}