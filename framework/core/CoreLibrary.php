<?php
class CoreLibrary extends ZoopLibrary
{
	protected function init()
	{
		$this->registerMod('app');
		$this->registerMod('build');
		$this->registerMod('cli');
		$this->registerMod('db');
		$this->registerMod('form');
		$this->registerMod('gui');
		$this->registerMod('session');
		$this->registerMod('utils');
		$this->registerMod('xml');
		$this->registerMod('zap');
		$this->registerMod('zone');
	}
}