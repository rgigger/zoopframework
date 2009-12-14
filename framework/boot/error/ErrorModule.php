<?php
class ErrorModule extends ZoopModule
{
	protected function init()
	{
		$this->addClass('Error');
		$this->addClass('ZoopException');
		$this->addClass('WebErrorHandler');
		$this->addClass('CliErrorHandler');
	}
}
