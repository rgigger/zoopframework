<?php
class ExperimentalLibrary extends ZoopLibrary
{
	protected function init()
	{
		$this->registerMod('auth');
		$this->registerMod('couch');
		$this->registerMod('doc');
		$this->registerMod('graphic');
		$this->registerMod('mail');
		$this->registerMod('migration');
		$this->registerMod('mizithra');
		$this->registerMod('permission');
		$this->registerMod('pherver');
		$this->registerMod('wit');
	}
}