<?php

require_once("Date.php");

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
		foreach(pehppy::getTemplateDirList() as $templateDir)
			$this->addTemplateDir($templateDir);
		
		//	set the compile directory
		$this->setCompileDir(app_temp_dir . "/gui");
		
		//	set the compile directory
		$this->setCacheDir(app_temp_dir . "/guicache");
		
		//	set the config directory
		$this->setConfigDir(app_dir . "/guiconfig");
		
		//	set the plugin directories
		//$this->addPluginDir(pehppy_dir . '/smarty/plugins');	//	one for smarty
		$this->addPluginDir(dirname(__file__) . '/plugins');	//	one for plugins added into gui
		$this->addPluginDir(app_dir . "/guiplugins");			//	one or plugins specific to the app
		
		$smarty->debugging = app_status == 'dev' ? true : false;
		$smarty->compile_check = app_status == 'dev' ? true : false;
		
		//	we want to run this filter on every single smarty script that we execute
		//	it finds all places where we echo out a simple variable and escapes the html
		$this->autoload_filters = array('pre' => array("strip_html"));
		
		$this->assign("template_root", gui_template_dir);
		
		//	it should probably only do this if they are defined so you can use it
		//	without using the zone stuff
		if(defined("SCRIPT_URL") || defined("SCRIPT_REF") || defined("ORIG_PATH"))
		{
			$this->assign("VIRTUAL_URL", SCRIPT_URL . ORIG_PATH);
			$this->assign("BASE_HREF", SCRIPT_REF);
			$this->assign("SCRIPT_URL", SCRIPT_URL);
		}
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
};
?>
