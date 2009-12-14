<?php
class ConfigModule extends ZoopModule
{
	protected function init()
	{
		$this->depend('spyc');
		$this->addClass('Yaml');
		$this->addClass('Config');
		$this->addClass('ConfigInstance');
		$this->addClass('ConfigException');
	}
	
	public function configure()
	{
		Config::Load();
	}
}
