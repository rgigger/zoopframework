<?php
class SmartyModule extends ZoopModule
{
	protected function init()
	{
		$this->addInclude('lib/Smarty.class.php');
	}
}
