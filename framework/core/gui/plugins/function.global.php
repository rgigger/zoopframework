<?php
function smarty_function_global($params, &$smarty)
{
	$smarty->globalTplVars[$params['var']] = $params['value'];
}
