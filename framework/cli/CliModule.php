<?php
class CliModule extends ZoopModule
{
	/**
	 * This method is overridden to tell zoop which classes exist as part of this module
	 * so that they can be added to the autoloader
	 *
	 * @return unknown
	 */
	function getClasses()
	{
		return array('CliApplication');
	}
}
