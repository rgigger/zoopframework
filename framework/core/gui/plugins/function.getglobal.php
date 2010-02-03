<?php
function smarty_function_getglobal($params, &$smarty)
{
	if(!isset($params['assign']))
	{
		$params['assign'] = $params['var'];
	}
	if(isset($smarty->globalTplVars) && $smarty->globalTplVars[$params['var']])
		$smarty->assign($params['assign'], $smarty->globalTplVars[$params['var']]);
}