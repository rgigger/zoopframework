<?php
/**
 * Assigns a gui variable independant of a specific gui object
 * 
 * often we want to assign the variables before we know what type of gui we want so store the assigns
 * in a global array until we create the gui 
 * 
 * @param string $name the key that the gui object will use to identify this variable
 * @param mixed $value the value that should be associated with this key
 */
function GuiAssign($name, $value)
{
	global $GuiVars;
	$GuiVars[$name] = $value;
	return;	
}


/**
 * Returns an array of gui variables for use with rendering a page
 *
 * @return unknown
 */
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
