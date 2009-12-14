<?php
class ZoneModule extends ZoopModule
{
	protected function init()
	{
		$this->depend('app');
		$this->depend('gui');
		$this->addClass('Zone');
		$this->addClass('ZoneApplication');
		$this->addClass('GuiZone');
	}
}
