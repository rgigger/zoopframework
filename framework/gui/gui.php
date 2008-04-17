<?php
class Gui extends Smarty
{
	function __construct()
	{
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
		$this->setCompileDir(app_tmp_dir . "/gui");
		
		//	set the cache_dir directory
		//	what does this even do?  I'm pretty sure that is not set up
		$this->setCacheDir(app_tmp_dir . "/guicache");
		
		//	set the config directory
		//	what does this even do?  I'm pretty sure that is not set up
		$this->setConfigDir(app_dir . "/guiconfig");
		
		//	set the plugin directories
		$this->addPluginDir(dirname(__file__) . '/plugins');	//	one for plugins added into gui
		$this->addPluginDir(app_dir . "/guiplugins");			//	one or plugins specific to the app
		
		//	we shouldn't use the blanket app_status define any more, we should use specific varabiles
		//	for each behavior, and it should use the new config system
		$smarty->debugging = defined('app_status') && app_status == 'dev' ? true : false;
		$smarty->compile_check = defined('app_status') && app_status == 'dev' ? true : false;
		
		//	we want to run this filter on every single smarty script that we execute
		//	it finds all places where we echo out a simple variable and escapes the html
		//
		//	why wasn't this being used?
		//
		$this->autoload_filters = array('pre' => array("strip_html"));
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

//	often we want to assign the variables before we know what type of gui we want so store the assigns
//		in a global array until we create the gui 
function GuiAssign($name, $value)
{
	global $GuiVars;
	$GuiVars[$name] = $value;
	return;	
}

function GuiGetAssigns()
{
	global $GuiVars;
	if(!$GuiVars)
		$GuiVars = array();
	return $GuiVars;
}

function AddTemplateDir($dir)
{
	global $TemplateDirs;
	$TemplateDirs[] = $dir;
}

function GetTemplateDirs()
{
	global $TemplateDirs;
	if(!$TemplateDirs)	
		$TemplateDirs = array();
	return $TemplateDirs;
}
