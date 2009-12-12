<?php
class AppZone extends GuiZone
{
	protected $layout = 'main';
	
	function chooseGui($guiType)
	{
		$gui = new AppGui();
		$gui->layout = $this->layout;
		/*
		switch($guiType)
		{
			case 'main':
				
				break;
			
			default:
				trigger_error("unknown gui type: $guiType");
				break;
		}
		*/
		
		return $gui;
	}
	
	function closePages($p, $z)
	{
		if(!$this->displayed())
			$this->display($p[0]);
	}
	
	//	there's probably a better way to do this.  this is just to change the default for guiType
	//	we could just make NULL mean 'main' in chooseGui above
	function display($templateName, $guiType = 'main')
	{
		parent::display($templateName, $guiType);
	}
}