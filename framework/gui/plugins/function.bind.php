<?php
function smarty_function_bind($params, &$smarty)
{
	assert(isset($params['data_object']));
	// handle the data bindings
	if(!isset($params['data_field']))
		trigger_error("gui:bind: if you specifiy a data object you must also specify a data field");
	if(!isset($smarty->pehppy->form))
		trigger_error("gui:bind: if you specifiy a data object you must first use the 'init_form' tag");
	
	$object = $params['data_object'];
	$field = $params['data_field'];
	if(isset($params['assign']))
		$smarty->assign($params['assign'], $smarty->pehppy->form->addBinding(get_class($object), $object->getId(), $field));
	else
		return $smarty->pehppy->form->addBinding(get_class($object), $object->getId(), $field);
}
