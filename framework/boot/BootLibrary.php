<?php
class BootLibrary extends ZoopLibrary
{
	protected function init()
	{
		$this->registerMod('config');
		$this->registerMod('error');
	}
}