<?php
function smarty_function_json($params, &$smarty)
{
	assert(isset($params['var']));
	return Zend_Json::encode($params['var']);
}
