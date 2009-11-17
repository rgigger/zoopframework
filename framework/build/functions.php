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

function _gen($path, $filePath = '', $params = array())
{
	global $_assigns;
	
	$templatePath = $path;
	
	if(!$filePath)
		$filePath = $path;
	
	$gui = new gui();
	if($_assigns)
		foreach($_assigns as $name => $value)
			$gui->assign($name, $value);
	
	foreach($params as $name => $value)
	{
		// echo "param: $name => $value\n";
		$gui->assign($name, $value);
	}
	
	$content = $gui->fetch($templatePath . '.tpl');
	_status("creating generated file '" . getcwd() . '/' . $filePath . "'");
	if(file_exists($filePath))
		die("file $filePath already exists\n");
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
