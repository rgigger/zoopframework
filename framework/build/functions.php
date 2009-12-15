<?php

define('_n', "\n");

function _en()
{
	echo _n;
}

function _mkdir($path, $mode = 0775)
{
	if(is_dir($path))
	{
		echo "notice: '$path' already exists\n";
		return;
	}
	
	_status("creating directory '$path'");
	mkdir($path, $mode, true);
}

function _chgrp($path, $group, $recursive = false)
{
	$r = $recursive ? 'recursively' : '';
	_status("changing group of '$path' to '$group' $r");
	if($recursive)
		_chgrp_r($path, $group);
	else
		chgrp($path, $group);
}

function _chgrp_r($path, $group)
{
	chgrp($path, $group);
	if(!is_dir($path))
		return;
	
	$dir = new DirectoryIterator($path);
	foreach($dir as $fileinfo)
	    if(!$fileinfo->isDot())
			_chgrp_r($path . '/' . $fileinfo->getFilename(), $group);
}

function _chmod($path, $mode, $recursive = false)
{
	$r = $recursive ? 'recursively' : '';
	$m = decoct($mode);
	_status("setting mode of '$path' to '$m' $r");
	if($recursive)
		_chmod_r($path, $mode);
	else
		chmod($path, $mode);
}

function _chmod_r($path, $mode)
{
	chmod($path, $mode);
	if(!is_dir($path))
		return;

	$dir = new DirectoryIterator($path);
	foreach($dir as $fileinfo)
	    if(!$fileinfo->isDot())
			_chmod_r($path . '/' . $fileinfo->getFilename(), $mode);
}

//  this is a hack, we need to just set this up with proper OOP and conveninece functions
function _forcegen()
{
    global $FORCEGEN;
    $FORCEGEN = true;
}

function _fetch($path, $params = array())
{
	global $_assigns, $FORCEGEN;
	
	$templatePath = $path;
	
	$gui = new gui();
	if($_assigns)
		foreach($_assigns as $name => $value)
			$gui->assign($name, $value);
	
	foreach($params as $name => $value)
	{
		// echo "param: $name => $value\n";
		$gui->assign($name, $value);
	}
	
	return $gui->fetch($templatePath . '.tpl');
}

function _gen($path, $filePath = '', $params = array())
{
	if(!$filePath)
		$filePath = $path;
	$content = _fetch($path, $params)
	_status("creating generated file '" . getcwd() . '/' . $filePath . "'");
	
	if(isset($FORCEGEN) && $FORCEGEN)
	    $forcegen = true;
	else
	    $forcegen = false;
	
	if(file_exists($filePath) && !$forcegen)
		trigger_error("file $filePath already exists");
	
	file_put_contents($filePath, $content);
}

function _cd($path)
{
	_status("changing directory to '$path'");
	chdir($path);
}

function _ln($target, $link)
{
	_status("linking '$link' to '$target'");
	symlink($target, $link);
}

function _status($message)
{
	echo "status: $message" . _n;
}

function _assign($name, $value)
{
	global $_assigns;
	_status("assigning '$value' to '$name'");
	return $_assigns[$name] = $value;
}
