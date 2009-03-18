<?php
function smarty_function_openform($params, &$smarty)
{
	$smarty->zoop->form = new Form();
	$action = $params['action'];
	return '<form name="main_form" method="POST" action="' . $action . '">';
}
