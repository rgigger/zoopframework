<?php
class ZendModule extends ZoopModule
{
	function configure()
	{
		ini_set('include_path', ini_get('include_path') . ':' . zoop_dir);
	}
}
