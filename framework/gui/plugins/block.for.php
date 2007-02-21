<?php
function smarty_block_for($params, $content, &$smarty, &$repeat)
{
	$start = $params['start'];
	$end = $params['end'];
	$varname = $params['varname'];
	
	if($repeat)
	{
		$smarty->_for['__default__']['cur'] = $start;
	}
	else
	{
		$smarty->_for['__default__']['cur']++;
	}
	
	if($smarty->_for['__default__']['cur'] > $end)
		$repeat = false;
	else
	{
		$smarty->assign($varname, $smarty->_for['__default__']['cur']);
		$repeat = true;
	}
	
	return $content;
}