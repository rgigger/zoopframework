<?php
function smarty_block_for($params, $content, &$smarty, &$repeat)
{
	$start = $params['start'];
	$end = $params['end'];
	$varname = $params['varname'];
	$name = isset($params['name']) ? $params['name'] : '__default__';
	
	if($repeat)
	{
		$smarty->_for[$name]['cur'] = $start;
	}
	else
	{
		$smarty->_for[$name]['cur']++;
	}
	
	if($smarty->_for[$name]['cur'] > $end)
		$repeat = false;
	else
	{
		$smarty->assign($varname, $smarty->_for[$name]['cur']);
		$repeat = true;
	}
	
	return $content;
}