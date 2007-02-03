<?php

class gui extends Smarty
{
	var $languageId;
	
	function gui()
	{
		//	call the parent contructor
		$this->Smarty();
		$this->template_dir = array();
   		
   		//	set the default for the base template dir
   		if(!defined("gui_template_dir") )
   			define("gui_template_dir", app_dir . "/templates");
   		
   		//	set the standard template directory and any others registerd with pehppy
   		$this->addTemplateDir(gui_template_dir);
		
		//	set the compile directory
		$this->setCompileDir(app_tmp_dir . "/gui");
		
		//	set the cache_dir directory
		$this->setCacheDir(app_tmp_dir . "/guicache");
		
		//	set the config directory
		$this->setConfigDir(app_dir . "/guiconfig");
		
		//	set the plugin directories
		//$this->addPluginDir(pehppy_dir . '/smarty/plugins');	//	one for smarty
		$this->addPluginDir(dirname(__file__) . '/plugins');	//	one for plugins added into gui
		$this->addPluginDir(app_dir . "/guiplugins");			//	one or plugins specific to the app
		
		$smarty->debugging = defined('app_status') && app_status == 'dev' ? true : false;
		$smarty->compile_check = defined('app_status') && app_status == 'dev' ? true : false;
		
		//	we want to run this filter on every single smarty script that we execute
		//	it finds all places where we echo out a simple variable and escapes the html
		$this->autoload_filters = array('pre' => array("strip_html"));
		
//		$this->assign("template_root", gui_template_dir);
		
		//	it should probably only do this if they are defined so you can use it
		//	without using the zone stuff
//		if(defined("SCRIPT_URL") || defined("SCRIPT_REF") || defined("ORIG_PATH"))
//		{
//			$this->assign("VIRTUAL_URL", SCRIPT_URL . ORIG_PATH);
//			$this->assign("BASE_HREF", SCRIPT_REF);
//			$this->assign("SCRIPT_URL", SCRIPT_URL);
//		}
	}
	
	function setLanguageId($languageId)
	{
		$this->languageId = $languageId;
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
	
	//	static and pseudo-static functions
	
	//	often we want to assign the variables before we know what type of gui we want so store the assigns
	//		in a global array until we create the gui 
	function assign($name, $value)
	{
		if(!(isset($this) && is_a($this,__CLASS__)))
		{
			global $GuiVars;
			$GuiVars[$name] = $value;
			return;
		}
		
		parent::assign($name, $value);
	}
	
	
	//	static
	function getAssigns()
	{
		global $GuiVars;
		if(!$GuiVars)
			$GuiVars = array();
		return $GuiVars;
	}
}