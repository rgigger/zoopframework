<?php
/**
 * Object extends the Smarty templating system to allow easy separation of business and
 * presentation logic
 *
 */
class Gui extends Smarty
{
	function __construct()
	{
		$config = GuiModule::sGetConfig();
		$tmpPath = Zoop::getTmpDir();
		
		//	call the parent contructor
		$this->Smarty();
		$this->template_dir = array();
   		
   		//	set the default for the base template dir
		//	this should be using the new config stuff, not defines
   		if(!defined("gui_template_dir") )
   			define("gui_template_dir", app_dir . "/templates");
   		
   		//	set the standard template directory and any others registerd with zoop
   		$this->addTemplateDir(gui_template_dir);
		
		//	set the compile directory
		$this->setCompileDir($tmpPath . "/gui");
		
		//	set the cache_dir directory
		//	what does this even do?  I'm pretty sure that is not set up
		$this->setCacheDir($tmpPath . "/guicache");
		
		//	set the config directory
		//	what does this even do?  I'm pretty sure that is not set up
		$this->setConfigDir(app_dir . "/guiconfig");
		
		//	set the plugin directories
		$this->addPluginDir(dirname(__file__) . '/plugins');	//	one for plugins added into gui
		$this->addPluginDir(app_dir . "/guiplugins");			//	one or plugins specific to the app
		
		//	we shouldn't use the blanket app_status define any more, we should use specific varabiles
		//	for each behavior, and it should use the new config system
		// $smarty->debugging = defined('app_status') && app_status == 'dev' ? true : false;
		// $smarty->compile_check = defined('app_status') && app_status == 'dev' ? true : false;
		
		//	we want to run this filter on every single smarty script that we execute
		//	it finds all places where we echo out a simple variable and escapes the html
		//
		//	unfortunately this filters everything.  The entire contents if the template.  I think it is escaping include.
		//	If we can get it to not do that then we can put this back in.
		//
		//$this->autoload_filters = array('pre' => array("strip_html"));
	}
	
	function setTemplateDir($inDir)
	{
		$this->template_dir = $inDir;
	}
	
	function addTemplateDir($inDir)
	{
		$this->template_dir[] = $inDir;
	}
	
	function setCompileDir($inDir)
	{
		$this->compile_dir = $inDir;
	}
	
	function setCacheDir($inDir)
	{
		$this->cache_dir = $inDir;
	}
	
	function setConfigDir($inDir)
	{
		$this->config_dir = $inDir;
	}
	
	function addPluginDir($inDir)
	{
		$this->plugins_dir[] = $inDir;
	}
}
