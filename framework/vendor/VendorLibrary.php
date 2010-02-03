<?php
class VendorLibrary extends ZoopLibrary
{
	protected function init()
	{
		$this->registerMod('phpmailer');
		$this->registerMod('smarty');
		$this->registerMod('spyc');
		$this->registerMod('Zend');
	}
}