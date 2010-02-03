<?php
function smarty_function_globalappend($params, &$smarty)
{
	$smarty->globalTplVars[$params['var']][] = $params['value'];
}
