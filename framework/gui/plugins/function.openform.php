<?php
function smarty_function_openform($params, &$smarty)
{
	//	get the paramaters
	$method = isset($params['method']) && $params['method'] ? $params['method'] : 'POST';
	$action = $params['action'];
	
	//	create the form object and store it in the smarty object
	$smarty->zoop->form = new Form();
	
	// pass on anything else they put in
	$extraFields = '';
	foreach($params as $paramName => $paramValue)
	{
		if(in_array($paramName, array('method', 'action')))
			continue;
		
		$extraFields .= ' ' . $paramName . '="' . $paramValue . '"';
	}
	
	return '<form name="main_form" method="' . $method . '" action="' . $action . '" ' . $extraFields . '>';
}
