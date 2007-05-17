<?php
class ZoneTest
{
	function subTest($p, $s)
	{
		//	we really need do this right and to set up a yaml module that abstracts away the underlying yaml engine
		include_once(zoop_dir . '/spyc/spyc.php');

		$array = Spyc::YAMLLoad($_SERVER['HOME'] . '/.zoop/install.yaml');
		
		print_r($array);
	}
}