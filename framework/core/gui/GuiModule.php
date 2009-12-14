<?php
class GuiModule extends ZoopModule
{
	static private $sConfig = null;
	
	protected function init()
	{
		$this->addClass('Gui');
		$this->depend('smarty');
		$this->addInclude('utils.php');
		$this->hasConfig = true;
	}
	
	protected function configure()
	{
		self::$sConfig = $this->getConfig();
	}
	
	static public function sGetConfig()
	{
		return self::$sConfig;
	}
}
