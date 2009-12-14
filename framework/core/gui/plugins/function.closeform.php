<?php
function smarty_function_closeform($params, &$smarty)
{
	if(isset($smarty->zoop->form))
	{
		$smarty->zoop->form->saveBindings();
		list($name, $value) = $smarty->zoop->form->getTagInfo();
		return '<input type="hidden" name="' . $name . '" value="' . $value . '"></form>';
	}
}
