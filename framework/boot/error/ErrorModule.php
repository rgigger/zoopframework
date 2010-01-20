<?php
class ErrorModule extends ZoopModule
{
	protected function init()
	{
		$this->addInclude('Error.php');
		$this->addClass('ZoopException');
		$this->addClass('WebErrorHandler');
		$this->addClass('CliErrorHandler');
	}
}
