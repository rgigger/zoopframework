<?php
class UtilsModule extends ZoopModule
{
	protected function init()
	{
		$this->addClass('BacktraceViewCli');
		$this->addInclude('Utils.php');
	}
}
