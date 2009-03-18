<?php
function smarty_function_closeform($params, &$smarty)
{
	$smarty->zoop->form->saveBindings();
	list($name, $value) = $smarty->zoop->form->getTagInfo();
	return '<input type="hidden" name="' . $name . '" value="' . $value . '"></form>';
}
